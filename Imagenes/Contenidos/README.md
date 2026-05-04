# Sistema de Imágenes

## Cómo agregar imágenes a categorías y subcategorías

Las imágenes ahora se gestionan a través de la tabla `contenido` usando el campo `url_externas`. Para agregar una imagen a una categoría o subcategoría:

1. **Crear un bloque de contenido** para la categoría (si no existe uno)
2. **Agregar la URL de la imagen** en la tabla `contenido` asociada al bloque
3. **La URL debe terminar en una extensión de imagen** (.jpg, .png, .gif, .webp, .svg, .jpeg)

### Ubicación de imágenes
- Las imágenes deben colocarse en la carpeta `Imagenes/Contenidos/`
- Ejemplo de URL: `Imagenes/Contenidos/mi-imagen.jpg`

### Diferenciación automática
El sistema diferencia automáticamente entre:
- **Enlaces externos**: URLs que no terminan en extensiones de imagen
- **Imágenes**: URLs que terminan en .jpg, .png, .gif, .webp, .svg, .jpeg

### Funcionamiento
- **Categorías principales**: Muestra la primera imagen encontrada en cualquiera de sus bloques
- **Subcategorías**: Cada subcategoría muestra su primera imagen encontrada
- **Bloques de contenido**: Pueden tener tanto enlaces externos como imágenes asociadas