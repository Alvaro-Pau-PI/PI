# 🔄 CI/CD - Integració i Desplegament Continu (Frontend)

El frontend utilitza **GitHub Actions** per automatitzar el cicle de vida del programari, des de la integració de codi fins al desplegament en producció a AWS.

## 🛠️ Pipeline: `deploy-frontend.yml`

El flux de treball es defineix al fitxer `.github/workflows/deploy-frontend.yml`.

### 🎯 Trigger (Disparador)
El pipeline s'executa automàticament quan:
- Hi ha un **Push** a la branca `main`.
- Els canvis afecten a la carpeta `frontend/` o al propi workflow.

### stages (Fases) del Pipeline

#### 1. **Deploy (Desplegament)**
Aquest job s'encarrega d'actualitzar l'aplicació al servidor de producció.

**Passos:**
1. **Checkout**: Baixa el codi del repositori.
2. **SSH Connection**: Es connecta a la instància EC2 utilitzant la clau privada (`EC2_SSH_KEY`).
3. **Git Pull**: Actualitza el codi al servidor (`git pull origin main`).
4. **Docker Rebuild**:
   - Construeix la nova imatge Docker del frontend.
   - Injecta les variables d'entorn de producció (`VITE_API_URL`) com a `build-args`.
   - Reinicia el contenidor amb `docker compose up -d --build`.

```yaml
# Fragment clau del workflow
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

## 🛡️ Secrets i Seguretat

Les credencials sensibles no es guarden al codi, sinó als **GitHub Repository Secrets**:

| Secret | Descripció | Exemple |
|--------|-----------|---------|
| `EC2_HOST` | IP pública / DNS del servidor | `3.123.45.67` |
| `EC2_USER` | Usuari SSH | `ubuntu` |
| `EC2_SSH_KEY` | Clau privada SSH (.pem) | `-----BEGIN RSA...` |
| `VITE_API_URL` | URL del Backend | `https://api...` |

---

## 📈 Estratègia de Rollback

Si un desplegament falla o introdueix un error crític:

1. **Revertir commit**: Localment, fer `git revert <commit-hash>`.
2. **Push**: Pujar el revert a `main`.
3. **Auto-Deploy**: GitHub Actions detectarà el canvi i desplegarà la versió anterior automàticament.

## ✅ Verificació del Desplegament

Després de l'execució del pipeline:
1. Visita `https://AlberoPerezTech.ddaw.es`.
2. Obre la consola del navegador (F12) i verifica que no hi ha errors 404/500.
3. Comprova que la versió de l'aplicació ha canviat (per exemple, un canvi visual recent).
