<div x-data="modalDialogComponent()" 
     @modal-dialog-open.window="openDialog($event.detail)"
     @keydown.escape.window="if (isOpen) cancel()"
     @keydown.enter.window="if (isOpen) confirm()"
     class="relative z-[9999]" 
     style="display: none;" 
     x-show="isOpen"
     x-cloak>

    <!-- Backdrop with Smooth Blur -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="if (mode === 'alert') confirm(); else cancel();"
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity"
         aria-hidden="true"></div>

    <!-- Centered Dialog Wrapper -->
    <div class="fixed inset-0 z-[10000] overflow-y-auto p-4 flex min-h-full items-center justify-center text-center sm:p-0">
        
        <div x-show="isOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
             @click.outside="if (mode === 'alert') confirm(); else cancel();"
             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-md border border-slate-200/90"
             role="dialog" 
             aria-modal="true" 
             :aria-labelledby="$id('modal-title')">
            
            <div class="p-6 sm:p-7">
                <div class="flex items-start space-x-4">
                    
                    <!-- Dynamic Tone Icon -->
                    <div class="shrink-0 flex items-center justify-center w-12 h-12 rounded-xl ring-8"
                         :class="{
                             'bg-rose-50 text-rose-600 ring-rose-50/60': tone === 'danger',
                             'bg-amber-50 text-amber-600 ring-amber-50/60': tone === 'warning',
                             'bg-blue-50 text-blue-950 ring-blue-50/60': tone === 'info',
                             'bg-emerald-50 text-emerald-600 ring-emerald-50/60': tone === 'success'
                         }">
                        
                        <!-- Danger Icon (Trash / Alert Exclamation) -->
                        <template x-if="tone === 'danger'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </template>

                        <!-- Warning Icon -->
                        <template x-if="tone === 'warning'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>

                        <!-- Info Icon -->
                        <template x-if="tone === 'info'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>

                        <!-- Success Icon -->
                        <template x-if="tone === 'success'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                    </div>

                    <!-- Title & Message Text -->
                    <div class="flex-grow min-w-0 pt-0.5">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug" 
                            :id="$id('modal-title')" 
                            x-text="title"></h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed" 
                           x-html="message"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons Bar -->
            <div class="bg-slate-50/80 px-6 py-4 border-t border-slate-100 flex items-center justify-end space-x-2.5">
                
                <!-- Cancel Button (Only shown in Confirm mode) -->
                <button type="button" 
                        x-show="mode === 'confirm'" 
                        @click="cancel()" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-colors shadow-2xs focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <span x-text="cancelText">Cancel</span>
                </button>

                <!-- Confirm / OK Button -->
                <button type="button" 
                        @click="confirm()" 
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white shadow-2xs transition-colors focus:outline-none focus:ring-2"
                        :class="{
                            'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500/30': tone === 'danger',
                            'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500/30': tone === 'warning',
                            'bg-blue-950 hover:bg-blue-900 focus:ring-blue-900/30': tone === 'info' || tone === 'primary',
                            'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500/30': tone === 'success'
                        }">
                    <span x-text="confirmText">Confirm</span>
                </button>
            </div>

        </div>
    </div>
</div>
