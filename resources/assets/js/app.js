require('./bootstrap');

import '@babel/polyfill';
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import VueRouter from 'vue-router';
import ElementUI from 'element-ui';
import VueEvents from 'vue-events';
import locale from 'element-ui/lib/locale/lang/en';
import VueSimplemde from 'vue-simplemde';
import PageRoutes from '../../../Modules/Page/Assets/js/PageRoutes';
import MediaRoutes from '../../../Modules/Media/Assets/js/MediaRoutes';
import UserRoutes from '../../../Modules/User/Assets/js/UserRoutes';

Vue.use(ElementUI, { locale });
Vue.use(VueI18n);
Vue.use(VueRouter);
Vue.use(require('vue-shortkey'), { prevent: ['input', 'textarea'] });

Vue.use(VueEvents);
Vue.use(VueSimplemde);
require('./mixins');

Vue.component('ckeditor', require('../../../Modules/Core/Assets/js/components/CkEditor.vue').default);

const { currentLocale, adminPrefix } = window.AsgardCMS;

function makeBaseUrl() {
    if (window.AsgardCMS.hideDefaultLocaleInURL === '1') {
        return adminPrefix;
    }
    return `${currentLocale}/${adminPrefix}`;
}

const router = new VueRouter({
        mode: 'history',
        base: makeBaseUrl(),
        routes: [
            ...PageRoutes,
            ...MediaRoutes,
            ...UserRoutes,
        ],
    }),
    messages = {
        [currentLocale]: window.AsgardCMS.translations,
    },
    i18n = new VueI18n({
        locale: currentLocale,
        messages,
    }),
    app = new Vue({
        el: '#app',
        router,
        i18n,
    });

window.axios.interceptors.response.use(null, (error) => {
    if (error.response === undefined) {
        console.log(error);
        return;
    }
    if (error.response.status === 403) {
        app.$notify.error({
            title: app.$t('core.unauthorized'),
            message: app.$t('core.unauthorized-access'),
        });
        window.location = route('dashboard.index');
    }
    if (error.response.status === 401) {
        app.$notify.error({
            title: app.$t('core.unauthenticated'),
            message: app.$t('core.unauthenticated-message'),
        });
        window.location = route('login');
    }
    return Promise.reject(error);
});
