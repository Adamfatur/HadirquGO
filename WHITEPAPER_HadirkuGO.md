# WHITE PAPER SISTEM HadirkuGO

## 1. Executive Summary

HadirkuGO adalah platform kehadiran digital berbasis QR Code yang dirancang untuk mentransformasi cara organisasi, institusi pendidikan, dan bisnis mengelola kehadiran anggotanya. Sistem ini menggabungkan teknologi QR Code dinamis dengan elemen gamifikasi komprehensif untuk menciptakan pengalaman kehadiran yang tidak hanya akurat, tetapi juga memotivasi.

Masalah utama yang diselesaikan oleh HadirkuGO adalah rendahnya engagement dan akurasi dalam sistem kehadiran konvensional. Sistem absensi tradisional — baik manual maupun digital — cenderung monoton, mudah dimanipulasi, dan tidak memberikan insentif bagi pengguna untuk hadir secara konsisten.

HadirkuGO menawarkan solusi melalui pendekatan gamifikasi yang terintegrasi: setiap aktivitas kehadiran menghasilkan poin, poin menentukan level pengguna, dan performa kehadiran ditampilkan dalam leaderboard kompetitif. Sistem ini juga dilengkapi dengan fitur Challenge antar pengguna, Achievement badges, Quiz interaktif, Reward system, serta SaiQu — asisten AI berbasis Google Gemini yang membantu pengguna memahami data kehadiran mereka secara real-time.

Keunggulan utama HadirkuGO:
- QR Code dinamis dengan masa berlaku 10 detik untuk mencegah manipulasi
- Sistem gamifikasi multi-layer (Poin, Level, Leaderboard, Achievement, Reward)
- AI Agent (SaiQu) terintegrasi untuk analisis data personal
- Multi-role architecture (Admin, Owner, Lecturer, Student, Parent)
- Multi-tenant business model yang mendukung banyak organisasi dalam satu platform

---

## 2. Background & Problem Statement

### 2.1 Latar Belakang

Dalam lingkungan pendidikan dan bisnis modern, pencatatan kehadiran merupakan komponen kritis dalam evaluasi kinerja dan kedisiplinan. Namun, sistem kehadiran konvensional menghadapi berbagai tantangan fundamental yang menghambat efektivitasnya.

### 2.2 Permasalahan Utama

**Rendahnya Akurasi Pencatatan**
Sistem absensi manual (tanda tangan, kartu hadir) sangat rentan terhadap manipulasi seperti titip absen. Bahkan sistem digital berbasis fingerprint atau kartu RFID masih memiliki celah keamanan dan memerlukan investasi hardware yang signifikan.

**Kurangnya Motivasi Kehadiran**
Sistem kehadiran tradisional bersifat pasif — hanya mencatat tanpa memberikan feedback atau insentif. Pengguna tidak memiliki motivasi intrinsik maupun ekstrinsik untuk meningkatkan konsistensi kehadiran mereka.

**Keterbatasan Analisis Data**
Data kehadiran yang dikumpulkan jarang dimanfaatkan secara optimal. Organisasi kesulitan mengidentifikasi pola kehadiran, mengukur produktivitas berbasis durasi, atau memberikan penghargaan kepada anggota yang konsisten.

**Fragmentasi Manajemen Multi-Lokasi**
Organisasi dengan banyak lokasi operasional membutuhkan sistem terpusat yang mampu mengelola kehadiran di berbagai titik secara simultan, lengkap dengan analisis per lokasi.

### 2.3 Kelemahan Solusi yang Sudah Ada

Solusi kehadiran digital yang ada di pasaran umumnya:
- Hanya berfokus pada pencatatan tanpa elemen engagement
- Tidak menyediakan mekanisme gamifikasi untuk memotivasi pengguna
- Tidak memiliki kemampuan AI untuk analisis data personal
- Terbatas pada satu model bisnis (single-tenant)
- Tidak mendukung multi-role dengan granularitas akses yang berbeda

---

## 3. Objectives / Goals

### 3.1 Tujuan Sistem

1. **Meningkatkan Akurasi Kehadiran** — Menggunakan QR Code dinamis dengan token yang expire dalam 10 detik untuk memastikan kehadiran fisik yang valid
2. **Memotivasi Konsistensi** — Menerapkan gamifikasi komprehensif (poin, level, leaderboard, achievement) untuk mendorong kehadiran yang konsisten
3. **Menyediakan Insight Berbasis Data** — Memberikan analisis kehadiran yang mendalam melalui statistik, ranking, dan AI assistant
4. **Mendukung Multi-Organisasi** — Menyediakan platform multi-tenant yang dapat digunakan oleh berbagai organisasi secara independen
5. **Membangun Komunitas Kompetitif** — Menciptakan ekosistem kompetisi sehat melalui challenge, leaderboard, dan rival system

### 3.2 Target Pengguna

| Role | Deskripsi | Kapabilitas Utama |
|------|-----------|-------------------|
| **Admin** | Administrator sistem | Manajemen pengguna global, pengaturan role |
| **Owner** | Pemilik bisnis/organisasi | Manajemen bisnis, lokasi, staff, quiz, banner, produk |
| **Lecturer** | Pengajar/supervisor | Manajemen tim, monitoring kehadiran, evaluasi, QR check-in/out |
| **Student** | Anggota/peserta | Check-in/out via QR, akses gamifikasi, challenge, quiz |
| **Parent** | Orang tua/wali | Monitoring kehadiran anak (dashboard khusus) |

