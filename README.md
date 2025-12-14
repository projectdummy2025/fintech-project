# Personal Finance Webapp

Aplikasi web ringan untuk pelacakan keuangan pribadi dan keluarga. Dibangun menggunakan PHP dan MariaDB, dirancang untuk berjalan optimal di shared hosting environment seperti InfinityFree.

## 🌐 Live Demo

**URL:** [https://personal-finance.infinityfreeapp.com](https://personal-finance.infinityfreeapp.com)

Aplikasi ini sudah di-deploy dan berjalan di InfinityFree hosting. Anda dapat mengakses demo langsung untuk melihat fitur-fitur yang tersedia.

## Tentang Proyek

Personal Finance Webapp adalah solusi sederhana untuk mengelola keuangan pribadi. Aplikasi ini fokus pada pencatatan transaksi harian, kategorisasi pengeluaran dan pemasukan, serta visualisasi ringkasan keuangan bulanan.

### Tujuan Pengembangan

Proyek ini dikembangkan dengan tiga tujuan utama:
- Menyediakan finance tracker yang praktis dan mudah digunakan untuk kebutuhan personal dan keluarga
- Sebagai proyek portofolio terstruktur untuk mendemonstrasikan kompetensi dalam backend development, database design, dan basic security
- Menjaga kesederhanaan untuk memastikan performa stabil di hosting dengan resource terbatas

---

## Fitur Utama

### Core Features (MVP)

- **User Authentication** - Sistem login dengan username dan password yang di-hash
- **Transaction Management** - Input untuk transaksi pemasukan dan pengeluaran
- **Dashboard Summary** - Ringkasan income, expenses, dan surplus/deficit bulanan
- **Category Management** - Manajemen kategori pemasukan dan pengeluaran
- **Wallet Management** - Kelola sumber dana (cash, bank, e-wallet)
- **Monthly Transaction List** - Daftar transaksi bulanan dengan filter

### UI Components

Aplikasi ini menggunakan design system minimalist professional dengan komponen-komponen berikut:
- **Buttons & Inputs** - Tombol dengan loading states dan input fields dengan focus states yang jelas
- **Cards & Containers** - Card components dengan hover effects dan gradient headers
- **Tables & Badges** - Tabel data dengan hover rows dan badges berwarna soft
- **Alerts & UX States** - Alert dengan border-left accent, loading spinners, skeleton screens, dan empty states
- **Page Transitions** - Transisi antar halaman yang smooth menggunakan Swup.js

### Optional Features

- Export to CSV
- Budget per category
- Simple charts menggunakan Chart.js

---

## Technology Stack

| Component | Technology |
|-----------|-----------|
| Backend | PHP (mini-MVC architecture) |
| Database | MariaDB |
| Frontend | HTML, Tailwind CSS, Alpine.js, Chart.js, Swup.js |
| Icons | Phosphor Icons |
| Fonts | Inter (UI), Roboto Mono (numbers) |
| Hosting | InfinityFree (shared hosting) |

---

## Directory Structure

```
htdocs/                    # Root folder di hosting
├── app/                   # Application logic (Protected via .htaccess)
│   ├── Controllers/       # Request handlers
│   ├── Models/            # Data models
│   ├── Views/             # HTML templates
│   │   ├── layout/        # Layout templates
│   │   ├── auth/          # Login/Register pages
│   │   ├── dashboard/     # Dashboard page
│   │   ├── categories/    # Category management
│   │   ├── wallets/       # Wallet management
│   │   ├── transactions/  # Transaction pages
│   │   └── demo/          # UI components demo
│   ├── Core/              # Core framework files
│   └── .htaccess          # Deny from all
├── config/                # Configuration files (Protected)
│   ├── database.php       # Database configuration
│   └── .htaccess          # Deny from all
├── storage/               # Storage for logs (Protected)
│   └── logs/              # Application logs
├── public/                # Public assets
│   ├── custom.css         # Custom CSS dengan design system
│   └── critical.css       # Critical CSS untuk performance
├── file/                  # Database schema files
│   └── schema.sql         # Database schema
├── index.php              # Entry point
├── .htaccess              # Main routing rules
└── README.md              # This file
```

---

## Database Schema

### Tables Overview

#### users
Menyimpan informasi user dan kredensial

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | User ID |
| username | VARCHAR(50) | Username unik |
| email | VARCHAR(255) | Email address (Unique) |
| password_hash | VARCHAR(255) | Hashed password |
| email_verified_at | TIMESTAMP | Waktu verifikasi email |
| is_active | BOOLEAN | Status aktif user |
| last_login_at | TIMESTAMP | Waktu login terakhir |
| created_at | TIMESTAMP | Waktu registrasi |
| updated_at | TIMESTAMP | Waktu update terakhir |

#### login_attempts
Mencatat percobaan login untuk keamanan

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Attempt ID |
| ip_address | VARCHAR(45) | IP Address user |
| username | VARCHAR(50) | Username yang dicoba |
| attempt_time | TIMESTAMP | Waktu percobaan |

#### wallets
Menyimpan daftar wallet/sumber dana per user

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Wallet ID |
| user_id | INT (FK) | Reference ke users |
| name | VARCHAR(100) | Nama wallet |
| description | TEXT | Deskripsi (optional) |
| created_at | TIMESTAMP | Waktu pembuatan |

#### categories
Menyimpan kategori income/expense per user

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK, AI) | Category ID |
| user_id | INT (FK) | Reference ke users |
| name | VARCHAR(100) | Nama kategori |
| type | ENUM('income','expense') | Jenis kategori |
| created_at | TIMESTAMP | Waktu pembuatan |

