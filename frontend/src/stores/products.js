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
            max_price: null,
            sustainable_only: false,
            refurbished_only: false,
            local_only: false
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

                // Asignar datos paginados con comprobación defensiva
                let productsData = [];
                if (response.data && Array.isArray(response.data.data)) {
                    productsData = response.data.data;
                } else if (Array.isArray(response.data)) {
                    productsData = response.data;
                } else {
                    console.error('Formato de respuesta inesperado:', response.data);
                }

                // TEMPORAL: Añadir datos de sostenibilidad simulados a productos existentes
                const products = productsData.map((product, index) => {
                    // Asignar diferentes valores eco alternando productos
                    const ecoVariant = index % 5;

                    return {
                        ...product,
                        eco_score: ecoVariant === 0 ? 85 :
                            ecoVariant === 1 ? 75 :
                                ecoVariant === 2 ? 65 :
                                    ecoVariant === 3 ? 50 : 0,
                        is_refurbished: ecoVariant === 0 || ecoVariant === 1,
                        is_recyclable: ecoVariant <= 2,
                        has_eco_packaging: ecoVariant <= 3,
                        is_local_supplier: ecoVariant === 0 || ecoVariant === 2,
                        carbon_footprint: ecoVariant === 0 ? 2.5 :
                            ecoVariant === 1 ? 3.8 :
                                ecoVariant === 2 ? 4.2 : null
                    };
                });

                this.products = products;

                // FILTRADO LOCAL (Solo mientras se use simulación)
                if (this.filters.sustainable_only) {
                    this.products = this.products.filter(p => p.eco_score >= 70);
                }
                if (this.filters.refurbished_only) {
                    this.products = this.products.filter(p => p.is_refurbished);
                }
                if (this.filters.local_only) {
                    this.products = this.products.filter(p => p.is_local_supplier);
                }

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
                max_price: null,
                sustainable_only: false,
                refurbished_only: false,
                local_only: false
            };
            this.fetchProducts(1);
        },
        async fetchProduct(id) {
            this.loading = true;
            try {
                // Fetch product details
                const response = await http.get(`/api/products/${id}`);
                const product = response.data;

                // Añadir datos de sostenibilidad simulados para el detalle (ID basado)
                const ecoSeed = parseInt(id) % 5;
                this.currentProduct = {
                    ...product,
                    eco_score: ecoSeed === 0 ? 85 : ecoSeed === 1 ? 75 : ecoSeed === 2 ? 65 : ecoSeed === 3 ? 50 : 0,
                    is_refurbished: ecoSeed === 0 || ecoSeed === 1,
                    is_recyclable: ecoSeed <= 2,
                    has_eco_packaging: ecoSeed <= 3,
                    is_local_supplier: ecoSeed === 0 || ecoSeed === 2,
                    carbon_footprint: ecoSeed === 0 ? 2.5 : ecoSeed === 1 ? 3.8 : ecoSeed === 2 ? 4.2 : null
                };

                // Fetch reviews if endpoint exists
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
            const response = await http.post(`/api/products/${productId}/reviews`, reviewData);
            // Refrescar el producte per veure la nova ressenya
            await this.fetchProduct(productId);
            return response.data;
        }
    }
})
