# Database Information

## Database Schema

**Database Name:** `fintech_db`  
**Charset:** `utf8mb4_unicode_ci`  
**Engine:** InnoDB

## Tables

### 1. `users`
Menyimpan data user dan kredensial login.

| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | INT UNSIGNED | PK, AI | User ID |
| username | VARCHAR(50) | UNIQUE | Username unik |
| password_hash | VARCHAR(255) | - | Hashed password (bcrypt) |
| created_at | TIMESTAMP | - | Waktu registrasi |

**Indexes:** `idx_username`

---

### 2. `wallets`
Menyimpan daftar wallet/sumber dana per user.

| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | INT UNSIGNED | PK, AI | Wallet ID |
| user_id | INT UNSIGNED | FK | Reference ke users |
| name | VARCHAR(100) | - | Nama wallet |
| description | TEXT | - | Deskripsi wallet |
| created_at | TIMESTAMP | - | Waktu dibuat |

**Indexes:** `idx_user_id`  
**Foreign Keys:** `user_id → users(id)` ON DELETE CASCADE

---

### 3. `categories`
Menyimpan kategori income/expense per user.

| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | INT UNSIGNED | PK, AI | Category ID |
| user_id | INT UNSIGNED | FK | Reference ke users |
| name | VARCHAR(100) | - | Nama kategori |
| type | ENUM | - | 'income' atau 'expense' |
| created_at | TIMESTAMP | - | Waktu dibuat |

**Indexes:** `idx_user_id`, `idx_type`, `idx_user_type`  
**Foreign Keys:** `user_id → users(id)` ON DELETE CASCADE

---

### 4. `transactions`
Menyimpan semua transaksi keuangan.

| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | INT UNSIGNED | PK, AI | Transaction ID |
| user_id | INT UNSIGNED | FK | Reference ke users |
| wallet_id | INT UNSIGNED | FK | Reference ke wallets |
| category_id | INT UNSIGNED | FK | Reference ke categories |
| amount | DECIMAL(15,2) | - | Jumlah transaksi |
| type | ENUM | - | 'income' atau 'expense' |
| notes | TEXT | - | Catatan transaksi |
| date | DATE | - | Tanggal transaksi |
| created_at | TIMESTAMP | - | Waktu input |

**Indexes:**  
- `idx_user_id`, `idx_wallet_id`, `idx_category_id`, `idx_date`, `idx_type`
- Composite: `idx_user_date`, `idx_user_type`, `idx_user_wallet`, `idx_user_category`

**Foreign Keys:**  
- `user_id → users(id)` ON DELETE CASCADE
- `wallet_id → wallets(id)` ON DELETE RESTRICT
- `category_id → categories(id)` ON DELETE RESTRICT

---

## Setup Instructions

### 1. Import Schema
```bash
mysql -u root -p < file/schema.sql
```

### 2. Import Seed Data (Optional)
```bash
mysql -u root -p fintech_db < file/seed_data.sql
```

### 3. Configure Application
Update `config/database.php` dengan kredensial database:
```php
$host = 'localhost';
$dbname = 'fintech_db';
$username = 'your_username';
$password = 'your_password';
```

---

## Optimization Strategy

### Indexes
- **Single column indexes:** Untuk filter sederhana (user_id, date, type)
- **Composite indexes:** Untuk query kompleks yang sering digunakan:
  - `idx_user_date`: Dashboard summary per periode
  - `idx_user_type`: Filter income/expense per user
  - `idx_user_wallet`: Transaksi per wallet
  - `idx_user_category`: Transaksi per kategori

### Foreign Keys
- **CASCADE:** Hapus user → hapus semua data terkait
- **RESTRICT:** Cegah hapus wallet/category yang masih digunakan

### Query Patterns
Indexes dioptimasi untuk query berikut:
```sql
-- Dashboard summary
SELECT SUM(amount) FROM transactions 
WHERE user_id = ? AND date BETWEEN ? AND ?;

-- Filter by category
SELECT * FROM transactions 
WHERE user_id = ? AND category_id = ?;

-- Monthly transactions
SELECT * FROM transactions 
WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ?;
```

---

## Security Considerations

1. **Password Hashing:** Gunakan `password_hash()` dengan bcrypt
2. **Prepared Statements:** Semua query harus menggunakan PDO prepared statements
3. **Input Validation:** Validasi semua input sebelum insert/update
4. **Foreign Keys:** Enforce referential integrity di database level
5. **Character Set:** utf8mb4 mencegah charset-based injection

---

