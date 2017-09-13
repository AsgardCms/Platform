<template>
    <el-form-item label="Tags">
        <el-select v-model="tags" multiple filterable allow-create @change="doSomething">
            <el-option v-for="tag in availableTags"
                       :key="tag.slug"
                       :label="tag.slug"
                       :value="tag.name">
            </el-option>
        </el-select>
    </el-form-item>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            namespace: {String}
        },
        data() {
            return {
                tags: {},
                availableTags: {},
            }
        },
        methods: {
            doSomething() {
                this.$emit('input', this.tags);
            }
        },
        mounted() {
            axios.get(route('api.tag.tag.by-namespace', {namespace: this.namespace}))
                .then(response => {
                    this.availableTags = response.data;
                });
        }
    }
</script>
