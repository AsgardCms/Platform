import RoleTable from './components/RoleTable.vue';
import RoleForm from './components/RoleForm.vue';
import UserTable from './components/UserTable.vue';
import UserForm from './components/UserForm.vue';
import UserProfile from './components/UserProfile.vue';
import ApiKeys from './components/ApiKeys.vue';

const { locales } = window.AsgardCMS;

export default [
    // Role Routes
    {
        path: '/user/roles',
        name: 'admin.user.role.index',
        component: RoleTable,
    },
    {
        path: '/user/roles/create',
        name: 'admin.user.role.create',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'new-role',
        },
    },
    {
        path: '/user/roles/:roleId/edit',
        name: 'admin.user.role.edit',
        component: RoleForm,
        props: {
            locales,
            pageTitle: 'title.edit',
        },
    },
    // User Routes
    {
        path: '/user/users',
        name: 'admin.user.user.index',
        component: UserTable,
    },
    {
        path: '/user/users/create',
        name: 'admin.user.user.create',
        component: UserForm,
        props: {
            locales,
            pageTitle: 'title.new-user',
        },
    },
    {
        path: '/user/users/:userId/edit',
        name: 'admin.user.user.edit',
        component: UserForm,
        props: {
            locales,
            pageTitle: 'title.edit-user',
        },
    },
    // Account routes
    {
        path: '/account/profile',
        name: 'admin.user.user.account',
        component: UserProfile,
    },
    {
        path: '/account/api-keys',
        name: 'admin.user.user.account.api-keys',
        component: ApiKeys,
    },
];
