# Personal Finance Webapp

> A lightweight web application for individual financial tracking, focusing on essential cashflow management.

## 📋 Overview

Personal Finance Webapp adalah aplikasi web ringan untuk pelacakan keuangan pribadi dan keluarga. Dibangun menggunakan PHP dan MariaDB, aplikasi ini dirancang untuk berjalan optimal di shared hosting environment seperti InfinityFree.

### Tujuan Utama

* ✅ Menyediakan finance tracker yang praktis dan mudah digunakan untuk kebutuhan personal dan keluarga
* 💼 Sebagai proyek portofolio terstruktur untuk mendemonstrasikan kompetensi dalam backend development, database design, dan basic security
* 🚀 Menjaga kesederhanaan untuk memastikan performa stabil di hosting dengan resource terbatas

---

## ✨ Features

### Core Features (MVP)

* 🔐 **User Authentication** - Username/password dengan hashed storage
* 💰 **Transaction Management** - Input untuk income dan expense transactions
* 📊 **Dashboard Summary** - Ringkasan income, expenses, surplus/deficit
* 🏷️ **Category Management** - Manajemen kategori pemasukan dan pengeluaran
* 💳 **Wallet/Source Management** - Kelola sumber dana (cash, bank, e-wallet)
* 📅 **Monthly Transaction List** - Daftar transaksi bulanan dengan filter

### Optional Features

* 📤 Export to CSV
* 🎯 Budget per category
* 📈 Simple charts menggunakan Chart.js

---

## � Deployment to InfinityFree
1.  **Prepare Files**:
    *   Pastikan file `.htaccess` sudah ada di folder `app/`, `config/`, dan `storage/` (Isinya: `Deny from all`).
    *   Compress semua file project (kecuali `.git` dan `node_modules`) menjadi `.zip`.

2.  **Upload**:
    *   Buka File Manager (MonstaFTP) di InfinityFree.
    *   Masuk ke folder `htdocs`.
    *   Upload dan Extract file `.zip` di dalam `htdocs`.
    *   **PENTING**: Pastikan `index.php` berada langsung di dalam `htdocs`, bukan di subfolder. Jika ada di subfolder `public`, pindahkan isinya ke root `htdocs` dan sesuaikan path `require` di `index.php`.

3.  **Database**:
    *   Buat database baru di Panel InfinityFree (MySQL Databases).
    *   Buka phpMyAdmin.
    *   Import file `file/schema.sql` (atau `schema.sql .example`).
    *   Update `config/database.php` dengan credentials dari InfinityFree (Host, Username, Password, DB Name).

4.  **Security Check**:
    *   Coba akses `yourdomain.com/config/database.php`. Harus muncul **403 Forbidden**.
    *   Coba akses `yourdomain.com/app/models/User.php`. Harus muncul **403 Forbidden**.

---

## �🛠️ Technology Stack

| Component | Technology |
|-----------|-----------|
| **Backend** | PHP (procedural or mini-MVC architecture) |
| **Database** | MariaDB |
| **Frontend** | HTML, Bootstrap, Chart.js (optional) |
| **Hosting** | InfinityFree (shared hosting) |

---

## 📁 Directory Structure (InfinityFree / Production)

```
htdocs/                    # Root folder di hosting
├── app/                   # Application logic (Protected via .htaccess)
│   ├── controllers/       # Request handlers
│   ├── models/            # Data models
│   ├── views/             # HTML templates
│   ├── core/              # Core framework files
│   └── .htaccess          # Deny from all
├── config/                # Configuration files (Protected via .htaccess)
│   ├── database.php       # Database configuration
│   └── .htaccess          # Deny from all
├── storage/               # Storage for logs (Protected via .htaccess)
│   └── logs/              # Application logs
├── public/                # (Optional: Assets can go here or directly in root)
│   ├── css/
│   └── js/
├── index.php              # Entry point
├── .htaccess              # Main routing rules
└── README.md              # This file
```

---

## 🗄️ Database Schema

### Tables Overview

#### `users`
Menyimpan informasi user dan kredensial

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | User ID |
| username | VARCHAR(50) | Username unik |
| password_hash | VARCHAR(255) | Hashed password |
| created_at | TIMESTAMP | Waktu registrasi |

#### `wallets`
Menyimpan daftar wallet/sumber dana per user

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Wallet ID |
| user_id | INT (FK) | Reference ke users |
| name | VARCHAR(100) | Nama wallet |
| description | TEXT | Deskripsi (optional) |

#### `categories`
Menyimpan kategori income/expense per user

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Category ID |
| user_id | INT (FK) | Reference ke users |
| name | VARCHAR(100) | Nama kategori |
| type | ENUM('income','expense') | Jenis kategori |

#### `transactions`
Menyimpan semua transaksi keuangan

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Transaction ID |
| user_id | INT (FK) | Reference ke users |
| wallet_id | INT (FK) | Reference ke wallets |
| category_id | INT (FK) | Reference ke categories |
| amount | DECIMAL(15,2) | Jumlah transaksi |
| type | ENUM('income','expense') | Jenis transaksi |
| notes | TEXT | Catatan (optional) |
| date | DATE | Tanggal transaksi |
| created_at | TIMESTAMP | Waktu input |

---

## 🚀 Getting Started

### Prerequisites

* PHP 7.4 or higher
* MariaDB/MySQL 5.7 or higher
* Web server (Apache/Nginx)
* Composer (optional, for dependencies)

### Installation

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd fintech-project
   ```

2. **Setup database**
   * Create database baru di MariaDB
   * Import schema dari `database/schema.sql`
   * Update kredensial di `config/database.php`

3. **Configure application**
   ```bash
   cp config/database.example.php config/database.php
   # Edit database.php dengan kredensial Anda
   ```

4. **Run locally**
   ```bash
   php -S localhost:8000 -t public
   ```

5. **Access application**
   * Buka browser: `http://localhost:8000`

---

## ⚠️ Limitations & Notes

* ❌ Hindari proses backend yang berat (heavy processing)
* ❌ Tidak ada fitur email (tidak didukung di InfinityFree)
* ✅ Minimal assets dan libraries untuk performa optimal
* ✅ Gunakan prepared statements untuk semua DB queries
* ✅ Session-based authentication (cookie-based)

---

## 🔒 Security Considerations

* Password hashing menggunakan `password_hash()` dan `password_verify()`
* CSRF token protection untuk semua form submissions
* Prepared statements untuk mencegah SQL injection
* Input validation dan sanitization
* Session security (httponly, secure flags)

---

## 🎯 Future Extensions

* 👥 Multi-user accounting separation dengan role management
* 📊 Budget visualization dan tracking
* 🖨️ Printable reports (PDF export)
* 📱 API endpoints untuk mobile client
* 🔔 Reminder untuk recurring transactions
* 📈 Advanced analytics dan insights

---

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

Developed as a portfolio project to demonstrate full-stack development capabilities.

---

## 📞 Support

Untuk pertanyaan atau issue, silakan buka issue di repository ini atau hubungi developer.

---

**Happy Tracking! 💰📊**
