# BHB – Beach House Booking

Minimal slice using Laravel 12 + Livewire + Mail queues + (optional) Google Calendar.  
You can run it either with **Laravel Sail (Docker)** _or_ **without Docker**.

---

## 1) Quick start (with Laravel Sail / Docker)

### Prerequisites
- Docker Desktop (or compatible)
- Composer (for initial install if needed)

### 1. Boot containers
```bash
./vendor/bin/sail up -d
```

This starts **MySQL**, **Redis**, and **Mailpit**.

### 2. Install dependencies
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 3. Environment
```bash
cp .env.example .env
./vendor/bin/sail artisan key:generate
```

Minimal values (Sail uses container hostnames):
```
APP_NAME="BHB"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=bhb
DB_USERNAME=sail
DB_PASSWORD=password

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@bhb.local"
MAIL_FROM_NAME="BHB"
```

### 4. Migrate & seed
```bash
./vendor/bin/sail artisan migrate --seed
```

### 5. Build the assets
```bash
npm run dev # Or npm run build for static assets
```
App: `http://localhost:8000`

### 6. Run the **booking queue**
```bash
./vendor/bin/sail artisan queue:work --queue=booking-mail
```

### 7. Check outgoing mail
Mailpit UI: `http://localhost:8025`

---

## 2) Running **without Docker** (no Sail)

### Prerequisites
- PHP 8.3+ with extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml, curl
- Composer
- MySQL / MariaDB
- Node.js

### 1. Install dependencies
```bash
composer install
npm install
npm run build
```

### 2. Environment (no-redis)
```bash
cp .env.example .env
php artisan key:generate
```

Update:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bhb
DB_USERNAME=root
DB_PASSWORD=secret

QUEUE_CONNECTION=database
CACHE_STORE=file
SESSION_DRIVER=file

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@bhb.local"
MAIL_FROM_NAME="BHB"
```

### 3. Database & queue tables
```bash
php artisan migrate
php artisan queue:table
php artisan migrate
```

### 4. Seed sample data
```bash
php artisan db:seed --class=BookingSeeder
```

### 5. Serve the app
```bash
php artisan serve
```
App: `http://127.0.0.1:8000`

### 6. Run the **booking queue**
```bash
php artisan queue:work --queue=booking-mail,calendar,mail,default
```

---

## Common commands

- Clear caches:
```bash
php artisan optimize:clear
```
- Rebuild assets:
```bash
npm run dev
```

---

## Notes

- **Queues:** booking-mail (admin + guest mails), calendar (Google events), mail (other mails), default (rest).
- **Google Calendar:** leave env unset if not testing that part.
