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

            // Importación bajo demanda para resolver el precio efectivo de la oferta
            let effectivePrice = parseFloat(product.price);
            if (product.is_offer_active !== undefined) {
                // Inline calculation to avoid circular dependencies if any
                const now = new Date();
                let isValid = product.is_offer_active;

                if (isValid && product.offer_start_date) {
                    const startDateStr = product.offer_start_date.endsWith('Z') ? product.offer_start_date.slice(0, -1) : product.offer_start_date;
                    const startDate = new Date(startDateStr);
                    if (startDate > now) isValid = false;
                }

                if (isValid && product.offer_end_date) {
                    const endDateStr = product.offer_end_date.endsWith('Z') ? product.offer_end_date.slice(0, -1) : product.offer_end_date;
                    const endDate = new Date(endDateStr);
                    if (endDate < now) isValid = false;
                }

                if (isValid && product.discount_price !== null) {
                    effectivePrice = parseFloat(product.discount_price);
                }
            }

            if (existingItem) {
                existingItem.quantity++;
                existingItem.price = effectivePrice; // Refresh price in case offer changed
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: effectivePrice,
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
