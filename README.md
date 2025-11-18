# FitForge (Laravel-inspired Fitness Platform)

This repository contiene un MVP offline inspirado en Laravel 11 que replica las funciones clave solicitadas: autenticación con roles, base de datos de ejercicios con videos, generador de entrenamientos, rutinas personalizadas, sesiones en vivo, histórico tipo GitHub, estadísticas con Chart.js, modos free/premium y panel administrativo.

> **Nota**: Debido a las restricciones sin conexión, se implementó un micro framework PHP compatible con PHP 8.2+ y la misma estructura de carpetas de Laravel. Los controladores, servicios y modelos se comportan igual que en Laravel, pero persisten en archivos JSON para permitir pruebas locales sin MySQL. Puedes migrar fácilmente la lógica a una instalación completa de Laravel copiando los directorios `app`, `resources`, `routes` y las migraciones incluidas.

## 🚀 Instalación rápida

```bash
php -S localhost:8000 -t public
```

Esto inicia el servidor embebido de PHP y expone la plataforma en `http://localhost:8000`.

### Semillas

```bash
php database/seeders/DatabaseSeeder.php
```

El seeder crea:
- Usuario admin (`admin@example.com` / `password`)
- Coach (`coach@example.com` / `password`)
- Usuario premium (`user@example.com` / `password`)
- 20 ejercicios con video/thumbnail
- Programa global Push/Pull/Legs
- Sesión y progreso de ejemplo

## 📂 Estructura

- `app/Models` – Modelos (User, Exercise, WorkoutProgram, WorkoutSession, etc.)
- `app/Http/Controllers` – Controladores UI + API
- `app/Services` – Servicios (generador, rutinas, progreso)
- `app/Policies` – Policies de acceso
- `routes/web.php` / `routes/api.php` – Definición de rutas
- `resources/views` – Blade templates + componentes Tailwind/Alpine
- `database/migrations` – Migraciones Laravel-ready
- `database/seeders` – Semillas JSON/offline
- `public/index.php` – Front controller + router mínimo

## 🧪 Funcionalidades destacadas

- **Auth avanzado**: registro/login/logout, roles (`user`, `coach`, `admin`), campos extendidos (peso, altura, objetivos, plan).
- **Ejercicios**: filtros por músculo/equipo/dificultad, detalle con video incrustado y CTA para rutinas.
- **Generador automático**: pasos guiados por equipo + músculo + tamaño; devuelve DTO con sets/reps.
- **Rutinas personalizadas**: CRUD básico, duplicado y activación de rutina actual.
- **Sesiones en vivo**: lista de sesiones, progreso de ejercicios e intensidad.
- **Historial GitHub-like + Progress charts**: grid de contribuciones y gráfico de peso (Chart.js).
- **Planes Free vs Premium**: middleware `CheckPremium` y campo `premium_until` listo para extender.
- **Panel Admin/Coach**: KPIs, CRUD rápido de ejercicios y vista de usuarios/programas.

## 🔧 Variables `.env`

Duplicar `.env.example` → `.env` y ajustar las variables si migras a MySQL real.

```
APP_NAME=FitForge
APP_ENV=local
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=fitforge
DB_USERNAME=root
DB_PASSWORD=secret
```

## 🧰 Scripts útiles

- `php -S localhost:8000 -t public` – Levanta el servidor.
- `php database/seeders/DatabaseSeeder.php` – Rellena datos demo.

## ✅ Próximos pasos sugeridos

1. Conectar un motor MySQL/MariaDB real y ejecutar las migraciones incluidas.
2. Sustituir el router mínimo por Laravel 11 una vez tengas Composer online.
3. Integrar Laravel Breeze / Sanctum para auth completa.
4. Añadir almacenamiento de archivos para thumbnails y videos propios.

Con esta base puedes seguir extendiendo el MVP siguiendo el diseño propuesto sin depender de internet.
