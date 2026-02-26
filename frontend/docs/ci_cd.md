# 🔄 CI/CD - Integración y Despliegue Continuo (Frontend)

El frontend utiliza **GitHub Actions** para automatizar el ciclo de vida del software, desde la integración de código hasta el despliegue en producción en AWS.

## 🛠️ Pipeline: `deploy-frontend.yml`

El flujo de trabajo se define en el archivo `.github/workflows/deploy-frontend.yml`.

### 🎯 Trigger (Disparador)
El pipeline se ejecuta automáticamente cuando:
- Hay un **Push** a la rama `main`.
- Los cambios afectan a la carpeta `frontend/` o al propio workflow.

### Fases (Stages) del Pipeline

#### 1. **Deploy (Despliegue)**
Este trabajo se encarga de actualizar la aplicación en el servidor de producción.

**Pasos:**
1. **Checkout**: Descarga el código del repositorio.
2. **SSH Connection**: Se conecta a la instancia EC2 utilizando la clave privada (`EC2_SSH_KEY`).
3. **Git Pull**: Actualiza el código en el servidor (`git pull origin main`).
4. **Docker Rebuild**:
   - Construye la nueva imagen Docker del frontend.
   - Inyecta las variables de entorno de producción (`VITE_API_URL`) como `build-args`.
   - Reinicia el contenedor con `docker compose up -d --build`.

```yaml
# Fragmento clave del workflow
- name: Deploy to EC2
  uses: appleboy/ssh-action@v1.0.3
  with:
    script: |
      cd /home/ubuntu/PI
      git pull origin main
      export VITE_API_URL=${{ secrets.VITE_API_URL }}
      docker compose -f docker-compose.prod.yml up -d --build frontend
```

---

## 🛡️ Secretos y Seguridad

Las credenciales sensibles no se guardan en el código, sino en los **GitHub Repository Secrets**:

| Secreto | Descripción | Ejemplo |
|--------|-----------|---------|
| `EC2_HOST` | IP pública / DNS del servidor | `3.123.45.67` |
| `EC2_USER` | Usuario SSH | `ubuntu` |
| `EC2_SSH_KEY` | Clave privada SSH (.pem) | `-----BEGIN RSA...` |
| `VITE_API_URL` | URL del Backend | `https://api...` |

---

## 📈 Estrategia de Rollback

Si un despliegue falla o introduce un error crítico:

1. **Revertir commit**: Localmente, hacer `git revert <commit-hash>`.
2. **Push**: Subir el revert a `main`.
3. **Auto-Deploy**: GitHub Actions detectará el cambio y desplegará la versión anterior automáticamente.

## Verificación del Despliegue

Después de la ejecución del pipeline:
1. Visita `http://18.206.113.196` (o `https://proyecto03.ddaw.es` si el DNS ya está delegado).
2. Abre la consola del navegador (F12) y verifica que no hay errores 404/500.
3. Comprueba que la versión de la aplicación ha cambiado (por ejemplo, un cambio visual reciente).
