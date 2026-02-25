# 🌐 Visión Global del SistemaEste documento describe la arquitectura global de la tienda online "AlberoPerezTech", integrando todos los componentes (Frontend, Backend, BBDD, Infraestructura) para ofrecer una visión de conjunto del funcionamiento técnico.## 🏗️ Componentes PrincipalesEl sistema se compone de 4 bloques fundamentales que interactúan entre sí:1. **Frontend (Vue.js SPA)**: La interfaz de usuario accesible desde el navegador.2. **Backend (Laravel API)**: El cerebro, gestiona la lógica de negocio y seguridad.3. **Base de Datos (MySQL)**: Almacenamiento persistente de la información.4. **Infraestructura (AWS)**: Servidoras y redes donde se ejecuta todo.## 🔄 Flujo de Datos (Data Flow)```mermaid
sequenceDiagram participante User as Usuario (Browser)    participant Nginx as Proxy Invers (AWS)
    participant Vue as Frontend (Container)
    participant Laravel as Backend API (Container)
    participant DB as MySQL (Container/RDS)

    User->>Nginx: HTTPS Request (alberoperez.tech)
    Nginx->>Vue: Serveix index.html + JS
    Vue->>User: Renderitza APP
    
    User->>Vue: Click "Veure Productos"
    Vue->>Nginx: API Request (/api/products)
    Nginx->>Laravel: Proxy Pass (Port 8002)
    Laravel->>DB: Query SQL
    DB-->>Laravel: Retorna Resultats
    Laravel-->>Vue: Retorna JSON
    Vue-->>User: Actualitza UI amb dades
```

## 🔐 Seguridad Transversal### 1. HTTPS con Let's Encrypt Todas las comunicaciones externas están cifradas con TLS/SSL. Los certificados se renuevan automáticamente vía Certbot en el servidor Nginx (Host).### 2. CORES y SanctumEl frontend y el backend están en subdominios distintos (`www` vs `api`).- **CORAS**: El backend sólo acepta peticiones de orígenes confiables (`FRONTEND_URL`).- **Sanctum**: Utiliza cookies `httpOnly` y `SameSite= ataques XSS).### 3. Aislamiento de RedLa base de datos no está accesible directamente desde Internet. Sólo el contenedor de Backend puede hablar con ella (ver `docker-compose.prod.yml` y reglas de red).## 🌍 Entornos: Desarrollo vs ProducciónEl sistema está diseñado para ejecutarse de forma idéntica en local y en el nube gracias a Docker, pero con configuraciones adaptadas:| Component | Desenvolupament (Local) | Producció (AWS) |
|-----------|-------------------------|-----------------|
| **Domini** | `localhost` | `AlberoPerezTech.ddaw.es` |
| **Port Front** | 5173 | 8001 (intern) -> 443 (públic) |
| **Port Back** | 8000 | 8002 (intern) -> 443 (públic) |
| **SSL** | No (HTTP) | Sí (HTTPS) |
| **BD Access** | Directe (3308) | Bloquejat (només intern) |

## 📦 Sistema de DespliegueUtilizamos una estrategia de **Repositorios Independientes con Monorepo Virtual**:- Aunque el código está en un solo repositorio Git, tratamos `frontend/` y `laravel/` como proyectos separados con ciclas de vida propios.- Esto permite actualizar el frontend sin tocar el backend, y viceversa, mejorando la mantenibilidad y reduciendo riesgos.