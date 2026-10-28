<!-- Nirmaan "Mission Launch" Interactive & Fun Preloader -->
<div id="nirmaan-preloader" 
     class="fixed inset-0 z-[999999] flex flex-col items-center justify-center bg-[#050914] text-white select-none overflow-hidden"
     style="opacity: 1; visibility: visible; transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
    
    <!-- Background Canvas for Interactive Floating Stars & Sparkles -->
    <canvas id="preloader-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-70"></canvas>

    <!-- Radar Tactical Concentric Circles -->
    <div class="absolute w-[440px] h-[440px] rounded-full border border-blue-500/15 pointer-events-none z-0 preloader-pulse-ring"></div>
    <div class="absolute w-[320px] h-[320px] rounded-full border border-dashed border-amber-400/20 pointer-events-none z-0 preloader-spin-slow"></div>
    <div class="absolute w-[200px] h-[200px] rounded-full border border-blue-400/25 pointer-events-none z-0"></div>

    <!-- Ambient Glowing Nebulae -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[420px] h-[420px] rounded-full bg-blue-600/20 blur-[100px] pointer-events-none z-0 animate-pulse"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[220px] h-[220px] rounded-full bg-amber-500/20 blur-[70px] pointer-events-none z-0"></div>

    <!-- Center Mission Core Container -->
    <div class="relative z-20 flex flex-col items-center text-center px-6 max-w-sm w-full cursor-pointer" id="preloader-core">
        
        <!-- Radar Scan & Orbiting Fighter Jet Container -->
        <div class="relative w-36 h-36 flex items-center justify-center mb-4">
            
            <!-- Radar Scanner Sweep Line -->
            <div class="absolute inset-0 rounded-full border border-amber-400/25 pointer-events-none overflow-hidden">
                <div class="w-full h-full preloader-radar-beam"></div>
            </div>

            <!-- Orbiting Fighter Jet -->
            <div class="absolute inset-0 preloader-orbit-system pointer-events-none">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 flex items-center justify-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-blue-950 shadow-[0_0_18px_#F59E0B] text-xs font-black transform -rotate-45">
                        ✈️
                    </span>
                </div>
            </div>

            <!-- Central Holographic Shield / Badge -->
            <div class="relative w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-900/90 via-blue-950 to-[#07112c] border-2 border-amber-400/40 p-3 shadow-[0_0_35px_rgba(30,58,138,0.8)] backdrop-blur-xl flex items-center justify-center preloader-float">
                
                <!-- Tactical HUD Crosshair Accents -->
                <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-4 h-0.5 bg-amber-400/60"></div>
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-4 h-0.5 bg-amber-400/60"></div>
                <div class="absolute top-1/2 -left-1 -translate-y-1/2 w-0.5 h-4 bg-amber-400/60"></div>
                <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-0.5 h-4 bg-amber-400/60"></div>

                <!-- Animated Nirmaan Emblem -->
                <svg viewBox="0 0 100 100" class="w-full h-full overflow-visible" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FEF08A" />
                            <stop offset="50%" stop-color="#F59E0B" />
                            <stop offset="100%" stop-color="#D97706" />
                        </linearGradient>
                    </defs>

                    <!-- Golden Swoosh -->
                    <path d="M12 65 C18 20, 60 16, 88 40 C65 24, 30 28, 20 65 Z" 
                          fill="url(#goldGrad)" />
                    
                    <path d="M16 55 C24 28, 55 24, 76 42" 
                          stroke="#FDE047" 
                          stroke-width="3" 
                          stroke-linecap="round" />

                    <!-- Monogram "N" -->
                    <path d="M28 78 L28 32 L40 32 L60 62 L60 32 L72 32 L72 78 L60 78 L40 48 L40 78 Z" 
                          fill="#FFFFFF" />

                    <!-- Glowing Beacon -->
                    <circle cx="82" cy="28" r="4.5" fill="#FDE047" class="animate-ping" opacity="0.8" />
                    <circle cx="82" cy="28" r="4" fill="url(#goldGrad)" />
                </svg>
            </div>
        </div>

        <!-- Typography & Brand Pill -->
        <div class="space-y-1 mb-4">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-950/80 border border-amber-400/30 text-[10px] font-mono tracking-widest text-amber-300 uppercase shadow-inner">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>MISSION: PREPARATION</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-black tracking-[0.22em] text-white uppercase font-sans drop-shadow-[0_2px_15px_rgba(255,255,255,0.25)]">
                NIRMAAN
            </h1>
            
            <p class="text-[10px] sm:text-[11px] font-semibold text-blue-200/80 tracking-[0.3em] uppercase">
                DEFENCE &bull; SSB &bull; ACADEMY
            </p>
        </div>

        <!-- Fun Cadet Status Line -->
        <div class="h-6 flex items-center justify-center mb-3">
            <span id="preloader-status" class="text-xs sm:text-[13px] font-mono text-amber-300 font-semibold transition-all duration-200 tracking-wide">
                🎯 Calibrating tactical radar...
            </span>
        </div>

        <!-- Precision Energy Bar -->
        <div class="w-64 sm:w-72 mb-3">
            <div class="relative h-2.5 w-full bg-blue-950/90 rounded-full border border-blue-700/60 p-0.5 overflow-hidden shadow-[inset_0_1px_4px_rgba(0,0,0,0.8)]">
                <!-- Hairline segmented grid marks -->
                <div class="absolute inset-0 flex justify-between px-1.5 pointer-events-none opacity-30 z-20">
                    <span class="w-[1px] h-full bg-white"></span>
                    <span class="w-[1px] h-full bg-white"></span>
                    <span class="w-[1px] h-full bg-white"></span>
                    <span class="w-[1px] h-full bg-white"></span>
                    <span class="w-[1px] h-full bg-white"></span>
                </div>
                
                <!-- Glowing Progress Fill -->
                <div id="preloader-bar" 
                     class="h-full bg-gradient-to-r from-blue-500 via-amber-400 to-yellow-300 rounded-full transition-all duration-100 ease-out shadow-[0_0_14px_rgba(251,191,36,0.9)] relative"
                     style="width: 0%;">
                    <div class="absolute right-0 top-0 bottom-0 w-2 bg-white rounded-full animate-ping opacity-75"></div>
                </div>
            </div>
        </div>

        <!-- Telemetry & Percentage -->
        <div class="flex items-center justify-between w-64 sm:w-72 text-xs font-mono">
            <span class="text-slate-400 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <span>WARP ENGINE</span>
            </span>
            <span id="preloader-pct" class="text-amber-400 font-black text-sm tracking-wider">0%</span>
        </div>

        <!-- Interactive Boost Button -->
        <button type="button" 
                id="preloader-boost-btn"
                onclick="window.boostLaunch(event)" 
                class="mt-5 group relative inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-blue-900/80 to-blue-950 hover:from-amber-500/20 hover:to-amber-600/20 border border-amber-400/40 text-[11px] font-bold text-amber-300 hover:text-amber-200 uppercase tracking-wider transition-all duration-150 transform hover:scale-105 active:scale-95 shadow-lg">
            <span>🚀 Tap to Boost Launch</span>
            <span class="text-[9px] bg-amber-400/20 px-1.5 py-0.5 rounded text-amber-300 font-mono">+Fast</span>
        </button>

    </div>
</div>

<style>
/* Micro Animations & Tactical Effects */
@keyframes preloaderFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-6px) rotate(1deg); }
}