#### transactions
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

### Database Optimizations
1. **InnoDB Engine**: Semua tabel menggunakan InnoDB untuk ACID compliance dan foreign key support.
2. **Composite Indexes**: Index gabungan (idx_user_date, idx_user_type) untuk optimasi query umum:
   - Dashboard summary (filter by user + date range)
   - Transaction list (filter by user + wallet/category)
   - Monthly reports (filter by user + date)
3. **Data Integrity**:
   - `ON DELETE CASCADE` untuk users memastikan data cleanup bersih.
   - `ON DELETE RESTRICT` untuk wallets/categories mencegah penghapusan data master yang sedang digunakan.
4. **Charset**: `utf8mb4` mendukung full Unicode termasuk emojis.

---

## Getting Started

### Prerequisites

- PHP 7.4 or higher
- MariaDB/MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Installation (Local Development)

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd fintech-project
   ```

2. **Setup database**
   - Create database baru di MariaDB
   - Import schema dari `file/schema.sql`
   - Copy `config/database.example.php` ke `config/database.php`
   - Update kredensial di `config/database.php`

3. **Run locally**
   ```bash
   php -S localhost:8000
   ```

4. **Access application**
   - Buka browser: `http://localhost:8000`

---

## Deployment to InfinityFree

### ✅ Status: Successfully Deployed

