# Bagisto 2.5 Beauty & Nails Theme (`wilbere/bagisto-beauty-theme`)

Un tema de storefront moderno, ultra-rápido y de alta conversión para **Bagisto 2.5**, diseñado específicamente para tiendas de **cosmética, esmaltes y nail art**.

Desarrollado bajo el stack nativo de Bagisto 2.5: **Laravel 13**, **PHP 8.4**, **Tailwind CSS v4** y **Vue 3.5**, siguiendo directrices estrictas de diseño anti-cliché (`frontend-design`) y optimización de Core Web Vitals (`modern-web-guidance`).

---

## Características Principales

* **Lienzo Editorial Neutro**: Paleta basada en tonos alabastro y yeso con textos en carbón profundo, asegurando que la pigmentación de los esmaltes y productos sea el centro de atención.
* **Selector Visual de Swatches y Acabados**: Componente táctil interactivo para previsualizar tonos (gel UV, acabado mate, brillante, escarchado y magnético/cat-eye).
* **Sticky Mobile Buy Bar**: Barra flotante inferior fija para dispositivos móviles con selector rápido de tono y botón de compra en tiempo real.
* **Mini-Cart Drawer Interactivo**: Carrito deslizable con barra de progreso dinámico para envíos gratuitos.
* **Arquitectura de Overlays Nativa**: Utiliza el sistema de herencia de vistas de Bagisto 2.5 (`views_namespace: beauty` con fallback a `default`), garantizando 100% de compatibilidad con extensiones existentes de pago y checkout.

---

## Requisitos del Sistema

* **PHP**: `^8.4`
* **Bagisto**: `2.5.x`
* **Laravel**: `13.x`
* **Node.js**: `>= 20.x` (para compilar assets en desarrollo)

---

## Instalación en Producción (vía Packagist)

### 1. Requerir el paquete vía Composer
```bash
composer require wilbere/bagisto-beauty-theme
```

### 2. Publicar los assets precompilados
```bash
php artisan vendor:publish --tag=beauty-theme-assets
```

### 3. Activar el tema en el Panel de Administración
1. Dirígete a **Configuración > Canales** (Settings > Channels).
2. Edita tu canal activo.
3. En la sección **Temas** (Themes), selecciona **Beauty & Nails**.
4. Guarda los cambios y limpia la caché:
   ```bash
   php artisan optimize:clear
   ```

---

## Desarrollo Local con Podman / Docker

Si estás desarrollando el tema localmente junto a una instancia de Bagisto (como `/home/wilbere/workspace/demo`):

### 1. Mapeo de Volumen en `compose.yaml`
Añade el montaje del directorio local del tema dentro del contenedor con la etiqueta `:z` (obligatorio para SELinux Enforcing en Fedora):

```yaml
services:
  app:
    volumes:
      - .:/var/www/html:z
      - ../theme-bagisto:/var/www/html/packages/wilbere/beauty-theme:z
```

### 2. Registrar en Composer Local
Dado que Bagisto ya incluye `"url": "packages/*/*"` en su `composer.json`, el tema será detectado automáticamente. Solo ejecuta dentro del contenedor:
```bash
composer dump-autoload
php artisan optimize:clear
```

### 3. Compilación de Assets con Vite
Dentro de la carpeta del tema:
```bash
npm install
npm run dev     # Servidor de desarrollo con Hot Module Replacement (HMR)
npm run build   # Compilación para producción
```

---

## Licencia

Distribuido bajo la licencia [MIT](LICENSE).
