# 🚀 Guía Paso a Paso: Desplegament en AWS Academy (Sprint 2 - C4)

Sigue estos pasos AL PIE DE LA LETRA para desplegar tu proyecto y aprobar todos los requisitos (RA) sin perder datos.

---

## ⚠️ AVISOS CRÍTICOS (AWS Academy)
1.  **NO uses el botón "Reset Lab"**: Eso borra TODO. Si se acaba el tiempo, la máquina se apaga (Stop), pero tus datos se guardan en el disco.
2.  **USA ELASTIC IP**: Es **OBLIGATORIO**. Si no la usas, cada vez que enciendas el laboratorio la IP cambiará y tu dominio dejará de funcionar.
    *   **TU IP es**: `18.206.113.196`
3.  **Seguridad**: No subas tu clave `.pem` al repositorio público.

---

## FASE 1: Configuración en AWS Console (Navegador)

### 1. Iniciar la Instancia EC2
1.  Entra en **AWS Academy Learner Lab** -> **Start Lab**. Espera a que el punto esté verde.
2.  Clic en **AWS** (punto verde) para abrir la consola.
3.  Ve a **EC2** -> **Launch Instance**.
    *   **Name**: `Servidor-Web-GrupX`
    *   **OS Images**: `Ubuntu Server 22.04 LTS (HVM)`.
    *   **Instance Type**: `t2.micro` (o `t2.small` si te dejan).
    *   **Key Pair**: Selecciona `vockey` (la clave automática de Academy). Descarga el archivo `LabsUser.pem` si no lo tienes, o una nueva si creas otra.

### 2. Configurar el Firewall (Security Group)
En "Network settings", crea un **Security Group** nuevo y añade estas reglas ("Inbound rulas"):

| Type | Protocol | Port Range | Source | Descripciónnn |
| :--- | :--- | :--- | :--- | :--- |
| SSH | TCP | 22 | 0.0.0.0/0 | Acceso terminal |
| HTTP | TCP | 80 | 0.0.0.0/0 | Web normal |
| HTTPS | TCP | 443 | 0.0.0.0/0 | Web segura |
| Custom TCP | TCP | 21 | 0.0.0.0/0 | FTP Control |
| Custom TCP | TCP | 30000-30050 | 0.0.0.0/0 | FTP Passiu (Datos) |

### 3. Asignar IP Fija (Elastic IP) **¡MUY IMPORTANTE!**
1.  En el menú izquierdo EC2 -> **Network & Security** -> **Elastic IPs**.
2.  Clic en **Allocate Elastic IP address** -> Allocate.
3.  Selecciona la IP `18.206.113.196` -> **Actions** -> **Associate Elastic IP address**.
4.  En "Instance", selecciona tu servidor web creado.
5.  Clic en **Associate**.
    *   *Anota esta IP. Será la IP pública de tu web para siempre.*

---

## FASE 2: Preparación de Archivos (YA REALIZADO ✅)

Los archivos de configuración ya han sido personalizados con tus datos. **No necesitas editar nada**, solo verifica que sea correcto:

### 1. Verifica `deploy/scripts/setup_system.sh`
*   Dominio principal: `DOMAIN_APP="app.alberoperez.tech"`
*   Nombres: `MEMBERS_NAMES="Pau Albero Mora i Alvaro Perez Morilla"`
*   Alumnos: `STUDENTS=("pau" "alvaro")`
*   *Nota: La base de datos y las claves se configurarán más adelante.*

### 2. Verifica las configuraciones de Apache (`deploy/config/*.conf`)
*   Ya apuntan a tus dominios realas:
    *   `app.alberoperez.tech`
    *   `backup.alberoperez.tech`
    *   `test.alberoperez.tech`

---

## FASE 3: Subida y Ejecución (Terminal)

### 1. Conectar al Servidor
Abre tu terminal (en local) donde tengas la clave `.pem`.

