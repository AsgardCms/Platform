import MediaManager from './components/MediaManager.vue';
import MediaList from './components/MediaList.vue';
import MediaForm from './components/MediaForm.vue';

const { locales } = window.AsgardCMS;

export default [
    {
        path: '/media/media',
        component: MediaManager,
        children: [
            {
                path: '',
                component: MediaList,
                name: 'admin.media.media.index',
            },
            {
                path: ':mediaId/edit',
                component: MediaForm,
                name: 'admin.media.media.edit',
                props: {
                    locales,
                },
            },
        ],
    },
];
