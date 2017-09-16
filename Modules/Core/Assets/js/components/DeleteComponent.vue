<template>
    <button class="btn btn-danger btn-flat" @click="deleteRow"><i class="fa fa-trash"></i></button>
</template>

<script>
    import TranslationHelper from '../../../../Core/Assets/js/mixins/TranslationHelper'

    export default {
        mixins: [TranslationHelper],
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
                    confirmButtonText: this.trans('core.button.delete'),
                    cancelButtonText: this.trans('core.button.cancel'),
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
                        message: this.trans('core.delete cancelled')
                    });
                });
            }
        },
        mounted() {
            this.deleteMessage = this.trans('core.modal.confirmation-message');
            this.deleteTitle = this.trans('core.modal.title');
        }
    }
</script>
