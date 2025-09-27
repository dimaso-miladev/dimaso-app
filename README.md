# Dimaso Site

>  Dimaso
> @auhthor: Nguyen Binh

## Features

- Laravel 8
- Vue + VueRouter + Vuex + VueI18n + ESlint
- Pages with dynamic import and custom layouts
- Login, register, email verification and password reset
- Authentication with JWT
- Socialite integration
- Bootstrap 5 + Font Awesome 5

## Installation

- Edit `.env` and set your database connection details
- (When installed via git clone or download, run `php artisan key:generate` and `php artisan jwt:secret`)
- `php artisan migrate`
- `npm install`

## Usage

#### Development

```bash
npm run dev
```

#### Production

```bash
npm run build
```

## Cấu trúc `resources/js`

```
resources/js/
  components/            # Component dùng chung (Card, Button, Checkbox, Navbar, Loading...)
  layouts/               # Bố cục trang
    auth.vue             # Layout cho khách (chưa đăng nhập)
    main.vue             # Layout chính cho người dùng đã đăng nhập
    admin.vue            # Layout khu vực quản trị (dashboard)
  pages/                 # Các trang (lazy-load qua router)
    auth/                # Login, Register, Password reset, Email verification
    settings/            # Trang thiết lập tài khoản (yêu cầu đăng nhập)
    coreui/              # Nhóm trang admin (ví dụ: Dashboard)
    home.vue, welcome.vue
  router/
    routes.js            # Khai báo route và dynamic imports
    index.js             # Cấu hình Vue Router, middleware, loading bar
  store/
    modules/             # Vuex modules (auth, lang, ...)
    index.js             # Tự động nạp modules
    mutation-types.js
  plugins/
    axios.js             # Interceptors: Authorization, Accept-Language, lỗi 401/5xx
    i18n.js              # i18n và lazy-load messages theo locale
    fontawesome.js, index.js
  lang/                  # Tệp ngôn ngữ (được lazy-load)
  app.js                 # Entry khởi tạo Vue (store, router, i18n, plugins)
```

### Quy ước layout và middleware
- Mặc định app dùng layout `main` (file: `resources/js/components/App.vue`).
- Trang cho khách (chưa đăng nhập): đặt `layout: 'auth'`, `middleware: 'guest'`.
- Trang cho người dùng: đặt `layout: 'main'`, `middleware: 'auth'` khi cần bảo vệ.
- Khu vực quản trị: đặt `layout: 'admin'`, `middleware: 'auth'` (có thể bổ sung middleware phân quyền riêng).

Ví dụ (trang cần đăng nhập):

```js
export default {
  layout: 'main',
  middleware: 'auth',
  metaInfo () { return { title: 'Profile' } }
}
```

### Router và dynamic imports
- Khai báo route tại `resources/js/router/routes.js` và dùng helper `page(path)` để lazy-load trang.
- Middleware toàn cục: `locale`, `check-auth` được thiết lập trong `resources/js/router/index.js`.
- Nhóm admin có sẵn: `/admin` → ví dụ `/admin/dashboard`.

### Store và i18n
- Vuex modules tự động nạp từ `store/modules`. Module `auth` lưu token (cookie) và thông tin user.
- i18n dùng lazy-load message theo locale trong `plugins/i18n.js`.

### Plugins và Axios
- `plugins/axios.js` tự đính `Authorization` và `Accept-Language`, xử lý 401 (hết hạn token) và 5xx (hiển thị thông báo thân thiện).

### Component dùng chung
- Đăng ký global tại `resources/js/components/index.js` (Card, Child, Button, Checkbox, vform components...).
- Navbar hiển thị trạng thái đăng nhập và liên kết tới trang thiết lập, đăng xuất.

### Thêm trang mới nhanh
1) Tạo file trong `resources/js/pages/...`.
2) Thêm route trong `resources/js/router/routes.js` qua `page('path/to.vue')`.
3) Chỉ định `layout` và `middleware` phù hợp (xem quy ước ở trên).

## Socialite

This project comes with GitHub as an example for [Laravel Socialite](https://laravel.com/docs/5.8/socialite).

To enable the provider create a new GitHub application and use `https://example.com/api/oauth/github/callback` as the Authorization callback URL.

Edit `.env` and set `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET` with the keys form your GitHub application.

For other providers you may need to set the appropriate keys in `config/services.php` and redirect url in `OAuthController.php`.

## Email Verification

To enable email verification make sure that your `App\User` model implements the `Illuminate\Contracts\Auth\MustVerifyEmail` contract.

## Testing

```bash
# Run unit and feature tests
vendor/bin/phpunit

# Run Dusk browser tests
php artisan dusk
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Setup if your codding in idx workspace

### Part 1: Setting up NVM (Node Version Manager)

NVM allows you to install and switch between different Node.js versions. This is crucial for ensuring all team members use the same Node.js version for frontend asset compilation and related tasks.

**Step 1: Open Terminal**

In the Project IDX interface, open the Terminal window (usually located at the bottom of the screen).

**Step 2: Run the NVM Installation Script**

Paste and run the following command in the terminal to download and install NVM.

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
```

**Step 3: Activate NVM**

After installation, you need to reload your terminal configuration so that the `nvm` command is available.

```bash
source ~/.bashrc
```

**Step 4: Verify Installation**

To confirm NVM was installed successfully, run:

```bash
nvm --version
```

If you see a version number (e.g., 0.39.7) displayed, you have succeeded.

**Step 5: Install and Use the Project's Node.js Version**

We will use Node.js version 18 for this project.

Install Node.js version 18:

```bash
nvm install 18
```

Use the newly installed version:

```bash
nvm use 18
```

**(Recommended)** Set version 18 as the default so you don't need to re-run `nvm use` every time you open a new terminal:

```bash
nvm alias default 18
```

### Part 2: Configuring Laravel to Use SQLite

To simplify the development process, we will use SQLite instead of MySQL. SQLite does not require a separate database server and stores all data in a single file within the project.

**Step 1: Edit the `.env` File**

Open the `.env` file at the root of your project.
Find the database configuration lines for MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Delete or comment out these lines and replace them with the configuration for SQLite:

```dotenv
DB_CONNECTION=sqlite
```

**Step 2: Create the SQLite Database File**

Laravel needs a file to work with. Create this file in the terminal:

```bash
touch database/database.sqlite
```

This command will create an empty file named `database.sqlite` in the `database/` directory.

**Step 3: Clear Configuration Cache (Important Step)**

After changing the `.env` file, you must clear the configuration cache for Laravel to recognize the changes.

```bash
php artisan config:clear
```

### Part 3: Completing Installation and Running the Project

Your environment is now almost ready. Perform the final steps.

**Step 1: Install Dependencies**

```bash
# Install Composer packages (backend)
composer install

# Install NPM packages (frontend)
npm install
```
cmd_connect_mysql_host: mysql -h IP_Hosting -u username -p -P 3306 database_name

