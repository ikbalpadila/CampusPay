# 💳 CampusPay — Distributed Microservices Payment System

Sistem pembayaran kampus berbasis **Microservices Architecture** dengan pendekatan **Event-Driven** dan implementasi **Lamport Logical Clock** untuk menjaga konsistensi transaksi pada sistem terdistribusi.

---

## 📌 Overview

**CampusPay** adalah sistem pembayaran terdistribusi yang dirancang untuk menangani proses:

- Generate tagihan mahasiswa
- Pembayaran (Virtual Account & Manual)
- Pencatatan transaksi
- Notifikasi real-time
- Pelaporan keuangan

Sistem ini dibangun menggunakan pendekatan:

- Microservices Architecture  
- REST API + Event-Driven Communication  
- Message Broker (RabbitMQ)  
- Lamport Logical Clock  

---

## 🏗️ Architecture

📍 *Letakkan diagram arsitektur di sini*

Sistem terdiri dari beberapa service independen:

### 🔹 Core Services (HTTP REST)
- Auth Service → Autentikasi & Authorization  
- Student Service → Data mahasiswa & semester  
- Billing Service → Manajemen tagihan  
- VA Service → Generate Virtual Account  

### 🔹 Event-Driven Services
- Payment Service → Proses pembayaran  
- Transaction Service → Logging transaksi (Lamport Clock)  
- Notification Service → Kirim notifikasi  
- Report Service → Generate laporan PDF/Excel  

### 🔹 Message Broker
- RabbitMQ → Event communication antar service  

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
