# Development Plan - Personal Finance Webapp

> Roadmap pengembangan aplikasi Personal Finance Webapp dari fase foundation hingga deployment

---

## 📅 Timeline Overview

| Phase | Estimasi | Status |
|-------|----------|--------|
| Phase 1: Foundation | 2-3 hari | ⏳ Pending |
| Phase 2: Authentication | 2-3 hari | ⏳ Pending |
| Phase 3: Core Finance Logic | 4-5 hari | ⏳ Pending |
| Phase 4: Views | 3-4 hari | ⏳ Pending |
| Phase 5: Enhancements | 2-3 hari | ⏳ Pending |
| Phase 6: Deployment | 1-2 hari | ⏳ Pending |

**Total Estimasi:** 14-20 hari kerja

---

## 🏗️ Phase 1 — Foundation

**Tujuan:** Membangun struktur dasar project dan setup environment

### Tasks

- [ ] **1.1 Initialize Project Structure**
  - [ ] Buat folder structure sesuai architecture
  - [ ] Setup `.gitignore` untuk exclude sensitive files
  - [ ] Inisialisasi Git repository

- [ ] **1.2 Prepare Database Schema**
  - [ ] Design ERD lengkap dengan relationships
  - [ ] Buat SQL migration file untuk semua tables
  - [ ] Tambahkan indexes untuk performance
  - [ ] Setup foreign key constraints
  - [ ] Buat sample seed data untuk testing

- [ ] **1.3 Implement Configuration Loader**
  - [ ] Buat `config/database.php` dengan PDO connection
  - [ ] Buat `config/app.php` untuk app settings
  - [ ] Implement environment-based config (dev/prod)
  - [ ] Setup error handling dan logging mechanism

- [ ] **1.4 Setup Minimal Routing**
  - [ ] Buat simple router atau controller handler
  - [ ] Implement `.htaccess` untuk clean URLs
  - [ ] Setup base controller class
  - [ ] Test routing dengan dummy pages

### Deliverables
- ✅ Project structure lengkap
- ✅ Database schema ready
- ✅ Basic routing working
- ✅ Configuration system functional

---

## 🔐 Phase 2 — Authentication

**Tujuan:** Implement user authentication dan session management

### Tasks

- [ ] **2.1 Create User Model**
  - [ ] Buat `User.php` model dengan CRUD methods
  - [ ] Implement password hashing (`password_hash()`)
  - [ ] Buat method untuk verify password
  - [ ] Add validation untuk username/password

- [ ] **2.2 Implement Registration** (Optional)
  - [ ] Buat registration form
  - [ ] Validate input (unique username, password strength)
  - [ ] Hash password sebelum save ke database
  - [ ] Auto-login setelah registration

- [ ] **2.3 Implement Login/Logout**
  - [ ] Buat login form dengan validation
  - [ ] Verify credentials terhadap database
  - [ ] Set session variables setelah login sukses
  - [ ] Implement logout functionality (destroy session)
  - [ ] Add "Remember Me" functionality (optional)

- [ ] **2.4 Add Session Handling**
  - [ ] Configure session settings (timeout, security)
  - [ ] Implement session-based authentication check
  - [ ] Buat middleware/helper untuk protect routes
  - [ ] Handle session expiration gracefully

- [ ] **2.5 Add CSRF Tokens**
  - [ ] Generate CSRF token per session
  - [ ] Inject token ke semua forms
  - [ ] Validate token pada form submission
  - [ ] Handle token mismatch errors

### Deliverables
- ✅ User registration working (optional)
- ✅ Login/logout functional
- ✅ Session management secure
- ✅ CSRF protection implemented

---

## 💰 Phase 3 — Core Finance Logic

**Tujuan:** Implement business logic untuk wallet, category, dan transaction management

### Tasks

- [ ] **3.1 Wallet CRUD**
  - [ ] Buat `Wallet.php` model
  - [ ] Implement create wallet functionality
  - [ ] Implement read/list wallets per user
  - [ ] Implement update wallet
  - [ ] Implement delete wallet (dengan validation)
  - [ ] Add default wallet creation saat user register

- [ ] **3.2 Category CRUD**
  - [ ] Buat `Category.php` model
  - [ ] Implement create category (income/expense)
  - [ ] Implement read/list categories per user
  - [ ] Implement update category
  - [ ] Implement delete category (dengan validation)
  - [ ] Seed default categories untuk new users

- [ ] **3.3 Transaction Create/Read**
  - [ ] Buat `Transaction.php` model
  - [ ] Implement create transaction form
  - [ ] Validate transaction data (amount, date, etc.)
  - [ ] Save transaction ke database
  - [ ] Implement read/list transactions per user
  - [ ] Add pagination untuk transaction list

