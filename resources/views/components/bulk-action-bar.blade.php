@props([
    'actionUrl' => null,
    'itemName' => 'item',
    'itemNamePlural' => 'items',
    'actions' => [],
    'selectionVar' => 'selected',
])

@php
    // Default actions if none provided
    if (empty($actions)) {
        $actions = [
            [
                'name' => 'activate',
                'label' => 'Activate',
                'icon' => 'check',
                'style' => 'success',
            ],
            [
                'name' => 'deactivate',
                'label' => 'Deactivate',
                'icon' => 'pause',
                'style' => 'warning',
            ],
            [
                'name' => 'export',
                'label' => 'Export CSV',
                'icon' => 'download',
                'style' => 'secondary',
            ],
            [
                'name' => 'delete',
                'label' => 'Delete Selected',
                'icon' => 'trash',
                'style' => 'danger',
                'confirm' => 'Are you sure you want to permanently delete the selected ' . $itemNamePlural . '?',
            ],
        ];
    }
@endphp

<!-- Bulk Action Bar Responsive Floating Component -->
<div x-show="{{ $selectionVar }}.length > 0"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
     class="fixed bottom-3 sm:bottom-6 inset-x-3 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 z-40 max-w-3xl w-auto pointer-events-auto"
     role="region"
     aria-label="Bulk actions bar"
     style="display:none;">

    <div class="bg-slate-900/95 backdrop-blur-md text-white border border-slate-700/80 rounded-2xl shadow-2xl p-2.5 sm:px-4 sm:py-2.5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 sm:gap-5">
        
        <!-- Left: Selected Count Badge & Selection Helpers -->
        <div class="flex items-center justify-between sm:justify-start space-x-2.5 shrink-0">
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 rounded-lg bg-blue-600 text-white font-mono font-black text-xs shadow-xs" 
                      x-text="{{ $selectionVar }}.length">
                </span>
                <span class="text-xs font-semibold text-slate-200">
                    <span x-text="{{ $selectionVar }}.length === 1 ? '{{ $itemName }}' : '{{ $itemNamePlural }}'"></span>
                    <span class="hidden xs:inline">selected</span>
                </span>
            </div>

            <div class="flex items-center space-x-2 text-xs">
                <span class="text-slate-600 hidden sm:inline">•</span>
                <button type="button" 
                        @click="selectAllPage ? deselectAll() : selectAll()"
                        class="text-xs font-medium text-slate-300 hover:text-white underline-offset-2 hover:underline transition-colors cursor-pointer"
                        x-text="selectAllPage ? 'Deselect All' : 'Select All'">
                    Select All
                </button>
                <span class="text-slate-600">•</span>
                <button type="button" 
                        @click="deselectAll()" 
                        class="text-xs font-medium text-slate-400 hover:text-rose-300 transition-colors cursor-pointer"
                        title="Clear current selection">
                    Clear
                </button>
            </div>
        </div>

        <!-- Center/Right: Action Buttons or Custom Slot -->
        <div class="flex items-center flex-wrap sm:flex-nowrap justify-end gap-1.5 sm:gap-2">
            @if(isset($slot) && $slot->isNotEmpty())
                {{ $slot }}
            @else
                @foreach($actions as $action)
                    @php
                        $actName = $action['name'];
                        $actLabel = $action['label'] ?? ucfirst($actName);
                        $actStyle = $action['style'] ?? 'secondary';
                        $actConfirm = $action['confirm'] ?? null;
                        $actIcon = $action['icon'] ?? null;

                        $buttonClasses = match($actStyle) {
                            'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs',
                            'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs',
                            'warning' => 'bg-amber-600 hover:bg-amber-700 text-white shadow-xs',
                            'primary' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-xs',
                            default => 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700/80',
                        };
                    @endphp

                    <button type="button"
                            @click="executeBulkAction('{{ $actName }}', '{{ $actionUrl }}', {{ $actConfirm ? "'" . addslashes($actConfirm) . "'" : 'null' }})"
                            class="px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-semibold {{ $buttonClasses }} transition-all flex items-center space-x-1.5 cursor-pointer shrink-0">
                        @if($actIcon === 'trash')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        @elseif($actIcon === 'check')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($actIcon === 'pause')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($actIcon === 'download')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        @endif
                        <span>{{ $actLabel }}</span>
                    </button>
                @endforeach
            @endif

            <!-- Dismiss Button -->
            <button type="button" 
                    @click="deselectAll()" 
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                    title="Close bulk action bar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

    </div>

    <!-- Hidden Form for Native Submissions -->
    <form id="bulkActionForm" method="POST" :action="bulkActionTargetUrl" style="display:none;">
        @csrf
        <input type="hidden" name="action" :value="bulkActionName">
        <template x-for="id in {{ $selectionVar }}" :key="id">
            <input type="hidden" name="ids[]" :value="id">
        </template>
    </form>
</div>

@once
<script>
    if (typeof window.bulkActionManager === 'undefined') {
        window.bulkActionManager = function(config = {}) {
            return {
                selected: [],
                allIds: config.allIds || [],
                selectAllPage: false,
                bulkActionTargetUrl: config.actionUrl || '',
                bulkActionName: '',

                init() {
                    this.$watch('selected', (val) => {
                        this.selectAllPage = val.length > 0 && this.allIds.length > 0 && val.length === this.allIds.length;
                    });
                },

                isAllSelected() {
                    return this.allIds.length > 0 && this.selected.length === this.allIds.length;
                },

                toggleSelectAll() {
                    if (this.isAllSelected()) {
                        this.selected = [];
                        this.selectAllPage = false;
                    } else {
                        this.selected = [...this.allIds];
                        this.selectAllPage = true;
                    }
                },

                selectAll() {
                    this.selected = [...this.allIds];
                    this.selectAllPage = true;
                },

                deselectAll() {
                    this.selected = [];
                    this.selectAllPage = false;
                },

                toggleItem(id) {
                    const idx = this.selected.indexOf(id);
                    if (idx > -1) {
                        this.selected.splice(idx, 1);
                    } else {
                        this.selected.push(id);
                    }
                },

                async executeBulkAction(actionName, targetUrl, confirmMsg) {
                    if (this.selected.length === 0) {
                        if (typeof window.appAlert === 'function') {
                            await window.appAlert({
                                title: 'Selection Required',
                                message: 'Please select at least one item using the checkboxes to perform this action.',
                                tone: 'warning',
                                confirmText: 'Understood'
                            });
                        } else {
                            alert('Please select at least one item.');
                        }
                        return;
                    }

                    if (confirmMsg) {
                        let confirmed = false;
                        if (typeof window.appConfirm === 'function') {
                            confirmed = await window.appConfirm({
                                title: 'Confirm Bulk Action',
                                message: confirmMsg,
                                tone: actionName === 'delete' ? 'danger' : 'warning',
                                confirmText: actionName === 'delete' ? 'Delete Selected' : 'Proceed'
                            });
                        } else {
                            confirmed = confirm(confirmMsg);
                        }

                        if (!confirmed) {
                            return;
                        }
                    }

                    this.bulkActionName = actionName;
                    this.bulkActionTargetUrl = targetUrl || this.bulkActionTargetUrl;

                    this.$nextTick(() => {
                        const form = document.getElementById('bulkActionForm');
                        if (form) {
                            form.submit();
                        }
                    });
                }
            };
        };
    }
</script>
@endonce

