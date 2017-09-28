export default {
    methods: {
        selectSingleFile(event, model) {
            this[model].medias_single = _.merge(this[model].medias_single, {
                [event.zone]: event.id,
            });
        },
    }
}
