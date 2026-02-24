# 📋 Manual de Desplegament: Pas a Pas

Aquesta guia detalla el procés complet per desplegar l'aplicació "AlberoPerezTech" des de zero en un entorn de producció, utilitzant AWS i Docker.

## 📋 Requisits Previs (Abans de començar)

### 1. Compte AWS i Serveis Configurats
- **VPC** creada amb subxarxes públiques i privades (veure `arquitectura_aws.md`).
- **Instància EC2** (`Ubuntu 24.04`) en subxarxa pública, amb IP Elàstica assignada.
- **Port 22 (SSH)** obert al IP de l'administrador.
- **Ports 80 i 443** oberts a tothom (`0.0.0.0/0`).
- **Nom de domini** (`AlberoPerezTech.ddaw.es`) apuntant a la IP Elàstica (Route 53 o DNS extern).

### 2. Base de Dades (RDS)
- Instància MySQL creada en subxarxes privades.
- Security Group configurat per acceptar connexions des de la EC2 (Port 3306).
- Anotar `Endpoint`, `Usuari`, `Contrasenya` i `Nom de BD`.

---

## 🚀 Pas 1: Configuració Inicial del Servidor (EC2)

Connecta't a la màquina via SSH:

```bash
ssh -i clau.pem ubuntu@<IP-EC2>
```

### 1.1. Actualitzar i Instal·lar Docker

```bash
sudo apt update && sudo apt upgrade -y
# Instal·lar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
# Afegir usuari ubuntu al grup docker
sudo usermod -aG docker ubuntu
newgrp docker
```

### 1.2. Clonar el Repositori

```bash
cd /home/ubuntu
git clone <URL_REPO> PI
cd PI
```

---

## 🔧 Pas 2: Configuració de l'Entorn

Crea el fitxer `.env` de producció amb les credencials reals:

```bash
cp .env.example .env
nano .env
```

Omple les variables crítiques:
- `App URL`: `https://AlberoPerezTech.ddaw.es`
- `DB_HOST`: Endpoint de la RDS (ex: `mydb.xxxx.eu-west-1.rds.amazonaws.com`)
- `DB_PASSWORD`: La contrasenya de la RDS.
- `VITE_API_URL`: `https://api.AlberoPerezTech.ddaw.es`

---

## 🌐 Pas 3: Configuració de Nginx Host (SSL)

Aquest pas configura el proxy invers i obté els certificats HTTPS.

```bash
sudo ./deploy/nginx/setup_prod.sh
```

> Aquest script instal·larà `certbot`, configurarà `/etc/nginx/sites-available/` i sol·licitarà certificats a Let's Encrypt per als teus dominis.

---

## 🤖 Pas 4: Desplegament Automàtic (GitHub CI/CD)

A partir d'ara, no cal entrar al servidor. GitHub Actions ho farà tot.

### 4.1. Configurar Secrets a GitHub

Vés a `Settings > Secrets and variables > Actions` del repositori i afegeix:

- `EC2_HOST`: IP Elàstica.
- `EC2_USER`: `ubuntu`.
- `EC2_SSH_KEY`: Contingut del fitxer `.pem`.
- `VITE_API_URL`: `https://api.AlberoPerezTech.ddaw.es`.
- `DB_PASSWORD`: Contrasenya de la RDS.

### 4.2. Primer Desplegament

Fes un `git push` a la branca `main`. GitHub:
1. Detectarà el canvi.
2. Executarà els workflows `deploy-frontend.yml` i `deploy-backend.yml`.
3. Connectarà a la EC2.
4. Construirà les imatges Docker `pi_prod_frontend` i `pi_prod_laravel_app`.
5. Executarà les migracions de BD.

---

## 🧪 Pas 5: Validació i Proves

1. **Frontend**: Obre `https://AlberoPerezTech.ddaw.es`. Ha de carregar sense errors SSL.
2. **API**: Obre `https://api.AlberoPerezTech.ddaw.es/api/products`. Ha de tornar un JSON.
3. **Usuari de Prova**:
   - Email: `admin@example.com`
   - Password: `password` (o el definit als seeders).

Si tot funciona, el sistema està en producció! 🎉
