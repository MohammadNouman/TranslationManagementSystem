# TranslationManagementSystem
Kindly run these commands one by one
composer install
php artisan migrate
php artisan db:seed
This is the login user email & password 
email: test@example.com 
password: password123

These are the endpoint you have to hit 

{{local_url}}/api/login
{{local_url}}/api/translations     //get
{{local_url}}/api/translations            //post 
{{local_url}}/api/translations/{id}
{{local_url}}/api/translations/search
{{local_url}}/api/translations/export