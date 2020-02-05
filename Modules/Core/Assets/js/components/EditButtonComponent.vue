<template>
    <el-button size="mini" @click.prevent="goToEditPage()"><i class="fa fa-pencil"></i></el-button>
</template>

<script>
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        mixins: [ShortcutHelper],
        props: {
            to: { required: true, type: Object },
        },
        methods: {
            goToEditPage() {
                this.pushRoute(this.to);
            },
        },
    };
</script>