- [ ] **3.4 Filters for Month/Category/Wallet**
  - [ ] Implement filter by month/year
  - [ ] Implement filter by category
  - [ ] Implement filter by wallet
  - [ ] Implement combined filters
  - [ ] Add search functionality (by notes)

- [ ] **3.5 Dashboard Summary Logic**
  - [ ] Calculate total income per period
  - [ ] Calculate total expense per period
  - [ ] Calculate surplus/deficit
  - [ ] Group transactions by category
  - [ ] Calculate wallet balances
  - [ ] Implement date range selection

### Deliverables
- ✅ Wallet management complete
- ✅ Category management complete
- ✅ Transaction CRUD functional
- ✅ Filtering system working
- ✅ Dashboard calculations accurate

---

## 🎨 Phase 4 — Views

**Tujuan:** Membuat UI/UX yang clean dan responsive

### Tasks

- [ ] **4.1 Base Layout Template**
  - [ ] Buat `layout/header.php` dengan navigation
  - [ ] Buat `layout/footer.php`
  - [ ] Buat `layout/sidebar.php` (optional)
  - [ ] Integrate Bootstrap CSS framework
  - [ ] Setup responsive design
  - [ ] Add loading states dan error messages

- [ ] **4.2 Login/Register Pages**
  - [ ] Design login form dengan validation feedback
  - [ ] Design register form (optional)
  - [ ] Add password visibility toggle
  - [ ] Implement error/success messages
  - [ ] Add "Forgot Password" link (optional)

- [ ] **4.3 Transaction Form Page**
  - [ ] Design form dengan semua fields
  - [ ] Implement dynamic category dropdown (filter by type)
  - [ ] Add date picker
  - [ ] Implement amount input dengan formatting
  - [ ] Add notes textarea
  - [ ] Implement form validation (client-side)

- [ ] **4.4 Dashboard Page**
  - [ ] Design summary cards (income, expense, balance)
  - [ ] Show recent transactions list
  - [ ] Add quick action buttons
  - [ ] Implement date range selector
  - [ ] Show category breakdown (optional chart)

- [ ] **4.5 Category & Wallet Management Pages**
  - [ ] Design category list dengan edit/delete actions
  - [ ] Design wallet list dengan edit/delete actions
  - [ ] Implement modal untuk add/edit
  - [ ] Add confirmation dialog untuk delete
  - [ ] Show usage count per category/wallet

- [ ] **4.6 Transaction List Page**
  - [ ] Design transaction table dengan sorting
  - [ ] Implement filter controls (month, category, wallet)
  - [ ] Add pagination controls
  - [ ] Implement edit/delete actions
  - [ ] Add export button (untuk CSV)
  - [ ] Show running balance (optional)

### Deliverables
- ✅ All pages designed dan responsive
- ✅ Consistent UI/UX across pages
- ✅ Forms dengan proper validation
- ✅ User-friendly navigation

---

## 🚀 Phase 5 — Enhancements

**Tujuan:** Menambahkan fitur tambahan untuk improve user experience

### Tasks

- [ ] **5.1 Chart.js Integration**
  - [ ] Install Chart.js library
  - [ ] Create income vs expense chart (line/bar)
  - [ ] Create category breakdown chart (pie/doughnut)
  - [ ] Create monthly trend chart
  - [ ] Add interactive tooltips
  - [ ] Implement responsive charts

- [ ] **5.2 Export CSV**
  - [ ] Implement CSV export untuk transactions
  - [ ] Add filters untuk export (date range, category)
  - [ ] Format CSV dengan proper headers
  - [ ] Handle large datasets (memory limit)
  - [ ] Add download functionality

- [ ] **5.3 Validation Improvements**
  - [ ] Strengthen server-side validation
  - [ ] Add client-side validation dengan JavaScript
  - [ ] Implement real-time validation feedback
  - [ ] Add custom validation rules
  - [ ] Improve error messages (user-friendly)

- [ ] **5.4 Error Handling and Logging**
  - [ ] Implement centralized error handler
  - [ ] Log errors ke file (`storage/logs/`)
  - [ ] Create user-friendly error pages (404, 500)
  - [ ] Add debug mode untuk development
  - [ ] Implement try-catch blocks di critical sections

- [ ] **5.5 Additional Features**
  - [ ] Add budget per category functionality
  - [ ] Implement budget tracking dan alerts
  - [ ] Add recurring transaction templates (optional)
  - [ ] Implement dark mode toggle (optional)
  - [ ] Add keyboard shortcuts (optional)

### Deliverables
- ✅ Charts working dan informative
- ✅ CSV export functional
- ✅ Robust validation system
- ✅ Proper error handling
- ✅ Enhanced user experience

