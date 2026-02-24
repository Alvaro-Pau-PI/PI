# 🔄 CI/CD - Integració i Desplegament Continu (Backend)

El backend utilitza un pipeline de **GitHub Actions** més complex que el frontend, ja que inclou proves automatitzades i migracions de base de dades.

## 🛠️ Pipeline: `deploy-backend.yml`

El flux de treball es defineix al fitxer `.github/workflows/deploy-backend.yml`.

### 🎯 Trigger (Disparador)
El pipeline s'executa automàticament quan:
- Hi ha un **Push** a la branca `main`.
- Els canvis afecten a la carpeta `laravel/` o al propi workflow.

### Stages (Fases) del Pipeline

#### 1. **Test (Integració Contínua)**
Abans de desplegar res, verifiquem que el codi funcioni.

**Passos:**
1. **Configuració**: PHP 8.4, Composer.
2. **Dependències**: Instal·la paquets (`composer install`).
3. **Environment**: Copia `.env.example` i genera clau d'aplicació.
4. **Execució de Tests**: Llança `php artisan test` (PHPUnit).
> ⚠️ Si algun test falla, el pipeline s'atura i **NO es realitza el desplegament**.

```yaml
# Fragment de test
- name: Execute tests
  run: php artisan test
```

#### 2. **Deploy (Desplegament)**
Només s'executa si la fase de `Test` ha tingut èxit (`if: success()`).

**Passos:**
1. **SSH Connection**: Es connecta a la instància EC2.
2. **Git Pull**: Baixa els canvis aprovats.
3. **Docker Rebuild**: Reconstrueix i reinicia els contenidors PHP-FPM i Nginx.
4. **Migracions**: Executa `php artisan migrate --force` per actualitzar l'esquema de la BD sense preguntes interactives.
5. **Optimització**: Neteja i regenera les cachés de configuració, rutes i vistes.

```yaml
# Comandos crítics de post-desplegament
docker compose exec -T laravel-app php artisan migrate --force
docker compose exec -T laravel-app php artisan config:cache
docker compose exec -T laravel-app php artisan route:cache
```

---

## 🛡️ Gestió de Migracions en Producció

Com que el desplegament és automàtic, les migracions de base de dades han de ser **no destructives**.
- ❌ **Evitar**: Renombrar columnes o esborrar taules sense còpia de seguretat prèvia.
- ✅ **Preferir**: Afegir columnes noves, marcar registres com "deprecated" abans d'esborrar-los.

---

## 📈 Rollback i Recuperació

En cas d'error crític després del desplegament:

1. **Revertir Codi**: Fes `git revert` i push per tornar a la versió anterior.
2. **Revertir BD (Manual)**: Si una migració ha trencat dades, cal connectar-se per SSH i executar `php artisan migrate:rollback --step=1` amb precaució extrema.

## ✅ Verificació de l'API

Després del desplegament:
1. Prova un endpoint públic: `curl https://api.AlberoPerezTech.ddaw.es/api/products` -> Ha de tornar 200 OK.
2. Verifica els logs si hi ha error 500: `docker compose logs laravel-app`.
