<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Backend Rinku

Este proyecto es el backend de **Rinku**, desarrollado con **Laravel 12** (PHP 8.2, PostgreSQL 17). Proporciona una API REST para el cálculo de nómina mensual con roles (Chofer, Cargador, Auxiliar), coberturas, entregas, ISR 9/12 % y vales 4 % (solo internos).

## 🚀 Requisitos previos
- PHP 8.2 o superior
- Composer
- PostgreSQL 17 (o compatible)

## 📦 Instalación del proyecto

1. Clona el repositorio:

```bash
git clone https://github.com/TechMaster01/backend-rinku.git
cd backend-rinku
```

2. Instala las dependencias de PHP:

```bash
composer install
```

3. Crea la base de datos en PostgreSQL (desde psql o un cliente como DBeaver):

```sql
CREATE DATABASE Rinku;
```

4. Copia el archivo de entorno:

```bash
cp .env.example .env
```

5. Configura las variables de entorno en el archivo .env:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Asegúrate de colocar el nombre de la base de datos, tu usuario y contraseña de postgreSQL.

6. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

7. Ejecuta las migraciones:

```bash
php artisan migrate
```

8. Rueda los seeders para poblar la base de datos:

```bash
php artisan db:seed
```

🛠️ Comandos útiles

```bash
php artisan serve – Inicia el servidor de desarrollo en http://127.0.0.1:8000
```

🤝 Contribuciones

Se agradecen contribuciones, reportes de issues y sugerencias. ¡Adelante, todas son bienvenidas!


---

Hecho con ❤️ usando Laravel + PHP

---
