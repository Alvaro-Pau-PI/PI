# 📋 Manual de Despliegue: Paso a Paso

Esta guía detalla el proceso completo para desplegar la aplicación "AlberoPerezTech" desde cero en un entorno de producción, utilizando AWS y Docker.

## 📋 Requisitos Previos (Antes de empezar)

### 1. Cuenta AWS y Servicios Configurados
- **VPC** creada con subredes públicas y privadas (ver `arquitectura_aws.md`).
- **Instancia EC2** (`Ubuntu 24.04`) en subred pública, con IP Elástica asignada.
- **Puerto 22 (SSH)** abierto a la IP del administrador.
- **Puertos 80 y 443** abiertos a todo el mundo (`0.0.0.0/0`).
- **Nombre de dominio** (`proyecto03.ddaw.es`) apuntando a la IP Elástica (Route 53 o DNS externo).

### 2. Base de Datos (RDS)
- Instancia MySQL creada en subredes privadas.
- Security Group configurado para aceptar conexiones desde la EC2 (Puerto 3306).
- Anotar `Endpoint`, `Usuario`, `Contraseña` y `Nombre de BD`.

---

## 🚀 Paso 1: Configuración Inicial del Servidor (EC2)

Conéctate a la máquina vía SSH:

```bash
ssh -i clau.pem ubuntu@<IP-EC2>
```

### 1.1. Actualizar e Instalar Docker

```bash
sudo apt update && sudo apt upgrade -y
# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
# Añadir usuario ubuntu al grupo docker
sudo usermod -aG docker ubuntu
newgrp docker
```

### 1.2. Clonar el Repositorio

```bash
cd /home/ubuntu
git clone <URL_REPO> PI
cd PI
```

---

## 🔧 Paso 2: Configuración del Entorno

Crea el archivo `.env` de producción con las credenciales reales:

```bash
cp .env.example .env
nano .env
```

Rellena las variables críticas:
- `APP_URL`: `https://proyecto03.ddaw.es`
- `DB_HOST`: Endpoint de la RDS (ej: `mydb.xxxx.eu-west-1.rds.amazonaws.com`)
- `DB_PASSWORD`: La contraseña de la RDS.
- `VITE_API_URL`: `https://api.proyecto03.ddaw.es`

---

## 🌐 Paso 3: Configuración de Nginx Host (SSL)

Este paso configura el proxy inverso y obtiene los certificados HTTPS.

```bash
sudo ./deploy/nginx/setup_prod.sh
```

> Este script instalará `certbot`, configurará `/etc/nginx/sites-available/` y solicitará certificados a Let's Encrypt para tus dominios.

---

## 🤖 Paso 4: Despliegue Automático (GitHub CI/CD)

A partir de ahora, no hace falta entrar al servidor. GitHub Actions lo hará todo.

### 4.1. Configurar Secrets en GitHub

Ve a `Settings > Secrets and variables > Actions` del repositorio y añade:

- `EC2_HOST`: IP Elástica.
- `EC2_USER`: `ubuntu`.
- `EC2_SSH_KEY`: Contenido del archivo `.pem`.
- `VITE_API_URL`: `https://api.proyecto03.ddaw.es`.
- `DB_PASSWORD`: Contraseña de la RDS.

### 4.2. Primer Despliegue

Haz un `git push` a la rama `main`. GitHub:
1. Detectará el cambio.
2. Ejecutará los workflows `deploy-frontend.yml` y `deploy-backend.yml`.
3. Conectará a la EC2.
4. Construirá las imágenes Docker `pi_prod_frontend` y `pi_prod_laravel_app`.
5. Ejecutará las migraciones de BD.

---

## 🧪 Paso 5: Validación y Pruebas

1. **Frontend**: Abre `http://18.206.113.196` (o `https://proyecto03.ddaw.es` si el DNS ya está delegado). Debe cargar sin errores SSL.
2. **API**: Abre `http://18.206.113.196:8000/api/products` (o `https://api.proyecto03.ddaw.es/api/products` si el DNS ya está delegado). Debe devolver un JSON.
3. **Usuario de Prueba**:
   - Email: `admin@example.com`
   - Password: `password` (o el definido en los seeders).

Si todo funciona, ¡el sistema está en producción! 🎉
