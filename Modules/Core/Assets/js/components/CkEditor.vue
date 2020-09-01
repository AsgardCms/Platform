<template>
    <editor
        tag-name="textarea"
        :type="types"
        :config="config"
        :read-only="readOnlyMode"
        :value="value"
        @input="$emit('input', $event)"
    ></editor>
</template>

<script>
    import CKEditor from 'ckeditor4-vue';

    let inc = new Date().getTime();

    export default {
        components: {
            'editor': CKEditor.component,
        },
        props: {
            name: {
                type: String,
                default: () => `editor-${++inc}`,
            },
            value: {
                type: String,
                default: () => '',
            },
            id: {
                type: String,
                default: () => `editor-${inc}`,
            },
            types: {
                type: String,
                default: () => 'classic',
            },
            config: {
                type: Object,
                default: () => {
                    if (window.AsgardCMS.ckeditorCustomConfig !== '') {
                        return {
                            customConfig: window.AsgardCMS.ckeditorCustomConfig,
                        };
                    }

                    return undefined;
                },
            },
            instanceReadyCallback: {
                type: Function,
                default: '',
            },
            readOnlyMode: {
                type: Boolean,
                default: () => false,
            },
        },
        data() {
            return {};
        },
    };
</script>
