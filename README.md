# Book Borrowing Management System

## 🛠 Prerequisites
- PHP
- Laravel
- Composer
- MySQL
- Node.js & npm

## 🚀 Installation Steps

1. Clone the Repository
```bash
git clone git@github.com:Mohamed-G2021/Book-borrowing-system.git

cd Book-borrowing-system
```

2. Install PHP Dependencies
```bash
composer install
```

3. Install Node.js Dependencies
```bash
npm install
npm run build
```

4. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

5. Setup Database
- Create a MySQL database
- Update `.env` with your database credentials
```bash
php artisan migrate:fresh --seed
```

6. Start Development Server
```bash
php artisan serve
```

## 🔐 Default Credentials

### Admin
- Email: `admin@gmail.com`
- Password: `admin123`

### User
- Email: `user@gmail.com`
- Password: `user123`

## 📦 Features
- Book Management
- User Authentication
- Borrowing System
- PDF Export
