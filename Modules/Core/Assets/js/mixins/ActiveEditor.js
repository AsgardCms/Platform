export default {
    methods: {
        getCurrentEditor() {
            const configuredEditor = window.AsgardCMS.editor;
            if (configuredEditor === undefined || configuredEditor === 'ckeditor') {
                return 'ckeditor';
            }
            if (configuredEditor === 'simplemde') {
                return 'markdown-editor';
            }
            return configuredEditor;
        },
    },
};
