<template>
    <el-button size="mini" @click.prevent="goToEditPage()"><i class="fa fa-pencil"></i></el-button>
</template>

<script>
    export default {
        props: {
            to: { required: true, type: Object },
        },
        methods: {
            goToEditPage() {
                this.$router.push(this.to);
            },
        },
    };
</script>
