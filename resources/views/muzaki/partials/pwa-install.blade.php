<style>
    .pwa-install-btn {
        position: fixed;
        right: 16px;
        bottom: 16px;
        z-index: 1050;
        display: none;
        border: 0;
        border-radius: 999px;
        background: #fc8c04;
        color: #fff;
        font-weight: 800;
        box-shadow: 0 12px 30px rgba(252, 140, 4, .35);
        padding: .78rem 1rem;
    }
    .pwa-install-btn:hover,
    .pwa-install-btn:focus {
        background: #d97706;
        color: #fff;
    }
    @media (display-mode: standalone) {
        .pwa-install-btn { display: none !important; }
    }
</style>

<button type="button" class="pwa-install-btn" id="pwaInstallButton">Install Aplikasi</button>

<script>
    let muzakiInstallPrompt = null;
    const pwaInstallButton = document.getElementById('pwaInstallButton');

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js', { scope: '/' }).catch(function () {});
        });
    }

    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        muzakiInstallPrompt = event;
        if (pwaInstallButton && !window.matchMedia('(display-mode: standalone)').matches) {
            pwaInstallButton.style.display = 'inline-flex';
        }
    });

    pwaInstallButton?.addEventListener('click', async function () {
        if (!muzakiInstallPrompt) return;

        pwaInstallButton.style.display = 'none';
        muzakiInstallPrompt.prompt();
        await muzakiInstallPrompt.userChoice;
        muzakiInstallPrompt = null;
    });

    window.addEventListener('appinstalled', function () {
        if (pwaInstallButton) {
            pwaInstallButton.style.display = 'none';
        }
        muzakiInstallPrompt = null;
    });
</script>
