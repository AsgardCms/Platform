export default {
    methods: {
        selectMultipleFile(event, model) {
            if (!this[model].medias_multi) {
                this[model].medias_multi = {};
            }
            if (!this[model].medias_multi[event.zone]) {
                this[model].medias_multi[event.zone] = { files: [] };
            }
            this[model].medias_multi[event.zone].files.push(event.id);
        },
        unselectFile(event, model) {
            if (!this[model].medias_multi) {
                this[model].medias_multi = {};
            }
            if (!this[model].medias_multi[event.zone]) {
                this[model].medias_multi[event.zone] = { files: [] };
                if (this.$refs['multiple-media'] !== undefined && this.$refs['multiple-media'].selectedMedia !== undefined && !_.isEmpty(this.$refs['multiple-media'].selectedMedia)) {
                    _.forEach(this.$refs['multiple-media'].selectedMedia, (file, key) => {
                        this[model].medias_multi[event.zone].files.push(file.id);
                    });
                }
            }
            this[model].medias_multi[event.zone].files = _.reject(this[model].medias_multi[event.zone].files, media => media === event.id);
        },
    },
};