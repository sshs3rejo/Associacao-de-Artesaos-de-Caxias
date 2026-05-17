/**
 * Associação de Artesãos de Caxias - Carrinho de Compras Modulado
 * Foco em Clean Code e Separação de Preocupações (SoC)
 */

function formatarPreco(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

window.abrirDetalhes = function(botao) {
    const card = botao.closest(".produto-card");
    window.produtoAtual = {
        id: card.dataset.id,
        nome: card.dataset.nome,
        preco: parseFloat(card.dataset.preco),
        descricao: card.dataset.descricao,
        imagem: card.dataset.imagem,
        estoque: parseInt(card.dataset.estoque) || 0,
    };

    document.getElementById("modal-nome").textContent = window.produtoAtual.nome;
    document.getElementById("modal-preco").textContent = formatarPreco(window.produtoAtual.preco);
    document.getElementById("modal-descricao").textContent = window.produtoAtual.descricao;
    document.getElementById("modal-img").src = window.produtoAtual.imagem;
    document.getElementById("modal-quantidade").value = 1;

    const estoqueDiv = document.getElementById("modal-estoque");
    estoqueDiv.innerHTML = window.produtoAtual.estoque > 0
        ? `<span class="em-estoque">Em estoque (${window.produtoAtual.estoque} disponível)</span>`
        : `<span class="fora-estoque">Fora de estoque</span>`;

    bootstrap.Modal.getOrCreateInstance(document.getElementById("modal-detalhes")).show();
};

window.adicionarAoCarrinho = function() {
    const qtd = parseInt(document.getElementById("modal-quantidade").value);
    const item = window.carrinho.find(i => i.id === window.produtoAtual.id);
    if (item) {
        item.quantidade += qtd;
    } else {
        window.carrinho.push({
            id: window.produtoAtual.id,
            nome: window.produtoAtual.nome,
            preco: window.produtoAtual.preco,
            quantidade: qtd,
            imagem: window.produtoAtual.imagem,
        });
    }
    window.atualizarBadge();
    const bs = bootstrap.Modal.getInstance(document.getElementById("modal-detalhes"));
    if (bs) bs.hide();
    
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Produto adicionado ao carrinho!',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
};

window.fecharModal = function() {
    const modal = document.getElementById("modal-detalhes");
    const bs = bootstrap.Modal.getInstance(modal);
    if (bs) { bs.hide(); return; }
    modal.style.display = "none";
    document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
    document.body.classList.remove("modal-open");
};

window.fecharCarrinho = function() {
    const modal = document.getElementById("modal-carrinho");
    const bs = bootstrap.Modal.getInstance(modal);
    if (bs) { bs.hide(); return; }
    modal.style.display = "none";
    document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
    document.body.classList.remove("modal-open");
};

window.onclick = function(event) {
    const modal = document.getElementById("modal-detalhes");
    const modalCarrinho = document.getElementById("modal-carrinho");
    if (event.target === modal) {
        const bs = bootstrap.Modal.getInstance(modal);
        if (bs) bs.hide();
    }
    if (event.target === modalCarrinho) {
        const bs = bootstrap.Modal.getInstance(modalCarrinho);
        if (bs) bs.hide();
    }
};

window.adicionarRapido = function(botao) {
    const card = botao.closest(".produto-card");
    const produto = {
        id: card.dataset.id,
        nome: card.dataset.nome,
        preco: parseFloat(card.dataset.preco),
        imagem: card.dataset.imagem,
    };
    const item = window.carrinho.find(i => i.id === produto.id);
    if (item) {
        item.quantidade += 1;
    } else {
        window.carrinho.push({ ...produto, quantidade: 1 });
    }
    window.atualizarBadge();
    
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Produto adicionado ao carrinho!',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
};

window.atualizarBadge = function() {
    const badge = document.getElementById("badge-carrinho");
    const total = window.carrinho.reduce((s, i) => s + i.quantidade, 0);
    badge.textContent = total;
    
    if (total > 0) {
        badge.style.setProperty('display', 'block', 'important');
    } else {
        badge.style.setProperty('display', 'none', 'important');
    }
};

window.abrirCarrinho = function() {
    const conteudo = document.getElementById("carrinho-conteudo");
    if (window.carrinho.length === 0) {
        conteudo.innerHTML = '<div class="text-center py-4 text-muted"><i class="bi bi-cart-x fs-1 d-block mb-2"></i>Seu carrinho está vazio</div>';
    } else {
        let html = '<div class="carrinho-itens">';
        let total = 0;
        window.carrinho.forEach(item => {
            const subtotal = item.preco * item.quantidade;
            total += subtotal;
            html += `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-bold" style="color: #8b5a3c;">${item.nome}</div>
                        <div style="color: #c85a3a;">${formatarPreco(item.preco)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtd('${item.id}', -1)">-</button>
                        <span class="fw-bold">${item.quantidade}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="alterarQtd('${item.id}', 1)">+</button>
                        <span class="fw-bold ms-3" style="color: #c85a3a;">${formatarPreco(subtotal)}</span>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removerItem('${item.id}')"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        html += `
            <div class="bg-light p-3 rounded mt-3">
                <div class="d-flex justify-content-between"><span>Subtotal:</span><span>${formatarPreco(total)}</span></div>
                <div class="d-flex justify-content-between"><span>Frete:</span><span>Grátis</span></div>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2 pt-2 border-top" style="color: #8b5a3c;">Total: <span>${formatarPreco(total)}</span></div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn flex-fill text-white fw-bold" style="background-color: #7a2f1f;" onclick="finalizarCompra()">
                    <i class="fa fa-check-circle me-1"></i> Finalizar Compra
                </button>
                <button class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Continuar Comprando</button>
            </div>
        `;
        conteudo.innerHTML = html;
    }
    bootstrap.Modal.getOrCreateInstance(document.getElementById("modal-carrinho")).show();
};

window.alterarQtd = function(id, delta) {
    const item = window.carrinho.find(i => i.id === id);
    if (!item) return;
    item.quantidade += delta;
    if (item.quantidade <= 0) {
        window.carrinho = window.carrinho.filter(i => i.id !== id);
    }
    window.atualizarBadge();
    window.abrirCarrinho();
};

window.removerItem = function(id) {
    window.carrinho = window.carrinho.filter(i => i.id !== id);
    window.atualizarBadge();
    window.abrirCarrinho();
};

window.finalizarCompra = function() {
    if (window.carrinho.length === 0) return;
    if (window.Laravel.auth) {
        window.enviarCheckoutAPI();
    } else {
        bootstrap.Modal.getOrCreateInstance(document.getElementById("modal-guest-checkout")).show();
    }
};

window.enviarCheckoutAPI = async function(dadosCliente) {
    let botao;
    if (dadosCliente) {
        botao = document.querySelector('[onclick="enviarCheckoutGuest()"]');
    } else {
        botao = document.querySelector('[onclick="finalizarCompra()"]');
    }
    
    const textoOriginal = botao ? botao.innerHTML : '';
    if (botao) { botao.disabled = true; botao.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Processando...'; }

    const body = dadosCliente || {
        itens: window.carrinho.map(item => ({
            id_produto: item.id,
            quantidade: item.quantidade,
        })),
    };

    try {
        const response = await fetch(window.Laravel.checkoutUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.error || data.message || 'Erro ao processar pedido');

        window.location.href = data.redirect_url;
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: err.message,
        });
        if (botao) { botao.disabled = false; botao.innerHTML = textoOriginal; }
    }
};

window.enviarCheckoutGuest = function() {
    const name = document.getElementById('guest_name').value.trim();
    const email = document.getElementById('guest_email').value.trim();
    if (!name || !email) return;

    const body = {
        itens: window.carrinho.map(item => ({
            id_produto: item.id,
            quantidade: item.quantidade,
        })),
        guest_name: name,
        guest_email: email,
        guest_phone: document.getElementById('guest_phone')?.value || '',
    };

    window.enviarCheckoutAPI(body);
};

document.addEventListener("DOMContentLoaded", function() {
    if (typeof window.carrinho === 'undefined') window.carrinho = [];
    window.atualizarBadge();
});
