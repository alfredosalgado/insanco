# Guía de Optimización SEO

Como agente MCP, tu tarea es optimizar el SEO de un sitio web generando los archivos `robots.txt`, `.htaccess`, `sitemap.xml` y las meta tags HTML, así como el JSON-LD de Schema.org. Debes seguir estrictamente los formatos base proporcionados, dejando todos los campos de información específica vacíos para que puedan ser rellenados manualmente.

## Tareas a Realizar

---

## 1. Generar `robots.txt`

### Instrucciones:

- Crea un archivo `robots.txt` siguiendo el formato general proporcionado
- Asegura que se permita el acceso a todos los `User-agent: *`
- Incluye las directivas `Allow:` y `Disallow:` para gestionar el rastreo de archivos y directorios sensibles o importantes
- Por defecto, puedes incluir los directorios `/enviar.php`, `/ignore/`, `/docs/`, `/instrucciones/`, `/imagenes/` en `Disallow:` y `/assets/`, `/assets/css/`, `/assets/js/`, `/assets/img/`, etc. en `Allow:`
- Añade User-agent específicos para Googlebot y Bingbot permitiendo el acceso
- Incluye una directiva `Crawl-delay: 1`
- Asegúrate de incluir la línea `Sitemap: URL_DEL_SITEMAP_XML`

### Formato Base para `robots.txt`:

```txt
# Robots.txt para [Nombre del Sitio]
# Este archivo complementa las meta tags robots en cada página

# Permitir acceso a todos los robots de búsqueda
User-agent: *
Allow: /

# Bloquear acceso a directorios administrativos y archivos sensibles
Disallow: /enviar.php
Disallow: /ignore/
Disallow: /docs/
Disallow: /instrucciones/
Disallow: /imagenes/

# Permitir acceso específico a recursos importantes
Allow: /assets/
Allow: /assets/css/
Allow: /assets/js/
Allow: /assets/img/

# Especificar la ubicación del sitemap
Sitemap: [URL_DEL_SITEMAP_XML]

# Configuración específica para motores de búsqueda principales
User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /

# Tiempo de espera entre solicitudes (en segundos)
Crawl-delay: 1
```


---

## 2. Generar `.htaccess`

### Instrucciones:

Crea un archivo `.htaccess` que incluya las secciones de Redirecciones, Seguridad básica, Rendimiento y SEO, siguiendo el formato actualizado proporcionado.

- **Redirecciones:** Habilita `RewriteEngine On`. Incluye una regla unificada para forzar HTTPS y www. Añade reglas para remover la extensión `.html` de las URLs visibles y redirigir peticiones internas sin `.html` al archivo real
- **Seguridad:** Incluye directivas para proteger archivos sensibles (`.htaccess`, `md|txt|log|ini|bak`) y añade headers de seguridad (`X-Content-Type-Options`, `X-Frame-Options`, etc.)
- **Rendimiento:** Activa la compresión GZIP con `mod_deflate` y habilita la caché del navegador con `mod_expires` para diferentes tipos de archivos, ajustado para un sitio estático
- **SEO:** Incluye una cabecera `X-Robots-Tag` para permitir indexación, define páginas de error 404 y 500 personalizadas, fuerza la codificación UTF-8, define los tipos MIME para evitar warnings, evita el listado de directorios y define el archivo de inicio

### Formato Base para `.htaccess` (Actualizado):
```apache
# ========================================
# .htaccess para [Nombre del Sitio] - Sitio Estático
# SEO • Seguridad • Rendimiento • Redirecciones
# ========================================

# Activar motor de reescritura
RewriteEngine On

# Forzar HTTPS + www en una sola regla (evita redirecciones dobles)
RewriteCond %{HTTPS} off [OR]
RewriteCond %{HTTP_HOST} !^www\.dominio\.cl$ [NC]
RewriteRule ^(.*)$ https://www.[dominio.cl]/$1 [R=301,L]

# Remover extensión .html de URLs visibles (SEO amigable)
RewriteCond %{THE_REQUEST} /([^.]+)\.html [NC]
RewriteRule ^ /%1 [NC,L,R=301]

# Redirigir peticiones internas sin .html al archivo real
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^.]+)$ $1.html [NC,L]

# ========================================
# Seguridad básica
# ========================================

# Proteger archivos sensibles
<Files ".htaccess">
    Order allow,deny
    Deny from all
</Files>

<FilesMatch "^(?!robots\.txt$).*\.(md|txt|log|ini|bak)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Headers de Seguridad para protección adicional
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "DENY"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# ========================================
# Rendimiento
# ========================================

# Activar compresión GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css application/xml application/xhtml+xml application/rss+xml application/javascript application/x-javascript
</IfModule>

# Habilitar caché navegador (ajustado para sitio estático)
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresDefault "access plus 2 days"
</IfModule>

# ========================================
# SEO
# ========================================

# Cabecera para permitir indexación de todo
<IfModule mod_headers.c>
    Header always set X-Robots-Tag "index, follow"
</IfModule>

# Página de error personalizada
ErrorDocument 404 /index.html
ErrorDocument 500 /500.html

# Forzar UTF-8 en todo
AddDefaultCharset UTF-8

# MIME Types (evita warnings en consola navegador)
AddType application/javascript .js
AddType text/css .css
AddType image/svg+xml .svg

# Evitar listado de archivos si no hay index
Options -Indexes

# Definir archivo de inicio
DirectoryIndex index.html index.htm
```

---

## 3. Generar `sitemap.xml`

### Instrucciones:

