import PageTable from './components/PageTable.vue';
import PageForm from './components/PageForm.vue';

const { locales } = window.AsgardCMS;

export default [
    {
        path: '/page/pages',
        name: 'admin.page.page.index',
        component: PageTable,
    },
    {
        path: '/page/pages/create',
        name: 'admin.page.page.create',
        component: PageForm,
        props: {
            locales,
            pageTitle: 'create page',
        },
    },
    {
        path: '/page/pages/:pageId/edit',
        name: 'admin.page.page.edit',
        component: PageForm,
        props: {
            locales,
            pageTitle: 'edit page',
        },
    },
];
