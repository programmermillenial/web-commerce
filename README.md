Download Source Code
Extract file yang sudah di download
letakkan folder project ke dalam folder server kalian
a. Jika menggunakan laragon letakkan folder project di dalam folder /www
b. Jika menggunakan apache/xampp letakkan di dalam folder htdoc
Rename file .env.example menjadi .env
Isi user, password root mysql dan nama database (harus dibikin sebelumnya)
Untuk database bisa disesuaikan dengan pilihan kalian MySQL, PostgreSQL, SQL Server, SQLite
Buka command prompt
Masuk ke dalam folder app projectnya misal d:/laragon/www/webcommerce/webcommerce_app
Masukkan perintah composer dump-autoload
Masukkan perintah php artisan migrate --seed
Masukkan perintah php artisan key:generate
Buka browser lalu masukkan URL http://localhost/webcommerce
Jika menggunakan port http://localhost:8080/webcommerce
Untuk port sesuaikan dengan port web server kalian masing2

Untuk halaman web http://localhost/webcommerce
Untuk halaman admin http://localhost/webcommerce/admin

Username : admin
Password : admin123
