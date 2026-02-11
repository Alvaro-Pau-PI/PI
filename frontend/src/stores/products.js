import { defineStore } from 'pinia'
import http from '@/services/http'

export const useProductStore = defineStore('products', {
    state: () => ({
        products: [],
        currentProduct: null,
        loading: false,
        error: null
    }),
    actions: {
        async fetchProducts() {
            this.loading = true;
            try {
                const response = await http.get('/api/products');
                this.products = response.data;
            } catch (err) {
                this.error = err.message || 'Error carregant productes';
            } finally {
                this.loading = false;
            }
        },
        async fetchProduct(id) {
            this.loading = true;
            try {
                // Fetch product details
                const response = await http.get(`/api/products/${id}`);
                this.currentProduct = response.data;

                // Fetch reviews if endpoint exists, otherwise empty
                try {
                    const reviewsResponse = await http.get(`/api/products/${id}/reviews`);
                    this.currentProduct.reviews = reviewsResponse.data;
                } catch (e) {
                    this.currentProduct.reviews = [];
                }

            } catch (err) {
                this.error = err.message || 'Error carregant producte';
            } finally {
                this.loading = false;
            }
        },
        async addReview(productId, reviewData) {
            try {
                await http.post(`/api/products/${productId}/reviews`, reviewData);
                // Refresh product to see new review
                await this.fetchProduct(productId);
            } catch (error) {
                throw error;
            }
        }
    }
})
