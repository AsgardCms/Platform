export default {
    methods: {
        selectMultipleFile(event, model) {
            if (!this[model].medias_multi) this[model].medias_multi = {};
            if (!this[model].medias_multi[event.zone]) this[model].medias_multi[event.zone] = { files: [] };
            this[model].medias_multi[event.zone].files.push(event.id);
        },
        unselectFile(event, model) {
            console.log(event.id);
            this[model].medias_multi[event.zone].files = _.reject(this[model].medias_multi[event.zone].files, media => media == event.id);
        },
    },
};
