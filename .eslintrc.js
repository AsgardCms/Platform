module.exports = {
    extends: [
        'airbnb-base',
        'plugin:vue/recommended',
    ],
    rules: {
        'indent': ['error', 4],
        'no-undef': 'off',
        'max-len': 'off',
        'no-console': 'off',
        'one-var': ['error', { 'var': 'consecutive', 'let': 'consecutive', 'const': 'consecutive' }],
        'no-plusplus': ['error', { 'allowForLoopAfterthoughts': true }],
        'quote-props': ['error', 'as-needed', { 'unnecessary': false }],
        'object-curly-newline': ['error', {
            'consistent': true,
            'multiline': true,
            'minProperties': 8,
        }],
        'no-param-reassign': ['error', { 'props': false }],
        'vue/html-indent': ['error', 4],
        'vue/max-attributes-per-line': ['error', {
            'singleline': 4,
            'multiline': {
                'max': 1,
                'allowFirstLine': false,
            },
        }],
        'vue/html-self-closing': ['error', {
            'html': {
                'void': 'never',
                'normal': 'any',
                'component': 'any',
            },
            'svg': 'any',
            'math': 'always',
        }],
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
