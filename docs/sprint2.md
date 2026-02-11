# Iteració 2: Migració a PHP + JSON Server

Aquesta segona iteració s'ha centrat en desenvolupar un backend funcional utilitzant PHP natiu i JSON Server, implementar la lògica d'usuaris i productes, i configurar el desplegament en un entorn remot.

## Objectius principals

- **Catàleg (C1)**: Implementar la importació automàtica de productes des d'Excel a JSON Server.
- **Usuaris (C2)**: Crear un sistema d'autenticació (registre/login) i gestió de perfils sense base de dades relacional.
- **Interacció (C3)**: Fomentar la participació mitjançant comentaris i valoracions en temps real.
- **Desplegament (C4)**: Configurar un entorn de producció en AWS amb servidors web, FTP i estratègies de backup.
- **Interfície (C5)**: Dissenyar una estructura visual clara, usable i accessible per a l'e-commerce.

## Activitats realitzades

### C1. Importació de Productes (Excel → JSON Server)
- **Script d'importació**: Desenvolupament d'un script en PHP utilitzant `PhpSpreadsheet` per llegir fitxers Excel (.xlsx, .csv).
- **Automatització**: Conversió de les dades importades a format JSON i enviament a JSON Server simulant una API REST.
- **Validació**: Comprovació de tipus de dades i gestió d'errors durant la càrrega.

### C2. Registre i Inici de Sessió
- **Autenticació PHP**: Implementació manual de registre i login utilitzant `password_hash()` per a seguretat.
- **Persistència JSON**: Emmagatzematge d'usuaris en un fitxer `users.json` gestionat per JSON Server.
- **Sessions**: Gestió d'estat mitjançant sessions PHP i cookies segures.

### C3. Comentaris i Valoracions
- **Interactivitat**: Implementació de càrrega i enviament de comentaris mitjançant AJAX/Fetch sense recarregar la pàgina.
- **Permisos**: Restricció de funcionalitats (comentar, valorar) només a usuaris autenticats.
- **Feedback**: Actualització dinàmica de la interfície en afegir noves valoracions.

### C4. Desplegament i Còpies de Seguretat (AWS)
- **Infraestructura**: Configuració d'instàncies AWS amb servidors Apache (HTTP/S) i FTP.
- **Seguretat**: Implementació de certificats SSL/TLS, usuaris aïllats per servei i restriccions SSH.
- **Backups**: Automatització de còpies de seguretat nocturnes de fitxers i dades.

### C5. Estructura i Usabilitat (DIW)
- **Layout**: Definició d'una plantilla base (Header, Main, Footer) responsiva.
- **Components Visuals**: Integració de cercador, filtres, targetes de producte i carret visible.
- **Accessibilitat**: Compliment de criteris WCAG 2.1 AA, navegació per teclat i contrast adequat.

## Resultats obtinguts

- Un backend flexible basat en PHP i JSON Server.
- Un sistema funcional d'importació massiva de dades.
- Una plataforma desplegada en núvol (AWS) amb mesures de seguretat i recuperació.
- Una experiència d'usuari millorada amb disseny responsiu i interaccions dinàmiques.

---

Aquesta iteració ha servit com a pas intermedi per consolidar conceptes de backend i desplegament abans de la migració definitiva a un framework MVC com Laravel.
