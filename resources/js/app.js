import './bootstrap';
import Alpine from 'alpinejs';

// ─── Alpine.js Setup ────────────────────────────────────────────────────────
window.Alpine = Alpine;
Alpine.start();

// ─── Dark Mode ──────────────────────────────────────────────────────────────
const theme = localStorage.getItem('theme') || 'light';
if (theme === 'dark') {
    document.documentElement.classList.add('dark');
}

window.toggleDarkMode = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

// ─── Toast Notification System ───────────────────────────────────────────────
window.Toast = {
    container: null,

    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },

    show(message, type = 'success', duration = 4000) {
        this.init();

        const icons = {
            success: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            warning: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `${icons[type] || ''}<span class="flex-1">${message}</span>
            <button onclick="this.parentElement.remove()" class="opacity-70 hover:opacity-100 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>`;

        this.container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success: (msg, d) => Toast.show(msg, 'success', d),
    error: (msg, d) => Toast.show(msg, 'error', d),
    warning: (msg, d) => Toast.show(msg, 'warning', d),
    info: (msg, d) => Toast.show(msg, 'info', d),
};

// ─── CSRF Axios Setup ────────────────────────────────────────────────────────
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ─── Confirm Delete Modal ─────────────────────────────────────────────────────
window.confirmDelete = function (formId) {
    if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
        document.getElementById(formId).submit();
    }
};

// ─── Sidebar Toggle ──────────────────────────────────────────────────────────
window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
};

// ─── Auto-hide Flash Messages ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Auto-show server-side flash messages as toasts
    const flashSuccess = document.getElementById('flash-success');
    const flashError = document.getElementById('flash-error');

    if (flashSuccess) Toast.success(flashSuccess.dataset.message);
    if (flashError) Toast.error(flashError.dataset.message);

    // Initialize tooltips
    initTooltips();

    // Initialize loading states on forms
    initFormLoading();
});

function initTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(el => {
        el.addEventListener('mouseenter', () => {
            const tip = document.createElement('div');
            tip.className = 'fixed z-50 bg-gray-900 text-white text-xs rounded px-2 py-1 pointer-events-none';
            tip.textContent = el.dataset.tooltip;
            tip.id = 'active-tooltip';
            document.body.appendChild(tip);

            const rect = el.getBoundingClientRect();
            tip.style.top = `${rect.bottom + 6}px`;
            tip.style.left = `${rect.left + rect.width / 2 - tip.offsetWidth / 2}px`;
        });

        el.addEventListener('mouseleave', () => {
            document.getElementById('active-tooltip')?.remove();
        });
    });
}

function initFormLoading() {
    document.querySelectorAll('form[data-loading]').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner w-4 h-4"></span> Processing...';
            }
        });
    });
}
