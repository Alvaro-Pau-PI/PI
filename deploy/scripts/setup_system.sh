#!/bin/bash


DOMAIN_MAIN="alberoperez.tech" 

DOMAIN_APP="app.$DOMAIN_MAIN"
DOMAIN_BACKUP="backup.$DOMAIN_MAIN"
DOMAIN_TEST="test.$DOMAIN_MAIN"

MEMBERS_NAMES="Pau Albero Mora i Alvaro Perez Morilla"

# Configuración BD (Se hará más adelante, dejamos valores por defecto)
DB_USER="root"
DB_PASS="" # Sin contraseña por defecto en instalación fresca
DB_NAME="projecte"

# Usuarios de los alumnos
STUDENTS=("pau" "alvaro")

echo "--- Iniciando despliegue para $MEMBERS_NAMES ---"

# 1. Actualizar sistema e instalar paquetes (Incluido mysql-server para el futuro)
export DEBIAN_FRONTEND=noninteractive
apt-get update && apt-get upgrade -y
apt-get install -y apache2 vsftpd certbot python3-certbot-apache apache2-utils mysql-server mysql-client libapache2-mod-php php-mysql

# 2. Configurar Usuarios de Sistema y Directorios (RA4-b)
id -u app &>/dev/null || useradd -m -d /home/app -s /bin/bash app
id -u backup &>/dev/null || useradd -m -d /home/backup -s /bin/bash backup

# Estructura de directorios
mkdir -p /home/app/ftp/www /home/app/logs
mkdir -p /home/backup/ftp/fitxers

# Permisos Base
chown -R app:app /home/app/ftp/www
chmod 755 /home/app/ftp
chmod 755 /home/app

chown -R backup:backup /home/backup/ftp/fitxers
chmod 755 /home/backup/ftp
chmod 755 /home/backup

# Contraseñas (Se pedirán al ejecutar)
echo "-------------------------------------------------"
echo "⚠️  Pon contraseña para el usuario 'app':"
passwd app
echo "⚠️  Pon contraseña para el usuario 'backup':"
passwd backup
echo "-------------------------------------------------"

# 3. Configurar SSH (RA3-1)
# Bloqueamos root y password, pero dejamos preparado el fichero authorized_keys
sed -i 's/^PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/^PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config

mkdir -p /home/ubuntu/.ssh
touch /home/ubuntu/.ssh/authorized_keys
chmod 700 /home/ubuntu/.ssh
chmod 600 /home/ubuntu/.ssh/authorized_keys
chown -R ubuntu:ubuntu /home/ubuntu/.ssh

systemctl restart ssh

# Mensaje de bienvenida (MOTD)
echo "Benvingut a la instància AWS de AlberoPerez Tech ($MEMBERS_NAMES)" > /etc/motd

# 4. Configurar Apache (RA2-e, f, h)
a2enmod ssl rewrite headers auth_digest userdir

# --- FIX CRÍTICO: Habilitar PHP en UserDir (carpetas de Pau y Alvaro) ---
# Esto permite que https://test.../~pau/index.php se ejecute y no se descargue
if ls /etc/apache2/mods-available/php*.conf 1> /dev/null 2>&1; then
    sed -i 's/php_admin_flag engine Off/#php_admin_flag engine Off/' /etc/apache2/mods-available/php*.conf
    echo "✅ PHP habilitado en directorios de usuario."
else
    echo "⚠️  No se encontró configuración de PHP para Apache. ¿Está instalado libapache2-mod-php?"
fi

# 5. Configurar FTP (vsftpd) (RA4-e, g)
cp /etc/vsftpd.conf /etc/vsftpd.conf.bak

# Crear lista de usuarios permitidos
echo "app" | tee /etc/vsftpd.userlist
echo "backup" | tee -a /etc/vsftpd.userlist

# Bloquear acceso FTP a ubuntu
echo "ubuntu" | tee -a /etc/ftpusers

# 6. Entorno de Pruebas: Pau y Alvaro (RA3-8)
for student in "${STUDENTS[@]}"; do
    if ! id -u "$student" &>/dev/null; then
        useradd -m -d "/home/$student" -s /bin/bash "$student"
        echo "✅ Usuario $student creado."
    fi
    
    # Crear directorios FTP/Web para cada alumno
    mkdir -p "/home/$student/ftp/www"
    
    # Permisos para que Apache pueda entrar (UserDir)
    chown -R "$student:$student" "/home/$student/ftp/www"
    chmod 755 "/home/$student/ftp"
    chmod 755 "/home/$student" # Importante para UserDir
    
    # Añadir a vsftpd userlist
    echo "$student" | tee -a /etc/vsftpd.userlist
    
    echo "-------------------------------------------------"
    echo "⚠️  Pon contraseña para el alumno '$student':"
    passwd "$student"
    echo "-------------------------------------------------"
done

# Reiniciar servicios
systemctl restart vsftpd
systemctl reload apache2

echo "✅ INSTALACIÓN COMPLETADA."
echo "👉 Siguiente paso: Copia los archivos .conf a /etc/apache2/sites-available/"
echo "👉 Después ejecuta: sudo certbot --apache"