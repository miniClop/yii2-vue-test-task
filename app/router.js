import {createRouter, createWebHistory} from 'vue-router';
import ClientsList from './components/ClientsList';
import ClientItem from './components/ClientItem';
import ClientForm from './components/ClientForm';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            name: 'clients',
            path: '/',
            component: ClientsList,
        },
        {
            name: 'client-form',
            path: '/client/edit/:clientId?',
            component: ClientForm,
            props: true,
            alias: '/client/add'
        },
        {
            name: 'client-item',
            path: '/client/:clientId(\\d+)',
            component: ClientItem,
            props: true
        },
    ],
});

export default router;
