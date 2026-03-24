# 🚀 DDAW - Desplegament d'Aplicacions Web

## 📋 Descripción del Módulo

El módulo **DDAW (Desplegament d'Aplicacions Web)** se centra en el despliegue, configuración y gestión de infraestructura para la aplicación e-commerce AlberoPerezTech. Implementa soluciones de hosting, servidores web, bases de datos, seguridad y automatización del despliegue en entornos de producción.

---

## 🎯 Resultados de Aprendizaje (RA) Cumplidos

### **RA1 - Infraestructura de desplegament**
- ✅ **RA1.a**: Configuració d'infraestructura de hosting web
- ✅ **RA1.b**: Instal·lació i configuració de servidors web
- ✅ **RA1.c**: Gestió de bases de dades en entorns de producció
- ✅ **RA1.d**: Implementació de solucions d'alta disponibilitat

### **RA2 - Serveis web i protocols**
- ✅ **RA2.a**: Configuració de protocols HTTP/HTTPS
- ✅ **RA2.b**: Implementació de certificats SSL/TLS
- ✅ **RA2.c**: Optimització de servidors web per rendiment
- ✅ **RA2.d**: Configuració de balanceig de càrrega

### **RA3 - Seguretat en desplegament**
- ✅ **RA3.a**: Implementació de mesures de seguretat en servidors
- ✅ **RA3.b**: Configuració de firewalls i accessos segurs
- ✅ **RA3.c**: Gestió d'usuaris i permisos en sistemes
- ✅ **RA3.d**: Monitorització i detecció d'incidents

### **RA4 - Automatització i CI/CD**
- ✅ **RA4.a**: Implementació de pipelines d'integració contínua
- ✅ **RA4.b**: Automatització de desplegaments
- ✅ **RA4.c**: Gestió de configuracions amb infraestructura com a codi
- ✅ **RA4.d**: Monitorització i logging en producció

### **RA5 - Optimització i rendiment**
- ✅ **RA5.a**: Optimització de rendiment d'aplicacions web
- ✅ **RA5.b**: Implementació de sistemes de caché
- ✅ **RA5.c**: Monitorització de mètriques de rendiment
- ✅ **RA5.d**: Escalabilitat horitzontal i vertical

---

## 🛠️ Herramientas y Tecnologías

### **Infraestructura Cloud**
| Tecnología | Versión | Uso en el Proyecto |
|-----------|--------|-------------------|
| **AWS EC2** | Ubuntu 24.04 | Servidores de aplicación |
| **AWS RDS** | MySQL 8.0 | Base de datos gestionada |
| **AWS Route 53** | - | Gestión DNS |
| **AWS CloudFront** | - | CDN y caché |
| **AWS Certificate Manager** | - | Certificados SSL/TLS |

### **Servidores Web**
| Herramienta | Versión | Funcionalidad |
|-------------|--------|-------------|
| **Nginx** | stable-alpine | Servidor web principal |
| **Apache** | 2.4 | Servidor legacy (backend) |
| **PHP-FPM** | 8.4 | Procesador PHP |
| **Node.js** | 18.x | Runtime para frontend |

### **Contenerización**
| Herramienta | Versión | Propósito |
|-------------|--------|----------|
| **Docker** | 24.x | Contenerización de aplicaciones |
| **Docker Compose** | 2.x | Orquestación de contenedores |
| **Multi-stage builds** | - | Optimización de imágenes |

### **CI/CD y Automatización**
| Tecnología | Versión | Uso |
|-------------|--------|-----|
| **GitHub Actions** | - | Pipelines de CI/CD |
| **SSH Keys** | - | Conexiones seguras |
| **Bash Scripts** | - | Automatización de despliegue |

### **Monitorización y Logging**
| Herramienta | Versión | Función |
|-------------|--------|---------|
| **AWS CloudWatch** | - | Monitorización y logging |
| **Nginx Access Logs** | - | Logs de acceso |
| **Application Logs** | Laravel | Logs de aplicación |
| **Health Checks** | - | Monitoreo de salud |

### **Seguridad**
| Tecnología | Versión | Propósito |
|-------------|--------|----------|
| **Let's Encrypt** | - | Certificados SSL gratuitos |
| **vsftpd** | 3.0+ | Servidor FTP seguro con SSL |
| **Apache/Nginx** | 2.4/1.18 | Servidores web seguros |
| **Systemd Services** | - | Gestión de servicios del sistema |

---

## 📋 Tareas Realizadas por Sprint

### **Sprint 1: Fundamentos de Despliegue**
- ✅ Configuración de entorno de servidor
- ✅ Instalación stack LAMP
- ✅ Configuración Apache y PHP
- ✅ Primer despliegue básico

### **Sprint 2: Servidor Web**
- ✅ Configuración avanzada Apache/Nginx
- ✅ Implementación de HTTPS
- ✅ Virtual hosts configurados
- ✅ Gestión de dominios y DNS

### **Sprint 3: Base de Datos y Seguridad**
- ✅ Instalación y configuración MySQL
- ✅ Configuración vsftpd con SSL
- ✅ Gestión de usuarios y permisos
- ✅ Scripts de backup automáticos

### **Sprint 4: Producción**
- ✅ Configuración servidor AWS
- ✅ Despliegue en producción
- ✅ Monitorización básica
- ✅ Logging implementado

### **Sprint 5-6: CI/CD y Cloud**
- ✅ Dockerización completa
- ✅ Pipeline CI/CD automatizado
- ✅ Despliegue automático
- ✅ Configuración SSL con Let's Encrypt
- ✅ Monitorización y logging básicos

---

## 🏗️ Arquitectura de Despliegue

### **Infraestructura AWS**
```yaml
# AWS Infrastructure Configuration
Infrastructure:
  VPC:
    CIDR: 10.0.0.0/16
    Subnets:
      Public:
        - Subnet-Public-1A: 10.0.1.0/24
        - Subnet-Public-1B: 10.0.2.0/24
      Private:
        - Subnet-Private-1A: 10.0.11.0/24
        - Subnet-Private-1B: 10.0.12.0/24
  
  EC2:
    Instance: t3.medium
    AMI: ubuntu-24.04
    Storage: 50GB SSD
    Security Groups:
      - WebSG: HTTP/HTTPS from 0.0.0.0/0
      - SSHSG: SSH from admin IP
  
  RDS:
    Engine: MySQL 8.0
    Instance: db.t3.micro
    Storage: 20GB
    Multi-AZ: false
  
  Route53:
    Domain: proyecto03.ddaw.es
    Records:
      A: proyecto03.ddaw.es → EC2 IP
      A: api.proyecto03.ddaw.es → EC2 IP
```

### **Configuración Nginx**
```nginx
# /deploy/config/vhost_app.conf
<VirtualHost *:80>
    ServerName app.alberoperez.tech
    ServerAdmin webmaster@localhost
    DocumentRoot /home/app/ftp/www
    
    # Redirección HTTP -> HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    ErrorLog /home/app/logs/error.log
    CustomLog /home/app/logs/access.log combined
</VirtualHost>
        proxy_set_header X-Forwarded-Proto $scheme;
    }
    
    # Static Assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}

# API Subdomain
server {
    listen 443 ssl http2;
    server_name api.proyecto03.ddaw.es;
    
    ssl_certificate /etc/letsencrypt/live/api.proyecto03.ddaw.es/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.proyecto03.ddaw.es/privkey.pem;
    
    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### **Docker Compose Producción**
```yaml
# docker-compose.prod.yml
version: '3.8'

services:
  frontend:
    build:
      context: ./frontend
      dockerfile: Dockerfile
      target: production
    container_name: pi_prod_frontend
    restart: unless-stopped
    environment:
      - VITE_API_URL=${VITE_API_URL}
      - VITE_N8N_WEBHOOK_URL=${VITE_N8N_WEBHOOK_URL}
    networks:
      - app-network

  laravel-app:
    build:
      context: ./laravel
      dockerfile: Dockerfile
    container_name: pi_prod_laravel_app
    restart: unless-stopped
    environment:
      - DB_HOST=mysql
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
    networks:
      - app-network

  mysql:
    image: mysql:8.0
    container_name: pi_prod_mysql
    restart: unless-stopped
    environment:
      - MYSQL_ROOT_PASSWORD=${DB_PASSWORD}
      - MYSQL_DATABASE=${DB_DATABASE}
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - app-network

volumes:
  mysql_data:
    depends_on:
      - mysql
    networks:
      - app-network

  mysql:
    image: mysql:8.0
    container_name: pi_prod_mysql
    restart: unless-stopped
    environment:
      - MYSQL_DATABASE=${DB_DATABASE}
      - MYSQL_USER=${DB_USERNAME}
      - MYSQL_PASSWORD=${DB_PASSWORD}
      - MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - app-network

  nginx:
    image: nginx:stable-alpine
    container_name: pi_prod_nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./deploy/nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./deploy/nginx/sites-available:/etc/nginx/sites-available
      - /etc/letsencrypt:/etc/letsencrypt:ro
    depends_on:
      - frontend
      - laravel-app
    networks:
      - app-network

networks:
  app-network:
    driver: bridge

volumes:
  mysql_data:
```

---

## 🔄 CI/CD Implementation

### **GitHub Actions - Frontend Pipeline**
```yaml
# .github/workflows/deploy-frontend.yml
name: Frontend CI/CD

on:
  push:
    branches: [ main ]
    paths:
      - 'frontend/**'
      - '.github/workflows/deploy-frontend.yml'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to EC2
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.EC2_HOST }}
          username: ${{ secrets.EC2_USER }}
          key: ${{ secrets.EC2_SSH_KEY }}
          script: |
            cd /home/ubuntu/PI
            git pull origin main
            export VITE_API_URL=${{ secrets.VITE_API_URL }}
            docker compose -f docker-compose.prod.yml up -d --build
            docker compose -f docker-compose.prod.yml up -d --build --force-recreate frontend
