<template>
    <div role="alert" class="el-alert el-alert--error">
        <i class="el-alert__icon el-icon-error is-big"></i>
        <div class="el-alert__content">
            <p class="el-alert__title is-bold">Error</p>
            <div class="el-alert__description">
                <div v-for="(errors, field) in form.errors.all()" :key="field">
                    <p>{{ field }}</p>
                </div>
                <ul>
                    <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        name: 'FormErrors',
        props: {
            form: { required: true, type: Form },
        },
    };
</script>
