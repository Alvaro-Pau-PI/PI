# ═══════════════════════════════════════════════════════════════════════════════
# Dockerfile - Frontend Vue (Multi-stage Build)
# Etapa 1: Instal·la dependències i compila l'aplicació Vue
# Etapa 2: Serveix el build estàtic mitjançant Nginx
# ═══════════════════════════════════════════════════════════════════════════════

# --- Etapa 1: Build ---
FROM node:20-alpine AS build-stage
WORKDIR /app

# Variable per a configurar la URL de l'API en temps de build
ARG VITE_API_URL=http://localhost:8000
ENV VITE_API_URL=${VITE_API_URL}

# Variable per a configurar la URL de n8n en temps de build
ARG VITE_N8N_BASE_URL=http://localhost:5678
ENV VITE_N8N_BASE_URL=${VITE_N8N_BASE_URL}

# Instal·lar dependències (aprofita cache de Docker si package.json no canvia)
COPY package*.json ./
RUN npm install

# Copiar codi font i compilar
COPY . .
RUN npm run build

# --- Etapa 2: Producció amb Nginx ---
FROM nginx:stable-alpine AS production-stage

# Parametrizar la URL del backend de Laravel Nginx
ARG NGINX_BACKEND_URL=http://nginx:8000

# Copiar el build estàtic i la configuració de Nginx
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Substituir el backend per defecte per l'argument
RUN sed -i "s|http://nginx:8000|${NGINX_BACKEND_URL}|g" /etc/nginx/conf.d/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
