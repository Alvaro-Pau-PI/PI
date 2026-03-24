# 🖥️ DWES - Desplegament Web Entorn Servidor

## 📋 Descripción del Módulo

El módulo **DWES (Desplegament Web Entorn Servidor)** se especializa en el desarrollo del backend y la infraestructura del servidor de la aplicación web e-commerce AlberoPerezTech. Implementa una API REST profesional con Laravel, gestión de bases de datos MySQL, autenticación segura, testing automatizado y documentación de APIs.

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
| **PHPUnit** | 11.x | Tests unitarios y de integración |
| **Laravel Pint** | 1.x | Formato y calidad de código |
| **Faker** | 1.x | Datos de prueba |

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

## 📋 Tareas Realizadas

### **API REST Completa**
- ✅ **Endpoints CRUD** - API completa para productos, usuarios, pedidos y reseñas
- ✅ **Laravel API Resources** - Formato JSON consistente y estructurado
- ✅ **Route Model Binding** - Binding automático de modelos en rutas
- ✅ **API Versioning** - Sistema de versionado de endpoints
- ✅ **Error Handling** - Manejo centralizado de errores JSON

### **Autenticación y Seguridad**
- ✅ **Laravel Sanctum** - Autenticación SPA con tokens seguros
- ✅ **OAuth2 Google** - Login social con Laravel Socialite
- ✅ **Password Hashing** - Cifrado seguro de contraseñas
- ✅ **CSRF Protection** - Protección contra ataques CSRF
- ✅ **Input Validation** - Validación robusta de datos de entrada

### **Base de Datos y Modelos**
- ✅ **MySQL 8.0** - Base de datos relacional optimizada
- ✅ **Eloquent Models** - 6 modelos principales con relaciones
- ✅ **Migrations** - Control de versiones de estructura BD
- ✅ **Seeders** - Datos iniciales y de prueba
- ✅ **Query Optimization** - Consultas optimizadas con índices

### **Testing Automatizado**
- ✅ **PHPUnit 11.x** - 9 tests funcionales y de integración
- ✅ **Feature Tests** - Tests de endpoints API
- ✅ **Unit Tests** - Tests de lógica de negocio
- ✅ **Database Transactions** - Rollback automático en tests
- ✅ **Factory Pattern** - Datos de prueba con Faker

### **Documentación API**
- ✅ **Laravel Scribe** - Documentación automática de endpoints
- ✅ **OpenAPI/Swagger** - Especificación estándar de API
- ✅ **Interactive Documentation** - UI para probar endpoints
- ✅ **Code Examples** - Ejemplos de uso para cada endpoint
- ✅ **API Versioning Docs** - Documentación por versión

### **Integraciones Externas**
- ✅ **Google OAuth2** - Autenticación social completa
- ✅ **JSON Server** - Mock API para desarrollo frontend
- ✅ **n8n Workflows** - Integración con automatización
- ✅ **Email Notifications** - Sistema de notificaciones
- ✅ **File Processing** - Subida y procesamiento de archivos

---

## 🔗 Conexiones con Otros Módulos

### **Con DWEC (Frontend)**
- API REST consumida por Vue.js SPA
- Autenticación con tokens Sanctum
- Estado sincronizado con Pinia

### **Con DDAW (Despliegue)**
- Configuración para servidor Nginx
- Variables de entorno de producción
- Logs centralizados de aplicación

### **Con DIG (Digitalización)**
- Endpoints para chatbot y workflows
- APIs de recomendación y analytics
- Integración con n8n automation

### **Con SOST (Sostenibilidad)**
- API de métricas ASG y eco-badges
- Datos de sostenibilidad en tiempo real
- Endpoints para filtrado sostenible

---

## 📈 Logros Destacados

1. **🔐 API REST Segura**: Autenticación Sanctum + OAuth2 implementada
2. **📊 Base de Datos Robusta**: MySQL con Eloquent y relaciones completas
3. **🧪 Testing Automatizado**: 9 tests funcionales verificando calidad
4. **📚 Documentación Profesional**: API documentada con Scribe/Swagger
5. **⚡ Rendimiento Optimizado**: Queries optimizadas y caché implementado
6. **🔗 Integraciones Funcionales**: Google OAuth2, n8n, JSON Server
7. **🛡️ Seguridad Completa**: Validación, CSRF, hashing robusto

---

## 📋 Componentes Implementados por Sprint

### **Sprint 2: Catálogo de productos**
- ✅ **C1 - Importación Excel → JSON Server** - ProductImportController.php con PhpSpreadsheet para conversión Excel a JSON
- ✅ **C2 - Registro y login usuarios** - Laravel Breeze con autenticación completa y password hashing
- ✅ **C3 - Comentarios y valoraciones productos** - ReviewController.php con sistema de reseñas y rating

### **Sprint 3: Backend Laravel + API base**
- ✅ **C1 - Creación proyecto Laravel** - laravel/ estructura completa con .env configurado para MySQL
- ✅ **C2 - Modelo datos y migraciones** - Product model con Eloquent y migraciones MySQL implementadas
- ✅ **C3 - Autenticación Laravel Breeze** - Sistema de autenticación completo con rutas /register y /login
- ✅ **C4 - Importación Excel a BBDD** - ProductImportController.php con validación y logs de importación
- ✅ **C5 - API base productos** - /api/products endpoints con filtros, paginación y Resource classes
- ✅ **C6 - CRUD administración productos** - Panel admin implementado con acceso restringido
- ✅ **C7 - Pruebas básicas Laravel** - PHPUnit tests funcionando para API y validación

### **Sprint 4: Cliente SPA Vue y control de roles**
- ✅ **C2 - Integración autenticación API** - Tokens Sanctum implementados para comunicación con frontend Vue
- ✅ **C3 - Gestión roles y permisos** - Middleware de permisos y control de acceso granular implementado

### **Sprint 5/6: Integraciones externes y despliegue final**
- ✅ **C1 - Integración OAuth2** - SocialAuthController.php con Google Login (OpenID Connect) funcionando
- ✅ **C2 - Documentación API Swagger** - Laravel Scribe configurado con documentación automática OpenAPI
- ✅ **C8 - Documentación final** - README profesional con validación de implementaciones

---

## 🎯 Conclusión del Módulo

El módulo DWES ha sido implementado con éxito proporcionando un backend robusto, seguro y escalable. La API REST completa con Laravel, el sistema de autenticación moderna, la base de datos optimizada y el testing automatizado garantizan una infraestructura backend de alta calidad. Todas las herramientas y tecnologías están verificadas y funcionando en producción, cumpliendo con los más altos estándares de desarrollo backend.
