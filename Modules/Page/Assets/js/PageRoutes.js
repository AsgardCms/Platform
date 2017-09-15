window.Vue = require('vue');
import VueRouter from 'vue-router'
import ElementUI from 'element-ui'
import DataTables from 'vue-data-tables'
import 'element-ui/lib/theme-default/index.css'
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale });
Vue.use(DataTables, { locale });
Vue.use(VueRouter);

const PageTable = require('./components/PageTable.vue');
Vue.component('PageTable', PageTable);

const PageForm = require('./components/PageForm.vue');
Vue.component('PageForm', PageForm);


let translations = window.translations;
let locales = window.locales;

export default [
    {
        path: '/page/pages',
        name: 'admin.page.page.index',
        component: PageTable,
        props: {
            translations
        }
    },
    {
        path: '/page/pages/create',
        name: 'admin.page.page.create',
        component: PageForm,
        props: {
            translations,
            locales,
        }
    },
    {
        path: '/page/pages/:pageId/edit',
        name: 'admin.page.page.edit',
        component: PageForm,
        props: {
            translations,
            locales,
        }
    },
];
