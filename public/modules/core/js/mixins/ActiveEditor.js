export default {
    methods: {
        getCurrentEditor() {
            const configuredEditor = window.AsgardCMS.editor;
            if (configuredEditor === 'simplemde') {
                return 'markdown-editor';
            }
            if (configuredEditor === 'ckeditor') {
                return 'ckeditor';
            }
            return 'ckeditor';
        },
    },
};
