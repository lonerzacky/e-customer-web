# 🏦 E-Customer Web

Frontend web portal untuk sistem **E-Customer** — aplikasi yang memberikan akses nasabah untuk melihat informasi rekening, portofolio produk, dan aktivitas transaksi secara real-time.

## 🚀 Fitur Utama
- 🔐 **Login Nasabah** — autentikasi via token API `apiecustomer`
- 📊 **Dashboard Ringkasan** — total saldo tabungan, deposito, kredit, dan portofolio
- 💰 **Tabungan** — daftar rekening, saldo akhir, dan mutasi transaksi
- 🏦 **Deposito** — daftar penempatan deposito aktif
- 💳 **Kredit** — daftar rekening kredit, jadwal angsuran, dan realisasi
- 👤 **Profil Nasabah** — data pribadi dan informasi kontak
- 🕒 **Sinkronisasi Real-time** — update data langsung dari core system
- 🧩 **Responsif & Ringan** — dibangun dengan Tailwind dan KeenIcons

---

## 🛠️ Teknologi
| Layer              | Teknologi                                                   |
|--------------------|-------------------------------------------------------------|
| Frontend Framework | **Laravel Blade**                                           |
| CSS Framework      | **TailwindCSS**                                             |
| Icons              | **KeenIcons (Metronic)**                                    |
| Charting           | **ApexCharts / Chart.js**                                   |
| HTTP Client        | **Axios**                                                   |
| Auth               | JWT via `accessToken` & `refreshToken`                      |
| Backend API        | [`apiecustomer`](https://github.com/azharbyte/apiecustomer) |

---

## ⚙️ Setup Development

### 1️⃣ Clone Repository
```bash
git clone https://github.com/lonerzacky/-e-customer-web.git
cd e-customer-web
```

### **2️⃣ Install Dependencies**
```bash
composer install
npm install
```

### **3️⃣ Copy .env Example**
```bash
cp .env.example .env
```
Lalu ubah konfigurasi sesuai environment:
```env
APP_NAME="E-Customer Web"
APP_URL=http://localhost:8000

API_BASE_URL=http://localhost:8080/api
```

### **4️⃣ Generate Key & Build Assets**
```bash
php artisan key:generate
npm run dev
```

### **5️⃣ Jalankan Server Lokal**
```bash
php artisan serve
```

## **🧩 Struktur Folder Utama**
```tree
e-customer-web/
├── app/Http/Controllers/     # Controller untuk halaman
├── resources/views/          # Blade template (Dashboard, Tabungan, Kredit, dll)
├── public/theme/             # Assets (Tailwind, KeenIcons, ChartJS)
├── routes/web.php            # Routing web utama
├── package.json              # Frontend build scripts
└── README.md
```

## **🔒 Autentikasi**

Setiap request API menggunakan header:
```auth
Authorization: Bearer <accessToken>
```

Akses token diperbarui otomatis oleh interceptor axios menggunakan refreshToken.

## **📦 Build untuk Produksi**
```bash
npm run build
```

Output hasil build akan tersedia di:
```txt
/public/build
```
