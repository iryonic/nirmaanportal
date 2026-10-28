<!-- Nirmaan Minimalist Fullscreen White Preloader -->
<style id="nirmaan-preloader-critical-css">
    #nirmaan-preloader {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        background-color: #ffffff !important;
        background: #ffffff !important;
        z-index: 2147483647 !important; /* Highest valid 32-bit z-index */
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
        user-select: none !important;
        -webkit-user-select: none !important;
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.4s ease-out !important;
        pointer-events: all !important;
        overflow: hidden !important;
    }

    #nirmaan-preloader.preloader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }

    .nirmaan-loader-wrap {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 24px !important;
        box-sizing: border-box !important;
    }

    .nirmaan-loader-icon-box {
        position: relative !important;
        width: 96px !important;
        height: 96px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .nirmaan-lens-loader {
        --color-1: #172554;
        --accent: #F59E0B;
        --size: 1px;

        width: calc(48 * var(--size)) !important;
        height: calc(48 * var(--size)) !important;
        display: block !important;
        margin: 0 auto !important;
        position: relative !important;
        border: calc(3.5 * var(--size)) solid var(--color-1) !important;
        border-radius: 50% !important;
        box-sizing: border-box !important;
        animation: nirmaanAnimLoader 2s ease-in-out infinite !important;
    }

    /* Inner Minimal Focal Dot */
    .nirmaan-lens-loader::before {
        content: '' !important;
        box-sizing: border-box !important;
        width: calc(6 * var(--size)) !important;
        height: calc(6 * var(--size)) !important;
        background: var(--accent) !important;
        border-radius: 50% !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        box-shadow: 0 0 10px rgba(245, 158, 11, 0.7) !important;
    }

    /* Handle of the scanning lens */
    .nirmaan-lens-loader::after {
        content: '' !important;
        box-sizing: border-box !important;
        width: calc(6 * var(--size)) !important;
        height: calc(24 * var(--size)) !important;
        background: var(--color-1) !important;
        transform: rotate(-45deg) !important;
        position: absolute !important;
        bottom: calc(-20 * var(--size)) !important;
        left: calc(46 * var(--size)) !important;
        border-radius: 3px !important;
    }

    @keyframes nirmaanAnimLoader {
        0% {
            transform: translate(calc(-10 * var(--size)), calc(-10 * var(--size)));
        }
        25% {
            transform: translate(calc(-10 * var(--size)), calc(10 * var(--size)));
        }
        50% {
            transform: translate(calc(10 * var(--size)), calc(10 * var(--size)));
        }
        75% {
            transform: translate(calc(10 * var(--size)), calc(-10 * var(--size)));
        }
        100% {
            transform: translate(calc(-10 * var(--size)), calc(-10 * var(--size)));
        }
    }

    .nirmaan-loader-brand {
        margin-top: 16px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    .nirmaan-loader-brand-title {
        font-size: 13px !important;
        font-weight: 800 !important;
        letter-spacing: 0.3em !important;
        color: #172554 !important;
        text-transform: uppercase !important;
        line-height: 1.2 !important;
    }

    .nirmaan-loader-brand-subtitle {
        font-size: 10px !important;
        font-weight: 600 !important;
        letter-spacing: 0.25em !important;
        color: #94a3b8 !important;
        text-transform: uppercase !important;
        margin-top: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    .nirmaan-loader-dot-pulse {
        width: 5px !important;
        height: 5px !important;
        border-radius: 50% !important;
        background-color: #f59e0b !important;
        animation: nirmaanPulseDot 1.4s ease-in-out infinite !important;
        display: inline-block !important;
    }

    @keyframes nirmaanPulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(0.7); }
    }
</style>

<div id="nirmaan-preloader" 
     style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; height: 100dvh !important; background-color: #ffffff !important; background: #ffffff !important; z-index: 2147483647 !important; display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; margin: 0 !important; padding: 0 !important; box-sizing: border-box !important; opacity: 1 !important; visibility: visible !important; pointer-events: all !important; overflow: hidden !important;">
    
    <div class="nirmaan-loader-wrap">
        <div class="nirmaan-loader-icon-box">
            <span class="nirmaan-lens-loader"></span>
        </div>

        <div class="nirmaan-loader-brand">
            <span class="nirmaan-loader-brand-title">NIRMAAN</span>
            <span class="nirmaan-loader-brand-subtitle">
                <span class="nirmaan-loader-dot-pulse"></span>
                <span>Loading</span>
            </span>
        </div>
    </div>
</div>

<script>
(function() {
    const preloader = document.getElementById('nirmaan-preloader');
    if (!preloader) return;

    // Lock document scroll while loader is active to hide content behind solid white
    try {
        document.documentElement.style.overflow = 'hidden';
    } catch(e) {}

    let isDismissed = false;

    function dismissPreloader() {
        if (isDismissed) return;
        isDismissed = true;
        
        try {
            document.documentElement.style.overflow = '';
        } catch(e) {}

        preloader.classList.add('preloader-hidden');
        preloader.style.opacity = '0';
        preloader.style.visibility = 'hidden';
        preloader.style.pointerEvents = 'none';

        setTimeout(function() {
            if (preloader.parentNode) {
                preloader.parentNode.removeChild(preloader);
            }
        }, 450);
    }

    // Allow user to click anywhere to dismiss immediately
    preloader.addEventListener('click', dismissPreloader);

    // Dismiss cleanly after window load
    if (document.readyState === 'complete') {
        setTimeout(dismissPreloader, 600);
    } else {
        window.addEventListener('load', function() {
            setTimeout(dismissPreloader, 600);
        });
    }

    // Safe ceiling fallback: guarantees dismissal even on slow network connections
    setTimeout(dismissPreloader, 1800);
})();
</script>
