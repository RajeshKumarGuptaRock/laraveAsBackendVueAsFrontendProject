import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from '../pages/Dashboard.vue';
import Posts from '../pages/posts/Index.vue';
import Login from '../pages/Login.vue';

import { useAuthStore } from '../stores/auth';

const routes = [

    {
        path: '/',
        redirect: '/login',
    },

    {
        path: '/login',
        component: Login,
    },

    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },

    {
        path: '/posts',
        component: Posts,
        meta: {
            requiresAuth: true
        }
    },

];

const router = createRouter({

    history: createWebHistory(),

    routes,

});

router.beforeEach(async (to, from, next) => {

    const authStore = useAuthStore();

    if (!authStore.user) {

        await authStore.fetchUser();

    }

    if (to.meta.requiresAuth && !authStore.user) {

        next('/login');

    } else {

        next();

    }

});

export default router;