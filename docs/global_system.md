# 🌐 Visió Global del Sistema

Aquest document descriu l'arquitectura global de la botiga en línia "AlberoPerezTech", integrant tots els components (Frontend, Backend, BBDD, Infraestructura) per a oferir una visió de conjunt del funcionament tècnic.

## 🏗️ Components Principals

El sistema es compon de 4 blocs fonamentals que interactuen entre si:

1. **Frontend (Vue.js SPA)**: La interfície d'usuari accessible des del navegador.
2. **Backend (Laravel API)**: El cervell, gestiona la lògica de negoci i seguretat.
3. **Base de Dades (MySQL)**: Emmagatzematge persistent de la informació.
4. **Infraestructura (AWS)**: Servidors i xarxes on s'executa tot.

## 🔄 Flux de Dades (Data Flow)

```mermaid
sequenceDiagram
    participant User as Usuari (Browser)
    participant Nginx as Proxy Invers (AWS)
    participant Vue as Frontend (Container)
    participant Laravel as Backend API (Container)
    participant DB as MySQL (Container/RDS)

    User->>Nginx: HTTPS Request (alberoperez.tech)
    Nginx->>Vue: Serveix index.html + JS
    Vue->>User: Renderitza APP
    
    User->>Vue: Click "Veure Productes"
    Vue->>Nginx: API Request (/api/products)
    Nginx->>Laravel: Proxy Pass (Port 8002)
    Laravel->>DB: Query SQL
    DB-->>Laravel: Retorna Resultats
    Laravel-->>Vue: Retorna JSON
    Vue-->>User: Actualitza UI amb dades
```

## 🔐 Seguretat Transversal

### 1. HTTPS amb Let's Encrypt
Totes les comunicacions externes estan xifrades amb TLS/SSL. Els certificats es renoven automàticament via Certbot al servidor Nginx (Host).

### 2. CORS i Sanctum
El frontend i el backend estan en subdominis diferents (`www` vs `api`).
- **CORS**: El backend només accepta peticions d'orígens confiables (`FRONTEND_URL`).
- **Sanctum**: Utilitza cookies `httpOnly` i `SameSite=Lax` per mantenir la sessió sense exposar tokens al JavaScript (preveu atacs XSS).

### 3. Aïllament de Xarxa
La base de dades no és accessible directament des d'Internet. Només el contenidor del Backend pot parlar amb ella (veure `docker-compose.prod.yml` i regles de xarxa).

## 🌍 Entorns: Desenvolupament vs Producció

El sistema està dissenyat per executar-se de manera idèntica en local i al núvol gràcies a Docker, però amb configuracions adaptades:

| Component | Desenvolupament (Local) | Producció (AWS) |
|-----------|-------------------------|-----------------|
| **Domini** | `localhost` | `AlberoPerezTech.ddaw.es` |
| **Port Front** | 5173 | 8001 (intern) -> 443 (públic) |
| **Port Back** | 8000 | 8002 (intern) -> 443 (públic) |
| **SSL** | No (HTTP) | Sí (HTTPS) |
| **BD Access** | Directe (3308) | Bloquejat (només intern) |

## 📦 Sistema de Desplegament

Utilitzem una estratègia de **Repositoris Independents amb Monorepo Virtual**:
- Encara que el codi està en un sol repositori Git, tractem `frontend/` i `laravel/` com a projectes separats amb cicles de vida propis.
- Això permet actualitzar el frontend sense tocar el backend, i viceversa, millorant la mantenibilitat i reduint riscos en els desplegaments.
