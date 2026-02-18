import { defineStore } from 'pinia';

export const useWishlistStore = defineStore('wishlist', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('wishlist_items')) || []
    }),
    getters: {
        totalItems: (state) => state.items.length
    },
    actions: {
        toggleWishlist(product) {
            const index = this.items.findIndex(item => item.id === product.id);

            if (index === -1) {
                // Añadir
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image
                });
            } else {
                // Quitar
                this.items.splice(index, 1);
            }
            this.saveWishlist();
        },

        isInWishlist(productId) {
            return this.items.some(item => item.id === productId);
        },

        saveWishlist() {
            localStorage.setItem('wishlist_items', JSON.stringify(this.items));
        }
    }
});
