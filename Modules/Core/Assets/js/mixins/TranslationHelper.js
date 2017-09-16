export default {
    methods: {
        trans(string) {
            // Makes a string: page.
            let array = string.split('.');

            if (array.length < 2) { return this.$t(string) }

            let first = array.splice(0,1);
            let key = array.join('.');

            return this.$t(`${first}['${key}']`);
        },
    }
}
