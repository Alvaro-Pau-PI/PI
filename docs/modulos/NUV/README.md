# ☁️ NUV - Núvol

## 📋 Descripción del Módulo

El módulo **NUV (Núvol)** se especializa en la implementación y gestión de servicios cloud para la plataforma e-commerce AlberoPerezTech. Diseña, configura y mantiene una infraestructura real en AWS, implementando Docker Compose para orquestación de contenedores, CI/CD automatizado con GitHub Actions, y optimización de recursos para producción.

---

## 🛠️ Herramientas y Tecnologías

### **Infraestructura Cloud Real**
| Servicio | Configuración Real | Uso en el Proyecto |
|----------|-------------------|-------------------|
| **EC2** | t3.medium (Ubuntu 24.04) | Servidor producción 18.206.113.196 |
| **Docker Compose** | - | Orquestación de contenedores completa |
| **GitHub Actions** | - | CI/CD automatizado funcionando |
| **SSL/TLS** | Let's Encrypt | HTTPS configurado y operativo |

### **Docker y Contenedores**
| Herramienta | Versión | Implementación Real |
|-------------|--------|-------------------|
| **docker-compose.yml** | - | Desarrollo completo con 8 servicios |
| **docker-compose.prod.yml** | - | Producción optimizada |
| **Frontend Container** | Vue 3 + Nginx | Build y deploy automático |
| **Backend Container** | Laravel 12 + PHP-FPM | API REST con MySQL |
| **Database Container** | MySQL 8.0 | Base de datos persistente |

### **CI/CD y Automatización**
| Herramienta | Versión | Funcionalidad Real |
|-------------|--------|-------------------|
| **GitHub Actions** | - | Deploy automático a EC2 |
| **deploy-frontend.yml** | - | Pipeline frontend funcionando |
| **Docker Build** | - | Build automático con cache |
| **SSH Deployment** | - | Deploy seguro con claves |
| **Environment Variables** | - | Configuración producción segura |

### **Scripts y Configuración**
| Herramienta | Implementación | Propósito |
|-------------|---------------|---------|
| **setup_system.sh** | Bash | Configuración inicial servidor |
| **backup.sh** | Bash | Backups automáticos diarios |
| **vsftpd.conf** | Config | Servidor FTP seguro |
| **nginx.conf** | Config | Servidor web optimizado |

### **Monitorización y Logs**
| Herramienta | Implementación Real | Uso |
|-------------|-------------------|-----|
| **Docker Logs** | - | Monitorización contenedores |
| **Nginx Access Logs** | - | Logs de acceso web |
| **Application Logs** | Laravel | Logs de aplicación |
| **Health Checks** | Docker | Verificación estado servicios |
| **Performance Monitoring** | Básica | Métricas de rendimiento |

---

## 📋 Tareas Realizadas

### **Infraestructura Cloud Real**
- ✅ **EC2 Server** - Instancia t3.medium en 18.206.113.196 funcionando
- ✅ **Ubuntu 24.04** - Sistema operativo configurado y seguro
- ✅ **Docker Engine** - Instalado y configurado para producción
- ✅ **Networking** - Configuración de red y seguridad básica
- ✅ **Storage** - Discos persistentes para datos y backups

### **Dockerización Completa**
- ✅ **docker-compose.yml** - Desarrollo con 8 servicios integrados
- ✅ **docker-compose.prod.yml** - Producción optimizada y segura
- ✅ **Frontend Container** - Vue 3 + Nginx con build automático
- ✅ **Backend Container** - Laravel 12 + PHP-FPM + MySQL
- ✅ **Multi-service Architecture** - n8n, phpMyAdmin, JSON Server

### **CI/CD Automatizado**
- ✅ **GitHub Actions** - Pipeline completo implementado
- ✅ **deploy-frontend.yml** - Deploy automático funcionando
- ✅ **Build Optimization** - Cache y build paralelo
- ✅ **SSH Deployment** - Conexión segura con claves
- ✅ **Environment Management** - Variables producción seguras

### **Scripts de Despliegue**
- ✅ **setup_system.sh** - Configuración inicial servidor completo
- ✅ **backup.sh** - Backups automáticos diarios programados
- ✅ **vsftpd.conf** - Servidor FTP seguro con SSL
- ✅ **nginx.conf** - Configuración web optimizada

### **Monitorización y Mantenimiento**
- ✅ **Docker Logs** - Monitorización centralizada de contenedores
- ✅ **Web Server Logs** - Logs de acceso y error Nginx
- ✅ **Application Logs** - Logs Laravel integrados
- ✅ **Health Checks** - Verificación automática de servicios
- ✅ **Performance Metrics** - Métricas básicas de rendimiento

---

## � Conexiones con Otros Módulos

### **Con DWES (Backend)**
- API Laravel desplegada en contenedor Docker
- Base de datos MySQL persistente en cloud
- Logs y monitorización centralizados

### **Con DWEC (Frontend)**
- Build y deploy automático con GitHub Actions
- Servidor Nginx configurado para producción
- Optimización de assets y caché

### **Con DDAW (Despliegue Web)**
- Scripts de configuración compartidos
- Certificados SSL gestionados centralmente
- Configuración FTP para gestión de archivos

### **Con DIG (Digitalización)**
- n8n desplegado como contenedor Docker
- Workflows automatizados en producción
- Integración con APIs externas

---

## 📈 Logros Destacados

1. **☁️ Infraestructura Real**: EC2 funcionando en 18.206.113.196
2. **🐳 Docker Completo**: 8 servicios orquestados con Docker Compose
3. **🚀 CI/CD Funcional**: GitHub Actions deploy automático
4. **🔐 HTTPS Seguro**: Certbot SSL configurado y renovando
5. **📋 Scripts Automatizados**: setup_system.sh y backup.sh funcionando
6. **📊 Monitorización Activa**: Logs y health checks operativos
7. **💰 Costos Optimizados**: t3.medium con recursos eficientes

---

## 📋 Componentes Implementados por Sprint

### **Sprint 1: Entorn, aparador i contacte**
- ✅ **C3 - Configuración entorno trabajo** - Docker (nginx, php, mysql) con docker-compose.yml completo

### **Sprint 5/6: Integraciones externes y despliegue final**
- ✅ **C7 - Despliegue Cloud, DNS, CI/CD** - EC2 real en 18.206.113.196 con GitHub Actions y Docker Compose producción
- ✅ **C8 - Documentación final** - README profesional con validación de implementaciones cloud

---

## 🎯 Conclusión del Módulo

El módulo NUV ha sido implementado con éxito creando una infraestructura cloud real y funcional. La orquestación con Docker Compose, el CI/CD automatizado con GitHub Actions, y los scripts de despliegue garantizan una solución profesional y escalable. Todas las tecnologías están verificadas y funcionando en producción en el servidor EC2 real, proporcionando una base sólida y mantenible para la aplicación e-commerce.
