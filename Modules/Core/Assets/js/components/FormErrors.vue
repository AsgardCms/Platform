<template>
    <el-alert v-if="form.errors.any()" title="Error" type="error" show-icon>
        <div v-for="(errors, field) in form.errors.all()" :key="field">
            <p>{{ field }}</p>
            <ul>
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>
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
