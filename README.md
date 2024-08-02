Installation : 
Install Composer

Clone the repository: git clone (https://github.com/safaweb/GestionTicket.git)

Install PHP dependencies: composer install (or update)

Setup configuration: cp .env.example .env ( nom base cree & donner mailler ) 

Generate application key: php artisan key:generate

Create a database and update your configuration.( meme nom du .env)

Run database migration: php artisan migrate

Run database seeder: php artisan db:seed

Create a symlink to the storage: php artisan storage:link

Run npm : npm install vite laravel-vite-plugin

Run the dev server: php artisan serve & other terminal in same time : npm run dev 






Dummy Account
Super Admin
Email: superadmin@example.com
Password: 12345678

Chef Projet
Email: chefprojet@example.com
Password: 12345678

Employeur
Email: staffprojet@example.com
Password: 12345678

Exemple User
Email: user@example.com
Password: 12345678

