# Laravel React Template

This Laravel React template is a great starting point for building modern, scalable web applications. It uses TypeScript for type safety, Sass for CSS pre-processing, and Tailwind CSS for utility-first CSS.

**The template is pre-configured with everything you need to get started, including:**

- A Laravel backend with a basic API
- A React frontend with a simple homepage
- TypeScript support for type safety
- Spatie for roles and permissions [Visit documentation](https://spatie.be/docs/laravel-permission/v5/introduction)
- Sass support for CSS pre-processing
- Tailwind CSS support for utility-first CSS
- Vitest support for valid ui to avoid any bugs

**NOTES if you using any linux operation system please write those commands (TO AVOID ANY ERRORS)**
```shell
sudo apt-get install php8.2-dom
composer update
```

**To use the template, simply clone the repository and run the following commands:**
```shell
composer install
npm install
npm start
```

**Additional commands:**
```shell
# test react pages using typescript.
npm run test:ts

# test react pages & components with vitest
npm run test
npm run test:coverage

# build front end files.
npm run build
```

**This template is perfect for building a wide variety of web applications, including:**

- E-commerce websites
- Social media platforms
- Content management systems
- SaaS applications
- And more!

**Additional features and benefits:**

- Easy to use
- Scalable
- Secure
- Well-supported

If you are looking for a complete and feature-rich Laravel React template with TypeScript, Sass, and Tailwind CSS support, then this is the template for you.

## Docker Setup

The project includes a Docker environment that provisions PHP-FPM, Nginx, MySQL, Redis, and a Node-based Vite development server.

### Prerequisites

- Docker Engine 24+
- Docker Compose (v2 plugin or standalone)

### First-Time Setup

```bash
cp .env.example .env
docker compose up --build -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

The React development server runs inside the `frontend` container (available at http://dimaso-app:5173). The Laravel backend is served through Nginx at http://dimaso-app.

### Useful Commands

```bash
# Run PHPUnit tests
docker compose exec app php artisan test

# Install additional npm packages
docker compose exec frontend npm install <package-name>

# Stop and remove containers, networks, volumes
docker compose down
```
