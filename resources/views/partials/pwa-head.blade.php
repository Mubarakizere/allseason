<!-- PWA & Favicon Meta Tags -->
<link rel="icon" type="image/x-icon" href="/favicon.ico" />
<link rel="icon" type="image/png" sizes="16x16" href="/favicon_io/favicon-16x16.png" />
<link rel="icon" type="image/png" sizes="32x32" href="/favicon_io/favicon-32x32.png" />
<link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png" />
<link rel="manifest" href="/manifest.json" />

<meta name="theme-color" content="#2d4a3e" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="AllSeason" />
<meta name="application-name" content="AllSeason" />
<meta name="msapplication-TileColor" content="#2d4a3e" />
<meta name="msapplication-TileImage" content="/favicon_io/android-chrome-192x192.png" />

<!-- PWA Install Banner & Service Worker Script -->
<style>
    .pwa-install-banner {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        background: #14161a;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.45);
        border-radius: 16px;
        padding: 14px 18px;
        display: none;
        align-items: center;
        gap: 14px;
        max-width: 380px;
        width: calc(100% - 32px);
        animation: pwaSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(8px);
    }
    @keyframes pwaSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .pwa-install-banner .pwa-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .pwa-install-banner .pwa-content {
        flex: 1;
        min-width: 0;
    }
    .pwa-install-banner .pwa-title {
        font-size: 13.5px;
        font-weight: 700;
        margin: 0 0 2px 0;
        color: #ffffff;
        line-height: 1.2;
    }
    .pwa-install-banner .pwa-desc {
        font-size: 11.5px;
        color: #9ca3af;
        margin: 0;
        line-height: 1.3;
    }
    .pwa-install-banner .pwa-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .pwa-btn-install {
        background: #2d4a3e;
        color: #ffffff !important;
        border: none;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .pwa-btn-install:hover {
        background: #3a5e50;
    }
    .pwa-btn-close {
        background: transparent;
        border: none;
        color: #6b7280;
        font-size: 16px;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
        border-radius: 4px;
    }
    .pwa-btn-close:hover {
        color: #ffffff;
    }
    @media (max-width: 575.98px) {
        .pwa-install-banner {
            bottom: 16px;
            left: 16px;
            right: 16px;
            width: auto;
        }
    }
</style>

<div id="pwaInstallBanner" class="pwa-install-banner" role="dialog" aria-live="polite">
    <img src="/favicon_io/android-chrome-192x192.png" alt="All Season Garden" class="pwa-icon">
    <div class="pwa-content">
        <p class="pwa-title">Install All Season Garden</p>
        <p class="pwa-desc">Add to Home Screen for quick access & offline mode</p>
    </div>
    <div class="pwa-actions">
        <button id="pwaInstallBtn" class="pwa-btn-install">Install</button>
        <button id="pwaCloseBtn" class="pwa-btn-close" aria-label="Close">&times;</button>
    </div>
</div>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('PWA ServiceWorker registered with scope:', registration.scope);
                })
                .catch(function(err) {
                    console.log('PWA ServiceWorker registration failed:', err);
                });
        });
    }

    let deferredPwaPrompt;
    const pwaBanner = document.getElementById('pwaInstallBanner');
    const pwaInstallBtn = document.getElementById('pwaInstallBtn');
    const pwaCloseBtn = document.getElementById('pwaCloseBtn');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPwaPrompt = e;
        if (!localStorage.getItem('pwa_dismissed')) {
            setTimeout(function() {
                if (pwaBanner) pwaBanner.style.display = 'flex';
            }, 2500);
        }
    });

    if (pwaInstallBtn) {
        pwaInstallBtn.addEventListener('click', async () => {
            if (!deferredPwaPrompt) return;
            deferredPwaPrompt.prompt();
            const { outcome } = await deferredPwaPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log('User accepted the PWA install prompt');
            }
            deferredPwaPrompt = null;
            if (pwaBanner) pwaBanner.style.display = 'none';
        });
    }

    if (pwaCloseBtn) {
        pwaCloseBtn.addEventListener('click', () => {
            if (pwaBanner) pwaBanner.style.display = 'none';
            localStorage.setItem('pwa_dismissed', 'true');
        });
    }

    window.addEventListener('appinstalled', () => {
        console.log('All Season Garden PWA was installed successfully');
        if (pwaBanner) pwaBanner.style.display = 'none';
    });
</script>
