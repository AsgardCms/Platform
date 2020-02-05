export default {
    methods: {
        pushRoute(route, onError) {
            this.$router.push(route, () => {}, (error, ...args) => {
                // vue-router 3.1.0+ push/replace causes NavigationDuplicated error
                // for routing to the same location
                if (error.name === 'NavigationDuplicated') {
                    return;
                }

                if (typeof onError === 'function') {
                    onError(error, ...args);
                }
            });
        },
    },
};
