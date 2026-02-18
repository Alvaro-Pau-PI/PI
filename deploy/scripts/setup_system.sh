#!/bin/bash

# Configuration
DOMAIN_APP="app.projecteGrupX.es" # CHANGE THIS
DOMAIN_BACKUP="backup.projecteGrupX.es" # CHANGE THIS
DOMAIN_TEST="test.projecteGrupX.es" # CHANGE THIS
MEMBERS_NAMES="NOM COGNOM" # CHANGE THIS
DB_USER="root" # CHANGE THIS IF NEEDED
DB_PASS="root" # CHANGE THIS IF NEEDED
DB_NAME="projecte" # CHANGE THIS IF NEEDED

# List of student usernames for test environments
STUDENTS=("alumne1" "alumne2" "alumne3")

echo "Iniciando configuración del servidor..."

# 1. Actualizar sistema e instalar paquetes
apt-get update && apt-get upgrade -y
apt-get install -y apache2 vsftpd certbot python3-certbot-apache apache2-utils mysql-client

# 2. Configurar Usuarios y Directorios (RA4-b)
# Crear usuarios app y backup si no existen
id -u app &>/dev/null || useradd -m -d /home/app -s /bin/bash app
id -u backup &>/dev/null || useradd -m -d /home/backup -s /bin/bash backup

# Estructura de directorios (Requirements 3.2, 3.3)
mkdir -p /home/app/ftp/www /home/app/logs
mkdir -p /home/backup/ftp/fitxers

# Permisos
chown -R app:app /home/app/ftp/www
chmod 755 /home/app/ftp
chmod 755 /home/app

chown -R backup:backup /home/backup/ftp/fitxers
chmod 755 /home/backup/ftp
chmod 755 /home/backup

# Contraseñas
echo "Establece contraseña para usuario 'app':"
passwd app
echo "Establece contraseña para usuario 'backup':"
passwd backup

# 3. Configurar SSH (RA3-1)
sed -i 's/^PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/^PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config

# Añadir claves públicas autorizadas (Placeholder)
echo "Paste public keys for members here (manual step or via script arguments)"
# Example: echo "ssh-rsa AAAA..." >> /home/ubuntu/.ssh/authorized_keys

# Asegurar permisos correctos de SSH
mkdir -p /home/ubuntu/.ssh
chmod 700 /home/ubuntu/.ssh
touch /home/ubuntu/.ssh/authorized_keys
chmod 600 /home/ubuntu/.ssh/authorized_keys
chown -R ubuntu:ubuntu /home/ubuntu/.ssh

systemctl restart ssh

# Mensaje de bienvenida (MOTD)
echo "Benvingut a la instància de Servidor Web en AWS de $MEMBERS_NAMES" > /etc/motd

# 4. Configurar Apache (RA2-e, f, h)
a2enmod ssl rewrite headers auth_digest userdir

# 5. Configurar FTP (vsftpd) (RA4-e, g)
cp /etc/vsftpd.conf /etc/vsftpd.conf.bak
# Configuration file should be copied manually or via deployment pipeline

# Crear lista de usuarios permitidos vsftpd
echo "app" | tee /etc/vsftpd.userlist
echo "backup" | tee -a /etc/vsftpd.userlist

# Bloquear acceso FTP a ubuntu
echo "ubuntu" | tee -a /etc/ftpusers

# 6. Entorno de Pruebas (RA3-8) - Multi-usuario
for student in "${STUDENTS[@]}"; do
    if ! id -u "$student" &>/dev/null; then
        useradd -m -d "/home/$student" -s /bin/bash "$student"
        echo "Usuario $student creado."
    fi
    
    # Crear directorios FTP/Web para cada alumno
    mkdir -p "/home/$student/ftp/www"
    chown -R "$student:$student" "/home/$student/ftp/www"
    chmod 755 "/home/$student/ftp"
    chmod 755 "/home/$student"
    
    # Añadir a vsftpd userlist
    echo "$student" | tee -a /etc/vsftpd.userlist
    
    echo "Establece contraseña para usuario '$student':"
    passwd "$student"
done

# Reiniciar servicios
systemctl restart vsftpd
systemctl reload apache2

echo "Configuración base completada."
echo "IMPORTANTE: Edita /home/ubuntu/.ssh/authorized_keys para añadir claves de acceso de los miembros."
