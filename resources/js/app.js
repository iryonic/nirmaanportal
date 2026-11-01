import Alpine from 'alpinejs';

/**
 * Custom Industry-Level Modal Dialog Component for Alpine.js
 */
window.modalDialogComponent = function() {
    return {
        isOpen: false,
        mode: 'confirm', // 'confirm' | 'alert'
        tone: 'danger',  // 'danger' | 'warning' | 'info' | 'success'
        title: 'Confirm Action',
        message: '',
        confirmText: 'Confirm',
        cancelText: 'Cancel',

        openDialog(detail = {}) {
            this.mode = detail.mode || 'confirm';
            this.tone = detail.tone || (this.mode === 'alert' ? 'info' : 'danger');
            this.title = detail.title || (this.mode === 'alert' ? 'Notification' : 'Confirm Action');
            this.message = detail.message || '';
            this.confirmText = detail.confirmText || (this.mode === 'alert' ? 'Understood' : 'Yes, Proceed');
            this.cancelText = detail.cancelText || 'Cancel';
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        confirm() {
            this.isOpen = false;
            document.body.classList.remove('overflow-hidden');
            window.dispatchEvent(new CustomEvent('modal-dialog-resolved', { detail: { confirmed: true } }));
        },

        cancel() {
            this.isOpen = false;
            document.body.classList.remove('overflow-hidden');
            window.dispatchEvent(new CustomEvent('modal-dialog-resolved', { detail: { confirmed: false } }));
        }
    };
};

/**
 * Universal Promise-based Modal APIs
 */
window.appConfirm = function(messageOrOptions, options = {}) {
    let config = {};
    if (typeof messageOrOptions === 'string') {
        config = { message: messageOrOptions, ...options };
    } else {
        config = { ...messageOrOptions };
    }

    if (!config.tone) {
        const text = ((config.title || '') + ' ' + (config.message || '')).toLowerCase();
        if (/delete|remove|destroy|erase|cancel|purge/i.test(text)) {
            config.tone = 'danger';
        } else if (/suspend|deactivate|pause|unpublish/i.test(text)) {
            config.tone = 'warning';
        } else if (/publish|activate|verify|paid/i.test(text)) {
            config.tone = 'success';
        } else {
            config.tone = 'danger';
        }
    }

    return new Promise(resolve => {
        window.dispatchEvent(new CustomEvent('modal-dialog-open', {
            detail: { ...config, mode: 'confirm' }
        }));

        const handler = function(e) {
            window.removeEventListener('modal-dialog-resolved', handler);
            resolve(Boolean(e.detail?.confirmed));
        };
        window.addEventListener('modal-dialog-resolved', handler);
    });
};

window.appAlert = function(messageOrOptions, options = {}) {
    let config = {};
    if (typeof messageOrOptions === 'string') {
        config = { message: messageOrOptions, ...options };
    } else {
        config = { ...messageOrOptions };
    }

    if (!config.tone) {
        config.tone = 'info';
    }

    return new Promise(resolve => {
        window.dispatchEvent(new CustomEvent('modal-dialog-open', {
            detail: { ...config, mode: 'alert' }
        }));

        const handler = function() {
            window.removeEventListener('modal-dialog-resolved', handler);
            resolve();
        };
        window.addEventListener('modal-dialog-resolved', handler);
    });
};

// Seamless replacement of browser's window.alert
const nativeAlert = window.alert;
window.alert = function(msg) {
    if (typeof msg === 'string' || typeof msg === 'number') {
        window.appAlert(String(msg));
    } else {
        nativeAlert(msg);
    }
};

/**
 * Global Interceptor for Forms with Confirmation
 * Intercepts both [data-confirm] and traditional [onsubmit*="confirm("] forms
 */
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form || !(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.dataset.confirmBypassed === 'true') {
        form.dataset.confirmBypassed = 'false';
        return;
    }

    let confirmMsg = form.dataset.confirm;
    let confirmTitle = form.dataset.confirmTitle || 'Please Confirm';
    let confirmTone = form.dataset.confirmTone || 'danger';
    let confirmButton = form.dataset.confirmButton || 'Yes, Proceed';

    // If no data-confirm, check if form has inline onsubmit with confirm()
    if (!confirmMsg && form.getAttribute('onsubmit')) {
        const onsubmitAttr = form.getAttribute('onsubmit');
        const match = onsubmitAttr.match(/confirm\s*\(\s*(['"`])(.*?)\1\s*\)/);
        if (match && match[2]) {
            confirmMsg = match[2].replace(/\\'/g, "'").replace(/\\"/g, '"');
            // Store and remove the inline onsubmit so native confirm doesn't execute
            form.dataset.interceptedOnsubmit = onsubmitAttr;
            form.removeAttribute('onsubmit');
        }
    }

    if (confirmMsg) {
        e.preventDefault();
        e.stopPropagation();

        window.appConfirm({
            title: confirmTitle,
            message: confirmMsg,
            tone: confirmTone,
            confirmText: confirmButton
        }).then(confirmed => {
            if (confirmed) {
                form.dataset.confirmBypassed = 'true';
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            } else if (form.dataset.interceptedOnsubmit) {
                form.setAttribute('onsubmit', form.dataset.interceptedOnsubmit);
            }
        });
    }
}, true);

/**
 * Global Bulk Action Manager with modern dialog prompts
 */
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
                await window.appAlert({
                    title: 'Selection Required',
                    message: 'Please select at least one item using the checkboxes to perform this action.',
                    tone: 'warning',
                    confirmText: 'Understood'
                });
                return;
            }

            if (confirmMsg) {
                const confirmed = await window.appConfirm({
                    title: 'Confirm Bulk Action',
                    message: confirmMsg,
                    tone: actionName === 'delete' ? 'danger' : 'warning',
                    confirmText: actionName === 'delete' ? 'Delete Selected' : 'Proceed'
                });
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

window.Alpine = Alpine;
Alpine.start();

