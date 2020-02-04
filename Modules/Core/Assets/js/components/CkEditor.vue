<template>
    <textarea
        :name="name"
        :id="id"
        :value="value"
        :types="types"
        :config="config"
        :disabled="readOnlyMode"
        class="editor"
    ></textarea>
</template>

<script>
    let inc = new Date().getTime();

    export default {
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
            },
            readOnlyMode: {
                type: Boolean,
                default: () => false,
            },
        },
        data() {
            return {
                instanceValue: '',
            };
        },
        computed: {
            instance() {
                return CKEDITOR.instances[this.id];
            },
        },
        watch: {
            value(val) {
                try {
                    if (this.instance) {
                        this.update(val);
                    }
                } catch (e) {
                }
            },
            readOnlyMode(val) {
                this.instance.setReadOnly(val);
            },
        },
        mounted() {
            this.create();
        },
        methods: {
            create() {
                if (typeof CKEDITOR === 'undefined') {
                    console.log('CKEDITOR is missing (http://ckeditor.com/)');
                } else {
                    if (this.types === 'inline') {
                        CKEDITOR.inline(this.id, this.config);
                    } else {
                        CKEDITOR.replace(this.id, this.config);
                    }

                    this.instance.setData(this.value);

                    this.instance.on('instanceReady', () => {
                        this.instance.setData(this.value);
                    });

                    // Ckeditor change event
                    this.instance.on('change', this.onChange);

                    // Ckeditor mode html or source
                    this.instance.on('mode', this.onMode);

                    // Ckeditor blur event
                    this.instance.on('blur', evt => {
                        this.$emit('blur', evt);
                    });

                    // Ckeditor focus event
                    this.instance.on('focus', evt => {
                        this.$emit('focus', evt);
                    });

                    // Ckeditor contentDom event
                    this.instance.on('contentDom', evt => {
                        this.$emit('contentDom', evt);
                    });

                    // Ckeditor dialog definition event
                    CKEDITOR.on('dialogDefinition', evt => {
                        this.$emit('dialogDefinition', evt);
                    });

                    // Ckeditor file upload request event
                    this.instance.on('fileUploadRequest', evt => {
                        this.$emit('fileUploadRequest', evt);
                    });

                    // Ckditor file upload response event
                    this.instance.on('fileUploadResponse', evt => {
                        setTimeout(() => {
                            this.onChange();
                        }, 0);
                        this.$emit('fileUploadResponse', evt);
                    });

                    // Listen for instanceReady event
                    if (typeof this.instanceReadyCallback !== 'undefined') {
                        this.instance.on('instanceReady', this.instanceReadyCallback);
                    }

                    // Registering the beforeDestroyed hook right after creating the instance
                    this.$once('hook:beforeDestroy', () => {
                        this.destroy();
                    });
                }
            },
            update(val) {
                if (this.instanceValue !== val) {
                    this.instance.setData(val, { internal: false });
                    this.instanceValue = val;
                }
            },
            destroy() {
                try {
                    let editor = window['CKEDITOR'];
                    if (editor.instances && editor.instances[this.id]) {
                        editor.instances[this.id].destroy();
                    }
                } catch (e) {
                }
            },
            onMode() {
                if (this.instance.mode === 'source') {
                    let editable = this.instance.editable();
                    editable.attachListener(editable, 'input', () => {
                        this.onChange();
                    });
                }
            },
            onChange() {
                let html = this.instance.getData();
                if (html !== this.value) {
                    this.$emit('input', html);
                    this.instanceValue = html;
                }
            },
        },
    };
</script>
