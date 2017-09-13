
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import ElementUI from 'element-ui'
import DataTables from 'vue-data-tables'
import 'element-ui/lib/theme-default/index.css'
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale });
Vue.use(DataTables, { locale });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('ckeditor', require('../../../Modules/Core/Assets/js/components/CkEditor.vue'));
Vue.component('DeleteButton', require('../../../Modules/Core/Assets/js/components/DeleteComponent.vue'));
Vue.component('PageTable', require('../../../Modules/Page/Assets/js/components/PageTable.vue'));
Vue.component('PageForm', require('../../../Modules/Page/Assets/js/components/PageForm.vue'));

const app = new Vue({
    el: '#app',
});
