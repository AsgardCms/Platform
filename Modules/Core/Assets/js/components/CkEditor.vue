<template>
    <textarea
        :name="name"
        :id="id"
        :value="value"
        :types="types"
        :config="config"
        class="editor"
    ></textarea>
</template>

<script>
    // Source: https://github.com/dangvanthanh/vue-ckeditor2
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
        },
        data() {
            return { destroyed: false };
        },
        computed: {
            instance() {
                return CKEDITOR.instances[this.id];
            },
        },
        watch: {
            value(val) {
                if (this.instance) {
                    const html = this.instance.getData();
                    if (val !== html) {
                        this.instance.setData(val);
                    }
                }
            },
        },
        mounted() {
            if (typeof CKEDITOR === 'undefined') {
                console.log('CKEDITOR is missing (http://ckeditor.com/)');
            } else {
                if (this.types === 'inline') {
                    CKEDITOR.inline(this.id, this.config);
                } else {
                    CKEDITOR.replace(this.id, this.config);
                }
                this.instance.on('change', () => {
                    const html = this.instance.getData();
                    if (html !== this.value) {
                        this.$emit('input', html);
                        this.$emit('update:value', html);
                    }
                });
                this.instance.on('blur', () => {
                    this.$emit('blur', this.instance);
                });
                this.instance.on('focus', () => {
                    this.$emit('focus', this.instance);
                });
            }
        },
        beforeDestroy() {
            if (!this.destroyed) {
                this.instance.focusManager.blur(true);
                this.instance.removeAllListeners();
                try {
                    this.instance.destroy();
                } catch (e) {
                }
                this.destroyed = true;
            }
        },
    };
</script>

<style>
    .editor::after {
        content: "";
        display: table;
        clear: both;
    }
</style>
