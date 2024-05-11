Step 1
install PHP

Step2
安裝 composer

Step3
複製 evn 檔案
cp .env.example .env

Step4
php.ini unComment listing extension below

extension=fileinfo
extension=gd
extension=zip
extension=intl

Step5
composer update

Step6(not confirm)
server.php change index.php path

require_once **DIR**.'/index.php';

Step7
php artisan key:generate

Step8
php artisan migrate

Step9
php artisan db:seed

Step10
php artisan serve
