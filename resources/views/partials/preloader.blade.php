<!-- Nirmaan Minimalist White Preloader -->
<div id="nirmaan-preloader" 
     class="fixed inset-0 z-[999999] flex flex-col items-center justify-center bg-white text-slate-900 select-none transition-opacity duration-400 ease-out"
     style="opacity: 1; visibility: visible;">
    
    <!-- Minimalist Scanning Lens Loader Container -->
    <div class="flex flex-col items-center justify-center p-6">
        
        <!-- Animated Minimal Loader -->
        <div class="relative w-24 h-24 flex items-center justify-center">
            <span class="loader"></span>
        </div>

        <!-- Clean Minimal Typography -->
        <div class="mt-4 flex flex-col items-center text-center">
            <span class="text-xs sm:text-sm font-bold tracking-[0.3em] text-blue-950 uppercase font-sans">
                NIRMAAN
            </span>
            <span class="text-[10px] font-medium text-slate-400 tracking-[0.25em] uppercase mt-1.5 flex items-center gap-1.5">
                <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Loading</span>
            </span>
        </div>

    </div>
</div>

<style>
.loader {
  --color-1: #172554;
  --accent: #F59E0B;
  --size: 1px;

  width: calc(48 * var(--size));
  height: calc(48 * var(--size));
  display: block;
  margin: calc(20 * var(--size)) auto;
  position: relative;
  border: calc(3.5 * var(--size)) solid var(--color-1);
  border-radius: 50%;
  box-sizing: border-box;
  animation: animloader 2s ease-in-out infinite;
}

/* Inner Minimal Focal Dot */
.loader::before {
  content: '';
  box-sizing: border-box;
  width: calc(6 * var(--size));
  height: calc(6 * var(--size));
  background: var(--accent);
  border-radius: 50%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
}

/* Handle of the scanning lens */
.loader::after {
  content: '';
  box-sizing: border-box;
  width: calc(6 * var(--size));
  height: calc(24 * var(--size));
  background: var(--color-1);
  transform: rotate(-45deg);
  position: absolute;
  bottom: calc(-20 * var(--size));
  left: calc(46 * var(--size));
  border-radius: 3px;
}

@keyframes animloader {
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
</style>

<script>
(function() {
    const preloader = document.getElementById('nirmaan-preloader');
    if (!preloader) return;

    let isFinished = false;

    function finish() {
        if (isFinished) return;
        isFinished = true;
        preloader.style.opacity = '0';
        preloader.style.pointerEvents = 'none';
        setTimeout(() => {
            preloader.style.display = 'none';
            preloader.remove();
        }, 400);
    }

    // Allow user click to skip immediately
    preloader.addEventListener('click', finish);

    // Fade out smoothly once document has loaded
    if (document.readyState === 'complete') {
        setTimeout(finish, 800);
    } else {
        window.addEventListener('load', () => {
            setTimeout(finish, 800);
        });
    }

    // Failsafe ceiling: dismiss after 1.6s under any circumstances
    setTimeout(finish, 1600);
})();
</script>
