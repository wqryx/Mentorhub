# Scripts de Utilidad para MentorHub

Este directorio contiene scripts de utilidad para la plataforma MentorHub, organizados por categoría para facilitar su uso y mantenimiento.

## Estructura de Carpetas

- **database/**: Scripts relacionados con la base de datos (verificación, configuración, pruebas)
- **migrations/**: Scripts para ejecutar y gestionar migraciones
- **utils/**: Scripts de utilidad general (usuarios, configuración, reinicio)

## Scripts de Base de Datos (`database/`)

| Script | Descripción |
|--------|-------------|
| `check-db.php` | Verifica la conexión a la base de datos y lista las tablas existentes |
| `check_db_config.php` | Verifica la configuración de la base de datos en el archivo .env |
| `check_connection.php` | Comprueba si se puede establecer conexión con la base de datos |
| `check-tables.php` / `check_tables.php` | Verifica la existencia de tablas específicas en la base de datos |
| `check_x_db.php` | Verifica la conexión a una base de datos específica |
| `database_test.php` | Realiza pruebas básicas en la base de datos |
| `fix_database.php` | Corrige problemas comunes en la base de datos |
| `setup-database.php` / `setup_mentohub_db.php` | Configura la base de datos desde cero |
| `update-db-config.php` / `update_db_config.php` | Actualiza la configuración de la base de datos |
| `create-cache-table.php` | Crea la tabla de caché para Laravel |
| `create-mentor-availabilities-table.php` | Crea la tabla de disponibilidad de mentores |
| `simple-db-check.php` | Realiza una verificación simple de la base de datos |

## Scripts de Migraciones (`migrations/`)

| Script | Descripción |
|--------|-------------|
| `fix-migrations.php` | Corrige problemas con las migraciones |
| `migrate-step-by-step.php` | Ejecuta las migraciones paso a paso, permitiendo verificar cada una |
| `reset_migrations.php` | Reinicia todas las migraciones |
| `run-migrations-fixed.php` | Versión mejorada del script de migraciones |
| `run-migrations.php` | Ejecuta todas las migraciones pendientes |
| `run_specific_migrations.php` | Ejecuta migraciones específicas seleccionadas por el usuario |

## Scripts de Utilidad (`utils/`)

| Script | Descripción |
|--------|-------------|
| `check-mentor-profiles.php` | Verifica los perfiles de mentores en la base de datos |
| `check-users.php` | Verifica la información de usuarios en la base de datos |
| `list_users.php` | Lista todos los usuarios registrados en la plataforma |
| `simple_users.php` | Muestra una lista simple de usuarios (ID, nombre, email) |
| `verify-users.php` | Verifica la integridad de los datos de usuarios |
| `final_reset.php` | Reinicia completamente la aplicación (¡usar con precaución!) |
| `fix_env.php` | Corrige problemas en el archivo .env |
| `simple_reset.php` | Realiza un reinicio básico de la aplicación |

## Recomendaciones de Uso

1. **Antes de usar cualquier script**:
   - Haga una copia de seguridad de la base de datos
   - Verifique que el script es el adecuado para su tarea
   - Revise el código para entender qué hace exactamente

2. **Scripts de reinicio**:
   - Usar con extrema precaución
   - Preferiblemente en entornos de desarrollo, nunca en producción sin respaldo

3. **Scripts de migración**:
   - `migrate-step-by-step.php` es recomendado para depuración
   - `run-migrations-fixed.php` es la versión más estable para uso general

4. **Scripts de base de datos**:
   - `check_db.php` es el más completo para verificación
   - `setup-database.php` es recomendado para configuración inicial

## Notas Importantes

- Algunos scripts pueden estar duplicados con funcionalidad similar pero diferente implementación
- Se recomienda usar los scripts más completos y actualizados
- Evite modificar estos scripts a menos que entienda completamente su funcionamiento
