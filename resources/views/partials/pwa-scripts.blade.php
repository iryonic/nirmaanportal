<!-- PWA Service Worker Registration & Install Listener -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then((registration) => {
                    console.log('[Nirmaan PWA] Registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.warn('[Nirmaan PWA] Registration error:', error);
                });
        });
    }

    // Deferred install prompt handler
    window.addEventListener('beforeinstallprompt', (e) => {
        // Stash event so app can trigger install if desired
        window.deferredPrompt = e;
        window.dispatchEvent(new CustomEvent('pwa-installable'));
    });

    window.addEventListener('appinstalled', () => {
        window.deferredPrompt = null;
        console.log('[Nirmaan PWA] Installed successfully!');
    });
</script>
