# Iteració 3: Catàleg de productes, Usuaris i Desplegament

Aquesta iteració s'ha centrat en la implementació de la lògica de negoci principal de l'E-commerce, la gestió d'usuaris, la interactivitat amb comentaris, el disseny final i el desplegament en producció.

## Objectius principals

- **Implementar el catàleg de productes (C1)**: Importació automàtica des d'Excel i visualització a la web.
- **Gestionar usuaris (C2)**: Sistema de registre, inici de sessió i perfil d'usuari segur.
- **Fomentar la interacció (C3)**: Sistema de comentaris i valoracions en temps real (AJAX).
- **Desplegar en producció (C4)**: Configuració de servidors AWS (Web, FTP, Backups) amb seguretat (HTTPS).
- **Millorar la UI/UX (C5)**: Disseny responsiu, accessible i centrat en l'usuari.

## Activitats realitzades

### C1. Catàleg de productes
- **Importació de dades**: Implementació d'un script (amb `Laravel Excel` / `PhpSpreadsheet`) per llegir fitxers `.xlsx` i carregar productes (nom, preu, estoc, imatge) a la base de dades.
- **Validació**: Comprovació de tipus de dades (numèrics per preu/estoc) i gestió d'errors durant la importació.
- **Visualització**: Creació del llistat de productes i fitxa detallada.

### C2. Registre i inici de sessió
- **Autenticació**: Implementació de registre i login segur utilitzant Laravel Breeze (substituint la proposta inicial de JSON Server per MySQL per major robustesa).
- **Gestió de sessions**: Ús de cookies i sessions segures, amb xifratge de contrasenyes (`bcrypt`).
- **Perfil d'usuari**: Pàgina per consultar i editar les dades personals de l'usuari autenticat.

### C3. Comentaris i valoracions
- **Sistema de Reviews**: Implementació de funcionalitat per deixar comentaris i puntuacions (estrelles) als productes.
- **Interactivitat AJAX**: Càrrega i enviament de comentaris sense recarregar la pàgina per millorar l'experiència d'usuari.
- **Gestió de permisos**: Restricció perquè només els usuaris autenticats puguin valorar.

### C4. Desplegament i Infraestructura (AWS)
- **Servidors**: Configuració de Virtual Hosts per a Producció (`app`) i Backups (`backup`).
- **Seguretat**: Implementació de certificats SSL/TLS (HTTPS) i restricció d'accessos SSH/FTP.
- **Còpies de seguretat**: Automatització de backups nocturns de fitxers i base de dades.
- **Infraestructura**: Ús d'Elastic IP i aïllament d'usuaris de sistema.

### C5. Estructura i UI/UX
- **Disseny Visual**: Implementació d'una plantilla base coherent (Header, Footer, Dark Mode).
- **Components Clau**: Integració de cercador, carret visible, targetes de producte i botons d'acció clars.
- **Accessibilitat i Responsivitat**: Adaptació del disseny a dispositius mòbils (menú hamburguesa) i compliment de criteris d'accessibilitat bàsics.

## Resultats obtinguts

- Una botiga online funcional amb catàleg dinàmic importable massivament.
- Un entorn segur on els usuaris poden registrar-se, gestionar el seu perfil i participar (valoracions).
- Una interfície moderna, fosca ("Dark Mode") i adaptada a mòbils, fidel a la identitat corporativa.
- Infraestructura preparada per a producció amb polítiques de seguretat i còpies de seguretat automatitzades.
