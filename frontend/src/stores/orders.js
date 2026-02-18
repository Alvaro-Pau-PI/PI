import { defineStore } from 'pinia';

export const useOrdersStore = defineStore('orders', {
    state: () => ({
        orders: JSON.parse(localStorage.getItem('user_orders')) || []
    }),
    actions: {
        createOrder(cartItems, total) {
            if (cartItems.length === 0) return null;

            const newOrder = {
                id: 'ORD-' + Date.now().toString(36).toUpperCase(),
                date: new Date().toISOString(),
                status: 'Completado', // Simulado
                items: [...cartItems],
                total: total
            };

            this.orders.unshift(newOrder); // Añadir al principio
            this.saveOrders();
            return newOrder.id;
        },

        saveOrders() {
            localStorage.setItem('user_orders', JSON.stringify(this.orders));
        }
    }
});
