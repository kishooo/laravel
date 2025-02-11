<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

#1- Composer i<br>
#2- cp .env.example .env<br>
#3- php artisan key:generate
#4- Set Up Database
    Check .env file settings:
    Edit the .env file and update your database configuration:
    
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=root
    DB_PASSWORD=your_database_password

#5- php artisan migrate
#6- php artisan serve
