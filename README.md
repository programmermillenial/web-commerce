# Instalasi Aplikasi WebCommerce

## Persyaratan Sistem

Pastikan server Anda telah terinstall:

* PHP 8.3 atau lebih terbaru
* Composer
* Database (MySQL, PostgreSQL, SQL Server, atau SQLite)
* Web Server (Laragon, XAMPP, Apache, Nginx, dan lain-lain)

---

## Langkah Instalasi

### 1. Download Source Code

Download source code aplikasi kemudian extract file hasil download.

### 2. Letakkan Folder Project

Pindahkan folder project ke direktori web server Anda:

**Laragon**

```text
C:/laragon/www/
```

**XAMPP**

```text
C:/xampp/htdocs/
```

Contoh:

```text
C:/laragon/www/webcommerce
```

---

### 3. Konfigurasi Environment

Rename file:

```text
.env.example
```

menjadi:

```text
.env
```

Kemudian sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webcommerce
DB_USERNAME=root
DB_PASSWORD=
```

> Pastikan database sudah dibuat terlebih dahulu sesuai dengan nama yang digunakan pada konfigurasi.

Aplikasi mendukung beberapa jenis database:

* MySQL
* PostgreSQL
* SQL Server
* SQLite

---

### 4. Install Dependency

Buka Command Prompt atau Terminal, kemudian masuk ke folder project:

```bash
cd C:/laragon/www/webcommerce
```

Jalankan perintah berikut:

```bash
composer dump-autoload
```

---

### 5. Generate Application Key

```bash
php artisan key:generate
```

---

### 6. Migrasi dan Seeder Database

Jalankan perintah berikut untuk membuat struktur tabel dan data awal:

```bash
php artisan migrate --seed
```

---

### 7. Jalankan Aplikasi

Buka browser dan akses URL berikut:

**Jika menggunakan port default:**

```text
http://localhost/webcommerce
```

**Jika menggunakan port custom (contoh 8080):**

```text
http://localhost:8080/webcommerce
```

> Sesuaikan port dengan konfigurasi web server yang digunakan.

---

## Akses Aplikasi

### Halaman Website

```text
http://localhost/webcommerce
```

### Halaman Admin

```text
http://localhost/webcommerce/admin
```

---

## Login Administrator

**Username**

```text
admin
```

**Password**

```text
admin123
```

> Demi keamanan, disarankan untuk segera mengganti password administrator setelah berhasil login pertama kali.