- Crea un archivo `sitemap.xml` siguiendo el formato XML proporcionado
- Incluye la estructura básica urlset con el esquema
- Añade una etiqueta `<url>` de ejemplo para la página principal (/)
- Para cualquier URL adicional, incluye una etiqueta `<url>` con los campos `<loc>`, `<lastmod>`, `<changefreq>` y `<priority>`
- Asegúrate de que estos campos también estén vacíos o con placeholders genéricos

### Formato Base para `sitemap.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- Página Principal -->
    <url>
        <loc></loc>
        <lastmod></lastmod>
        <changefreq></changefreq>
        <priority></priority>
    </url>

    <!-- Ejemplo de Página Adicional (comentado para dejar solo la estructura) -->
    <!--
    <url>
        <loc></loc>
        <lastmod></lastmod>
        <changefreq></changefreq>
        <priority></priority>
    </url>
    -->

</urlset>
```

---

## 4. Meta Tags y Datos Estructurados

### Instrucciones:

- Crea un bloque de código HTML que contenga las Meta Tags SEO, SEO Local, Open Graph, Twitter Card y el Structured Data (JSON-LD)
- Todos los campos de contenido (content, href, src, name, url, logo, etc.) deben dejarse completamente vacíos para ser rellenados
- La estructura debe ser idéntica a la proporcionada a continuación

### Formato Base para Meta Tags HTML y JSON-LD:

```html

<!-- SEO Meta Tags -->
<title></title>
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<meta name="robots" content="index, follow">
<link rel="canonical" href="">

<!-- SEO Local y Geolocalización -->
<meta name="geo.region" content=""> <!-- Ejemplo: CL-RM -->
<meta name="geo.placename" content=""> <!-- Ejemplo: Santiago -->
<meta name="geo.position" content=""> <!-- Ejemplo: -33.4489;-70.6693 -->
<meta name="ICBM" content=""> <!-- Ejemplo: -33.4489, -70.6693 -->
<meta name="contact" content=""> <!-- Ejemplo: email@dominio.cl -->
<meta name="coverage" content=""> <!-- Ejemplo: Santiago, Chile -->

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="">
<meta property="og:description" content="">
<meta property="og:image" content="">
<meta property="og:url" content="">
<meta property="og:type" content="website">
<meta property="og:site_name" content="">
<meta property="og:locale" content=""> <!-- Ejemplo: es_ES -->

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">

<!-- Structured Data (JSON-LD) - Schema.org para Negocio Local -->
<!-- Nota: Usar 'Organization' para entidades no locales o 'LocalBusiness' para negocios con ubicación física. -->
<script type="application/ld+json">
{
  "@context": "[https://schema.org](https://schema.org)",
  "@type": "LocalBusiness",
  "name": "",
  "description": "",
  "url": "",
  "logo": "",
  "telephone": "",
  "email": "",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "",
    "addressLocality": "",
    "addressRegion": "",
    "postalCode": "",
    "addressCountry": ""
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "",
    "contactType": "customer service",
    "email": ""
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "",
    "longitude": ""
  },
  "openingHours": "",
  "sameAs": [
    "",
    ""
  ],
  "areaServed": ""
}
</script>
```

---

## 5. Configuraciones Adicionales de SEO (en HTML)

### Instrucciones:

Configuraciones Adicionales (incluir en el `<head>` o cuerpo del HTML según corresponda):

#### Configuración del Favicon:
- Implementa las etiquetas de favicon en el `<head>` del HTML
- Asegúrate de que las rutas href sean relativas a la raíz del sitio (ej. /favicon.ico) para garantizar la máxima compatibilidad y facilitar la validación por parte de herramientas como Google y Lighthouse

#### Optimización de Etiquetas alt para Imágenes:
- Para cada imagen (`<img>`) en el contenido, añade una etiqueta alt descriptiva y concisa

#### Mejoras de Rendimiento y Accesibilidad:
- Añade preconnect para recursos externos
- Implementa un enlace "skip-link" para accesibilidad
- Asegúrate de que los enlaces externos y las imágenes decorativas sigan las mejores prácticas

### Formato Base para Configuraciones Adicionales:

```html

<!-- Favicon Completo para máxima compatibilidad -->
<link rel="apple-touch-icon" sizes="180x180" href="">
<link rel="icon" type="image/png" sizes="32x32" href="">
<link rel="icon" type="image/png" sizes="16x16" href="">
<link rel="icon" href="">

<!-- Mejoras de Rendimiento en HTML -->
<!-- Preconnect para acelerar la carga de recursos de dominios externos -->
<link rel="preconnect" href="[https://fonts.googleapis.com](https://fonts.googleapis.com)">
<link rel="preconnect" href="[https://fonts.gstatic.com](https://fonts.gstatic.com)" crossorigin>

<!-- Mejoras de Accesibilidad (WCAG) -->
<!-- Enlace para saltar al contenido principal (colocar justo después de la etiqueta <body>) -->
<a href="#main-content" class="skip-link">Saltar al contenido principal</a>

<!-- El contenido principal debe estar envuelto en una etiqueta <main> con el id correspondiente -->
<main id="main-content">
  <!-- Resto del contenido de la página -->
</main>

<!-- CSS recomendado para la accesibilidad (añadir en la hoja de estilos principal)

:focus {
    outline: 2px solid #005fcc; /* Usar un color de alto contraste */
    outline-offset: 2px;
}

.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: #000;
    color: #fff;
    padding: 8px;
    text-decoration: none;
    z-index: 1000;
    transition: top 0.3s;
}

.skip-link:focus {
    top: 6px;
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
-->

<!-- Ejemplo de enlace externo seguro -->
<a href="" target="_blank" rel="noopener noreferrer"></a>

<!-- Ejemplo de imagen decorativa accesible -->
<img src="" alt="" aria-hidden="true">
```
