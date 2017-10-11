import RoleTable from './components/RoleTable.vue';
import RoleForm from './components/RoleForm.vue';


const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/user/roles',
        name: 'admin.user.roles.index',
        component: RoleTable,
    },
    {
        path: '/user/roles/create',
        name: 'admin.user.roles.create',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'new-role',
        },
    },
    {
        path: '/user/roles/:roleId/edit',
        name: 'admin.user.roles.edit',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'title.edit',
        },
    },
];
