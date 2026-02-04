import axios from 'axios'

// Configuración global de axios
// En desarrollo, el proxy de Vite maneja las rutas /api
// En producción (Docker), necesitamos apuntar explícitamente al backend
const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    withCredentials: true // Para cookies de sesión/CSRF
})

export default apiClient
