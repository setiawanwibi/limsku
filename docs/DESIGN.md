# LIMSKU — Design

## 1. Tujuan

Dokumen ini menjadi panduan implementasi UI/UX LIMSKU.

Detail kebutuhan fitur mengikuti `PRD.md`.

---

## 2. Design Reference

### Figma

"https://www.figma.com/design/M0lZNBA6H17ICANIC7BV5e/LIMSKU-BBPOM-Bandar-Lampung?node-id=0-1&t=7JEGnAE1nwsAMrZq-1"

:contentReference[oaicite:0]{index=0}

Figma merupakan **source of truth untuk desain visual** LIMSKU.

Gunakan Figma sebagai acuan untuk:

- Layout
- Typography
- Color
- Spacing
- Components
- Icons
- Forms
- Tables
- Navigation
- Responsive layout

Jangan membuat desain alternatif jika desain yang dibutuhkan sudah tersedia di Figma.

---

## 3. Design Principle

UI harus:

- Profesional
- Sederhana
- Konsisten
- Mudah digunakan
- Fokus pada kebutuhan operasional laboratorium
- Tidak berlebihan secara visual

---

## 4. Implementation Rule

Saat mengimplementasikan UI:

1. Periksa desain Figma yang relevan.
2. Ikuti layout dan visual yang sudah dibuat.
3. Gunakan component yang sudah tersedia.
4. Jangan menambahkan style baru jika tidak diperlukan.
5. Jangan mengubah desain tanpa alasan yang jelas.
6. Gunakan responsive layout.
7. Jika suatu bagian belum tersedia di Figma, gunakan pola desain yang sudah ada.
8. Jika masih tidak jelas, jangan mengarang requirement baru.

---

## 5. UI Structure

Struktur aplikasi mengikuti desain Figma.

Komponen utama dapat meliputi:

- Sidebar
- Topbar
- Breadcrumb
- Page Header
- Main Content
- Form
- Table
- Modal
- Button
- Badge
- Alert
- Pagination

Gunakan kembali component yang sama jika memiliki fungsi yang sama.

---

## 6. Form

Form harus:

- memiliki label yang jelas
- menunjukkan field wajib
- memiliki validasi
- memberikan feedback error
- memiliki tombol aksi yang jelas

Detail visual mengikuti Figma.

---

## 7. Table

Gunakan DataTables jika diperlukan.

Table dapat menyediakan:

- Search
- Filter
- Sorting
- Pagination
- Action
- Status

Detail visual mengikuti Figma.

---

## 8. Status

Status workflow mengikuti `WORKFLOW.md`.

Visual status mengikuti Figma.

Status utama:

- Menunggu Pengujian
- Dalam Pengujian
- Menunggu Verifikasi
- Perlu Perbaikan
- Menunggu Approval MT
- Approved / Selesai

---

## 9. Responsive

Aplikasi harus dapat digunakan pada:

- Desktop
- Tablet
- Mobile

Implementasikan responsive behavior berdasarkan desain Figma.

---

## 10. AI Coding Agent Rule

Sebelum membuat atau mengubah UI:

1. Baca bagian terkait di `PRD.md`.
2. Periksa frame yang relevan di Figma.
3. Gunakan component dan style yang sudah tersedia.
4. Jangan membuat desain alternatif sendiri.
5. Pertahankan konsistensi antar halaman.

### Source of Truth

```text
PRD.md
→ Requirement

Figma
→ Visual Design

WORKFLOW.md
→ Business Flow

ARCHITECTURE.md
→ Technical Structure

DATABASE.md
→ Data Structure

Untuk keputusan visual, Figma menjadi acuan utama.

Implement the design, don't redesign it.