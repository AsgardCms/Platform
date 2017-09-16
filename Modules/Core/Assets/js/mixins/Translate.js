export default {
    data() {
        return {
            translations: {},
        };
    },
    methods: {
        translate(namespace, name) {
            return _.get(this.translations[namespace], name);
        }
    },
    mounted() {
        this.translations = window.AsgardCMS.translations;
    }
}
