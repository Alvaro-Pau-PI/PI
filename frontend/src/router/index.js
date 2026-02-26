import { createRouter, createWebHistory } from 'vue-router'
import InicioView from '../views/InicioView.vue'

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
            component: InicioView
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/AccesoView.vue'),
            meta: { guest: true }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('../views/RegistroView.vue'),
            meta: { guest: true }
        },
        {
            path: '/products',
            name: 'products',
            component: () => import('../views/ProductosView.vue')
        },
        {
            path: '/products/:id',
            name: 'product-detail',
            component: () => import('../views/ProductoDetalleView.vue')
        },
        {
            path: '/contact',
            name: 'contact',
            component: () => import('../views/ContactoView.vue')
        },
        {
            path: '/sostenibilidad',
            name: 'sustainability',
            component: () => import('../views/SostenibilidadView.vue')
        },
        {
            path: '/guia-montaje',
            name: 'guide',
            component: () => import('../views/GuiaMontajeView.vue')
        },
        {
            path: '/faq',
            name: 'faq',
            component: () => import('../views/PreguntasFrecuentesView.vue')
        },
        {
            path: '/politica-privacidad',
            name: 'privacy-policy',
            component: () => import('../views/PoliticaPrivacidadView.vue')
        },
        {
            path: '/terminos-condiciones',
            name: 'terms',
            component: () => import('../views/TerminosCondicionesView.vue')
        },
        {
            path: '/politica-cookies',
            name: 'cookies-policy',
            component: () => import('../views/PoliticaCookiesView.vue')
        },
        {
            path: '/cart',
            name: 'cart',
            component: () => import('../views/CarritoView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/orders',
            name: 'orders',
            component: () => import('../views/PedidosView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/favorites',
            name: 'favorites',
            component: () => import('../views/FavoritosView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/profile',
            name: 'profile',
            component: () => import('../views/PerfilView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/admin',
            component: () => import('../views/admin/MaquetaAdmin.vue'),
            meta: { requiresAuth: true, role: 'admin' },
            redirect: '/admin/products',
            children: [
                {
                    path: 'products',
                    name: 'admin-products',
                    component: () => import('../views/admin/GestionProductos.vue')
                },
                {
                    path: 'users',
                    name: 'admin-users',
                    component: () => import('../views/admin/GestionUsuarios.vue')
                },
                {
                    path: 'reviews',
                    name: 'admin-reviews',
                    component: () => import('../views/admin/GestionResenas.vue')
                },
                {
                    path: 'contacts',
                    name: 'admin-contacts',
                    component: () => import('../views/admin/GestionMensajes.vue')
                },
                {
                    path: 'orders',
                    name: 'admin-orders',
                    component: () => import('../views/admin/GestionPedidos.vue')
                }
            ]
        },
        {
            path: '/checkout',
            name: 'checkout',
            component: () => import('../views/PagoView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/montaje-pc',
            name: 'pc-builder',
            component: () => import('../views/MontajePCView.vue')
        },
        // Ruta 404 / Capturar el resto (Catch-all)
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

router.afterEach(() => {
    // Forzar siempre que la página suba arriba al cambiar de ruta
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });
});

export default router
