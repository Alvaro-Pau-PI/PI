# 🧪 Tests del Proyecto

Esta carpeta contiene todos los archivos de prueba utilizados durante el desarrollo y solución del problema de imágenes.

## 📁 Archivos de Prueba

### 📋 **Scripts de Test**
- `test_images.php` - Prueba de configuración de almacenamiento y enlaces simbólicos
- `test_product_creation.php` - Script para probar la creación de productos con imágenes
- `test_product_upload.sh` - Script bash para probar subida de imágenes vía API
- `test_final_images.sh` - Prueba final completa del sistema de imágenes
- `test_auth.php` - Prueba de autenticación y login
- `test_create_product.php` - Prueba básica de creación de productos

### 🌐 **Tests Web**
- `test_new_product.html` - Página HTML para probar visualización de imágenes

### 📚 **Documentación**
- `SOLUCION_IMAGENES.md` - Documentación completa del problema y solución
- `SOLUCION_FINAL_IMAGENES.md` - Resumen final de la solución implementada

## 🚀 **Cómo Usar**

### Para ejecutar los scripts:
```bash
# Desde la raíz del proyecto
./tests/test_final_images.sh

# O scripts PHP vía Docker
docker compose exec laravel-app php tests/test_images.php
```

### Para ver documentación:
```bash
cat tests/SOLUCION_FINAL_IMAGENES.md
```

## 📝 **Nota**
Estos archivos fueron creados durante el proceso de depuración y solución del problema de imágenes en el panel de administración. Pueden ser útiles para referencia futura o para realizar pruebas de regresión.

## ✅ **Estado Actual**
El problema de imágenes está completamente solucionado. Estos archivos se mantienen como referencia y para futuras pruebas.
