# MentorHub

Plataforma de mentoría educativa que conecta a estudiantes con mentores especializados para un aprendizaje personalizado y efectivo.

## 🚀 Características principales

- **Tres roles de usuario**: Administradores, Mentores y Estudiantes
- **Gestión de cursos**: Creación y administración de contenido educativo
- **Sesiones de mentoría**: Programación y seguimiento de sesiones individuales
- **Seguimiento de progreso**: Herramientas para monitorear el avance de los estudiantes
- **Recursos educativos**: Material complementario para el aprendizaje

## 🛠️ Tecnologías utilizadas

- **Backend**: Laravel 9
- **Frontend**: Tailwind CSS, Alpine.js
- **Base de datos**: MySQL
- **Autenticación**: Laravel Breeze
- **Despliegue**: Preparado para entornos de producción

## 📋 Requisitos del sistema

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL 5.7+

## 🚀 Instalación

1. Clonar el repositorio
2. Instalar dependencias de PHP:
   ```bash
   composer install
   ```
3. Instalar dependencias de Node.js:
   ```bash
   npm install
   ```
4. Configurar el archivo .env
5. Generar clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
6. Ejecutar migraciones:
   ```bash
   php artisan migrate --seed
   ```
7. Compilar assets:
   ```bash
   npm run build
   ```

## 🔐 Credenciales por defecto

- **Administrador**: admin@mentorhub.com / Admin123!
- **Mentor**: mentor@mentorhub.com / Mentor123!
- **Estudiante**: student@mentorhub.com / Student123!

## 📄 Licencia

Este proyecto está bajo la [Licencia MIT](LICENSE).

---

Desarrollado con ❤️ por el equipo de MentorHub**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
