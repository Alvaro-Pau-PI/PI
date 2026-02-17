#!/bin/bash
# Requisitos: 3.5 Backups nocturns

# Configuración
SOURCE_DIR="/home/app/ftp/www"
DEST_DIR="/home/backup/ftp/fitxers"
DATE=$(date +%Y%m%d)
BACKUP_FILE="app_backup_$DATE.tar.gz"
DB_BACKUP_FILE="db_backup_$DATE.sql"
DB_USER="root"
DB_PASS="root" # Cambiar por contraseña real o usar .my.cnf
DB_NAME="projecte" # Nombre de la base de datos

# 1. Backup Base de Datos (RA2-j)
# Se asume que 'mysqldump' está instalado y hay una DB
if command -v mysqldump &> /dev/null; then
    mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$SOURCE_DIR/$DB_BACKUP_FILE" 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "Database backup successful: $DB_BACKUP_FILE"
    else
        echo "Database backup failed or DB not accessible via script credentials."
    fi
else
    echo "mysqldump not found, skipping DB backup."
fi

# 2. Crear backup comprimido (Archivos + DB SQL si existe)
tar -czf "$DEST_DIR/$BACKUP_FILE" -C "$SOURCE_DIR" .

# Limpiar archivo SQL temporal del source
rm -f "$SOURCE_DIR/$DB_BACKUP_FILE"

# 3. Rotación de logs: mantener solo los últimos 7 días
find "$DEST_DIR" -name "app_backup_*.tar.gz" -mtime +7 -exec rm {} \;

# Mensaje de log
logger "Backup $BACKUP_FILE creado y antiguos eliminados."
