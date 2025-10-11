# Dimaso App Starter

Boilerplate cho ứng dụng nội bộ kết hợp **Laravel 10 + Inertia + React 18**. Codebase được chuẩn bị sẵn cho development theo mô hình monolith hiện đại: API Laravel phục vụ dữ liệu, frontend React render thông qua Inertia, build bằng Vite + Tailwind.

---

## 🔧 Tech Stack

- **Backend:** Laravel 10, PHP 8.1+, Sanctum (auth), Spatie Permission (RBAC), Ziggy (expose routes cho JS)
- **Frontend:** React 18 + TypeScript, Inertia.js, Vite, Tailwind CSS, Sass, Axios, Headless UI, Heroicons
- **Testing:** PHPUnit, Vitest + Testing Library + Jest DOM
- **DevOps:** Docker Compose (PHP-FPM, Nginx, Node/Vite, MySQL 8, Redis 7), Tailwind CLI, Laravel Pint (dev dependency)

---

## 🗂️ Cấu trúc chính

```
app/                     # Laravel application (Models, Providers, Http, Console, Exceptions)
resources/views/app.blade.php  # Inertia root template
resources/react/         # Frontend (App.tsx, Pages, Components, styles, types)
routes/                  # web.php (Inertia entry), api.php (Sanctum protected /user)
config/, database/, storage/, tests/  # Cấu hình chuẩn Laravel
docker/, docker-compose.yml  # Hạ tầng container hoá
```

- React pages đặt trong `resources/react/Pages`, components tái sử dụng trong `resources/react/Components`.
- Alias import: `@/` ⇒ `resources/react`, `~/` ⇒ `public` (`vite.config.ts` + `tsconfig.json`).
- Tailwind auto-scan `.ts/.tsx` và Blade (`tailwind.config.ts`).

---

## 🚀 Khởi tạo môi trường

### 1. Chuẩn bị

- PHP 8.1+ (gợi ý: brew/apt + ext php-dom, mbstring, gd, intl)
- Node 20+, npm 9+
- Composer 2+
- MySQL 8 / MariaDB 10.6+ (hoặc dùng Docker)

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate            # cập nhật DB trước khi chạy demo
npm run dev                    # chạy Vite (http://localhost:5173 nếu dùng artisan serve)
php artisan serve              # nếu không dùng Valet / Nginx riêng
```

> ⚠️ Spatie Permission yêu cầu bảng `roles`, `permissions`, … Chưa có migration trong repo → tạo migration riêng trước khi sử dụng trait `HasRoles`.

### 2. Frontend Dev Server

- `npm run dev` khởi động Vite (HMR). Laravel Vite plugin sẽ inject Inertia page tương ứng.
- `npm run build` tạo bundle production trong `public/build`.

### 3. Testing & QA

```bash
# Backend
php artisan test               # PHPUnit

# Frontend
npm run test                   # Vitest (jsdom)
npm run coverage               # Vitest + coverage
npm run test:ts                # TypeScript watcher (dev assist)
```

> Chạy `./vendor/bin/pint` để format code PHP (optional, cài sẵn dev dependency).

---

## 🐳 Docker Workflow

Project đã cấu hình Docker Compose để thống nhất môi trường.

```bash
cp .env.example .env
docker compose up --build -d                 # build tất cả services
docker compose exec dimaso-backend composer install
docker compose exec dimaso-backend php artisan key:generate
docker compose exec dimaso-backend php artisan migrate
docker compose logs -f                      # theo dõi log
```

- **Nginx:** http://localhost (proxy vào Laravel)
- **Vite dev server:** http://localhost:5173 (container `dimaso-frontend`)
- **MySQL:** localhost:3306 (credentials lấy từ `.env`)
- **Redis:** localhost:6379

Useful commands:

```bash
docker compose exec dimaso-backend php artisan test
docker compose exec dimaso-frontend npm install <package>
docker compose exec dimaso-frontend npm run test
docker compose down                         # stop & remove containers
```

> Lưu ý: `dimaso-frontend` đang chạy `npm install` mỗi lần container start, nên ưu tiên giữ container chạy hoặc tùy biến Dockerfile để cache node_modules.

---

## 🔄 Kiến trúc & Convention

- **Routing:** Laravel `routes/web.php` trả Inertia component `index`, frontend resolve bằng `createInertiaApp`. API route `/api/user` bảo vệ bằng Sanctum (`routes/api.php`).
- **State/Data flow:** React page nhận props từ controller (hiện chưa có controller custom). Axios đã cấu hình global CSRF headers (`resources/react/bootstrap.ts`).
- **Component pattern:** Function component (FC) + TypeScript, test được viết cạnh component (ví dụ `Components/Button/`).
- **Style:** Tailwind + Sass. `.scss` có thể mix `@apply` và lớp tuỳ chỉnh.
- **Testing:** Vitest (jsdom) + Testing Library; cleanup sau mỗi test (`button.test.tsx`). Backend có sample Feature test.

---

## 📝 Checklist khi mở rộng

1. **Migration & Seeders**
   - Thêm migration cho bảng roles/permissions nếu dùng Spatie.
   - Seed user mẫu bằng factory (`database/factories/UserFactory.php`).

2. **Routing**
   - Tạo controller thay cho closure khi luồng phức tạp.
   - Đồng bộ route name với Ziggy để frontend import.

3. **Frontend**
   - Hoàn thiện `resources/react/types/User.d.ts`.
   - Sử dụng layout component chung (`resources/react/Components/Layout/`) cho shell.
   - Sửa `vitest.workspace.ts` trỏ đúng `vite.config.ts` hoặc bỏ hẳn workspace entry chưa dùng.

4. **CI/CD (nếu cần)**
   - Thêm workflow chạy `composer install`, `npm ci`, `php artisan test`, `npm run test -- --run`.
   - Build Docker images dựa trên `docker/php/Dockerfile`.

---

## ❗ Known Issues & Notes

- `vitest.workspace.ts` hiện `extends: './vite.config.js'` nhưng file là `.ts` → cập nhật trước khi bật workspace mode.
- Chưa có migration cho Spatie Permission → trait `HasRoles` sẽ lỗi nếu truy cập chức năng roles/permissions trước khi tạo bảng.
- Home page chỉ là placeholder, cần thay bằng content thực tế.
- Không thấy commit convention hay lint script. Đề xuất tiêu chuẩn hoá (ví dụ Conventional Commits + Husky).

---

## 🤝 Quy trình làm việc gợi ý

1. Tạo branch từ `master`, `git checkout -b feature/<task>`.
2. Cài dependencies (composer/npm), chạy thử `php artisan serve` + `npm run dev`.
3. Viết tính năng kèm test (Vitest/PHPUnit).
4. Kiểm tra `php artisan test`, `npm run test`, `npm run build`.
5. Commit theo chuẩn nhóm, mở PR kèm mô tả, checklist migration/config liên quan.

Chào mừng bạn đến với Dimaso App Starter! Cứ follow quy trình trên và đừng quên cập nhật README khi thêm công cụ mới. 🚀
