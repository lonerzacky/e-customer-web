/**
 * Toggle show/hide untuk field password (reusable).
 *
 * Pakai event delegation di document, jadi otomatis bekerja untuk field yang
 * dirender belakangan (mis. di dalam modal) tanpa init manual per halaman.
 *
 * Markup minimal — bungkus input dalam wrapper `.relative`, tambahkan tombol
 * ber-atribut `data-toggle-password`:
 *
 *   <div class="relative">
 *     <input type="password" id="newPassword" class="... pr-10">
 *     <button type="button" data-toggle-password aria-label="Lihat password">
 *       <i class="ki-solid ki-eye text-xl"></i>
 *     </button>
 *   </div>
 *
 * Opsi target eksplisit: `data-toggle-password="idInput"` (kalau tombol tidak
 * berada di wrapper yang sama dengan input-nya).
 *
 * Ikon yang didukung otomatis:
 *  - KeenIcon: `<i class="ki-eye">` <-> `ki-eye-slash`.
 *  - Sepasang SVG: elemen ber-atribut `data-eye-show` & `data-eye-hide`
 *    (di-toggle class `hidden`).
 */
(function () {
    function findInput(btn) {
        const targetId = btn.getAttribute('data-toggle-password');
        if (targetId) return document.getElementById(targetId);
        const wrap = btn.closest('.relative') || btn.parentElement;
        return wrap ? wrap.querySelector('input') : null;
    }

    function toggle(btn) {
        const input = findInput(btn);
        if (!input) return;

        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Lihat password');

        const ki = btn.querySelector('i.ki-eye, i.ki-eye-slash');
        if (ki) {
            ki.classList.toggle('ki-eye', !show);
            ki.classList.toggle('ki-eye-slash', show);
        }

        const eyeShow = btn.querySelector('[data-eye-show]');
        const eyeHide = btn.querySelector('[data-eye-hide]');
        if (eyeShow && eyeHide) {
            eyeShow.classList.toggle('hidden', show);
            eyeHide.classList.toggle('hidden', !show);
        }
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-toggle-password]');
        if (!btn) return;
        e.preventDefault();
        toggle(btn);
    });
})();