---

## 🌐 Phase 6 — Deployment

**Tujuan:** Deploy aplikasi ke InfinityFree hosting dan ensure production-ready

### Tasks

- [ ] **6.1 Prepare Config for InfinityFree**
  - [ ] Update database credentials untuk production
  - [ ] Set production environment variables
  - [ ] Disable debug mode
  - [ ] Configure session settings untuk shared hosting
  - [ ] Update base URL dan paths

- [ ] **6.2 Optimize Queries**
  - [ ] Review semua database queries
  - [ ] Add indexes untuk frequently queried columns
  - [ ] Optimize JOIN operations
  - [ ] Implement query caching (optional)
  - [ ] Test query performance

- [ ] **6.3 Test Routes and Sessions**
  - [ ] Test semua routes di production environment
  - [ ] Verify session persistence
  - [ ] Test CSRF token functionality
  - [ ] Check file permissions
  - [ ] Test dengan different browsers

- [ ] **6.4 Upload to Hosting (InfinityFree)**
  - [ ] Compress files (app, config, public contents, storage, index.php)
  - [ ] Upload ke folder `htdocs`
  - [ ] **Ensure Security**: Verify `.htaccess` di `app/`, `config/`, `storage/`
  - [ ] Import database schema via phpMyAdmin
  - [ ] Update `config/database.php` dengan production credentials

- [ ] **6.5 Final Verification**
  - [ ] Test complete user flow (register → login → transactions)
  - [ ] Verify all CRUD operations
  - [ ] Test filters dan search
  - [ ] Check responsive design di mobile
  - [ ] Verify security measures (CSRF, SQL injection)
  - [ ] Test error handling
  - [ ] Check performance (page load times)

- [ ] **6.6 Documentation**
  - [ ] Update README dengan production URL
  - [ ] Document deployment process
  - [ ] Create user guide (optional)
  - [ ] Document known issues/limitations
  - [ ] Add troubleshooting guide

### Deliverables
- ✅ Application deployed successfully
- ✅ All features working di production
- ✅ Performance optimized
- ✅ Documentation complete

---

## 📊 Progress Tracking

### Current Status: **Phase 1 - Foundation** ⏳

**Overall Progress:** 0% (0/6 phases complete)

### Next Steps
1. Initialize project structure
2. Setup database schema
3. Configure development environment

---

## 🐛 Known Issues & Risks

| Issue | Impact | Mitigation |
|-------|--------|------------|
| InfinityFree limitations | Medium | Keep code lightweight, avoid heavy processing |
| Session handling di shared hosting | Medium | Test thoroughly, use file-based sessions |
| Database connection limits | Low | Implement connection pooling, optimize queries |
| No email support | Low | Use alternative notification methods |

---

## 📝 Notes & Decisions

### Architecture Decisions
- **Mini-MVC Pattern:** Chosen untuk balance antara simplicity dan maintainability
- **Session-based Auth:** Lebih suitable untuk shared hosting dibanding JWT
- **Bootstrap Framework:** Untuk rapid UI development dengan responsive design

### Database Decisions
- **Soft Deletes:** Tidak implemented untuk simplicity (dapat ditambahkan later)
- **Audit Trail:** Minimal (hanya created_at), dapat ditambahkan updated_at jika needed

### Security Decisions
- **Password Hashing:** PHP's `password_hash()` dengan bcrypt algorithm
- **CSRF Protection:** Token-based untuk semua form submissions
- **SQL Injection:** Prepared statements untuk semua queries

---

## 🎯 Success Criteria

### MVP Success Metrics
- ✅ User dapat register dan login
- ✅ User dapat create, read, update, delete transactions
- ✅ Dashboard menampilkan summary yang accurate
- ✅ Filters working correctly
- ✅ Application responsive di mobile dan desktop
- ✅ No critical security vulnerabilities
- ✅ Application stable di InfinityFree hosting

### Quality Metrics
- Page load time < 3 seconds
- Zero SQL injection vulnerabilities
- Zero XSS vulnerabilities
- Mobile-friendly (responsive design)
- Code documented dan maintainable

---

## 📚 Resources & References

### Documentation
- [PHP Manual](https://www.php.net/manual/en/)
- [MariaDB Documentation](https://mariadb.org/documentation/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [Chart.js Documentation](https://www.chartjs.org/docs/)

### Security Resources
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)

### Hosting
- [InfinityFree Documentation](https://forum.infinityfree.net/)

---

**Last Updated:** 2025-12-02  
**Status:** Planning Phase  
**Next Review:** After Phase 1 completion

---

*Happy Coding! 🚀*
