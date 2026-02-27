# 🖥️ DWES - Desplegament Web Entorn Servidor

## 📋 Descripción del Módulo

El módulo **DWES (Desplegament Web Entorn Servidor)** se centra en el desarrollo del backend y la infraestructura del servidor de la aplicación web e-commerce AlberoPerezTech. Implementa una API REST profesional con Laravel, gestión de bases de datos MySQL, autenticación segura y despliegue en producción.

---

## 🎯 Resultados de Aprendizaje (RA) Cumplidos

### **RA1 - Implantació d'arquitectures web**
- ✅ **RA1.d**: Instal·lació i configuració bàsica de servidors d'aplicacions (Laravel + Nginx)
- ✅ **RA1.e**: Instal·lació i configuració bàsica de tecnologies de virtualització (Docker, AWS)

### **RA2 - Implantació d'aplicacions web**
- ✅ **RA2.e**: Instal·lació de certificats digitals (Let's Encrypt)
- ✅ **RA2.f**: Assegurament de comunicacions client-servidor (HTTPS, Sanctum)
- ✅ **RA2.g**: Documentació de configuració i administració segura
- ✅ **RA2.h**: Ajustos necessaris per implantació d'aplicacions
- ✅ **RA2.i**: Ús de virtualització per desplegament web (Docker, AWS)

### **RA4 - Administració de servidors de transferència**
- ✅ **RA4.b**: Creació d'usuaris i grups per accés remot
- ✅ **RA4.e**: Ús de protocols segurs de transferència d'arxius (SFTP)
- ✅ **RA4.g**: Documentació de configuració i administració de FTP

### **RA7 - Seguretat en aplicacions web**
- ✅ **RA7.e**: Gestió de seguretat en l'accés a dades
- ✅ **RA7.f**: Restricció d'operacions segons rols d'usuari
- ✅ **RA7.g**: Validació i protecció d'endpoints d'API
- ✅ **RA7.h**: Implementació d'autenticació i autorització

### **RA8 - Qualitat i proves**
- ✅ **RA8.g**: Proves funcionals i d'integració automatitzades

### **RA9 - Integració amb serveis externs**
- ✅ **RA9**: Integració amb OAuth2 (Google) i documentació API

---

## 🛠️ Herramientas y Tecnologías

### **Stack Principal**
| Tecnología | Versión | Uso en el Proyecto |
|-----------|--------|-------------------|
| **Laravel** | 12.x | Framework PHP principal |
| **PHP** | 8.4 | Lenguaje backend |
| **MySQL** | 8.0 | Base de datos relacional |
| **Composer** | 2.x | Gestión de dependencias PHP |
| **Nginx** | stable-alpine | Servidor web |

### **Autenticación y Seguridad**
| Herramienta | Versión | Funcionalidad |
|-------------|--------|-------------|
| **Laravel Sanctum** | 4.x | Autenticación SPA |
| **Laravel Socialite** | 5.x | OAuth2 (Google) |
| **Password Hashing** | nativo | Cifrado de contraseñas |
| **CSRF Protection** | Laravel | Protección contra ataques |

### **API y Documentación**
| Herramienta | Versión | Propósito |
|-------------|--------|----------|
| **Laravel API Resources** | nativo | Formato JSON consistente |
| **L5-Swagger/Scribe** | 4.x | Documentación API automática |
| **Postman** | - | Testing de endpoints |

### **Testing y Calidad**
| Herramienta | Versión | Uso |
|-------------|--------|-----|
| **PHPUnit** | 10.x | Tests unitarios y de integración |
| **Laravel Dusk** | - | Tests E2E (opcional) |
| **PHP CodeSniffer** | - | Calidad de código |

### **Base de Datos**
| Herramienta | Versión | Función |
|-------------|--------|---------|
| **MySQL** | 8.0 | Base de datos principal |
| **phpMyAdmin** | - | Gestión visual de BD |
| **Eloquent ORM** | Laravel | Mapeo objeto-relacional |
| **Migrations** | Laravel | Control de versiones de BD |

### **Integraciones**
| Servicio | Integración | Uso |
|----------|-------------|-----|
| **Google OAuth2** | Socialite | Login social |
| **JSON Server** | - | API mock para desarrollo |
| **n8n** | - | Automatización de procesos |

---

## 📋 Tareas Realizadas por Sprint

### **Sprint 1: Fundamentos de Entorno Servidor**
- ✅ Configuración inicial de entorno de desarrollo
- ✅ Instalación de stack LAMP básico
- ✅ Configuración de Apache y PHP
- ✅ Primer contacto con Git y GitHub

### **Sprint 2: Backend PHP Nativo**
- ✅ Desarrollo de API REST básica con PHP
- ✅ Implementación de sistema de usuarios
- ✅ Gestión de productos con JSON Server
- ✅ Configuración de servidor remoto AWS

### **Sprint 3: Migración a Laravel**
- ✅ Creación de proyecto Laravel
- ✅ Migración de datos a MySQL
- ✅ Implementación de Laravel Breeze
- ✅ API REST base con Laravel

### **Sprint 4: API Profesional**
- ✅ Documentación con Swagger/OpenAPI
- ✅ Integración OAuth2 con Google
- ✅ Sistema de autenticación robusto
- ✅ Pruebas automatizadas

### **Sprint 5-6: Producción y CI/CD**
- ✅ Dockerización completa del backend
- ✅ Pipeline CI/CD con GitHub Actions
- ✅ Despliegue en AWS con HTTPS
- ✅ Monitorización y logging en producción
- ✅ Despliegue en producción AWS
- ✅ CI/CD automatizado

---

## 🏗️ Arquitectura Implementada

### **Estructura del Backend**
```
laravel/
├── app/
│   ├── Http/Controllers/     # Controladores API y Web
│   ├── Http/Middleware/       # Middleware de autenticación
│   ├── Models/               # Modelos Eloquent
│   ├── Policies/             # Políticas de autorización
│   └── Providers/            # Service Providers
├── database/
│   ├── migrations/           # Migraciones de BD
│   ├── seeders/             # Datos de prueba
│   └── factories/           # Factories para testing
├── routes/
│   ├── api.php              # Rutas API REST
│   ├── web.php              # Rutas web
│   └── auth.php             # Rutas de autenticación
├── tests/
│   ├── Feature/             # Tests de integración
│   └── Unit/               # Tests unitarios
└── config/
    ├── database.php          # Configuración BD
    ├── sanctum.php          # Configuración auth
    └── scribe.php           # Configuración docs API
```

### **API REST Endpoints**
```
GET    /api/products              # Listar productos
GET    /api/products/{id}         # Detalle producto
POST   /api/products              # Crear producto (admin)
PUT    /api/products/{id}         # Actualizar producto (admin)
DELETE /api/products/{id}         # Eliminar producto (admin)

GET    /api/user                 # Usuario autenticado
POST   /api/login                # Iniciar sesión
POST   /api/logout               # Cerrar sesión
POST   /api/register             # Registro

GET    /api/products/{id}/reviews    # Reseñas de producto
POST   /api/products/{id}/reviews    # Crear reseña
PUT    /api/reviews/{id}             # Actualizar reseña
DELETE /api/reviews/{id}             # Eliminar reseña

GET    /api/oauth/google/redirect     # OAuth2 redirect
GET    /api/oauth/google/callback    # OAuth2 callback
```

---

## 📊 Métricas y Evidencias

### **Testing Automatizado**
```bash
PHPUnit Tests
✅ Tests: 37 passed
✅ Coverage: 85%
✅ Time: 2.34s
```

### **Endpoints API Documentados**
- ✅ **15 endpoints** principales documentados
- ✅ **Swagger UI** accesible en `/api/documentation`
- ✅ **Ejemplos JSON** para cada endpoint
- ✅ **Códigos de estado** documentados

### **Seguridad Implementada**
- ✅ **Autenticación Bearer** (Sanctum)
- ✅ **OAuth2 Google** integrado
- ✅ **Roles y permisos** granulares
- ✅ **Validaciones** en todos los inputs
- ✅ **HTTPS** obligatorio en producción

### **Performance**
- ✅ **Tiempo respuesta API**: <200ms promedio
- ✅ **Consultas optimizadas** con Eloquent
- ✅ **Caché configurado** para consultas frecuentes
- ✅ **Paginación** implementada en listados

---

## 🔗 Conexiones con Otros Módulos

### **Con DWEC (Frontend)**
- API REST consumida por Vue 3
- Autenticación compartida via tokens
- Validaciones coordinadas cliente-servidor

### **Con DIW (Diseño)**
- Respuestas JSON estructuradas para UI
- Assets optimizados para frontend
- Componentes reutilizables documentados

### **Con DIG (Digitalización)**
- Analytics y métricas en endpoints
- Sistema de recomendaciones via API
- Procesos asíncronos con n8n

### **Con DDAW/NUV (Cloud)**
- Dockerización completa del backend
- CI/CD automatizado para producción
- Despliegue en AWS con HTTPS

---

## 📈 Logros Destacados

1. **🏗️ Backend Profesional**: Laravel 12 con arquitectura MVC completa
2. **🔐 Seguridad Robusta**: Múltiples capas de autenticación y autorización
3. **📚 API Documentada**: Swagger/OpenAPI interactivo
4. **🧪 Testing Automatizado**: 37 tests funcionando
5. **☁️ Cloud Ready**: Despliegue automatizado en AWS
6. **🔗 Integraciones**: OAuth2 Google y servicios externos
7. **📊 Analytics**: Sistema de métricas y recomendaciones

---

## 🎯 Conclusión del Módulo

El módulo DWES ha sido implementado con éxito, proporcionando una base sólida y profesional para la aplicación e-commerce. El backend está completamente funcional, seguro y preparado para producción, cumpliendo con todos los criterios de evaluación y siguiendo las mejores prácticas de desarrollo web moderno.
