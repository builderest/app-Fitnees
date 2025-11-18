# FitForge

Plataforma full-stack construida con **Laravel 11**, TailwindCSS y Alpine para gestionar entrenamientos, rutinas, progreso y panel admin inspirado en Workout.cool.

## Requisitos
- PHP 8.2+
- Composer
- Node 18+
- MySQL/MariaDB

## Instalación
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install
npm run build # o npm run dev
```

Configura la base de datos y correo actualizando el archivo `.env` con los datos de Hostinger proporcionados.

## Comandos útiles
- `php artisan serve` para servir la aplicación.
- `php artisan migrate --seed` para crear tablas y datos demo.
- `npm run dev` para compilar assets con Vite.

## Funcionalidades
- Autenticación con registro, login, recuperación y verificación de correo.
- Roles `user`, `coach`, `admin` con políticas de acceso.
- Generador de entrenamientos por equipo/músculo.
- CRUD de rutinas y duplicación.
- Sesiones en vivo y registro de progreso.
- Estadísticas vía Chart.js (dataset listo) y API JSON para calendario.
- Panel Admin con métricas rápidas y alta de ejercicios.

## Estructura
- `app/Models` modelos Eloquent.
- `app/Http/Controllers` controladores web/API.
- `app/Services` lógica de dominio (generador, rutinas, progreso).
- `resources/views` Blade + componentes reutilizables.
- `routes/web.php` UI, `routes/api.php` endpoints autenticados.

¡Listo para desplegar en Hostinger ejecutando `php artisan config:cache` y `php artisan migrate --force`!
