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
        ? `<span class="text-green-600 font-semibold">Em estoque (${window.produtoAtual.estoque} disponível)</span>`
        : `<span class="text-red-600 font-semibold">Fora de estoque</span>`;

    showModal('modal-detalhes');
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
    hideModal('modal-detalhes');

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
    hideModal('modal-detalhes');
};

window.fecharCarrinho = function() {
    hideModal('modal-carrinho');
};

document.addEventListener('click', function(event) {
    const modals = ['modal-detalhes', 'modal-carrinho', 'modal-guest-checkout'];
    modals.forEach(id => {
        const el = document.getElementById(id);
        if (el && event.target === el) hideModal(id);
    });
});

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
        badge.style.display = 'inline';
    } else {
        badge.style.display = 'none';
    }
};

window.abrirCarrinho = function() {
    const conteudo = document.getElementById("carrinho-conteudo");
    if (window.carrinho.length === 0) {
        conteudo.innerHTML = '<div class="text-center py-4 text-gray-400"><i class="fa fa-shopping-cart text-5xl d-block mb-2"></i>Seu carrinho est\u00e1 vazio</div>';
    } else {
        let html = '<div class="divide-y divide-gray-200">';
        let total = 0;
        window.carrinho.forEach(item => {
            const subtotal = item.preco * item.quantidade;
            total += subtotal;
            html += `
                <div class="flex items-center justify-between py-3">
                    <div>
                        <div class="font-bold text-brand-light">${item.nome}</div>
                        <div class="text-price font-semibold">${formatarPreco(item.preco)}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-2 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="alterarQtd('${item.id}', -1)">-</button>
                        <span class="font-bold w-6 text-center">${item.quantidade}</span>
                        <button class="px-2 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="alterarQtd('${item.id}', 1)">+</button>
                        <span class="font-bold ml-2 text-price">${formatarPreco(subtotal)}</span>
                        <button class="ml-2 text-red-500 hover:text-red-700" onclick="removerItem('${item.id}')"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        html += `
            <div class="bg-gray-100 p-4 rounded-lg mt-3">
                <div class="flex justify-between"><span>Subtotal:</span><span>${formatarPreco(total)}</span></div>
                <div class="flex justify-between"><span>Frete:</span><span class="text-green-600">Gr\u00e1tis</span></div>
                <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t border-gray-300 text-brand-light">Total: <span>${formatarPreco(total)}</span></div>
            </div>
            <div class="flex gap-2 mt-3">
                <button class="flex-1 text-white font-bold px-4 py-3 rounded-lg" style="background-color: #7a2f1f;" onclick="finalizarCompra()">
                    <i class="fa fa-check-circle mr-1"></i> Finalizar Compra
                </button>
                <button class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100" onclick="hideModal('modal-carrinho')">Continuar Comprando</button>
            </div>
        `;
        conteudo.innerHTML = html;
    }
    showModal('modal-carrinho');
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
        showModal('modal-guest-checkout');
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
    if (botao) { botao.disabled = true; botao.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Processando...'; }

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
