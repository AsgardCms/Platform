<template>
    <el-alert title="Error" type="error" show-icon>
        <div v-for="(errors, field) in form.errors.all()" :key="field">
            <p>{{ field }}</p>
        </div>
        <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
    </el-alert>
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
