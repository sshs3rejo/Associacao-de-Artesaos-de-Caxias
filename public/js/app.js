/* ======================== MODAIS ======================== */
window.showModal = function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('modal-active');
    document.body.classList.add('overflow-hidden');
};

window.hideModal = function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('modal-active');
    document.body.classList.remove('overflow-hidden');
};

/* ======================== CONFIRMAÇÃO CUSTOMIZADA ======================== */
var confirmCallback = null;

window.showConfirm = function (message, callback, title) {
    title = title || 'Tem certeza?';
    document.getElementById('confirm-title').textContent = title;
    document.getElementById('confirm-message').textContent = message;
    confirmCallback = callback;
    showModal('modal-confirm');
};

var confirmBtn = document.getElementById('confirm-btn-yes');
if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
        hideModal('modal-confirm');
        if (typeof confirmCallback === 'function') {
            confirmCallback();
            confirmCallback = null;
        }
    });
}

window.confirmarExclusao = function (button) {
    var form = button.closest('form');
    showConfirm('Tem certeza? Esta ação não poderá ser desfeita!', function () {
        if (form) form.submit();
    });
};

/* ======================== TOAST ======================== */
window.mostrarToast = function (mensagem, tipo) {
    tipo = tipo || 'success';
    var toast = document.getElementById('toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-notification';
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }
    toast.textContent = mensagem;
    toast.className = 'toast-notification toast-' + tipo + ' toast-show';
    setTimeout(function () { toast.className = 'toast-notification toast-' + tipo; }, 3000);
};

/* ======================== NAVBAR MOBILE ======================== */
window.toggleMobileMenu = function () {
    var menu = document.getElementById('mobile-menu');
    var root = document.getElementById('navbar-root');
    var hamburger = document.getElementById('hamburger-icon');
    var closeIcon = document.getElementById('close-icon');
    if (menu) {
        menu.classList.toggle('hidden');
        menu.classList.toggle('block');
    }
    if (hamburger && closeIcon) {
        hamburger.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
    if (root) {
        root.classList.toggle('menu-open');
        document.body.classList.toggle('overflow-hidden');
    }
};

/* ======================== ADMIN/USER DROPDOWN ======================== */
window.toggleDropdown = function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    var isOpen = el.style.display !== 'none';
    document.querySelectorAll('.admin-dropdown').forEach(function (d) { d.style.display = 'none'; });
    el.style.display = isOpen ? 'none' : 'block';
};

document.addEventListener('click', function (e) {
    if (!e.target.closest) return;
    var dd = e.target.closest('.admin-dropdown-wrap');
    if (!dd) {
        document.querySelectorAll('.admin-dropdown').forEach(function (d) { d.style.display = 'none'; });
    }
});

/* ======================== NAVBAR SCROLL EFFECT ======================== */
(function () {
    var navbar = document.getElementById('navbar-root');
    if (!navbar) return;
    var ticking = false;
    function updateNavbar() {
        navbar.classList.toggle('scrolled', window.scrollY > 80);
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { requestAnimationFrame(updateNavbar); ticking = true; }
    });
    updateNavbar();
})();

/* ======================== PAGE LOADED ======================== */
document.addEventListener('DOMContentLoaded', function () {
    var mainContent = document.querySelector('.layout-main');
    if (mainContent) mainContent.classList.add('page-loaded');
    window.addEventListener('pageshow', function (event) {
        if (event.persisted && mainContent) mainContent.classList.add('page-loaded');
    });
});

/* ======================== MOBILE MENU ESCAPE KEY ======================== */
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        var menu = document.getElementById('mobile-menu');
        if (menu && !menu.classList.contains('hidden')) {
            toggleMobileMenu();
        }
    }
});

/* ======================== MÁSCARA DE TELEFONE ======================== */
window.mascaraTelefone = function (input) {
    var value = input.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length <= 2) {
        input.value = value;
    } else if (value.length <= 7) {
        input.value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
    } else {
        input.value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7, 11);
    }
};

/* ======================== MÁSCARA DE CPF ======================== */
window.mascaraCPF = function (input) {
    var value = input.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length <= 3) {
        input.value = value;
    } else if (value.length <= 6) {
        input.value = value.slice(0, 3) + '.' + value.slice(3);
    } else if (value.length <= 9) {
        input.value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6);
    } else {
        input.value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9) + '-' + value.slice(9, 11);
    }
};

/* ======================== VALIDAÇÃO DE ANEXO DE IMAGEM (CLIENTE) ======================== */
window.validarTamanhoImagem = function (input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            if (typeof window.mostrarToast === 'function') {
                window.mostrarToast('A imagem selecionada é muito grande! Escolha um arquivo de até 10MB.', 'danger');
            } else {
                alert('A imagem selecionada é muito grande! Escolha um arquivo de até 10MB.');
            }
            input.value = ''; // Limpa o input
            
            // Também tenta limpar qualquer preview de imagem se existir
            var preview = document.getElementById('preview-imagem');
            var img = document.getElementById('preview-img');
            if (preview && img) {
                preview.style.display = 'none';
                img.src = '';
            }
            return false;
        }
    }
    return true;
};


/* ======================== PAGE LOADER (navigation) ======================== */
window.showPageLoader = function () {
    var loader = document.getElementById('page-loader');
    if (loader) loader.classList.add('active');
};

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a:not([data-no-loader])').forEach(function (a) {
        var href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('http') || a.hasAttribute('data-no-loader')) return;
        a.addEventListener('click', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            var loader = document.getElementById('page-loader');
            if (loader) loader.classList.add('active');
        });
    });
    var loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('active');
});
