import t from 'typy';

export default {
    methods: {
        selectSingleFile(event, model) {
            const entity = t(this, model).safeObject;

            if (typeof entity.medias_single === 'undefined') {
                this.$set(entity, 'medias_single', {});
            }

            if (typeof entity.medias_single[event.zone] === 'undefined') {
                this.$set(entity.medias_single, event.zone, null);
            }

            if (event.id !== null && event.id !== undefined) {
                this.$set(entity.medias_single, event.zone, event.id);
            } else {
                this.$delete(entity.medias_single, event.zone);
            }
        },
    },
};
