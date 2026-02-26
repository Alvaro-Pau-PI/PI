# 🔄 CI/CD - Integración y Despliegue Continuo (Backend)

El backend utiliza un pipeline de **GitHub Actions** más complejo que el frontend, ya que incluye pruebas automatizadas y migraciones de base de datos.

## 🛠️ Pipeline: `deploy-backend.yml`

El flujo de trabajo se define en el archivo `.github/workflows/deploy-backend.yml`.

### 🎯 Trigger (Disparador)
El pipeline se ejecuta automáticamente cuando:
- Hay un **Push** a la rama `main`.
- Los cambios afectan a la carpeta `laravel/` o al propio workflow.

### Stages (Fases) del Pipeline

#### 1. **Test (Integración Continua)**
Antes de desplegar nada, verificamos que el código funcione.

**Pasos:**
1. **Configuración**: PHP 8.4, Composer.
2. **Dependencias**: Instala paquetes (`composer install`).
3. **Environment**: Copia `.env.example` y genera clave de aplicación.
4. **Ejecución de Tests**: Lanza `php artisan test` (PHPUnit).
> ⚠️ Si algún test falla, el pipeline se detiene y **NO se realiza el despliegue**.

```yaml
# Fragmento de test
- name: Execute tests
  run: php artisan test
```

#### 2. **Deploy (Despliegue)**
Solo se ejecuta si la fase de `Test` ha tenido éxito (`if: success()`).

**Pasos:**
1. **SSH Connection**: Se conecta a la instancia EC2.
2. **Git Pull**: Descarga los cambios aprobados.
3. **Docker Rebuild**: Reconstruye y reinicia los contenedores PHP-FPM y Nginx.
4. **Migraciones**: Ejecuta `php artisan migrate --force` para actualizar el esquema de la BD sin preguntas interactivas.
5. **Optimización**: Limpia y regenera las cachés de configuración, rutas y vistas.

```yaml
# Comandos críticos de post-despliegue
docker compose exec -T laravel-app php artisan migrate --force
docker compose exec -T laravel-app php artisan config:cache
docker compose exec -T laravel-app php artisan route:cache
```

---

## 🛡️ Gestión de Migraciones en Producción

Dado que el despliegue es automático, las migraciones de base de datos deben ser **no destructivas**.
- ❌ **Evitar**: Renombrar columnas o borrar tablas sin copia de seguridad previa.
- ✅ **Preferir**: Añadir columnas nuevas, marcar registros como "deprecated" antes de borrarlos.

---

## 📈 Rollback y Recuperación

En caso de error crítico después del despliegue:

1. **Revertir Código**: Haz `git revert` y push para volver a la versión anterior.
2. **Revertir BD (Manual)**: Si una migración ha roto datos, hay que conectarse por SSH y ejecutar `php artisan migrate:rollback --step=1` con precaución extrema.

## ✅ Verificación de la API

Después del despliegue:
1. Prueba un endpoint público: `curl https://api.AlberoPerezTech.ddaw.es/api/products` -> Debe devolver 200 OK.
2. Verifica los logs si hay error 500: `docker compose logs laravel-app`.
