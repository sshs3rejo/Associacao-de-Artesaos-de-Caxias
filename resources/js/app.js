import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Swal.mixin({
    customClass: {
        popup: 'rounded-2xl shadow-2xl',
        confirmButton: 'px-5 py-2.5 rounded-lg font-semibold text-sm text-white border-0 cursor-pointer transition-all duration-200',
        cancelButton: 'px-5 py-2.5 rounded-lg font-semibold text-sm bg-gray-200 text-gray-700 border-0 cursor-pointer hover:bg-gray-300 transition-all duration-200',
        title: 'text-brand font-bold text-xl',
        htmlContainer: 'text-gray-600',
    },
    buttonsStyling: false,
    reverseButtons: true,
});

window.showModal = function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('modal-active');
    document.body.classList.add('overflow-hidden');
};

window.hideModal = function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('modal-active');
    document.body.classList.remove('overflow-hidden');
};

window.confirmarExclusao = function (button) {
    const form = button.closest('form');
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Esta ação não poderá ser desfeita!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed && form) form.submit();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.querySelector('.layout-main');
    if (mainContent) mainContent.classList.add('page-loaded');
    window.addEventListener('pageshow', (event) => {
        if (event.persisted && mainContent) mainContent.classList.add('page-loaded');
    });
});

Alpine.start();
