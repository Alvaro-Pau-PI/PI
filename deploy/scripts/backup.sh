#!/bin/bash
# Requisitos: 3.5 Backups nocturns

# Configuración
SOURCE_DIR="/home/app/ftp/www"
DEST_DIR="/home/backup/ftp/fitxers"
DATE=$(date +%Y%m%d)
BACKUP_FILE="app_backup_$DATE.tar.gz"
DB_BACKUP_FILE="db_backup_$DATE.sql"

# Credenciales BD (Dejar así hasta que configuréis la BD real)
DB_USER="root"
DB_PASS="" 
DB_NAME="projecte"

# 1. Backup Base de Datos (Si existe)
if command -v mysqldump &> /dev/null; then
    # Intentamos conectar. Si falla (porque no está creada), no rompemos el script
    mysqldump -u "$DB_USER" "$DB_NAME" > "$SOURCE_DIR/$DB_BACKUP_FILE" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo "✅ Backup SQL realizado: $DB_BACKUP_FILE"
    else
        echo "⚠️  Aviso: No se pudo hacer backup SQL (¿La BD '$DB_NAME' existe?). Continuando solo con ficheros..."
        # Borramos el archivo vacío si se creó
        rm -f "$SOURCE_DIR/$DB_BACKUP_FILE"
    fi
else
    echo "⚠️  mysqldump no instalado."
fi

# 2. Crear backup comprimido (Archivos Web + SQL si existe)
tar -czf "$DEST_DIR/$BACKUP_FILE" -C "$SOURCE_DIR" .

# Limpiar archivo SQL temporal del source (si existe)
rm -f "$SOURCE_DIR/$DB_BACKUP_FILE"

# 3. Rotación: Borrar backups de más de 7 días
find "$DEST_DIR" -name "app_backup_*.tar.gz" -mtime +7 -exec rm {} \;

# Mensaje de log
logger "AlberoPerez Tech: Backup $BACKUP_FILE completado."