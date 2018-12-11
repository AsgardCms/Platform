import t from 'typy';

export default {
    methods: {
        selectMultipleFile(event, model) {
            const entity = t(this, model).safeObject;

            if (typeof entity.medias_multi === 'undefined') {
                this.$set(entity, 'medias_multi', {});
            }

            if (typeof entity.medias_multi[event.zone] === 'undefined') {
                this.$set(entity.medias_multi, event.zone, { files: [], orders: '' });
            }

            if (event.id !== null && event.id !== undefined) {
                const medias = new Set(entity.medias_multi[event.zone].files);
                medias.add(event.id);
                this.$set(entity.medias_multi[event.zone], 'files', [...medias]);
            }
        },
        unselectFile(event, model) {
            const entity = t(this, model).safeObject;

            if (event.id !== null && event.id !== undefined) {
                const medias = new Set(entity.medias_multi[event.zone].files);
                medias.delete(event.id);
                this.$set(entity.medias_multi[event.zone], 'files', [...medias]);
            }

            if (entity.medias_multi[event.zone].files.length === 0) {
                this.$delete(entity.medias_multi, event.zone);
            }
        },
    },
};
