Download repozitory:
git clone https://github.com/JaroslavMalik/test_rest.git

Setup DATABASE_URL propety in .env file

run: 
composer instal

then:
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

run project:
php bin/console server:run