```

### **GitHub Actions - Backend Pipeline**
```yaml
# .github/workflows/deploy-backend.yml
name: Backend CI/CD

on:
  push:
    branches: [ main ]
    paths:
      - 'laravel/**'
      - '.github/workflows/deploy-backend.yml'

jobs:
  test:
    runs-on: ubuntu-latest
    defaults:
      run:
        working-directory: ./laravel
    steps:
      - uses: actions/checkout@v4
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql
      
      - name: Install dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
      
      - name: Prepare environment
        run: cp .env.example .env && php artisan key:generate
      
      - name: Execute tests
        run: php artisan test

  deploy:
    needs: test
    if: success()
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to EC2
        uses: appleboy/ssh-action@v1.0.3
        env:
          DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
        with:
          host: ${{ secrets.EC2_HOST }}
          username: ${{ secrets.EC2_USER }}
          key: ${{ secrets.EC2_SSH_KEY }}
          script: |
            cd /home/ubuntu/PI
            git pull origin main
            export DB_PASSWORD=${{ secrets.DB_PASSWORD }}
            docker compose -f docker-compose.prod.yml up -d --build --force-recreate laravel-app
            docker compose exec laravel-app php artisan migrate --force
```

---

## 🔗 Conexiones con Otros Módulos

### **Con NUV (Cloud)**
- Scripts de despliegue automatizados
- Configuración de producción en AWS
- CI/CD integrado con GitHub Actions

### **Con DWES (Backend)**
- Base de datos MySQL en contenedor
- Variables de entorno configuradas
- Logs de aplicación centralizados

### **Con DWEC (Frontend)**
- Build optimizado para producción
- Servidor Nginx configurado
- Despliegue automático

### **Con SOST (Sostenibilidad)**
- Servidor optimizado para rendimiento
- Logs de acceso y errores
- Configuración eficiente de recursos

---


## 🎯 Conclusión del Módulo

El módulo DDAW ha sido implementado con una infraestructura real y funcional. Utilizando scripts bash para la configuración, Docker Compose para la orquestación, y GitHub Actions para CI/CD, hemos creado un despliegue eficiente y mantenible. La implementación se centra en tecnologías realmente utilizadas, evitando complejidades innecesarias y manteniendo un enfoque práctico y verificable.