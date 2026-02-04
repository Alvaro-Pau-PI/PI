import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/LoginView.vue'),
            meta: { guest: true }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/RegisterView.vue'),
            meta: { guest: true }
        },
        {
            path: '/products',
            name: 'products',
            component: () => import('../views/ProductsView.vue')
        },
        {
            path: '/products/:id',
            name: 'product-detail',
            component: () => import('../views/ProductDetailView.vue')
        },
        {
            path: '/contact',
            name: 'contact',
            component: () => import('../views/ContactView.vue')
        },
        {
            path: '/cart',
            name: 'cart',
            component: HomeView, // Placeholder
            meta: { requiresAuth: true }
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('../views/ProfileView.vue'),
            meta: { requiresAuth: true }
        }
    ]
})

import { useAuthStore } from '@/stores/auth';

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Intentar recuperar usuario si no existe (recarga de página)
    if (!authStore.user && !authStore.loading) {
        await authStore.fetchUser();
    }

    if (to.meta.requiresAuth && !authStore.user) {
        next('/login');
    } else if (to.meta.guest && authStore.user) {
        next('/');
    } else {
        next();
    }
});

export default router
