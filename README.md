# 💳 CampusPay — Smart Distributed Payment System

> 🚀 Sistem pembayaran kampus berbasis **Microservices Architecture** dengan pendekatan **Event-Driven** dan implementasi **Lamport Logical Clock** untuk menjaga konsistensi data pada sistem terdistribusi.

---

## 🌟 Highlight

- ⚡ 8 Microservices Architecture
- 🔄 Event-Driven System (RabbitMQ)
- ⏱️ Lamport Logical Clock Implementation
- 💰 Mass Billing Automation
- 📊 Real-time Notification System
- 🌙 Modern Admin Panel (Filament + Dark Mode)

---

## 📌 Overview

**CampusPay** adalah sistem pembayaran kampus berbasis microservices yang dirancang untuk:

- Mengelola tagihan mahasiswa secara otomatis
- Memproses pembayaran (Virtual Account & Manual)
- Mencatat transaksi secara real-time
- Mengirim notifikasi ke mahasiswa
- Menghasilkan laporan keuangan

### 🎯 Tujuan Sistem
- Mengurangi proses manual
- Menghindari duplikasi tagihan
- Meningkatkan transparansi transaksi
- Menyediakan sistem scalable & modern

---

## 🧠 My Role

Sebagai developer, saya:

- Mendesain arsitektur sistem microservices
- Mengembangkan backend menggunakan Laravel
- Mengimplementasikan event-driven architecture
- Menerapkan Lamport Logical Clock
- Mendesain database & API
- Membangun admin panel menggunakan Filament

---

## 🏗️ System Architecture

📍 **[Letakkan DIAGRAM ARSITEKTUR di sini]**

### 🔹 Core Services (REST API)
- Auth Service → Login & Authorization
- Student Service → Data mahasiswa & semester
- Billing Service → Manajemen tagihan
- VA Service → Generate Virtual Account

### 🔹 Event-Driven Services
- Payment Service → Proses pembayaran
- Transaction Service → Logging transaksi (Lamport Clock)
- Notification Service → Kirim notifikasi
- Report Service → Generate laporan

### 🔹 Message Broker
- RabbitMQ → Penghubung antar service

---

## 🔄 Business Flow

📍 **[Letakkan DIAGRAM USE CASE / FLOW di sini]**

Alur sistem:

1. Admin generate tagihan massal
2. Billing Service publish event ke RabbitMQ
3. Payment Service memproses pembayaran
4. Transaction Service mencatat transaksi (Lamport Clock)
5. Notification Service mengirim notifikasi
6. Mahasiswa melakukan pembayaran
7. Status tagihan terupdate otomatis

---

## ⏱️ Distributed System Concept

📍 **[Letakkan DIAGRAM LAMPORT CLOCK di sini]**

### 🧠 Lamport Logical Clock

Digunakan untuk:

- Menjaga urutan event antar service
- Sinkronisasi sistem tanpa shared clock
- Menghindari konflik data
- Audit log yang konsisten

---

## 🗄️ Database Design

📍 **[Letakkan ERD di sini]**

Entity utama:

- Mahasiswa
- Semester
- Tagihan
- Payment Type
- Virtual Account
- Transaction
- Notification

---

## ⚙️ Tech Stack

| Layer        | Technology              |
|--------------|------------------------|
| Backend      | Laravel (Microservices)|
| Frontend     | Filament Admin Panel   |
| Database     | MySQL                  |
| Messaging    | RabbitMQ               |
| API          | RESTful API            |
| Architecture | Microservices          |
| Pattern      | Event-Driven           |
| Logging      | Lamport Logical Clock  |

---

## 📦 Project Structure
campuspay/
│
├── services/
│ ├── auth-service/
│ ├── student-service/
│ ├── billing-service/
│ ├── payment-service/
│ ├── transaction-service/
│ ├── notification-service/
│ ├── report-service/
│ └── va-service/
│
├── docs/
├── README.md
