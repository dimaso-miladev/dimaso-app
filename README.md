# Dimaso - A Laravel & Vue.js Template

A modern, feature-rich boilerplate for building web applications using Laravel 8 and Vue.js. This project provides a solid foundation with authentication, social login, and a well-structured frontend architecture.

---

## Key Features

- **Backend:** Laravel 8
- **Frontend:** Vue.js with VueRouter, Vuex, and VueI18n
- **Authentication:** JWT-based authentication with login, registration, email verification, and password reset.
- **Social Login:** Pre-configured for socialite integration (GitHub example included).
- **Styling:** Bootstrap 5 and Font Awesome 5.
- **Structured Frontend:**
    - Dynamic page imports and custom layouts.
    - Centralized Axios configuration with interceptors for API requests.
    - Lazy-loading for i18n language files.
- **Development Environment:** Includes setup instructions for Project IDX, using NVM and SQLite for a simplified workflow.

---

## Installation & Setup

These instructions guide you through setting up the project, with a focus on the Project IDX environment.

### 1. Environment & Dependencies

**A. Setup Node.js using NVM (Node Version Manager)**

First, ensure you have the correct Node.js version.

```bash
# Install NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

# Activate NVM in your current terminal session
source ~/.bashrc

# Install and use Node.js version 18
nvm install 18
nvm use 18

# (Recommended) Set Node.js 18 as the default version
nvm alias default 18
```

**B. Install Backend & Frontend Dependencies**

```bash
# Install PHP packages
composer install

# Install JavaScript packages
npm install
```

### 2. Configure Application Environment

**A. Create `.env` File**

Copy the example environment file.

```bash
cp .env.example .env
```

**B. Generate Application Keys**

```bash
php artisan key:generate
php artisan jwt:secret
```

### 3. Configure Database (SQLite)

For ease of development, this guide uses SQLite.

**A. Update `.env` for SQLite**

Open your `.env` file and replace the `DB_*` variables for MySQL with the following single line:

```dotenv
DB_CONNECTION=sqlite
```

**B. Create the Database File**

```bash
touch database/database.sqlite
```

**C. Clear Configuration Cache**

To apply the `.env` changes, you must clear the config cache.

```bash
php artisan config:clear
```

### 4. Run Database Migrations

Set up the database schema by running the migration command.

```bash
php artisan migrate
```

---

## Usage

### Development

Run the Vite development server with hot-reloading.

```bash
npm run dev
```

### Production

Build the application for production.

```bash
npm run build
```

---

## Frontend Directory Structure (`resources/js`)

The frontend source is organized to be modular and scalable.

```
resources/js/
├── components/     # Shared Vue components (Card, Button, Navbar, etc.)
├── layouts/        # Page layouts
│   ├── default.vue # Layout for guests (not logged in)
│   ├── main.vue    # Main layout for authenticated users
│   └── admin.vue   # Layout for the admin dashboard area
├── pages/          # Application pages (lazy-loaded by the router)
│   ├── auth/       # Login, Register, Password Reset, etc.
│   ├── settings/   # User account settings pages
│   └── ...
├── router/
│   ├── routes.js   # Route definitions and lazy-loaded page imports
│   └── index.js    # Vue Router configuration, middleware, and loading bar
├── store/
│   ├── modules/    # Vuex store modules (e.g., auth, lang)
│   └── index.js    # Auto-importer for Vuex modules
├── plugins/
│   ├── axios.js    # Axios interceptors for Auth headers and error handling
│   ├── i18n.js     # i18n configuration with lazy-loaded language files
│   └── ...
├── lang/           # Language files (JSON format)
└── app.js          # Main Vue application entry point
```

---

## Frontend Conventions

### Layouts & Middleware

- The default application layout is `main.vue`.
- **Guest Pages:** For pages accessible to unauthenticated users, set `layout: 'default'` and `middleware: 'guest'`.
- **Authenticated Pages:** For pages that require a user to be logged in, set `layout: 'main'` and `middleware: 'auth'`.
- **Admin Pages:** Use `layout: 'admin'` and `middleware: 'auth'`. You can add more specific role-based middleware as needed.

**Example of a protected page:**
```javascript
export default {
  layout: 'main',
  middleware: 'auth',
  metaInfo () { return { title: 'Profile' } }
}
```

### Creating a New Page

1.  Create a new `.vue` file inside `resources/js/pages/`.
2.  Add a new route definition in `resources/js/router/routes.js` using the `page()` helper for lazy loading.
3.  Specify the appropriate `layout` and `middleware` in your new page component.

---

## Additional Features

### Socialite (Social Login)

The project includes a GitHub example for [Laravel Socialite](https://laravel.com/docs/8.x/socialite).

To enable it, create a GitHub application and set the "Authorization callback URL" to `https://your-domain.com/api/oauth/github/callback`.

Then, add your GitHub credentials to the `.env` file:
```dotenv
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
```

### Email Verification

To enable email verification, ensure your `App\User` model implements the `Illuminate\Contracts\Auth\MustVerifyEmail` contract.

---

## Testing

```bash
# Run unit and feature tests
vendor/bin/phpunit

# Run Dusk browser tests
php artisan dusk
```

## Changelog

Please see the [CHANGELOG](CHANGELOG.md) for information on recent changes.
