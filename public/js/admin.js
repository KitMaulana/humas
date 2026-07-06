/* ═══════════════════════════════════════════════════
   SIDACHEERS — Admin Panel JavaScript
═══════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

    // ── SIDEBAR TOGGLE (mobile) ──────────────────
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    // ── AUTO-DISMISS ALERTS ──────────────────────
    document.querySelectorAll('.alert[data-dismiss]').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .3s, transform .3s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });

    // ── DELETE CONFIRMATION MODAL ────────────────
    window.confirmDelete = function (formId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById(formId);
        if (modal && form) {
            modal.classList.add('show');
            document.getElementById('deleteConfirmBtn').onclick = () => form.submit();
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.remove('show');
    };

    // ── SLUG AUTO-GENERATION ─────────────────────
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    if (nameInput && slugInput && !slugInput.dataset.noAuto) {
        nameInput.addEventListener('input', () => {
            if (!slugInput.dataset.manual) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        });
        slugInput.addEventListener('input', () => {
            slugInput.dataset.manual = 'true';
        });
    }

    // ── FILE UPLOAD PREVIEW ──────────────────────
    document.querySelectorAll('.file-upload input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            const preview = this.closest('.file-upload').querySelector('.file-preview');
            if (preview && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
