export default {
    methods: {
        selectSingleFile(event, model) {
            if (typeof this[model].medias_single === 'undefined') {
                this.$set(this[model], 'medias_single', {});
            }

            if (typeof this[model].medias_single[event.zone] === 'undefined') {
                this.$set(this[model].medias_single, event.zone, null);
            }

            if (event.id !== null && event.id !== undefined) {
                this.$set(this[model].medias_single, event.zone, event.id);
            } else {
                this.$delete(this[model].medias_single, event.zone);
            }
        },
    },
};
