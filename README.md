<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## Shift Task Backend (Laravel 11)

This is the backend application for a **Shift Task Board**, similar to Jira. It allows users to register, log in, and manage tasks. After authentication, users can:

- Create tasks
- View/fetch tasks
- Update tasks
- Delete tasks
- Log in and log out

### 🔐 Authentication

This application uses **JWT (JSON Web Token)** for authentication. Only verified users can perform CRUD operations on tasks.

---

## 🚀 Tech Stack

- Laravel 11 (API Development)
- MySQL
- JWT Authentication

---

## 🛠️ Installation Instructions

1. **Clone the Repository**

```bash
git clone https://github.com/ramsharan0230/shift-task-backend.git
cd shift-task-backend

-- Log in to MySQL
mysql -u root -p

-- Or if using Vagrant
mysql -u homestead -psecret

-- Then create the database
CREATE DATABASE board_dbase;
\q


## 🛠️ Set Up Environment
cp .env.example .env

composer install

Generate JWT Secret
php artisan jwt:secret
JWT_SECRET=your_generated_secret_here
JWT_TTL=60
JWT_REFRESH_TTL=20160

## Generate Application Key
php artisan key:generate


## Run Migrations
php artisan migrate


## Optimize Autoloader
composer dump-autoload

## Contact
For any questions or suggestions, feel free to reach out via GitHub issues or email.

##  License

---
Let me know if you want to include usage examples (like API routes), postman collection links, or screenshots too!
