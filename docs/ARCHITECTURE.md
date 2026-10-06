# LIMSKU — Architecture

## 1. Purpose

Dokumen ini menjadi panduan teknis implementasi LIMSKU.

Detail kebutuhan dan fitur sistem mengikuti `PRD.md`.
Jangan mendefinisikan ulang requirement di dokumen ini.

---

## 2. Technology Stack

Gunakan stack yang ditentukan dalam PRD:

- CodeIgniter 3
- PHP 7.4
- MySQL / MariaDB
- Bootstrap
- JavaScript
- jQuery
- DataTables
- AJAX

Jangan mengganti framework atau versi utama tanpa persetujuan.

---

## 3. Application Structure

Gunakan pola MVC CodeIgniter 3.

```text
LIMSKU/
├── application/
│   ├── config/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── libraries/
│   ├── helpers/
│   └── services/
├── system/
├── docs/
└── index.php
Controller

Menangani request, validasi awal, permission, dan response.

Model

Menangani akses dan operasi database.

View

Menangani tampilan UI.

Service

Digunakan hanya untuk proses bisnis yang cukup kompleks dan perlu dipisahkan dari Controller.

4. Database

Database menggunakan MySQL/MariaDB.

Gunakan:

Primary Key
Foreign Key
Index
Unique constraint
Timestamp
InnoDB

Struktur database dijelaskan di:

DATABASE.md

Jangan membuat struktur database yang bertentangan dengan DATABASE.md.

5. Authentication & Authorization

Gunakan session CodeIgniter untuk authentication.

Setiap fitur yang membutuhkan akses khusus harus memiliki permission check di server.

Role dan akses mengikuti PRD.md.

Jangan hanya menyembunyikan tombol di UI untuk membatasi akses.

6. Security

Minimal gunakan:

Password hashing
CSRF protection
Server-side validation
Query Builder / parameterized query
Validasi upload
Permission checking
Audit trail untuk aktivitas penting
7. UI

Implementasi UI mengikuti:

DESIGN.md

Figma digunakan sebagai referensi visual jika tersedia.

Jangan membuat desain baru yang bertentangan dengan DESIGN.md.

8. Workflow

Alur bisnis mengikuti:

WORKFLOW.md

Jangan membuat alur baru atau melewati status workflow tanpa requirement yang jelas.

9. Coding Rules
Ikuti standar CodeIgniter 3.
Gunakan MVC dengan jelas.
Hindari business logic kompleks di Controller.
Hindari query database di View.
Gunakan reusable code jika memang diperlukan.
Jangan membuat abstraksi berlebihan.
Jangan membuat file/module yang belum diperlukan.
Jangan menambahkan fitur di luar PRD tanpa persetujuan.
Pertahankan kode yang sudah berjalan.
10. AI Coding Agent Rules

Sebelum mengubah kode:

Baca bagian terkait di PRD.md.
Jika menyangkut UI, baca DESIGN.md.
Jika menyangkut workflow, baca WORKFLOW.md.
Jika menyangkut database, baca DATABASE.md.
Implementasikan hanya kebutuhan yang diperlukan.

Prioritas:

PRD
 ↓
DESIGN / WORKFLOW / DATABASE
 ↓
ARCHITECTURE
 ↓
CODE

Jika requirement belum jelas, jangan mengarang aturan sendiri.

11. Prinsip Utama

Keep it simple. Follow the PRD. Don't over-engineer.