**Production URL:** [https://personal-finance.infinityfreeapp.com](https://personal-finance.infinityfreeapp.com)

Aplikasi ini telah berhasil di-deploy ke InfinityFree hosting dan berjalan dengan baik.

### Deployment Steps (Reference)

#### Step 1: Prepare Files

- Pastikan file `.htaccess` sudah ada di folder `app/`, `config/`, dan `storage/` (Isinya: `Deny from all`)
- Compress semua file project (kecuali `.git`) menjadi `.zip`

#### Step 2: Upload

- Buka File Manager (MonstaFTP) di InfinityFree
- Masuk ke folder `htdocs`
- Upload dan Extract file `.zip` di dalam `htdocs`
- Pastikan `index.php` berada langsung di dalam `htdocs`

#### Step 3: Database

- Buat database baru di Panel InfinityFree (MySQL Databases)
- Buka phpMyAdmin
- Import file `file/schema.sql`
- Update `config/database.php` dengan credentials dari InfinityFree

#### Step 4: Security Check

- Coba akses `yourdomain.com/config/database.php` - Harus muncul **403 Forbidden**
- Coba akses `yourdomain.com/app/Models/User.php` - Harus muncul **403 Forbidden**

### Production Environment

- **Hosting Provider:** InfinityFree
- **Server:** Shared Hosting
- **Database:** MySQL (MariaDB)
- **PHP Version:** 7.4+
- **Deployment Date:** December 2025

---

## Design System

Aplikasi ini menggunakan design system "Minimalist Professional" dengan prinsip-prinsip berikut:

### Color Palette

- **Primary**: Deep Teal (#0F766E) - Muted, trustworthy, professional
- **Background**: Soft Gray (#F9FAFB) dan White (#FFFFFF)
- **Text**: Dark Gray (#111827) untuk headings, Medium Gray (#6B7280) untuk body
- **Semantic**: Emerald (#059669), Red (#DC2626), Blue (#2563EB) - Muted tones

### Typography

- **UI Font**: Inter - Clean, modern, highly readable
- **Number Font**: Roboto Mono - Tabular numbers untuk data keuangan
- **Font Scale**: Responsive typography dari 12px hingga 36px

### Components

Semua komponen UI terdefinisi di `public/custom.css` dengan class-class berikut:
- `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-ghost`, `.btn-danger`
- `.card-custom`, `.empty-state`, `.error-state`
- `.table-custom`, `.badge`
- `.alert-custom`, `.spinner`, `.skeleton`

Demo lengkap komponen tersedia di `/demo/ui-components`

---

## Security Considerations

Aplikasi ini menerapkan best practices keamanan berikut:

- **Password Hashing** - Menggunakan `password_hash()` dan `password_verify()` dengan bcrypt algorithm
- **CSRF Protection** - Token-based untuk semua form submissions
- **SQL Injection Prevention** - Prepared statements untuk semua database queries
- **Input Validation** - Server-side validation untuk semua user input
- **Session Security** - HttpOnly dan Secure flags untuk session cookies
- **Directory Protection** - `.htaccess` untuk mencegah direct access ke sensitive folders

---

## Limitations & Notes

### InfinityFree Limitations

- Tidak ada fitur email (tidak didukung di InfinityFree)
- Resource terbatas (CPU, memory, concurrent connections)
- Hindari proses backend yang berat (heavy processing)

### Best Practices

- Gunakan minimal assets dan libraries untuk performa optimal
- Implement caching untuk query yang sering diakses
- Optimize images dan static assets
- Keep database queries efficient dengan proper indexing

---

## Development Progress

### Completed Phases

- Phase 1: Foundation (100%)
- Phase 2: Authentication (100%)
- Phase 3: Core Finance Logic (100%)
- Phase 4: Views & UI Components (100%)
  - Design System Foundation
  - Reusable UI Components
  - Dashboard, Categories, Wallets pages
  - Login/Register pages
  - Transaction Form & List pages
  - Advanced filters and search
- Phase 5: Enhancements (100%)
  - Charts & Visualizations
  - CSV Export
  - Validation improvements
- Phase 6: Deployment & Optimization (100%)
  - Production Deployment
  - Performance Tuning
  - Security Audits

### Status
✅ **Project Completed & Deployed**

---

## Bug Fixes & Changelog

### 2025-12-11: Transaction Filter Bug Fix

**Problem Identified:**
Filter pada halaman transactions mengalami beberapa bug kritis yang menyebabkan hasil filtering tidak akurat.

**Issues:**
1. Duplikasi fungsi `loadData()` menyebabkan konflik behavior
2. Filter dengan nilai kosong tetap diproses
3. Tombol "Clear all" tidak memiliki event handler
4. Default year/month yang tidak perlu menyebabkan hasil tidak sesuai ekspektasi

**Solutions:**
- Menghapus duplikasi fungsi `loadData()` di `TransactionsPage` object
- Memperbaiki `applyFilters()` untuk membersihkan nilai kosong sebelum diproses
- Meningkatkan logika `filterTransactions()` dengan validasi string yang lebih ketat
- Menambahkan event handler untuk tombol "Clear all"
- Menghapus default year/month, hanya menggunakan filter yang dipilih user

**Impact:**
- ✅ Filter kombinasi sekarang bekerja dengan benar
- ✅ Reset filter menghapus semua filter dengan sempurna
- ✅ Performa filtering lebih cepat dan akurat
- ✅ Kode lebih bersih dan maintainable

**Files Modified:**
- `public/js/app.js` (TransactionsPage: init, filterTransactions, bindEvents, applyFilters, resetFilters, renderActiveFilters)
- `app/Controllers/TransactionController.php` (index method filter logic)

---

## Future Extensions

Beberapa fitur yang dapat dikembangkan di masa depan:

- Multi-user accounting separation dengan role management
- Budget visualization dan tracking
- Printable reports (PDF export)
- API endpoints untuk mobile client
- Reminder untuk recurring transactions
- Advanced analytics dan insights
- Dark mode support

---

## License

This project is open source and available under the MIT License.

---

## Author

Developed as a portfolio project to demonstrate full-stack development capabilities with focus on clean code, security, and user experience.

---

## Support

Untuk pertanyaan atau issue, silakan buka issue di repository ini.

---

## 🔗 Links

- **Live Demo:** [https://personal-finance.infinityfreeapp.com](https://personal-finance.infinityfreeapp.com)
- **Repository:** [GitHub Repository](https://github.com/projectdummy2025/fintech-project)

Last Updated: 2025-12-14
