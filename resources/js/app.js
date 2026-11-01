import Alpine from 'alpinejs';

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

        executeBulkAction(actionName, targetUrl, confirmMsg) {
            if (this.selected.length === 0) {
                alert('Please select at least one item.');
                return;
            }

            if (confirmMsg && !confirm(confirmMsg)) {
                return;
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
