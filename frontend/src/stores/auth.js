import { defineStore } from 'pinia'
import http from '@/services/http'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        errors: null
    }),
    actions: {
        async fetchUser() {
            this.loading = true;
            try {
                const response = await http.get('/api/user');
                this.user = response.data;
            } catch (error) {
                this.user = null;
            } finally {
                this.loading = false;
            }
        },
        async login(credentials) {
            this.loading = true;
            this.errors = null;
            try {
                await http.get('/sanctum/csrf-cookie');
                await http.post('/login', credentials);
                await this.fetchUser();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    this.errors = { message: 'Error en inicio de sesión' };
                }
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async logout() {
            try {
                await http.post('/logout');
            } catch (error) {
                // Ignorar error en logout
            } finally {
                this.user = null;
            }
        },
        async register(userData) {
            this.loading = true;
            this.errors = null;
            try {
                await http.get('/sanctum/csrf-cookie');
                await http.post('/register', userData);
                await this.fetchUser();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || error.response.data;
                } else {
                    this.errors = { message: 'Error en el registre' };
                }
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async updateProfile(data) {
            this.loading = true;
            this.errors = null;
            try {
                const response = await http.put('/api/user/profile-information', data);
                // Actualizar estado usuario
                if (response.data.user) {
                    this.user = response.data.user;
                } else {
                    await this.fetchUser();
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || error.response.data;
                }
                throw error;
            } finally {
                this.loading = false;
            }
        },
        async updatePassword(data) {
            this.loading = true;
            this.errors = null;
            try {
                await http.put('/api/user/password', data);
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || error.response.data;
                }
                throw error;
            } finally {
                this.loading = false;
            }
        }
    }
})
