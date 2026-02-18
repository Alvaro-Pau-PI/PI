import { defineStore } from 'pinia';
import http from '@/services/http';

/**
 * Store de pedidos - Conecta con la API real de Laravel.
 * Gestiona la creación, consulta y seguimiento de pedidos.
 */
export const useOrdersStore = defineStore('orders', {
    state: () => ({
        orders: [],
        currentOrder: null,
        loading: false,
        error: null
    }),

    getters: {
        /** Pedidos ordenados por fecha descendente */
        sortedOrders: (state) => [...state.orders].sort((a, b) =>
            new Date(b.created_at) - new Date(a.created_at)
        ),

        /** Contar pedidos por estado */
        ordersByStatus: (state) => (status) =>
            state.orders.filter(o => o.status === status)
    },

    actions: {
        /**
         * Crear un nuevo pedido enviando datos al backend.
         * @param {Object} orderData - Datos del pedido (items, envío, tarjeta)
         * @returns {Object} El pedido creado
         */
        async createOrder(orderData) {
            this.loading = true;
            this.error = null;

            try {
                const response = await http.post('/api/orders', orderData);
                this.orders.unshift(response.data);
                return response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al crear el pedido';
                throw err;
            } finally {
                this.loading = false;
            }
        },

        /**
         * Obtener todos los pedidos del usuario autenticado.
         */
        async fetchOrders() {
            this.loading = true;
            this.error = null;

            try {
                const response = await http.get('/api/orders');
                this.orders = response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al cargar pedidos';
            } finally {
                this.loading = false;
            }
        },

        /**
         * Obtener detalle de un pedido específico.
         */
        async fetchOrder(orderId) {
            this.loading = true;
            try {
                const response = await http.get(`/api/orders/${orderId}`);
                this.currentOrder = response.data;
                return response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al cargar el pedido';
                throw err;
            } finally {
                this.loading = false;
            }
        },

        /**
         * [ADMIN] Obtener todos los pedidos con filtros opcionales.
         */
        async fetchAdminOrders(params = {}) {
            this.loading = true;
            try {
                const response = await http.get('/api/admin/orders', { params });
                return response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al cargar pedidos';
                throw err;
            } finally {
                this.loading = false;
            }
        },

        /**
         * [ADMIN] Actualizar el estado de un pedido.
         */
        async updateOrderStatus(orderId, status) {
            try {
                const response = await http.patch(`/api/admin/orders/${orderId}/status`, { status });
                // Actualizar en la lista local si existe
                const index = this.orders.findIndex(o => o.id === orderId);
                if (index !== -1) {
                    this.orders[index] = response.data;
                }
                return response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al actualizar estado';
                throw err;
            }
        }
    }
});
