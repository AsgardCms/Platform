<template>
    <button class="btn btn-danger btn-flat" @click="deleteRow"><i class="fa fa-trash"></i></button>
</template>

<script>
    export default {
        props: {
            deleteAction: {default: null},
            deleteMessage: {default: "Are you sure you want to delete this record?"},
            deleteTitle: {default: "Confirmation"},
            rowId: {default: null},
        },
        methods: {
            deleteRow(event) {
                this.$confirm(this.deleteMessage, this.deleteTitle, {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    let vm = this;
                    axios.delete(this.deleteAction)
                        .then(function (response) {
                            if (response.data.errors === false) {
                                vm.$message({
                                    type: 'success',
                                    message: response.data.message
                                });

                                $('#' + vm.rowId).remove();
                            }
                        })
                        .catch(function (error) {
                            vm.$message({
                                type: 'error',
                                message: response.data.message
                            });
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled'
                    });
                });
            }
        }
    }
</script>
