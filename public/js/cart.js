function formatarPreco(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',');
}

window.abrirDetalhes = function(botao) {
    var card = botao.closest(".produto-card");
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

    var estoqueDiv = document.getElementById("modal-estoque");
    estoqueDiv.innerHTML = window.produtoAtual.estoque > 0
        ? '<span class="text-green-600 font-semibold">Em estoque (' + window.produtoAtual.estoque + ' dispon\u00edvel)</span>'
        : '<span class="text-red-600 font-semibold">Fora de estoque</span>';

    showModal('modal-detalhes');
};

window.adicionarAoCarrinho = function() {
    var qtd = parseInt(document.getElementById("modal-quantidade").value);
    var item = window.carrinho.find(function (i) { return i.id === window.produtoAtual.id; });
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
    mostrarToast('Produto adicionado ao carrinho!');
};

window.fecharModal = function() { hideModal('modal-detalhes'); };
window.fecharCarrinho = function() { hideModal('modal-carrinho'); };

document.addEventListener('click', function(event) {
    ['modal-detalhes', 'modal-carrinho'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el && event.target === el) hideModal(id);
    });
});

window.adicionarRapido = function(botao) {
    var card = botao.closest(".produto-card");
    var produto = {
        id: card.dataset.id,
        nome: card.dataset.nome,
        preco: parseFloat(card.dataset.preco),
        imagem: card.dataset.imagem,
    };
    var item = window.carrinho.find(function (i) { return i.id === produto.id; });
    if (item) {
        item.quantidade += 1;
    } else {
        window.carrinho.push({ id: produto.id, nome: produto.nome, preco: produto.preco, imagem: produto.imagem, quantidade: 1 });
    }
    window.atualizarBadge();
    mostrarToast('Produto adicionado ao carrinho!');
};

window.atualizarBadge = function() {
    var badge = document.getElementById("badge-carrinho");
    var total = window.carrinho.reduce(function (s, i) { return s + i.quantidade; }, 0);
    badge.textContent = total;
    badge.style.display = total > 0 ? 'inline' : 'none';
};

window.abrirCarrinho = function() {
    var conteudo = document.getElementById("carrinho-conteudo");
    if (window.carrinho.length === 0) {
        conteudo.innerHTML = '<div class="text-center py-4 text-gray-400"><span class="text-5xl block mb-2">🛒</span>Seu carrinho est\u00e1 vazio</div>';
    } else {
        var html = '<div class="divide-y divide-gray-200">';
        var total = 0;
        window.carrinho.forEach(function(item) {
            var subtotal = item.preco * item.quantidade;
            total += subtotal;
            html += '<div class="flex items-center justify-between py-3">'
                  + '<div><div class="font-bold text-brand-light">' + item.nome + '</div>'
                  + '<div class="text-price font-semibold">' + formatarPreco(item.preco) + '</div></div>'
                  + '<div class="flex items-center gap-2">'
                  + '<button class="px-2 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="alterarQtd(\'' + item.id + '\',-1)">-</button>'
                  + '<span class="font-bold w-6 text-center">' + item.quantidade + '</span>'
                  + '<button class="px-2 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="alterarQtd(\'' + item.id + '\',1)">+</button>'
                  + '<span class="font-bold ml-2 text-price">' + formatarPreco(subtotal) + '</span>'
                  + '<button class="ml-2 text-red-500 hover:text-red-700 cursor-pointer border-0 bg-transparent" onclick="removerItem(\'' + item.id + '\')">🗑</button>'
                  + '</div></div>';
        });
        html += '</div>';
        html += '<div class="bg-gray-100 p-4 rounded-lg mt-3">'
              + '<div class="flex justify-between"><span>Subtotal:</span><span>' + formatarPreco(total) + '</span></div>'
              + '<div class="flex justify-between"><span>Frete:</span><span class="text-green-600">Gr\u00e1tis</span></div>'
              + '<div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t border-gray-300 text-brand-light">Total: <span>' + formatarPreco(total) + '</span></div>'
              + '</div>'
              + '<div class="flex gap-2 mt-3">'
              + '<button class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer bg-white" onclick="hideModal(\'modal-carrinho\')">Fechar</button>'
              + '</div>';
        conteudo.innerHTML = html;
    }
    showModal('modal-carrinho');
};

window.alterarQtd = function(id, delta) {
    var item = window.carrinho.find(function(i) { return i.id === id; });
    if (!item) return;
    item.quantidade += delta;
    if (item.quantidade <= 0) {
        window.carrinho = window.carrinho.filter(function(i) { return i.id !== id; });
    }
    window.atualizarBadge();
    window.abrirCarrinho();
};

window.removerItem = function(id) {
    window.carrinho = window.carrinho.filter(function(i) { return i.id !== id; });
    window.atualizarBadge();
    window.abrirCarrinho();
};

document.addEventListener("DOMContentLoaded", function() {
    if (typeof window.carrinho === 'undefined') window.carrinho = [];
    window.atualizarBadge();
});
