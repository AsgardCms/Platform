<template>
    <button class="btn btn-danger btn-flat" @click="deleteRow"><i class="fa fa-trash"></i></button>
</template>

<script>
    import Translate from '../../../../Core/Assets/js/mixins/Translate'
    export default {
        mixins: [Translate],
        props: {
            rows: {default: null},
            scope: {default: null},
        },
        data() {
            return {
                deleteMessage: '',
                deleteTitle: '',
            }
        },
        methods: {
            deleteRow(event) {
                this.$confirm(this.deleteMessage, this.deleteTitle, {
                    confirmButtonText: this.translate('core', 'button.delete'),
                    cancelButtonText: this.translate('core', 'button.cancel'),
                    type: 'warning'
                }).then(() => {
                    let vm = this;
                    axios.delete(this.scope.row.urls.delete_url)
                        .then(response => {
                            if (response.data.errors === false) {
                                vm.$message({
                                    type: 'success',
                                    message: response.data.message
                                });

                                vm.rows.splice(vm.scope.$index, 1);
                            }
                        })
                        .catch(error => {
                            vm.$message({
                                type: 'error',
                                message: response.data.message
                            });
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.translate('core', 'delete cancelled')
                    });
                });
            }
        },
        mounted() {
            this.deleteMessage = this.translate('core', 'modal.confirmation-message');
            this.deleteTitle = this.translate('core', 'modal.title');
        }
    }
</script>
