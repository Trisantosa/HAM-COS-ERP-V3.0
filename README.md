# HAM-COS ERP V3.0

## PT. Hajampo Asia Mineral

Enterprise Resource Planning System untuk manajemen operasional PT. Hajampo Asia Mineral.

### 🎯 Fitur Utama

- **Manajemen Karyawan** - Data master employees dengan division & certification tracking
- **Sistem Payroll** - Perhitungan gaji otomatis dengan validasi bank account & deduction rules
- **Dashboard Analytics** - Real-time insights untuk management level (DIRUT, Finance Manager)
- **Audit Trail** - Semua transaksi tercatat dengan session context (row-level scope)
- **WhatsApp Integration** - Notifikasi payroll via WhatsApp

### 🏗️ Architecture

Berbasis Laravel 12 dengan pola:
- **API-first** dengan standardized JSON response envelope
- **Global Exception Handling** untuk error konsistensi
- **Row-Level Scope** untuk multi-tenant data isolation
- **Repository Pattern** untuk abstraksi database
- **Service Layer** untuk business logic

Lihat [ARCHITECTURE.md](./ARCHITECTURE.md) untuk detail lengkap.

### 📋 Tech Stack

- **Backend**: Laravel 12, PHP 8.3+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Passport (OAuth2)
- **Messaging**: Twilio WhatsApp API
- **Testing**: PHPUnit 11
- **Container**: Docker & Docker Compose

### 🚀 Quick Start

```bash
# 1. Clone repository
git clone https://github.com/Trisantosa/HAM-COS-ERP-V3.0.git
cd HAM-COS-ERP-V3.0

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate:fresh --seed

# 5. Start server
php artisan serve
```

Untuk Docker setup, lihat [SETUP.md](./SETUP.md).

### 📚 Documentation

- [ARCHITECTURE.md](./ARCHITECTURE.md) - System design & blueprint
- [SETUP.md](./SETUP.md) - Installation & configuration guide
- [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) - API endpoints reference
- [DATABASE.md](./DATABASE.md) - Database schema & relationships

### 📦 Project Status

**Status**: Development Phase - Foundation Architecture Complete

- ✅ API Response trait standardized
- ✅ Global exception handler configured
- ✅ Bootstrap app.php finalized
- ✅ Middleware stack implemented
- ✅ Controllers & routes in progress
- ✅ Database migrations pending

### 🔐 Security

- Row-level scope enforcement untuk data isolation
- Permission-based access control
- Audit logging untuk compliance
- Session timeout protection
- CSRF & SQL injection protection (Laravel built-in)

### 📞 Support

Contact: PT. Hajampo Asia Mineral - Development Team

---

**Version**: 3.0.0
**Last Updated**: 2026-06-21
