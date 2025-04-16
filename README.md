# SeatLookFor 🎭🎟️

## Descripción 📌

SeatLookFor es una aplicación que permite a los usuarios subir imágenes de asientos en teatros, estadios y otros espacios de eventos con asientos numerados. Así, cualquier persona podrá conocer la vista real desde un asiento antes de comprar su entrada, asegurándose de que obtiene la mejor experiencia sin sorpresas. Además, la plataforma permite redirigir a los usuarios a los sitios oficiales para comprar entradas.

## Objetivos principales ✨

- 📷 **Subida de fotos**: Los usuarios pueden subir imágenes de sus asientos.
- ⭐ **Valoraciones**: Se pueden puntuar y comentar los asientos y los lugares.
- 🎭 **Eventos y espectáculos**: Listado de eventos próximos en cada lugar.
- 🎟️ **Compra de entradas**: Redirección a plataformas oficiales de venta de tickets.
- 📍 **Explora lugares**: Encuentra teatros, conciertos y más.

## Tecnologías utilizadas 🛠️

- **Frontend**: Angular
- **Backend**: Laravel
- **Base de datos**: MariaDB
- **Despliegue**: AWS


## Equipo 👥

- **Francisco Jiménez López**
- **Antonio J. Heredia Leiva**

## Anteproyecto 📄
Puedes consultar nuestro anteproyecto en Notion en el siguiente enlace:
[TFG - SeatLookFor](https://branched-juniper-ded.notion.site/TFG-1b984cda3c97803dbb8dd31a2e6bb895)

##Datos instalacion

# 🚀 Laravel + Docker - Entorno de Desarrollo

Este proyecto está configurado para ejecutarse en un entorno de desarrollo local utilizando **Laravel**, con contenedores Docker para PHP, Nginx y MySQL. También se utiliza **npm** para gestionar y compilar los assets del frontend mediante **Vite**.

---

## 📥 Clonar el repositorio

Primero, clona el proyecto y accede al directorio:

```bash
git clone https://github.com/toniipower/SeatLookFor/


composer global require laravel/installer


npm install && npm run build

php artisan sail:install

./vendor/bin/sail up -d -->Debes  tener abierto docker
