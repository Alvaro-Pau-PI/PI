# Iteración 2: Migración a PHP + JSON Server

Esta segunda iteración se ha centrado en desarrollar un backend funcional utilizando PHP nativo y JSON Server, implementar la lógica de usuarios y productos, y configurar el despliegue en un entorno remoto.

## Objetivos principales

- **Catálogo (C1)**: Implementar la importación automática de productos desde Excel a JSON Server.
- **Usuarios (C2)**: Crear un sistema de autenticación (registro/login) y gestión de perfiles sin base de datos relacional.
- **Interacción (C3)**: Fomentar la participación mediante comentarios y valoraciones en tiempo real.
- **Despliegue (C4)**: Configurar un entorno de producción en AWS con servidores web, FTP y estrategias de backup.
- **Interfaz (C5)**: Diseñar una estructura visual clara, usable y accesible para el e-commerce.

## Actividades realizadas

### C1. Importación de Productos (Excel → JSON Server)
- **Script de importación**: Desarrollo de un script en PHP utilizando `PhpSpreadsheet` para leer ficheros Excel (.xlsx, .csv).
- **Automatización**: Conversión de los datos importados a formato JSON y envío a JSON Server simulando una API REST.
- **Validación**: Comprobación de tipos de datos y gestión de errores durante la carga.

### C2. Registro e Inicio de Sesión
- **Autenticación PHP**: Implementación manual de registro y login utilizando `password_hash()` para la seguridad.
- **Persistencia JSON**: Almacenamiento de usuarios en un fichero `users.json` gestionado por JSON Server.
- **Sesiones**: Gestión de estado mediante sesiones PHP y cookies seguras.

### C3. Comentarios y Valoraciones
- **Interactividad**: Implementación de carga y envío de comentarios mediante AJAX/Fetch sin recargar la página.
- **Permisos**: Restricción de funcionalidades (comentar, valorar) solo a usuarios autenticados.
- **Feedback**: Actualización dinámica de la interfaz al añadir nuevas valoraciones.

### C4. Despliegue y Copias de Seguridad (AWS)
- **Infraestructura**: Configuración de instancias AWS con servidores Apache (HTTP/S) y FTP.
- **Seguridad**: Implementación de certificados SSL/TLS, usuarios aislados por servicio y restricciones SSH.
- **Backups**: Automatización de copias de seguridad nocturnas de ficheros y datos.

### C5. Estructura y Usabilidad (DIW)
- **Layout**: Definición de una plantilla base (Header, Main, Footer) responsiva.
- **Componentes Visuales**: Integración de buscador, filtros, tarjetas de producto y carrito visible.
- **Accesibilidad**: Cumplimiento de criterios WCAG 2.1 AA, navegación por teclado y contraste adecuado.

## Resultados obtenidos

- Un backend flexible basado en PHP y JSON Server.
- Un sistema funcional de importación masiva de datos.
- Una plataforma desplegada en nube (AWS) con medidas de seguridad y recuperación.
- Una experiencia de usuario mejorada con diseño responsivo e interacciones dinámicas.

---

Esta iteración ha servido como paso intermedio para consolidar conceptos de backend y despliegue antes de la migración definitiva a un framework MVC como Laravel.
