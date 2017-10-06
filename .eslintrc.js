module.exports = {
    extends: [
        'airbnb-base',
        'plugin:vue/recommended',
    ],
    rules: {
        indent: [2, 4],
        'no-undef': 'off',
        'max-len': 'off',
        'no-console': 'off',
    },
    overrides: [
        {
            files: ['*.vue', '**/*.vue'],
            rules: {
                indent: 'off',
            },
        },
    ],
};
