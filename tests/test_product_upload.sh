#!/bin/bash

# Script para probar la creación de un producto con imagen
echo "=== Prueba de Creación de Producto con Imagen ==="

# URL del backend
BACKEND_URL="http://localhost:8000"

# Crear archivo temporal para cookies
COOKIE_JAR=$(mktemp)

# Obtener sesión de admin (login)
echo "1. Iniciando sesión como administrador..."
LOGIN_RESPONSE=$(curl -s -c "$COOKIE_JAR" -b "$COOKIE_JAR" -X POST "$BACKEND_URL/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@admin.test",
    "password": "password"
  }')

# Verificar si el login fue exitoso
if [ -z "$LOGIN_RESPONSE" ]; then
    echo "✅ Login exitoso (sesión establecida)"
else
    echo "❌ Error en login"
    echo "Respuesta: $LOGIN_RESPONSE"
    rm -f "$COOKIE_JAR"
    exit 1
fi

# Crear producto con imagen
echo "2. Creando producto con imagen..."

curl -s -c "$COOKIE_JAR" -b "$COOKIE_JAR" -X POST "$BACKEND_URL/api/products" \
  -F "name=Producto de Prueba $(date +%s)" \
  -F "description=Descripción del producto de prueba creado automáticamente" \
  -F "price=99.99" \
  -F "stock=10" \
  -F "category=Processadors" \
  -F "image=@/tmp/test-image.png" \
  -F "eco_score=75" \
  -F "is_refurbished=1"

echo ""
echo "3. Verificando producto creado..."

# Obtener el último producto
LAST_PRODUCT=$(curl -s "$BACKEND_URL/api/products?per_page=1" | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)

if [ -n "$LAST_PRODUCT" ]; then
    echo "✅ Producto creado con ID: $LAST_PRODUCT"
    
    # Obtener detalles del producto
    PRODUCT_DETAILS=$(curl -s "$BACKEND_URL/api/products/$LAST_PRODUCT")
    echo "Detalles:"
    echo "$PRODUCT_DETAILS" | grep -E '"name"|"image"|"images"'
    
    # Extraer la ruta de la imagen
    IMAGE_PATH=$(echo "$PRODUCT_DETAILS" | grep -o '"image":"[^"]*"' | cut -d'"' -f4 | sed 's/\\\//\//g')
    
    if [ -n "$IMAGE_PATH" ]; then
        echo ""
        echo "4. Probando acceso a la imagen..."
        echo "Ruta de imagen: $IMAGE_PATH"
        
        # Probar acceso vía backend
        BACKEND_IMAGE_URL="$BACKEND_URL/storage/$IMAGE_PATH"
        echo "Probando URL: $BACKEND_IMAGE_URL"
        
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$BACKEND_IMAGE_URL")
        if [ "$HTTP_CODE" = "200" ]; then
            echo "✅ Imagen accesible vía backend (HTTP $HTTP_CODE)"
        else
            echo "❌ Error accediendo a imagen vía backend (HTTP $HTTP_CODE)"
        fi
        
        # Probar acceso vía frontend
        FRONTEND_IMAGE_URL="http://localhost:5173/$IMAGE_PATH"
        echo "Probando URL: $FRONTEND_IMAGE_URL"
        
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$FRONTEND_IMAGE_URL")
        if [ "$HTTP_CODE" = "200" ]; then
            echo "✅ Imagen accesible vía frontend (HTTP $HTTP_CODE)"
        else
            echo "❌ Error accediendo a imagen vía frontend (HTTP $HTTP_CODE)"
        fi
    else
        echo "❌ No se encontró ruta de imagen en la respuesta"
    fi
else
    echo "❌ No se pudo obtener el ID del último producto"
fi

echo ""
echo "=== Prueba Completada ==="

# Limpiar archivo de cookies
rm -f "$COOKIE_JAR"
