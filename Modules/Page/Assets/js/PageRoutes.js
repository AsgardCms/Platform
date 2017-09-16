import PageTable from './components/PageTable.vue'
import PageTableServerSide from './components/PageTableServerSide.vue'
import PageForm from './components/PageForm.vue'

const translations = window.translations;
const locales = window.locales;

export default [
    {
        path: '/page/pages',
        name: 'admin.page.page.index',
        component: PageTableServerSide,
        props: {
            translations,
        }
    },
    {
        path: '/page/pages/create',
        name: 'admin.page.page.create',
        component: PageForm,
        props: {
            translations,
            locales,
            pageTitle: 'create page',
        }
    },
    {
        path: '/page/pages/:pageId/edit',
        name: 'admin.page.page.edit',
        component: PageForm,
        props: {
            translations,
            locales,
            pageTitle: 'edit page',
        }
    },
];
