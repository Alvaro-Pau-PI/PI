import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { top: 0, behavior: 'smooth' };
        }
    },
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
            path: '/sostenibilidad',
            name: 'sustainability',
            component: () => import('../views/SustainabilityView.vue')
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('../views/CartView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/orders',
            name: 'orders',
            component: () => import('../views/OrdersView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/favorites',
            name: 'favorites',
            component: () => import('../views/FavoritesView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('../views/ProfileView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/admin',
            component: () => import('../views/admin/AdminLayout.vue'),
            meta: { requiresAuth: true, role: 'admin' },
            redirect: '/admin/products',
            children: [
                {
                    path: 'products',
                    name: 'admin-products',
                    component: () => import('../views/admin/AdminProducts.vue')
                },
                {
                    path: 'orders',
                    name: 'admin-orders',
                    component: () => import('../views/admin/AdminOrders.vue')
                }
            ]
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: () => import('../views/CheckoutView.vue'),
            meta: { requiresAuth: true }
        },
        // Ruta 404 / Catch-all
        {
            path: '/:catchAll(.*)*',
            name: 'not-found',
            redirect: '/'
        }
    ]
})

import { useAuthStore } from '@/stores/auth';

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Intentar recuperar usuario si no existe (recarga de página)
    if (!authStore.user && !authStore.loading) {
        try {
            await authStore.fetchUser();
        } catch (e) {
            // Error silencioso, usuario no logueado
        }
    }

    // Verificar autenticación
    if (to.meta.requiresAuth && !authStore.user) {
        return next('/login');
    }

    // Verificar rol (si la ruta lo requiere)
    if (to.meta.role && authStore.user?.role !== to.meta.role) {
        return next('/'); // Redirigir a home si no tiene permisos
    }

    // Verificar invitado (login/register)
    if (to.meta.guest && authStore.user) {
        return next('/');
    }

    next();
});

export default router
