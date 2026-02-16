import { defineStore } from 'pinia'
import http from '@/services/http'

export const useProductStore = defineStore('products', {
    state: () => ({
        products: [],
        currentProduct: null,
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 12
        },
        filters: {
            search: '',
            category: '',
            min_price: null,
            max_price: null
        },
        loading: false,
        error: null
    }),
    actions: {
        async fetchProducts(page = 1) {
            this.loading = true;
            try {
                // Construir query string manualmente para control total
                const params = {
                    page: page,
                    ...this.filters
                };

                // Limpiar parámetros nulos o vacíos
                Object.keys(params).forEach(key => {
                    if (params[key] === null || params[key] === '') {
                        delete params[key];
                    }
                });

                const response = await http.get('/api/products', { params });

                // Asignar datos paginados
                this.products = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    total: response.data.total,
                    per_page: response.data.per_page
                };
            } catch (err) {
                this.error = err.message || 'Error carregant productes';
                this.products = [];
            } finally {
                this.loading = false;
            }
        },
        resetFilters() {
            this.filters = {
                search: '',
                category: '',
                min_price: null,
                max_price: null
            };
            this.fetchProducts(1);
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
