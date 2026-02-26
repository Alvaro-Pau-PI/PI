# Iteración 5 y 6: Integraciones externas, calidad, Docker y despliegue final

En estos dos últimos sprints hemos completado y profesionalizado el proyecto añadiendo integraciones con servicios externos, documentación interactiva de la API, mejoras avanzadas del frontend, diseño accesible, sostenibilidad, dockerización completa y despliegue en producción con HTTPS y CI/CD.

## Objetivos principales

- **C1. Integración externa (OAuth2)**: Permitir el inicio de sesión con Google mediante Laravel Socialite.
- **C2. Documentación API con Swagger**: Generar una documentación interactiva y completa de todos los endpoints.
- **C3. Mejoras avanzadas de Vue**: Implementar filtros, paginación, watchers y validación en tiempo real.
- **C4. Diseño final y accesibilidad**: Pulir la interfaz para cumplir estándares WCAG y lograr un diseño profesional.
- **C5. Digitalización inteligente**: Incorporar un recomendador de productos y una sección de destacados basada en datos.
- **C6. Sostenibilidad (ASG)**: Aplicar criterios de ecodiseño y añadir información transparente de sostenibilidad.
- **C7. Despliegue Cloud, DNS y CI/CD**: Desplegar la aplicación completa en AWS con HTTPS, DNS propio y pipelines automáticos.
- **C8. Documentación final y presentación**: Entregar la documentación técnica, el manual de usuario y hacer la demo al cliente.

## Actividades realizadas

### C1. Integración con Google (OAuth2)

- Instalación y configuración de **Laravel Socialite** con el proveedor Google.
- Implementación de los endpoints `/api/oauth/google/redirect` y `/api/oauth/google/callback` en el backend.
- Gestión segura de tokens: el `client_secret` permanece siempre en el backend, nunca se expone al frontend.
- Creación o actualización automática del usuario en la base de datos al autenticarse con Google.
- Botón "Iniciar sesión con Google" integrado en `LoginView.vue`, redirigiendo al flujo OAuth y devolviendo el token de sesión propio al frontend.

### C2. Documentación de la API con Swagger (OpenAPI)

- Instalación del paquete `l5-swagger` en el proyecto Laravel.
- Documentación de todos los endpoints principales: catálogo de productos, autenticación, usuarios, pedidos y panel de administración.
- Inclusión de esquemas de modelos, ejemplos JSON y códigos de estado (`200`, `201`, `401`, `403`, `422`, `500`).
- Configuración de la autenticación Bearer (Sanctum) directamente desde la UI de Swagger.
- Documentación accesible en `/api/documentation` con instrucciones para regenerarla con Artisan.

### C3. Mejoras avanzadas del frontend (Vue)

- Implementación de **filtros y paginación** en el listado de productos: búsqueda por texto, filtro por categoría y rango de precio.
- Uso de **watchers** en Pinia y componentes para refrescar automáticamente los datos cuando cambian los filtros o el estado de autenticación.
- Integración de **Vee-Validate + Yup** en los formularios de login, registro, crear producto y perfil, con validación en tiempo real y mensajes de error claros.
- Debounce en el buscador para reducir peticiones innecesarias a la API.
- Paginación en el backend con Eloquent (`paginate()` + `when()` para filtros dinámicos).

### C4. Diseño final y accesibilidad

- Revisión y unificación del sistema de estilos: variables CSS (`:root`), layout con Flexbox/Grid y componentes visuales coherentes en todas las vistas.
- Auditoría de accesibilidad WCAG 2.1 AA: contraste de colores, `alt` en imágenes, `label` vinculados en formularios, `aria-*` donde corresponde.
- Navegación completa con teclado y focus visible en todos los elementos interactivos.
- Optimización de imágenes: conversión a **WebP**, lazy loading y dimensiones adaptativas.
- Diseño completamente responsivo validado en escritorio, tablet y móvil.

### C5. Digitalización con tecnologías inteligentes

- Implementación de una sección de **"Productos destacados"** en la página de inicio, basada en métricas reales: número de visitas (`views`), media de valoración (`rating_avg`) y unidades vendidas.
- Endpoint `GET /api/products/featured` que devuelve los productos más relevantes según los datos acumulados.
- Integración de un **recomendador simple** en la vista de detalle de producto: muestra artículos de la misma categoría con valoración similar.
- Panel de mini-analytics en el área de administración con el top 5 de productos más vistos y más vendidos.

### C6. Sostenibilidad (ASG y ecodiseño)

- Optimización de assets: minificación de CSS/JS, compresión gzip/brotli en Nginx y reducción del número de peticiones.
- Introducción de una etiqueta **"Eco Score"** en los productos que cumplen criterios de sostenibilidad (embalaje reciclado, proveedor local, materiales reutilizables).
- Página informativa de sostenibilidad con los criterios ASG (Ambiental, Social, Gobernanza) aplicados al proyecto.
- Filtro de búsqueda por productos con etiqueta eco en el catálogo.

### C7. Despliegue Cloud, DNS y CI/CD

- Configuración de la zona DNS `projecte09.ddaw.es` con los registros A/CNAME necesarios para el frontend y el backend.
- **Dockerización completa** del proyecto: `Dockerfile` para frontend (Nginx + Vue build) y backend (PHP-FPM + Laravel), orquestados con `docker-compose`.
- Implementación de pipelines CI/CD independientes para frontend y backend:
  - **Frontend**: install → build → deploy automático.
  - **Backend**: install → tests → deploy automático + migraciones obligatorias post-deploy.
- Despliegue en **AWS** con arquitectura en capas: VPC propia con subredes públicas y privadas, Nginx como reverse proxy (terminación HTTPS), capa de aplicación en EC2 y base de datos en RDS en subred privada.
- Certificados **SSL/TLS con Let's Encrypt** en ambas aplicaciones para acceso HTTPS seguro.
- Security Groups configurados para aislar las capas: la base de datos solo acepta conexiones del backend.

### C8. Documentación final y presentación

- Redacción del **README técnico** completo: descripción del proyecto, stack tecnológico, instrucciones de desarrollo y producción con Docker, estructura de carpetas, endpoints clave y roles.
- Elaboración del **manual de usuario**: registro, inicio de sesión, navegación del catálogo, filtros, detalle de producto, gestión del perfil y funciones de administrador.
- Añadida ayuda contextual en la aplicación: tooltips en iconos, textos descriptivos en formularios complejos y mensajes de error comprensibles.
- Pruebas de compatibilidad en Firefox, Chrome y Safari, en escritorio y dispositivos móviles.
- **Demo final al cliente** mostrando el recorrido completo de la aplicación y la evolución del proyecto a lo largo de los 6 sprints.

## Resultados obtenidos

- Proyecto completo, estable y desplegado en producción con HTTPS y dominio propio.
- Integración con Google OAuth2 funcional y segura.
- API documentada interactivamente con Swagger y accesible públicamente.
- Frontend con filtros, paginación, validación en tiempo real y recomendaciones inteligentes.
- Diseño accesible, optimizado y sostenible.
- Pipelines CI/CD automatizados que permiten desplegar nuevas versiones sin intervención manual.
- Documentación técnica y manual de usuario entregados y validados.

---

Con estas dos iteraciones finales el proyecto intermodular queda **completo y listo para producción**, cubriendo todos los requisitos técnicos, de seguridad, accesibilidad, sostenibilidad y documentación profesional exigidos.
