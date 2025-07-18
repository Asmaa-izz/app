# ⚡ Laravel + Inertia.js + Vue 3 Starter Kit

A modern full-stack starter kit using:

---

## 🧱 Tech Stack

- **Laravel 12** (PHP ^8.3)
- **Vue 3**
- **Inertia.js**
- **Vite**
- **TailwindCSS**
- **Pinia**
- **RTL Support**
- **shadcn vue**
- **Sanctum Auth**
- **Pest for testing**

---

## 📦 Laravel Packages

- `laravel/jetstream` (with Inertia)
- `spatie/laravel-permission`
- `spatie/laravel-activitylog`
- `tightenco/ziggy`
- `laravel/sanctum`
- `laravel/pint`
- `laravel/pail`

---

## 📦 Frontend Packages

- `@inertiajs/vue3`
- `@tailwindcss/forms`
- `@tailwindcss/vite`
- `tailwindcss-animate`
- `tailwindcss-rtl`
- `vue-i18n`
- `pinia`
- `axios`
- `lucide-vue-next`
- `@vueuse/core`
- `reka-ui`
- `clsx`, `tailwind-merge`, `class-variance-authority`

---

## 📁 Ready for:

- Role & Permission system
- Activity Logging
- RTL + i18n
- SPA with Inertia
- Clean Tailwind UI

---

## 🚀 Installation

Follow the steps below to set up the project locally:

```bash
# 1. Clone the repository
git clone git@github.com:Asmaa-izz/app.git
cd your-repo

# 2. Install PHP dependencies
composer install

# 3. Copy and configure your environment
cp .env.example .env
php artisan key:generate

# 4. Install JavaScript dependencies
npm install && npm run dev

# 5. Run database migrations
php artisan migrate

# 6. Start the local server
php artisan serve
