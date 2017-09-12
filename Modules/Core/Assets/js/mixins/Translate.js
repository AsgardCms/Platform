export default {
    props: {
        translations: {default: null}
    },
    methods: {
        translate(namespace, name) {
            return _.get(this.translations[namespace], name);
        }
    }
}
