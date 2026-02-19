#!/bin/bash

# Script de configuració automàtica de Nginx + SSL (Certbot) al Host (EC2)
# Executa aquest script com a SUDO al servidor.

DOMAIN_FRONT="AlberoPerezTech.ddaw.es"
DOMAIN_API="api.AlberoPerezTech.ddaw.es"
EMAIL="admin@AlberoPerezTech.ddaw.es"

# Ports interns dels contenidors Docker
PORT_FRONT=8001
PORT_API=8002

echo "--- 🧠 Configurant Memoria Virtual (Swap) ---"
if [ ! -f /swapfile ]; then
    fallocate -l 2G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo "Swap de 2GB creat i activat."
else
    echo "Swap ja existent."
fi

echo "--- 🌐 Configurant Nginx per a $DOMAIN_FRONT i $DOMAIN_API ---"

# Parar Apache per evitar conflictes
systemctl stop apache2 || true
systemctl disable apache2 || true

# Instal·lar Docker i Certbot amb el plugin necessari
apt-get update
apt-get install -y docker.io docker-compose-v2 certbot python3-certbot-nginx

# Ensure services are enabled
systemctl enable docker
systemctl start docker
systemctl enable nginx

# Crear configuració Nginx per al Frontend
cat > /etc/nginx/sites-available/$DOMAIN_FRONT <<EOF
server {
    server_name $DOMAIN_FRONT www.$DOMAIN_FRONT;

    # Backend API (ruta relativa per a suportar IP i domini)
    location /api {
        proxy_pass http://127.0.0.1:$PORT_API;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }

    location / {
        proxy_pass http://127.0.0.1:$PORT_FRONT;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }
}
EOF

# Crear configuració Nginx per al Backend
# Eliminated redundant backend server block since it is now a location block in the main server
# cat > /etc/nginx/sites-available/$DOMAIN_API <<EOF
# server {
#     server_name $DOMAIN_API;
# 
#     location / {
#         proxy_pass http://127.0.0.1:$PORT_API;
#         proxy_set_header Host \$host;
#         proxy_set_header X-Real-IP \$remote_addr;
#         proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
#         proxy_set_header X-Forwarded-Proto \$scheme;
#     }
# }
# EOF

# Habilitar llocs
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/$DOMAIN_FRONT /etc/nginx/sites-enabled/
# ln -sf /etc/nginx/sites-available/$DOMAIN_API /etc/nginx/sites-enabled/

# Verificar i reiniciar Nginx
nginx -t && systemctl restart nginx

echo "--- 🔒 Generant certificats SSL amb Let's Encrypt ---"

# Sol·licitar certificats (no interactiu)
certbot --nginx -d $DOMAIN_FRONT -d www.$DOMAIN_FRONT --non-interactive --agree-tos -m $EMAIL --redirect
# certbot --nginx -d $DOMAIN_API --non-interactive --agree-tos -m $EMAIL --redirect

echo "--- ✅ Configuració completada! ---"
echo "Frontend: https://$DOMAIN_FRONT -> Docker :$PORT_FRONT"
echo "Backend:  https://$DOMAIN_API -> Docker :$PORT_API"