@keyframes preloaderOrbit {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes preloaderSpinReverse {
    0% { transform: rotate(360deg); }
    100% { transform: rotate(0deg); }
}

@keyframes preloaderPulseRing {
    0% { transform: scale(0.96); opacity: 0.15; }
    50% { transform: scale(1.04); opacity: 0.35; }
    100% { transform: scale(0.96); opacity: 0.15; }
}

@keyframes preloaderRadarSweep {
    0% { transform: rotate(0deg); opacity: 0.8; }
    50% { opacity: 0.4; }
    100% { transform: rotate(360deg); opacity: 0.8; }
}

.preloader-float {
    animation: preloaderFloat 3s ease-in-out infinite;
}

.preloader-orbit-system {
    animation: preloaderOrbit 2.4s linear infinite;
}

.preloader-spin-slow {
    animation: preloaderSpinReverse 16s linear infinite;
}

.preloader-pulse-ring {
    animation: preloaderPulseRing 3.5s ease-in-out infinite;
}

.preloader-radar-beam {
    background: conic-gradient(from 0deg, transparent 0deg, transparent 270deg, rgba(245, 158, 11, 0.45) 360deg);
    animation: preloaderRadarSweep 2.2s linear infinite;
}

/* Sparkle Particle on Click */
.preloader-sparkle {
    position: absolute;
    pointer-events: none;
    border-radius: 50%;
    animation: sparklePop 0.5s ease-out forwards;
    z-index: 9999999;
}

@keyframes sparklePop {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    100% { transform: translate(var(--dx), var(--dy)) scale(0); opacity: 0; }
}
</style>

<script>
(function() {
    const preloader = document.getElementById('nirmaan-preloader');
    if (!preloader) return;

    const bar = document.getElementById('preloader-bar');
    const pct = document.getElementById('preloader-pct');
    const status = document.getElementById('preloader-status');

    let current = 0;
    let target = 8;
    let isFinished = false;

    // --- Interactive Starfield Canvas ---
    const canvas = document.getElementById('preloader-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });

        const particles = Array.from({ length: 40 }, () => ({
            x: Math.random() * width,
            y: Math.random() * height,
            radius: Math.random() * 1.8 + 0.6,
            color: Math.random() > 0.6 ? '#F59E0B' : '#60A5FA',
            vx: (Math.random() - 0.5) * 0.4,
            vy: (Math.random() - 0.5) * 0.4 - 0.3,
            alpha: Math.random() * 0.7 + 0.3
        }));

        function renderStars() {
            if (isFinished) return;
            ctx.clearRect(0, 0, width, height);

            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                if (p.y < 0) p.y = height;
                if (p.x < 0) p.x = width;
                if (p.x > width) p.x = 0;

                ctx.save();
                ctx.globalAlpha = p.alpha;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.restore();
            });

            requestAnimationFrame(renderStars);
        }
        renderStars();
    }

    // --- Dynamic Cadet Status Milestones ---
    const statusMilestones = [
        { at: 0, text: '🎯 Calibrating tactical radar...' },
        { at: 22, text: '🎖️ Polishing cadet medals...' },
        { at: 45, text: '🧠 Syncing mock tests & questions...' },
        { at: 68, text: '🚀 Engaging warp thrusters...' },
        { at: 88, text: '⚡ SSB Command Center ready...' },
        { at: 100, text: '🏆 Mission Launch! Welcome Officer!' }
    ];

    function updateStatus(val) {
        for (let i = statusMilestones.length - 1; i >= 0; i--) {
            if (val >= statusMilestones[i].at) {
                if (status && status.textContent !== statusMilestones[i].text) {
                    status.style.opacity = '0.5';
                    setTimeout(() => {
                        if (status) {
                            status.textContent = statusMilestones[i].text;
                            status.style.opacity = '1';
                        }
                    }, 50);
                }
                break;
            }
        }
    }

    // --- Interactive Sparkle Spawner ---
    function spawnSparks(x, y) {
        for (let i = 0; i < 14; i++) {
            const spark = document.createElement('div');
            spark.className = 'preloader-sparkle';
            const size = Math.random() * 6 + 3;
            const angle = Math.random() * Math.PI * 2;
            const dist = Math.random() * 80 + 20;
            const dx = Math.cos(angle) * dist + 'px';
            const dy = Math.sin(angle) * dist + 'px';
            const isGold = Math.random() > 0.4;

            spark.style.width = size + 'px';
            spark.style.height = size + 'px';
            spark.style.left = x + 'px';
            spark.style.top = y + 'px';
            spark.style.backgroundColor = isGold ? '#FBBF24' : '#60A5FA';
            spark.style.boxShadow = isGold ? '0 0 10px #F59E0B' : '0 0 10px #3B82F6';
            spark.style.setProperty('--dx', dx);
            spark.style.setProperty('--dy', dy);

            document.body.appendChild(spark);
            setTimeout(() => spark.remove(), 550);
        }
    }

    // Interactive Boost Feature
    window.boostLaunch = function(e) {
        if (e) {
            e.stopPropagation();
            const rect = preloader.getBoundingClientRect();
            spawnSparks(e.clientX || (rect.width / 2), e.clientY || (rect.height / 2));
        }
        target = Math.min(100, target + 35);
        if (target >= 100) {
            current = 98;
        }
    };

    // Clicking anywhere on preloader gives +25% boost
    preloader.addEventListener('click', (e) => {
        spawnSparks(e.clientX, e.clientY);
        target = Math.min(100, target + 25);
    });

    // Deliberate pacing so the user enjoys the animation (~1.8 seconds default, or instant on boost)
    const startTime = Date.now();
    const minDuration = 1800; // 1.8 seconds total trip

    const progressInterval = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const naturalPct = Math.min(100, Math.floor((elapsed / minDuration) * 100));

        // Target tracks higher of natural progress or user clicks
        target = Math.max(target, naturalPct);

        if (current < target) {
            const step = Math.max(1, Math.ceil((target - current) * 0.35));
            current = Math.min(target, current + step);
            if (bar) bar.style.width = current + '%';
            if (pct) pct.textContent = current + '%';
            updateStatus(current);
        }

        if (current >= 100 && !isFinished) {
            isFinished = true;
            clearInterval(progressInterval);
            finishMission();
        }
    }, 25);

    function finishMission() {
        if (bar) bar.style.width = '100%';
        if (pct) pct.textContent = '100%';
        if (status) status.textContent = '🏆 Mission Launch! Welcome Officer!';

        const core = document.getElementById('preloader-core');
        if (core) {
            core.style.transition = 'transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease';
            core.style.transform = 'scale(1.12)';
        }

        setTimeout(() => {
            preloader.style.opacity = '0';
            preloader.style.transform = 'scale(1.06) translateY(-15px)';
            preloader.style.pointerEvents = 'none';

            setTimeout(() => {
                preloader.style.display = 'none';
                preloader.remove();
            }, 600);
        }, 300);
    }
})();
</script>
