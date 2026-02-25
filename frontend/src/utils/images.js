/**
 * Utilidades para la gestión de URLs de imágenes.
 * Unifica la lógica para obtener la URL completa de una imagen,
 * manejando tanto rutas locales como remotas y el prefijo de storage.
 */

export const getImageUrl = (path) => {
    if (!path) return '/img/placeholder-product.jpg';

    // Si ya es una URL completa (ej. de una CDN externa), la devolvemos tal cual
    if (path.startsWith('http')) return path;

    // En producción (dentro de Docker), preferimos rutas relativas para que el proxy de Nginx funcione
    const isProduction = import.meta.env.PROD || window.location.port !== '5173';

    // Limpiamos la ruta
    let cleanPath = path;

    // Si el path ya empieza por /, no añadimos otro
    const finalPath = cleanPath.startsWith('/') ? cleanPath : `/${cleanPath}`;

    if (isProduction) {
        // Codificamos la URI para manejar espacios y caracteres especiales en nombres de archivo
        return encodeURI(finalPath);
    }

    // En desarrollo (Vite) usamos la URL completa al backend
    const baseUrl = import.meta.env.VITE_API_URL || import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000';
    const normalizedBaseUrl = baseUrl.replace(/\/$/, '');

    // Codificamos la URI para manejar espacios y caracteres especiales en nombres de archivo
    return `${normalizedBaseUrl}${encodeURI(finalPath)}`;
};
