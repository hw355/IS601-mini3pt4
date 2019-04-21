###### To run the IS601-mini3pt3 project:
1. git clone https://github.com/hw355/IS601-mini3pt3.git
2. CD into IS601-mini3pt3 and run: composer install
3. cp .env.example to .env
4. run: php artisan key:generate
5. setup database (with sqlite or other https://laravel.com/docs/5.6/database)
6. run: php artisan migrate
7. run: phpunit
8. run: php artisan migrate:refresh --seed
