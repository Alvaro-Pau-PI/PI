#!/bin/bash
# Instalar Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
# Instalar json-server globalmente
sudo npm install -g json-server

# Preparar directorio de datos
sudo mkdir -p /home/app/ftp/data

# Copiar db.json desde el deploy (ruta absoluta)
if [ -f "/home/ubuntu/deploy/data/db.json" ]; then
    sudo cp /home/ubuntu/deploy/data/db.json /home/app/ftp/data/db.json
else
    echo "⚠️ db.json no encontrado en /home/ubuntu/deploy/data/"
fi

# Permisos
sudo chown -R app:app /home/app/ftp/data

# Configurar servicio systemd (ruta absoluta)
if [ -f "/home/ubuntu/deploy/config/jsonserver.service" ]; then
    sudo cp /home/ubuntu/deploy/config/jsonserver.service /etc/systemd/system/
    sudo systemctl daemon-reload
    sudo systemctl enable jsonserver
    sudo systemctl restart jsonserver
    echo "✅ Backend (JSON Server) instalado y arrancado en puerto 3000"
else
    echo "❌ Error: No se encuentra jsonserver.service en /home/ubuntu/deploy/config/"
fi
