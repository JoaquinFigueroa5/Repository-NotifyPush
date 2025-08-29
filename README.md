# 🔔 Repository-NotifyPush

<div align="center">

![Push Notifications](https://img.shields.io/badge/Push%20Notifications-Active-brightgreen?style=for-the-badge&logo=bell)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Web Push](https://img.shields.io/badge/Web%20Push-Protocol-blue?style=for-the-badge&logo=google-chrome)

**Sistema avanzado de notificaciones push que funciona tanto en primer plano como en segundo plano**

[🚀 Instalación](#-instalación) • [⚙️ Configuración](#️-configuración) • [📖 Documentación](#-documentación)
</div>

---

## 📋 Tabla de Contenidos

- [✨ Características](#-características)
- [🔧 Prerequisitos](#-prerequisitos)
- [🚀 Instalación](#-instalación)
- [⚙️ Configuración](#️-configuración)
- [🔑 Generación de VAPID Keys](#-generación-de-vapid-keys)
- [📱 Uso](#-uso)
- [🛠️ Solución de Problemas](#️-solución-de-problemas)
- [📄 Licencia](#-licencia)

---

## ✨ Características

- 🌟 **Notificaciones en tiempo real** - Funciona en primer y segundo plano
- 🔒 **Seguridad avanzada** - Implementación completa del protocolo Web Push
- 🎯 **Cross-platform** - Compatible con todos los navegadores modernos
- ⚡ **Alto rendimiento** - Optimizado para aplicaciones de gran escala
- 🔧 **Fácil configuración** - Setup automatizado con scripts incluidos

---

## 🔧 Prerequisitos

Antes de comenzar, asegúrate de tener instalado:

| Requisito | Versión | Descripción |
|-----------|---------|-------------|
| PHP | >= 7.4 | Lenguaje de programación |
| OpenSSL | >= 1.1.1 | Para generación de claves VAPID |
| Composer | Latest | Gestor de dependencias |

---

## 🚀 Instalación

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/JoaquinFigueroa5/Repository-NotifyPush
cd Repository-NotifyPush
```

### 2️⃣ Instalar dependencias

```bash
composer install
composer require minishlink/web-push
composer require php-amqplib/php-amqplib
```

---

## ⚙️ Configuración

### 🔐 Instalación de OpenSSL

> **⚠️ Importante:** OpenSSL es fundamental para la generación de claves VAPID

#### Para Windows:

1. **Descargar OpenSSL:**
   - Visita: [https://slproweb.com/products/Win32OpenSSL.html](https://slproweb.com/products/Win32OpenSSL.html)
   - Descarga la versión recomendada para tu sistema

2. **Configurar variables de entorno:**
   ```
   Variable: OPENSSL_CONF
   Valor: C:\OpenSSL-Win64\bin\openssl.cfg
   ```
   
   <div align="center">
   <img src="/imagesReadme/image2.png" alt="Configuración de variables de entorno" width="500"/>
   </div>

3. **Verificar instalación:**
   ```bash
   openssl -v
   ```
   
   ✅ **Salida esperada:** `OpenSSL 1.1.1x`

### 📜 Configuración de certificados SSL

1. **Descargar cacert.pem:**
   ```bash
   curl -o cacert.pem https://curl.se/ca/cacert.pem
   ```

2. **Ubicar archivo:**
   - Coloca `cacert.pem` en una carpeta segura
   - Recomendado: Misma carpeta de instalación de PHP

3. **Configurar php.ini:**
   
   Localiza y actualiza las siguientes líneas:
   ```ini
   ; Configuración SSL/TLS
   curl.cainfo = "C:\path\to\cacert.pem"
   openssl.cafile = "C:\path\to\cacert.pem"
   ```
   
   <div align="center">
   <img src="/imagesReadme/image.png" alt="Configuración PHP.ini" width="600"/>
   </div>

---

## 🔑 Generación de VAPID Keys

Una vez completada la configuración, genera tus claves VAPID:

```bash
php generateKeys.php
```

### 🎉 ¡Salida exitosa!

```
🔑 VAPID Keys generadas exitosamente:

📋 Public Key:  BEl62iUYgUivxIkv69yViEuiBIa6Ixi...
🔐 Private Key: aUWqagazNjr0BqXaS3Y2EmSKDs...

```

---

## 📱 Uso

### 🔧 Configuración básica

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Cargar claves VAPID
$keys = VapidKeys::load();

// Configurar cliente Web Push
$auth = [
    'VAPID' => [
        'subject'    => 'mailto:tu-email@gmail.com',
        'publicKey'  => 'CLAVE_PUBLICA_GENERADA',
        'privateKey' => 'CLAVE_PRIVADA_GENERADA',
    ],
];

$webPush = new WebPush($auth);

$webPush->queueNotification(
    $subscription,
    json_encode([
        'title' => 'Hola 👋',
        'body'  => '¡Notificación push individual!',
    ])
);
```

### 📤 Enviar notificación

```php
// Enviar notificación push
self.addEventListener('push', e => {
    const data = e.data.json();
    self.registration.showNotification(data.title, {
        body: data.body,
        icon: 'https://cdn-icons-png.flaticon.com/512/1827/1827370.png'
    });
});

```

---

## 🛠️ Solución de Problemas

<details>
<summary><strong>❌ Error: "openssl no reconocido como comando"</strong></summary>

**Causa:** OpenSSL no está instalado o no está en el PATH del sistema.

**Solución:**
1. Reinstalar OpenSSL siguiendo la [guía de instalación](#-instalación-de-openssl)
2. Verificar variables de entorno
3. Reiniciar terminal/símbolo del sistema

</details>

<details>
<summary><strong>❌ Error: "SSL certificate problem"</strong></summary>

**Causa:** Certificados SSL no configurados correctamente.

**Solución:**
1. Descargar `cacert.pem` actualizado
2. Verificar rutas en `php.ini`
3. Reiniciar servidor web

</details>

<details>
<summary><strong>❌ Error: "VAPID keys generation failed"</strong></summary>

**Causa:** Problemas con la configuración de OpenSSL.

**Solución:**
1. Verificar `openssl version`
2. Comprobar permisos de escritura
3. Ejecutar como administrador si es necesario

</details>

---

## 📊 Estado del Proyecto

| Característica | Estado | Versión |
|----------------|--------|---------|
| Notificaciones Web Push | ✅ Completo | 1.1.0 |
| Soporte multinavegador | ✅ Completo (Parcial) | 1.1.0 |
| Documentación | ✅ Completo | 1.0.0 |
| Testing automatizado | 🔝 A futuro | 0.0.0 |
| Dashboard admin | 🔝 A futuro | 0.0.0 |

---
### 🚀 Proceso de contribución

1. Fork el proyecto
2. Crea tu feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push al branch (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE.md](LICENSE.md) para más detalles.

---

<div align="center">

**⭐ Si este proyecto te ha sido útil, !dame una una estrella!**

![Visitors](https://visitor-badge.laobi.icu/badge?page_id=JoaquinFigueroa5.Repository-NotifyPush)
![Stars](https://img.shields.io/github/stars/JoaquinFigueroa5/Repository-NotifyPush?style=social)
![Forks](https://img.shields.io/github/forks/JoaquinFigueroa5/Repository-NotifyPush?style=social)

---

Hecho con ❤️ por [Joaki](https://github.com/JoaquinFigueroa5)

</div>