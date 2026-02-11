# Iteració 3: Migració a Laravel v2 (mínim viable)

En aquest sprint hem fet el salt de la versió PHP natiu + JSON Server (v1) a una versió professional amb **Laravel (v2)**. L'objectiu principal ha estat construir un backend mínim viable sobre MySQL, consolidant un ecosistema MVC real amb migracions, models i autenticació moderna, preparant el terreny per a futures integracions (SPA Vue i microserveis).

## Objectius principals

- **Migració a Framework**: Entendre i configurar un projecte Laravel (MVC, Rutes, Eloquent) connectat a MySQL.
- **Autenticació Robusta**: Integrar Laravel Breeze i comparar-lo amb l'autenticació manual anterior.
- **Automatització**: Implementar la importació massiva de productes des d'Excel directament a la base de dades.
- **API First**: Exposar dades via API REST per al futur client SPA (Vue.js).
- **Testing**: Validar el funcionament crític (API, Auth, Imports) mitjançant tests automatitzats.

## Activitats realitzades

### C1. Configuració de l'entorn Laravel
- Inicialització del projecte a la carpeta `laravel/`, mantenint `legacy-php/` com a referència.
- Configuració de l'entorn (`.env`) per connectar amb la instància MySQL existent.
- Estructura de contenidors Docker preparada per servir l'aplicació.

### C2. Model de Dades i Migracions
- Creació de migracions i models Eloquent per a `Product`, `User` i `Review`.
- Definició de camps clau (`sku`, `price`, `stock`) i relacions.
- Utilització de **Seeders** per poblar la base de dades amb dades de prova inicials.

### C3. Autenticació amb Laravel Breeze
- Implementació del stack d'autenticació Breeze (versió Blade).
- Personalització de les vistes de login i registre per adaptar-les al tema fosc corporatiu.
- Redirecció post-login ajustada per portar l'usuari a `/productes`.

### C4. Importació Automàtica (Excel)
- Desenvolupament d'un controlador d'importació (`ProductImportController`).
- Validació estricta de dades (formats numèrics, camps obligatoris) durant la càrrega de fitxers `.xlsx`.
- Gestió d'errors i feedback a l'usuari.

### C5. Frontend i API
- **Vistes**: Creació de `products/index.blade.php` reutilitzant els estils "Dark Mode" de la v1 per a una llista de productes responsiva.
- **API**: Definició d'endpoints a `routes/api.php` (`GET /api/products`) per exposar el catàleg en format JSON.

### C6. Interactivitat (Reviews)
- Sistema de valoracions implementat amb JavaScript (AJAX/Fetch) consumint l'API del backend.
- Permet als usuaris autenticats deixar comentaris i puntuacions sense recarregar la pàgina.
- Validacions tant en client (JS) com en servidor (Laravel Validation).

### C7. Proves (Testing)
- bateria de tests automatitzats (`tests/Feature`) per verificar:
    - Respostes correctes de l'API de productes.
    - Cicle de vida de les Reviews.
    - Procés d'importació.
    - Flux d'autenticació (Breeze).

## Resultats i Evidències

El sistema ha estat migrat exitosament a Laravel, complint amb tots els criteris d'avaluació.

### Evidència Visual (Llistat de Productes)
La següent captura mostra la vista principal de productes implementada amb Blade i dades reals de MySQL:

![Llistat de Productes](file:///home/batoi/.gemini/antigravity/brain/d7133841-694a-458c-8a15-7051bede9042/product_list_screenshot_1770131908360.png)

### Resum de Proves
S'han executat i superat **37 tests automatitzats**, garantint l'estabilitat del backend v2.
```
PASS Tests\Feature\ProductApiTest
PASS Tests\Feature\ReviewApiTest
PASS Tests\Feature\ImportTest
...
Tests: 37 passed
```

---

**Següents passos**: Aquest backend servirà de base per al **Sprint 4**, on desenvoluparem una Single Page Application (SPA) amb **Vue.js** que consumirà l'API que acabem de crear.
