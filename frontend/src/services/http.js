import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const http = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// Interceptor de respuesta para manejar errores globales
http.interceptors.response.use(
    (response) => response,
    async (error) => {
        const authStore = useAuthStore();

        if (error.response) {
            if (error.response.status === 401) {
                // Sesión expirada o no válida
                if (authStore.user) {
                    await authStore.logout(); // Limpiar estado local
                }
                // Opcional: Redirigir a login si no estamos ya allí
                // window.location.href = '/login'; 
            }

            if (error.response.status === 403) {
                // Acceso prohibido (Roles)
                console.warn('Acceso denegado: No tienes permisos suficientes.');
            }
        }

        return Promise.reject(error);
    }
);

export default http;
