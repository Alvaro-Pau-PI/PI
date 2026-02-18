import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('cart_items')) || []
    }),
    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),

        subtotal: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2),

        tax: (state) => (state.items.reduce((total, item) => total + (item.price * item.quantity), 0) * 0.21).toFixed(2), // 21% IVA

        totalPrice: (state) => {
            const sub = state.items.reduce((total, item) => total + (item.price * item.quantity), 0);
            return (sub * 1.21).toFixed(2);
        }
    },
    actions: {
        addItem(product) {
            const existingItem = this.items.find(item => item.id === product.id);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    image: product.image,
                    quantity: 1
                });
            }
            this.saveCart();
        },

        removeItem(productId) {
            this.items = this.items.filter(item => item.id !== productId);
            this.saveCart();
        },

        updateQuantity(productId, quantity) {
            const item = this.items.find(item => item.id === productId);
            if (item) {
                item.quantity = quantity;
                if (item.quantity <= 0) {
                    this.removeItem(productId);
                } else {
                    this.saveCart();
                }
            }
        },

        clearCart() {
            this.items = [];
            this.saveCart();
        },

        saveCart() {
            localStorage.setItem('cart_items', JSON.stringify(this.items));
        }
    }
});
