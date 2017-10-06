<template>
    <div></div>
</template>

<script>
    export default {
        methods: {
            ucfirst(string) {
                return string[0].toUpperCase() + string.substr(1);
            },
            ucwords(string) {
                return string.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
            },
        },
    };
</script>
