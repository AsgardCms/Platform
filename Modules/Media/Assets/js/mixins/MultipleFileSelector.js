export default {
    methods: {
        selectMultipleFile(event, model) {
            if (typeof this[model].medias_multi === 'undefined') {
                this.$set(model, 'medias_multi', {});
            }

            if (typeof this[model].medias_multi[event.zone] === 'undefined') {
                this.$set(this[model].medias_multi, event.zone, { files: [], orders: '' });
            }

            if (event.id !== null && event.id !== undefined) {
                const medias = new Set(this[model].medias_multi[event.zone].files);
                medias.add(event.id);
                this.$set(this[model].medias_multi[event.zone], 'files', [...medias]);
            }
        },
        unselectFile(event, model) {
            if (event.id !== null && event.id !== undefined) {
                const medias = new Set(this[model].medias_multi[event.zone].files);
                medias.delete(event.id);
                this.$set(this[model].medias_multi[event.zone], 'files', [...medias]);
            }

            if (this[model].medias_multi[event.zone].files.length === 0) {
                this.$delete(this[model].medias_multi, event.zone);
            }
        },
    },
};
