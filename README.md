# Personal Finance Webapp

Aplikasi web ringan untuk pelacakan keuangan pribadi dan keluarga. Dibangun menggunakan PHP dan MariaDB, dirancang untuk berjalan optimal di shared hosting environment seperti InfinityFree.

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
| Frontend | HTML, Tailwind CSS, Alpine.js, Chart.js |
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
| password_hash | VARCHAR(255) | Hashed password |
| created_at | TIMESTAMP | Waktu registrasi |

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

### Step 1: Prepare Files

- Pastikan file `.htaccess` sudah ada di folder `app/`, `config/`, dan `storage/` (Isinya: `Deny from all`)
- Compress semua file project (kecuali `.git`) menjadi `.zip`

### Step 2: Upload

- Buka File Manager (MonstaFTP) di InfinityFree
- Masuk ke folder `htdocs`
- Upload dan Extract file `.zip` di dalam `htdocs`
- Pastikan `index.php` berada langsung di dalam `htdocs`

### Step 3: Database

- Buat database baru di Panel InfinityFree (MySQL Databases)
- Buka phpMyAdmin
- Import file `file/schema.sql`
- Update `config/database.php` dengan credentials dari InfinityFree

### Step 4: Security Check

- Coba akses `yourdomain.com/config/database.php` - Harus muncul **403 Forbidden**
- Coba akses `yourdomain.com/app/Models/User.php` - Harus muncul **403 Forbidden**

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
- Phase 4: Views & UI Components (75%)
  - Design System Foundation
  - Reusable UI Components
  - Dashboard, Categories, Wallets pages
  - Login/Register pages

### In Progress

- Transaction Form & List pages
- Advanced filters and search

### Planned

- Phase 5: Enhancements (Charts, CSV Export, Validation improvements)
- Phase 6: Deployment & Optimization

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

Last Updated: 2025-12-08
