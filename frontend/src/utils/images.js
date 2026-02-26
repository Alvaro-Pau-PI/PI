/**
 * Utilidades para la gestión de URLs de imágenes.
 * Unifica la lógica para obtener la URL completa de una imagen,
 * manejando tanto rutas locales como remotas y el prefijo de storage.
 */

export const getImageUrl = (path) => {
    if (!path) return '/img/placeholder-product.jpg';

    // Si ya es una URL completa (ej. de una CDN externa), la devolvemos tal cual
    if (path.startsWith('http')) return path;

    // Limpiamos la ruta - eliminamos barras diagonales escapadas y espacios extra
    let cleanPath = path.replace(/\\\//g, '/').trim();

    // En Docker (Desarrollo), usamos URLs absolutas del backend para las imágenes
    // En Producción (AWS), usamos rutas relativas porque Nginx ya enruta '/storage'
    const baseUrl = import.meta.env.PROD ? '' : 'http://localhost:8000';
    const normalizedBaseUrl = baseUrl.replace(/\/$/, '');

    // Si el path ya empieza por /storage/, lo usamos directamente
    // Si no, añadimos /storage/ al principio
    const finalPath = cleanPath.startsWith('/storage/') ? cleanPath : `/storage${cleanPath.startsWith('/') ? cleanPath : '/' + cleanPath}`;

    // Codificamos la URI para manejar espacios y caracteres especiales en nombres de archivo
    return `${normalizedBaseUrl}${encodeURI(finalPath)}`;
};
