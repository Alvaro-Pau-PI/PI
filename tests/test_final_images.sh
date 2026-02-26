#!/bin/bash

echo "=== PRUEBA FINAL DE IMÁGENES ==="

# Producto de prueba
PRODUCT_ID=138

# Obtener datos del producto
echo "1. Obteniendo datos del producto $PRODUCT_ID..."
PRODUCT_DATA=$(curl -s "http://localhost:8000/api/products/$PRODUCT_ID")

# Extraer rutas de imágenes
MAIN_IMAGE=$(echo "$PRODUCT_DATA" | grep -o '"image":"[^"]*"' | cut -d'"' -f4 | sed 's/\\\//\//g')
GALLERY_IMAGES=$(echo "$PRODUCT_DATA" | grep -o '"images":\[[^]]*\]' | sed 's/"images":\[\(.*\)\]/\1/' | tr -d '"' | tr ',' '\n' | sed 's/\\\//\//g' | sed '/^$/d')

echo "Imagen principal: $MAIN_IMAGE"
echo "Imágenes de galería:"
echo "$GALLERY_IMAGES" | while read img; do
    echo "  - $img"
done

echo ""
echo "2. Probando acceso a imágenes..."

# Probar imagen principal
if [ -n "$MAIN_IMAGE" ]; then
    echo "Probando imagen principal: $MAIN_IMAGE"
    if [ "$MAIN_IMAGE" = "/img/productos/dybRyug0GgwhZTCwfbdYUHUQluAZlIhndiUmgtHF.png" ]; then
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000/storage/img/productos/dybRyug0GgwhZTCwfbdYUHUQluAZlIhndiUmgtHF.png")
        if [ "$HTTP_CODE" = "200" ]; then
            echo "  ✅ Imagen principal accesible (HTTP $HTTP_CODE)"
        else
            echo "  ❌ Error en imagen principal (HTTP $HTTP_CODE)"
        fi
    fi
fi

# Probar imágenes de galería
echo "$GALLERY_IMAGES" | while read img; do
    if [ -n "$img" ]; then
        echo "Probando galería: $img"
        if [ "$img" = "/img/productos/5ICzqEALu0kWmLZ8FQ09I6WpuiKRgO5DWNHvR7Xf.png" ]; then
            HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000/storage/img/productos/5ICzqEALu0kWmLZ8FQ09I6WpuiKRgO5DWNHvR7Xf.png")
            if [ "$HTTP_CODE" = "200" ]; then
                echo "  ✅ Galería accesible (HTTP $HTTP_CODE)"
            else
                echo "  ❌ Error en galería (HTTP $HTTP_CODE)"
            fi
        fi
    fi
done

echo ""
echo "3. Verificando función getImageUrl..."

# Simular la función getImageUrl
test_getImageUrl() {
    local path="$1"
    if [ -z "$path" ]; then
        echo "/img/placeholder-product.jpg"
        return
    fi
    
    if [[ "$path" == http* ]]; then
        echo "$path"
        return
    fi
    
    # Limpiar ruta
    local cleanPath=$(echo "$path" | sed 's/\\\//\//g' | xargs)
    
    # Añadir /storage/ si no está presente
    local finalPath
    if [[ "$cleanPath" == /storage/* ]]; then
        finalPath="$cleanPath"
    else
        finalPath="/storage${cleanPath}"
    fi
    
    echo "http://localhost:8000${finalPath}"
}

# Probar la función
MAIN_URL=$(test_getImageUrl "$MAIN_IMAGE")
echo "URL generada para imagen principal: $MAIN_URL"

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$MAIN_URL")
if [ "$HTTP_CODE" = "200" ]; then
    echo "  ✅ URL generada funciona (HTTP $HTTP_CODE)"
else
    echo "  ❌ Error en URL generada (HTTP $HTTP_CODE)"
fi

echo ""
echo "=== PRUEBA COMPLETADA ==="
