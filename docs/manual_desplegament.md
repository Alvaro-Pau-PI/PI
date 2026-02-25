# 📋 Manual de Despliegue: Paso a PasoEste guía detalla el proceso completo para desarrollar la aplicación "AlberoPerezTech" desde cero en un entorno de producción, utilizando AWS y Docker.## 📋 Requisitos Previos (Antes de empezar)### 1. Cuenta AWS y Servicios Configurados- **VPC** creada con subredas públicas y privadas (ver `arquitectura_aws.md`).- **Instancia EC2** (`Ubuntu 24.04`) en subred pública, con IP Elástica asignada.- **Puerto 22 (SSH)** abierto al IP del** abiertos a todo el mundo (`0.0.0.0/0`).- **Número de dominio** (`AlberoPerezTech.ddaw.es`) apuntando a la IP Elástica (Route 53 o DNS externo).### 2. Base de Datos (RDS)- Instancia MySQL creada en subredas privadas.- Security Group configurado para aceptar conexiones desde la EC2 (Port 3306).- Anotar `Endpoint`, `Usuario`, `Contraseña` y `Número de BD`.---

## 🚀 Paso 1: Configuración Inicial del Servidor (EC2)Connectate a la máquina vía SSH:```bash
ssh -i clau.pem ubuntu@<IP-EC2>
```

### 1.1. Actualizar e Instalar Docker```bash
sudo apt update && sudo apt upgrade -y# Instalar Dockercurl -fsSL https://get.docker.com -o get-docker.shsudo sh get-docker.sh# Añadir usuario ubuntu al grupo dockersudo usermod -aG docker ubuntunewgrp docker
### 1.2. Clonar el Repositorio```bash
cd /hombre/ubuntugit clone <URL_REPO> PI
cd PI```
---

## 🔧 Paso 2: Configuración del EntornoCrea el archivo `.env` de producción con las credenciales reales:```bash
cp .env.example .envnano .env```
Llena las variables críticas:- `App URL`: `https://AlberoPerezTech.ddaw.es`- `DB_HOST`: Endpoint de la RDS (ej: `mydb.xxxx.eu-west-1.rds.amazonaws.com`)- `DB_PASSWORD`: La contraseña de la RDS.- ` `https://api.AlberoPerezTech.ddaw.es`---

## 🌐 Paso 3: Configuración de Nginx Host (SSL)Este paso configura el proxy inverso y obtiene los certificados HTTPS.```bash
sudo ./deploy/nginx/setup_prod.sh```
> Este script instal·larà `certbot`, configurarà `/etc/nginx/sites-available/` i sol·licitarà certificats a Let's Encrypt per als teus dominis.

---

## 🤖 Paso 4: Despliegue Automático (GitHub CI/CD)A partir de ahora, no es necesario entrar en el servidor. GitHub Actions lo hará todo.### 4.1. Configurar Secretos en GitHubViene a `Settings > Secrets and variables > Actions` del repositorio y añade:- `EC2_HOST`: IP Elástica.- `EC2_USER`: `ubuntu`.- `EC2_SSH_KEY`: Contenido del archivo `.pem`.- `VITE_API_URL`: `https://api.AlberoPerezTech.ddaw.es`.- `DB_PASSWORD`.### 4.2. Primero DespliegueHaz un `git push` en la rama `main`. GitHub:1. Detectará el cambio.2. Ejecutará los workflows `deploy-frontend.yml` y `deploy-backend.yml`.3. Conectará a la EC2.4. Construirá las imágenes Docker `pi_prod_frontend` y `pi_prod_laravel_app`.5. Ejecutará las migraciones de BD.---

## 🧪 Paso 5: Validación y Pruebas1. **Frontend**: Abre `https://AlberoPerezTech.ddaw.es`. Debe cargar sin errores SSL.2. **API**: Abre `https://api.AlberoPerezTech.ddaw.es/api/products`. Debe devolver un JSON.3. **Usuario de Prueba**:- Email: `admin@example.com`- Password: `password` (o el definido en los seeders).Si todo funciona, ¡el sistema está en producción! 🎉