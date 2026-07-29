# CampusPay — Distributed Microservices Payment System

CampusPay adalah sistem pembayaran kampus berbasis Microservices Architecture yang dirancang menggunakan pendekatan Event-Driven serta implementasi Lamport Logical Clock untuk menjaga konsistensi data pada sistem terdistribusi.

---

## Overview

CampusPay dikembangkan untuk mengelola proses pembayaran kampus secara terstruktur, otomatis, dan scalable. Sistem ini menangani beberapa proses utama, yaitu:

- Generate tagihan mahasiswa
- Proses pembayaran (Virtual Account dan manual)
- Pencatatan transaksi
- Notifikasi kepada pengguna
- Pelaporan keuangan

Pendekatan yang digunakan dalam pengembangan sistem ini meliputi:

- Microservices Architecture
- REST API dan Event-Driven Communication
- Message Broker (RabbitMQ)
- Lamport Logical Clock untuk sinkronisasi waktu logis

---

## Tujuan Sistem

Tujuan utama dari pengembangan CampusPay adalah:

- Mengurangi proses manual dalam pengelolaan pembayaran
- Menghindari duplikasi data tagihan
- Meningkatkan efisiensi operasional
- Menyediakan sistem yang scalable dan modular
- Menjamin konsistensi data dalam sistem terdistribusi

---

## Arsitektur Sistem

[Letakkan diagram arsitektur di sini]

Sistem terdiri dari beberapa layanan (services) yang berdiri sendiri dan saling terhubung.

### Core Services (REST API)

- Auth Service  
  Mengelola proses autentikasi dan otorisasi pengguna.

- Student Service  
  Mengelola data mahasiswa dan semester.

- Billing Service  
  Mengelola pembuatan dan pengolahan tagihan.

- Virtual Account Service  
  Menghasilkan nomor Virtual Account untuk pembayaran.

### Event-Driven Services

- Payment Service  
  Mengelola proses pembayaran.

- Transaction Service  
  Mencatat transaksi dan mengelola Lamport Logical Clock.

- Notification Service  
  Mengirim notifikasi kepada pengguna.

- Report Service  
  Menghasilkan laporan dalam format PDF dan Excel.

### Message Broker

- RabbitMQ  
  Digunakan sebagai penghubung antar service melalui event.

---

## Alur Proses Sistem

[Letakkan diagram use case atau flow di sini]

Alur utama sistem adalah sebagai berikut:

1. Admin keuangan melakukan generate tagihan massal
2. Billing Service mengirim event ke RabbitMQ
3. Payment Service memproses event pembayaran
4. Transaction Service mencatat transaksi menggunakan Lamport Clock
5. Notification Service mengirim notifikasi ke mahasiswa
6. Mahasiswa melakukan pembayaran
7. Status tagihan diperbarui secara otomatis

---

## Konsep Sistem Terdistribusi

[Letakkan diagram Lamport Clock di sini]

Sistem ini mengimplementasikan Lamport Logical Clock untuk:

- Menjaga urutan kejadian (event ordering)
- Menghindari konflik data antar service
- Menyediakan pencatatan log yang konsisten
- Mendukung sinkronisasi sistem tanpa ketergantungan waktu fisik

---

## Desain Basis Data

[Letakkan ERD di sini]

Entity utama dalam sistem meliputi:

- Mahasiswa
- Semester
- Tagihan
- Payment Type
- Virtual Account
- Transaction
- Notification

---

## Teknologi yang Digunakan

| Komponen     | Teknologi                |
|--------------|--------------------------|
| Backend      | Laravel (Microservices)  |
| Frontend     | Filament Admin Panel     |
| Database     | MySQL                    |
| Messaging    | RabbitMQ                 |
| API          | RESTful API              |
| Arsitektur   | Microservices            |
| Pola         | Event-Driven             |
| Logging      | Lamport Logical Clock    |

---

## Struktur Proyek
```bash
campuspay/
│
├── services/
│   ├── auth-service/
│   ├── student-service/
│   ├── billing-service/
│   ├── payment-service/
│   ├── transaction-service/
│   ├── notification-service/
│   ├── report-service/
│   └── va-service/
│
├── docs/
├── README.md


---

## Peran Pengguna

### Mahasiswa

- Login ke sistem
- Melihat tagihan
- Menghasilkan Virtual Account
- Mengunggah bukti pembayaran
- Melihat riwayat transaksi
- Menerima notifikasi

### Admin Keuangan

- Generate tagihan massal
- Mengelola data mahasiswa
- Verifikasi pembayaran manual
- Mengekspor laporan

### Superadmin

- Mengelola pengguna dan hak akses
- Mengelola semester
- Melakukan monitoring sistem

---

## Fitur Utama

- Generate tagihan massal dengan validasi anti-duplikasi
- Pembayaran menggunakan Virtual Account
- Verifikasi pembayaran manual
- Notifikasi real-time
- Pencatatan transaksi terdistribusi
- Export laporan keuangan
- Sistem multi-role
- Tampilan responsif dengan dukungan mode gelap dan terang

---

## Optimasi Performa

- Batch insert untuk efisiensi database
- Event-driven processing untuk skalabilitas
- Queue-based processing untuk menghindari blocking
- Timeout handling antar service
- Isolasi service untuk meningkatkan reliability

---

## Tantangan dan Solusi

| Tantangan | Solusi |
|----------|--------|
| Proses massal lambat | Implementasi queue dan batch processing |
| Duplikasi data | Validasi data existing |
| Konsistensi antar service | Implementasi Lamport Clock |
| Ketergantungan service | Retry dan fallback mechanism |

---

## Contoh API

### Generate Tagihan Massal

Endpoint:
POST /api/billings/bulk-generate

Request:
{
  "payment_type_id": 1,
  "semester_id": 3,
  "nominal": 700000,
  "jatuh_tempo": "2026-07-01"
}

Response:
{
  "status": "success",
  "data": {
    "created": 120,
    "skipped": 5,
    "total": 125
  }
}

---

## Instalasi

Clone repository:

git clone https://github.com/USERNAME/campuspay.git  
cd campuspay  

Setup salah satu service:

cd services/billing-service  
composer install  
cp .env.example .env  
php artisan key:generate  
php artisan migrate  
php artisan serve  

Lakukan langkah yang sama untuk service lainnya.

---

## Konfigurasi Environment

STUDENT_SERVICE_URL=http://127.0.0.1:8002  
BILLING_SERVICE_URL=http://127.0.0.1:8003  
NOTIFICATION_SERVICE_URL=http://127.0.0.1:8007  

---

## Dampak Sistem

- Mengurangi waktu proses tagihan secara signifikan
- Mengurangi kesalahan input manual
- Meningkatkan transparansi pembayaran
- Mempermudah audit transaksi

---

## Referensi

- Lamport, L. (1978)
- Fowler, M. Microservices Architecture
- Kleppmann, M. Designing Data-Intensive Applications
- RabbitMQ Documentation
- Filament PHP Documentation

---

## Penulis

CampusPay Project  
MUHAMAD IKBAL NURPADILA

---

## Lisensi

MIT License

---

## Penutup

Sistem ini menunjukkan implementasi nyata dari konsep microservices dan distributed system dalam kasus penggunaan pembayaran kampus, dengan fokus pada skalabilitas, konsistensi, dan efisiensi operasional.
