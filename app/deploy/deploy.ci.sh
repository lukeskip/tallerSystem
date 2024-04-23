#!/bin/sh -x

cp .env.ci .env
# /opt/cpanel/ea-php74/root/bin/php /opt/cpanel/composer/bin/composer install
/opt/cpanel/ea-php74/root/bin/php artisan down
/opt/cpanel/ea-php74/root/bin/php artisan migrate:fresh --seed -v
/opt/cpanel/ea-php74/root/bin/php artisan storage:link
/opt/cpanel/ea-php74/root/bin/php artisan config:clear
/opt/cpanel/ea-php74/root/bin/php artisan cache:clear
/opt/cpanel/ea-php74/root/bin/php artisan route:clear
/opt/cpanel/ea-php74/root/bin/php artisan view:clear
/opt/cpanel/ea-php74/root/bin/php artisan up