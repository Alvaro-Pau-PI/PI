# 🚀 DDAW - Desplegament d'Aplicacions Web

## 📋 Descripción del Módulo

El módulo **DDAW (Desplegament d'Aplicacions Web)** se especializa en la configuración y gestión de servidores web, hosting y despliegue de aplicaciones. Implementa soluciones de infraestructura web local, servidores Apache/Nginx, certificados SSL, configuración FTP y automatización de despliegues en entornos de producción.

---

## 🛠️ Herramientas y Tecnologías

### **Servidores Web Principales**
| Herramienta | Versión | Funcionalidad |
|-------------|--------|-------------|
| **Apache** | 2.4 | Servidor web principal para backend |
| **Nginx** | stable-alpine | Servidor web para frontend y proxy |
| **PHP-FPM** | 8.4 | Procesador PHP optimizado |
| **Node.js** | 18.x | Runtime para build de frontend |

### **Seguridad y Certificados**
| Tecnología | Versión | Propósito |
|-------------|--------|----------|
| **Let's Encrypt** | Certbot 2.1 | Certificados SSL gratuitos |
| **vsftpd** | 3.0+ | Servidor FTP seguro con SSL |
| **Systemd Services** | - | Gestión de servicios del sistema |
| **SSH Keys** | - | Conexiones seguras al servidor |

### **Automatización y Scripts**
| Herramienta | Versión | Uso |
|-------------|--------|-----|
| **Bash Scripts** | - | Automatización de despliegue |
| **Docker** | 24.x | Contenerización para desarrollo |
| **Docker Compose** | 2.x | Orquestación local |
| **Multi-stage builds** | - | Optimización de imágenes |

### **Monitorización y Logs**
| Herramienta | Versión | Función |
|-------------|--------|---------|
| **Nginx Access Logs** | - | Logs de acceso web |
| **Apache Error Logs** | - | Logs de errores |
| **Application Logs** | Laravel | Logs de aplicación |
| **Health Checks** | - | Monitoreo de salud |

---

## 📋 Tareas Realizadas

### **Configuración de Servidores Web**
- ✅ **Apache 2.4** configurado con virtual hosts
- ✅ **Nginx** como proxy inverso y servidor estático
- ✅ **PHP-FPM 8.4** optimizado para rendimiento
- ✅ **Node.js 18.x** para build de frontend
- ✅ **Multi-hosting** con dominios configurados

### **Implementación de Seguridad**
- ✅ **Certificados SSL** con Let's Encrypt y Certbot
- ✅ **vsftpd 3.0+** configurado con FTPS implícito
- ✅ **Firewall UFW** con reglas personalizadas
- ✅ **SSH Keys** para acceso seguro sin contraseña
- ✅ **Systemd services** para gestión automática

### **Scripts de Automatización**
- ✅ **setup_prod.sh** - Configuración inicial del servidor
- ✅ **backup.sh** - Backup automático de archivos y base de datos
- ✅ **deploy.sh** - Despliegue automático con Git pull
- ✅ **ssl_renew.sh** - Renovación automática de certificados
- ✅ **monitor.sh** - Monitorización básica del sistema

### **Configuraciones Específicas**
- ✅ **Apache vhost_app.conf** - Virtual host para aplicación Laravel
- ✅ **Nginx nginx.conf** - Configuración principal y proxy
- ✅ **vsftpd.conf** - Servidor FTP seguro con SSL/TLS
- ✅ **php.ini** - Optimización de PHP para producción
- ✅ **my.cnf** - Configuración MySQL optimizada

### **Logs y Monitorización**
- ✅ **Nginx access logs** - Registro de todas las peticiones web
- ✅ **Apache error logs** - Registro de errores y accesos
- ✅ **Laravel logs** - Logs específicos de la aplicación
- ✅ **Health checks** - Verificación automática de servicios
- ✅ **Log rotation** - Rotación automática de logs

---

## 🔗 Conexiones con Otros Módulos

### **Con DWES (Backend)**
- Configuración Apache para Laravel
- Logs de aplicación centralizados
- Variables de entorno de producción

### **Con DWEC (Frontend)**
- Servidor Nginx para archivos estáticos
- Build optimizado con Node.js
- Cache y compresión web

### **Con NUV (Cloud)**
- Scripts compatibles con despliegue cloud
- Configuración de seguridad transferible
- Monitorización básica compartida

### **Con SOST (Sostenibilidad)**
- Optimización de servidores para eficiencia
- Logs de acceso para análisis de impacto
- Configuración eco-eficiente

---

## � Logros Destacados

1. **🏗️ Servidores Web Profesionales**: Apache + Nginx configurados
2. **🔐 Seguridad Completa**: SSL + FTPS + SSH implementados
3. **🔄 Automatización Total**: Scripts para todas las tareas
4. **📊 Monitorización Activa**: Logs y health checks funcionando
5. **⚡ Rendimiento Optimizado**: PHP-FPM y cache configurados
6. **🛠️ Mantenimiento Automático**: Backups y renovación SSL
7. **🌐 Multi-dominio**: Configuración para varios sitios

---

## 🎯 Conclusión del Módulo

El módulo DDAW ha sido implementado con éxito proporcionando una infraestructura web robusta y segura. La configuración de servidores Apache y Nginx, junto con los scripts de automatización y las medidas de seguridad, garantizan un despliegue profesional y mantenible. Todas las herramientas y tecnologías están verificadas y funcionando en producción.
