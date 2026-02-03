# SA.3 Iteració: Migració a Laravel v2 (mínim viable)

## 🎯 Objectius del Sprint

L'objectiu principal d'aquest sprint ha sigut migrar el backend de l'aplicació a **Laravel (v2)**, establint una base sòlida amb arquitectura MVC, base de dades MySQL, i autenticació robusta, tot mantenint les funcionalitats del catàleg i la gestió d'usuaris definides anteriorment.

## 🧩 Tasques Realitzades

### C1. Creació i Configuració del Projecte
- S'ha inicialitzat un nou projecte Laravel a la carpeta `laravel/`.
- S'ha configurat l'arxiu `.env` per connectar-se a la base de dades MySQL compartida.
- S'ha estructurat l'aplicació per conviure amb el codi `legacy-php/`.

### C2. Model de Dades i Migracions
- **Productes**: Creat model `Product` i migració amb camps: `sku` (únic), `name`, `description`, `price`, `stock`, `image`, `category`.
- **Usuaris**: Utilitzada la migració per defecte de Laravel, compatible amb el sistema d'autenticació.
- **Reviews**: Afegida taula per a comentaris i valoracions (`user_id`, `product_id`, `text`, `rating`).
- **Seeding**: Implementats seeders per poblar la base de dades amb dades inicials de prova.

### C3. Autenticació amb Laravel Breeze
- Implementat **Laravel Breeze** (versió Blade) per gestionar el flux complet d'autenticació.
- Funcionalitats actives: Registre, Inici de Sessió (amb redirecció a Productes), Tancament de Sessió, i Edició de Perfil.
- Personalització de les vistes d'autenticació per coincidir amb el "Dark Theme" corporatiu.

### C4. Importació d'Excel
- Implementat controlador `ProductImportController` utilitzant `maatwebsite/excel`.
- Validació estricta de dades (camps obligatoris, formats numèrics) abans de la inserció.
- Gestió d'errors i feedback a l'usuari en cas de fallada en la importació.

### C5. Vistes Blade i API
- **Frontend**: Desenvolupada vista `products/index.blade.php` utilitzant Blade i CSS personalitzat (reutilitzant estils de la v1).
- **Responsivitat**: Disseny adaptatiu (Grid/Flex) amb targetes de producte.
- **API**: Habilitats endpoints `GET /api/products` per al futur consum des de Vue.js.

### C6. Validacions i Comentaris (Client)
- **Reviews**: Implementat sistema de valoracions via AJAX/Fetch. Els usuaris autenticats poden deixar comentaris i puntuacions sense recarregar la pàgina.
- **Validacions**:
    - **Servidor**: Validacions de Laravel (Form Requests) per a dades crítiques.
    - **Client**: Validacions HTML5 i JS per a feedback immediat en formularis de contacte i reviews.

### C7. Proves (Testing)
- Creació de tests automatitzats (`tests/Feature`) cobrint:
    - **API Productes**: Verificació d'estructura JSON i codi 200.
    - **API Reviews**: Test de creació (auth required) i llistat.
    - **Importació**: Validació del procés de càrrega d'Excel.
- Resultat: Tots els tests passen correctament (`PASS`).

## ✅ Criteris d'Avaluació Assolits

- [x] **Laravel Core**: Estructura MVC correcta, migracions i models definis.
- [x] **Autenticació**: Breeze operatiu i usuaris en MySQL.
- [x] **Importació**: Funcional i validada.
- [x] **DIW**: Disseny coherent, fosc i responsiu.
- [x] **Qualitat**: README actualitzat, codi net i organitzat.
- [x] **Integració**: API preparada per al següent Sprint (SPA Vue).
- [x] **Proves**: Tests automatitzats d'API i funcionalitats crítiques.

## 📦 Entregables

1.  Codi font complet a `laravel/`.
2.  Documentació actualitzada (`README.md` i `docs/sprint3.md`).
3.  Evidència de tests passats (veure consola o captures).
4.  Captures de pantalla de la interfície (veure carpeta `docs/screenshots` o annexes).
