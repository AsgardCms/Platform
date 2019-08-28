<template>
    <el-select
        v-model="_locale_code"
        filterable
        remote
        value-key="code"
        :remote-method="fetchOptions"
        :loading="loading" >
        <el-option
            v-for   ="item in _options"
            :key    ="item.code"
            :label  ="item.label"
            :value  ="item.code">
            {{ item.label }}
            <span class='code' v-if="item.code">{{ item.code }}</span>
        </el-option>
    </el-select>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';

    export default {
        name: 'LocaleCodeSelect',
        props: {
            value:   {
                type: String,
                default: null,
            },
            options: {
                type: Array,
                default: null,
            },
        },
        data() {
            return {
                loading: false,
                locale_code: this.value,
                opts:  [],
            };
        },
        methods: {
            fetchOptions(query) {
                if (query.length >= 3) {
                    this.loading = true;
                    this.queryServer({
                        search: query,
                    });
                }
            },
            queryServer(customProperties) {
                const properties = {
                    order_by: 'name',
                    order:    'asc',
                    search:   this.searchQuery,
                };

                axios.get(route('api.translation.translations.list-locales-for-select', _.merge(properties, customProperties)))
                    .then((response) => {
                        this.loading = false;
                        this.opts = response.data.data;
                    });
            },
        },
        computed: {
            _locale_code: {
                get: function(){
                    return this.value;
                },
                set: function(new_val){
                    this.locale_code = new_val;
                    this.$emit('input', this.locale_code);
                }
            },
            _options: {
                get: function(){
                    if(this.opts.length>0){
                        return this.opts;
                    }
                    if(this.options!=null){
                        return this.options;
                    }
                    return [];
                },
            }
        },

    };
</script>

<style>
    .code {
        float: right;
        color: #8492a6;
        font-size: 13px;
    }
</style>

