<template>
    <el-button type="danger" @click="deleteRow" size="mini"><i class="fa fa-trash"></i></el-button>
</template>

<script>
    export default {
        props: {
            rows: { default: null },
            scope: { default: null },
        },
        data() {
            return {
                deleteMessage: '',
                deleteTitle: '',
            };
        },
        methods: {
            deleteRow(event) {
                this.$confirm(this.deleteMessage, this.deleteTitle, {
                    confirmButtonText: this.trans('core.button.delete'),
                    cancelButtonText: this.trans('core.button.cancel'),
                    type: 'warning',
                    confirmButtonClass: 'el-button--danger',
                }).then(() => {
                    const vm = this;
                    axios.delete(this.scope.row.urls.delete_url)
                        .then((response) => {
                            if (response.data.errors === false) {
                                vm.$message({
                                    type: 'success',
                                    message: response.data.message,
                                });

                                vm.rows.splice(vm.scope.$index, 1);
                            }
                        })
                        .catch((error) => {
                            vm.$message({
                                type: 'error',
                                message: error.data.message,
                            });
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.trans('core.delete cancelled'),
                    });
                });
            },
        },
        mounted() {
            this.deleteMessage = this.trans('core.modal.confirmation-message');
            this.deleteTitle = this.trans('core.modal.title');
        },
    };
</script>