```bash
# 1. Protege tu clave (si no lo has hecho)
chmod 400 LabsUser.pem

# 2. Sube la carpeta del proyecto
# Asegúrate de que la ruta local sea correcta.
# Si estás en '~/Documentos/Projecte intermodular 2/AWS', y el proyecto está en '../github/PI/deploy':
scp -i LabsUser.pem -r "../github/PI/deploy" ubuntu@18.206.113.196:~/deploy

# 3. Entra al servidor
ssh -i LabsUser.pem ubuntu@18.206.113.196
```

### 2. Ejecutar la Instalación Automática
Una vez dentro del servidor (`ubuntu@ip...`):

```bash
cd ~/deploy/scripts
chmod +x setup_system.sh
sudo ./setup_system.sh
```
*   El script instalará Apache, FTP, MySQL client y Certbot.
*   **Te pedirá contraseñas**: Escribe una contraseña segura cuando te pida para `app`, `backup` y los alumnos (`juan`, `maria`...).

---

## FASE 4: Activación de Servicios y HTTPS

### 1. Copiar Configuraciones de Apache
```bash
cd ~/deploy/config
sudo cp vhost_app.conf /etc/apache2/sites-available/app.conf
sudo cp vhost_backup.conf /etc/apache2/sites-available/backup.conf
sudo cp vhost_test.conf /etc/apache2/sites-available/test.conf

# Activamos los sitios y módulos necesarios
sudo a2ensite app.conf backup.conf test.conf
sudo a2enmod userdir ssl rewrite headers auth_digest
sudo systemctl reload apache2
```

### 2. Activar HTTPS (El Candado Verde) 🔒
```bash
sudo certbot --apache
```
*   Te preguntará un email (pon el tuyo).
*   Acepta los términos (Y).
*   Selecciona los dominios (pulsa Enter para todos o selecciona números).
*   Si todo va bien, dirá "Congratulations!".

### 3. Activar FTP Seguro (Configuración final)
```bash
sudo cp ~/deploy/config/vsftpd.conf /etc/vsftpd.conf
sudo systemctl restart vsftpd
```

### 4. Backups Automáticos
```bash
# Editar el cron
sudo crontab -e
# Elige opción 1 (nano) si pregunta.
# Ve al final del archivo y pega esta línea:
0 3 * * * /home/ubuntu/deploy/scripts/backup.sh
```
*   Guarda con `Ctrl+O`, `Enter` y sal con `Ctrl+X`.


---

## FASE 5: Tarea Manual Obligatoria (Claves SSH)

AWS Academy machaca el archivo `authorized_keys` al reiniciar si no tienes cuidado. Para dar acceso a tus compañeros y profesor (RA4-b):

1.  Pídelas su clave pública SSH (empieza por `ssh-rsa ...`).
2.  En el servidor:
    ```bash
    nano /home/ubuntu/.ssh/authorized_keys
    ```
3.  Pega las claves al final del archivo (una por línea).
4.  Guarda (`Ctrl+O`, `Enter`, `Ctrl+X`).

---

## ✅ CHECKLIST DE VALIDACIÓN (Lo que probará el profesor)

1.  **Web Principal**: Entra en `https://tudominio.com`. ¿Se ve la web? ¿Tiene candado?
2.  **Backups**: Entra en `https://backup.tudominio.com`. ¿Pide usuario/contraseña? ¿Ves los archivos `.tar.gz`?
3.  **Entorno Test**: Entra en `https://test.tudominio.com/~juan/` (o el usuario alumno que creaste). ¿Funciona?
4.  **FTP**: Abre FileZilla.
    *   Servidor: `tudominio.com`
    *   Usuario: `juan` (o `app`, o `backup`)
    *   Contraseña: La que pusiste.
    *   **IMPORTANTE**: En cifrado elige "Require explicit FTP over TLS".
    *   ¿Conecta? ¿Puedes subir un archivo?
5.  **Simulacro Backup**:
    *   Ejecuta: `sudo ~/deploy/scripts/backup.sh`
    *   Mira si en la carpeta backup FTP aparece el archivo nuevo.

¡Si todo esto funciona, tienes un 10 en el apartado C4! 🎉
