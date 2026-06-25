/**
 * Adaptasi layar penuh untuk Android Chrome & browser mobile.
 * Menyesuaikan tinggi viewport (address bar / gesture nav) dan warna bilah sistem.
 */
(function () {
    const root = document.documentElement;
    const themeColor = getComputedStyle(document.body).getPropertyValue('--theme-color').trim()
        || getComputedStyle(root).getPropertyValue('--color-primary').trim()
        || '#064E3B';

    function isAndroid() {
        return /Android/i.test(navigator.userAgent);
    }

    function updateViewportSize() {
        const vv = window.visualViewport;
        const height = vv ? vv.height : window.innerHeight;
        const width = vv ? vv.width : window.innerWidth;

        root.style.setProperty('--inv-vh', `${Math.round(height)}px`);
        root.style.setProperty('--inv-vw', `${Math.round(width)}px`);

        // Android gesture nav: tambah ruang bawah jika inset tidak terdeteksi
        if (isAndroid() && !CSS.supports('padding-bottom: env(safe-area-inset-bottom)')) {
            root.style.setProperty('--chrome-bottom', '12px');
        }
    }

    function updateThemeColor() {
        let meta = document.querySelector('meta[name="theme-color"]');
        if (!meta) {
            meta = document.createElement('meta');
            meta.name = 'theme-color';
            document.head.appendChild(meta);
        }
        meta.content = themeColor;
    }

    function lockScrollOnOpen() {
        if (!document.body.classList.contains('inv-open')) return;
        updateViewportSize();
    }

    updateViewportSize();
    updateThemeColor();

    window.addEventListener('resize', updateViewportSize, { passive: true });
    window.addEventListener('orientationchange', () => {
        setTimeout(updateViewportSize, 100);
        setTimeout(updateViewportSize, 350);
    }, { passive: true });

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', updateViewportSize, { passive: true });
        window.visualViewport.addEventListener('scroll', updateViewportSize, { passive: true });
    }

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) updateViewportSize();
    });

    // Saat undangan dibuka, hitung ulang tinggi layar
    const btnOpen = document.getElementById('btn-open');
    btnOpen?.addEventListener('click', () => {
        setTimeout(updateViewportSize, 50);
        setTimeout(updateViewportSize, 700);
    }, { passive: true });

    // Observer class inv-open
    const observer = new MutationObserver(lockScrollOnOpen);
    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

    // Hindari bounce scroll keluar halaman di Android
    document.addEventListener('touchmove', (e) => {
        if (!document.body.classList.contains('inv-open')) return;
        const scroller = document.getElementById('inv-scroll');
        if (scroller && !scroller.contains(e.target)) {
            e.preventDefault();
        }
    }, { passive: false });
})();
