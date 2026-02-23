# 👥 Guia de Contribució i Normes de l'Equip

Aquest document estableix les normes de treball col·laboratiu per garantir la qualitat del codi i la fluïdesa en el desenvolupament del projecte.

## 🌿 Estratègia de Branques (Branching Strategy)

Utilitzem una versió simplificada de **Gitflow**:

- **`main`**: Branca de **Producció**. El codi aquí SEMPRE ha de ser estable i desplegable.
  - Els pushes directes estan PROHIBITS.
  - Només rep canvis via Pull Request (PR).
  
- **`develop`**: Branca de **Integració**. Aquí es fusionen les features acabades.
  - És la base per crear noves branques de funcionalitat.

- **`feature/nom-de-la-tasca`**: Branques temporals per a desenvolupament.
  - Exemple: `feature/login-page`, `feature/api-products`.
  - Es creen des de `develop`.
  - S'esborren després de fer merge.

- **`fix/descripcio`**: Branques per corregir bugs crítics.
  - Exemple: `fix/cors-error`.

### Flux de Treball Típic
1. `git checkout develop`
2. `git pull origin develop` (actualitzar)
3. `git checkout -b feature/nova-funcionalitat`
4. ... fer feina, commits ...
5. `git push origin feature/nova-funcionalitat`
6. Crear Pull Request a GitHub (`feature/...` -> `develop`)

## 📝 Commit Policy (Política de Commits)

Seguim la convenció **Conventional Commits** per mantenir un historial clar:

- `feat: Missatge`: Una nova funcionalitat.
- `fix: Missatge`: Correcció d'un error.
- `docs: Missatge`: Canvis només en la documentació.
- `style: Missatge`: Canvis de format, espais, etc. (no lògica).
- `refactor: Missatge`: Refactorització de codi (sense canvis lògics).
- `test: Missatge`: Afegir o corregir tests.
- `chore: Missatge`: Tarees de manteniment (build, deps...).

**Exemple bo:** `feat: Afegir validació al formulari de registre`
**Exemple dolent:** `canvis al login`

## 💅 Code Style (Estil de Codi)

### Frontend (Vue)
- Utilitzem **ESLint** amb la configuració recomanada de Vue 3 (`plugin:vue/vue3-recommended`).
- Noms de components: **PascalCase** (`ProductCard.vue`).
- Props i Emits definits explícitament.

### Backend (Laravel)
- Utilitzem **Laravel Pint** (basat en PHP-CS-Fixer) per estandaritzar l'estil PSR-12.
- Noms de classes: **PascalCase**.
- Noms de mètodes/variables: **camelCase**.
- Noms de taules: **snake_case** (plural).

## ✔️ Criteris d'Acceptació (Definition of Done)

Una tasca es considera "acabada" quan:
1. El codi compleix l'estil definit.
2. Funciona en l'entorn local (Docker).
3. S'ha documentat si és necessari.
4. Ha passat la revisió (Code Review) d'un company.
5. El pipeline de CI/CD (Tests) ha passat en verd.

## 🤝 Repartiment de Responsabilitats

- **Frontend Leader**: Álvaro Pérez
  - Responsable de Vue, CSS, UX/UI.
- **Backend Leader**: Pau Albero
  - Responsable de Laravel, BD, API.
- **DevOps Shared**: Ambdós
  - Responsable de Docker, AWS, GitHub Actions.

> *Tots els membres de l'equip han de conèixer el funcionament bàsic de l'àrea de l'altre i poder fer canvis menors.*