### 3.3 Use Case Utama

- Institusi pendidikan yang ingin meningkatkan kedisiplinan kehadiran mahasiswa
- Organisasi bisnis yang membutuhkan tracking kehadiran karyawan di multi-lokasi
- Komunitas atau kelompok kerja yang ingin membangun budaya kehadiran kompetitif
- Lembaga pelatihan yang membutuhkan sistem kehadiran dengan elemen engagement

---

## 4. System Overview

### 4.1 Gambaran Umum

HadirkuGO adalah aplikasi web full-stack yang dibangun di atas framework Laravel 8. Sistem ini mengadopsi arsitektur monolitik dengan pola MVC (Model-View-Controller) dan dilengkapi dengan scheduled commands untuk pemrosesan data berkala. Platform ini mendukung autentikasi via Google OAuth dan menyediakan dashboard yang berbeda untuk setiap role pengguna.

### 4.2 Fitur Utama

**Modul Kehadiran (Attendance)**
- Check-in dan Check-out berbasis QR Code dinamis
- Token QR dengan masa berlaku 10 detik
- Tracking durasi per lokasi dan total durasi harian
- Multi-location support dalam satu sesi
- Notifikasi email otomatis saat check-in dan check-out
- Kalender kehadiran personal

**Modul Gamifikasi**
- Sistem Poin: 1 poin per check-in + poin durasi saat check-out (1 poin per menit)
- Sistem Level: Hierarki level berdasarkan akumulasi total poin
- Leaderboard Multi-Kategori: Top Points, Top Sessions, Top Duration, Top Locations, Top Teams (harian, mingguan, bulanan, tahunan)
- Achievement Badges: Daily MP (Morning Person), Longest Duration, Adventure Student
- Weekly Ranking dengan tracking pergerakan peringkat
- Rival System: Pengguna dapat memilih rival untuk perbandingan performa

**Modul Challenge**
- Tantangan antar pengguna (1v1)
- Tipe challenge: Points atau Duration
- Durasi challenge: 1-7 hari
- Tracking hasil: Win/Lose, Win Rate, Win Streak, Lose Streak

**Modul Quiz & Super Quiz**
- Quiz standar per bisnis dengan multiple choice
- Super Quiz dengan sistem eliminasi (gagal = game over)
- Tracking attempt dan hasil per pengguna

**Modul Reward & Redeem**
- Sistem reward berbasis probabilitas dengan fairness logic
- Produk yang dapat ditukar dengan poin (Redeem)
- Waiting list dan approval system untuk redemption
- Spin wheel mechanism untuk distribusi reward

**Modul Tim & Organisasi**
- Manajemen tim dengan leader, manager, dan member
- Attendance recap per tim
- Team leaderboard
- Transfer leadership dan dissolve team

**SaiQu — AI Assistant**
- Chatbot berbasis Google Gemini (gemini-2.5-flash dengan fallback)
- RAG (Retrieval-Augmented Generation) dengan data real-time pengguna
- Konteks otomatis berdasarkan topik pertanyaan
- Rate limiting (50 pesan/hari, 10 pesan/menit)
- Conversation history management

**Modul Feedback**
- Sistem feedback dengan like/unlike
- Status tracking (pending, reviewed, resolved)
- Admin moderation

### 4.3 Alur Kerja Sistem (Workflow)

```
┌─────────────────────────────────────────────────────────────────┐
│                    ALUR KEHADIRAN HadirkuGO                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  [Student/Lecturer]          [QR Scanner di Lokasi]             │
│        │                            │                           │
│        ▼                            │                           │
│  1. Buka Menu Check-in              │                           │
│        │                            │                           │
│        ▼                            │                           │
│  2. Generate QR Code ──────────────►│                           │
│     (Token 10 detik)                │                           │
│                                     ▼                           │
│                              3. Scan QR Code                    │
│                                     │                           │
│                                     ▼                           │
│                              4. Validasi Token                  │
│                                 ├── Token valid?                │
│                                 │   ├── Ya ──► 5. Proses        │
│                                 │   └── Tidak ► Error           │
│                                 │                               │
│                                 ▼                               │
│                          5. Record Attendance                   │
│                              ├── Check-in:                      │
│                              │   • Catat waktu & lokasi         │
│                              │   • +1 poin                      │
│                              │   • Kirim email notifikasi       │
│                              │   • Buat notifikasi sistem       │
│                              │                                  │
│                              └── Check-out:                     │
│                                  • Hitung durasi sesi           │
│                                  • +N poin (N = menit durasi)   │
│                                  • Kirim email notifikasi       │
│                                  • Buat notifikasi sistem       │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              BACKGROUND PROCESSES (Hourly)                │   │
│  │  • Update Leaderboard (semua kategori & periode)          │   │
│  │  • Update User Statistics                                 │   │
│  │  • Update Weekly Rankings                                 │   │
│  │  • Assign Achievement (Daily MP, Longest Duration, dll)   │   │
│  │  • Sync Leaderboard Frames & Titles                       │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---
