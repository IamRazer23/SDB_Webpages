#!/bin/sh
set -e

cd /var/www/html

echo "==> SD Biosensor Panamá — inicializando contenedor de aplicación"

# 1. Dependencias de Composer
if [ ! -d vendor ]; then
    echo "==> Instalando dependencias de Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 2. Archivo de entorno
if [ ! -f .env ]; then
    echo "==> Creando .env desde .env.example..."
    cp .env.example .env
fi

# 3. Clave de aplicación
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "==> Generando APP_KEY..."
    php artisan key:generate --force
fi

# 4. Esperar a que MySQL esté disponible
echo "==> Esperando a la base de datos (${DB_HOST}:${DB_PORT})..."
until php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}'); exit(0); } catch (\Throwable \$e) { exit(1); }" 2>/dev/null; do
    echo "   ...la base de datos aún no responde, reintentando en 3s"
    sleep 3
done
echo "==> Base de datos disponible."

# 5. Migraciones (idempotentes)
echo "==> Ejecutando migraciones..."
php artisan migrate --force

# 6. Seeders — solo si la base aún no tiene datos sembrados
SEED_COUNT=$(php -r "try { \$p = new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo \$p->query('SELECT COUNT(*) FROM product_categories')->fetchColumn(); } catch (\Throwable \$e) { echo 0; }")
if [ "$SEED_COUNT" = "0" ]; then
    echo "==> Base vacía: ejecutando seeders..."
    php artisan db:seed --force
else
    echo "==> La base ya contiene datos (${SEED_COUNT} categorías). Se omiten los seeders."
fi

# 7. Enlace simbólico de storage
php artisan storage:link 2>/dev/null || true

# 8. Permisos de escritura (entorno de desarrollo)
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

echo "==> Inicialización completa. Arrancando: $*"

exec "$@"
