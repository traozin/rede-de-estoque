echo "Aguardando MySQL iniciar..."
until nc -z -v -w30 $DB_HOST $DB_PORT; do
  echo "Aguardando conexão com o banco de dados..."
  sleep 2
done

if [ ! -f .env ]; then
  echo "Copiando .env.example para .env..."
  cp .env.example .env
fi

composer install

if ! grep -q "^JWT_SECRET=" .env; then
  echo "Gerando JWT_SECRET..."
  php artisan jwt:secret
fi

php artisan key:generate
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=8000