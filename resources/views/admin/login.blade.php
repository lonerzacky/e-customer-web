<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | {{config('ecustomer.companyName')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="{{asset('theme/assets/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <style>
        :root {
            --primary: #F37021;
            --secondary: #003366;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .hidden { display: none; }

        .kt-alert { transition: all 0.3s ease; }
        .kt-alert-success { background-color: #ecfdf5; border-color: #34d399; color: #065f46; }
        .kt-alert-warning { background-color: #fffbeb; border-color: #facc15; color: #78350f; }
        .kt-alert-error   { background-color: #fef2f2; border-color: #f87171; color: #991b1b; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="bg-[var(--secondary)] text-white flex flex-col items-center p-8">
        <img src="{{asset('theme/assets/media/logo.png')}}" class="h-16 mb-3" alt="{{config('ecustomer.companyName')}}">
        <h1 class="text-xl font-bold text-center">Admin Panel</h1>
        <p class="text-sm text-blue-100">{{config('ecustomer.companyName')}}</p>
    </div>

    <div class="p-8">
        <h2 class="text-xl font-bold text-[var(--secondary)] mb-1">Login Admin</h2>
        <p class="text-slate-500 mb-6 text-sm">Masuk untuk mengelola permintaan reset password</p>

        <div id="alertBox" class="kt-alert hidden mb-4 rounded-lg border p-3 text-sm">
            <span id="alertText"></span>
        </div>

        <form id="adminLoginForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" id="username" name="username" autocomplete="username"
                       class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#F37021] focus:border-[#F37021]"
                       placeholder="Masukkan username"/>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password"
                       class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#F37021] focus:border-[#F37021]"
                       placeholder="Masukkan password"/>
            </div>
            <button type="submit" id="btnLogin"
                    class="w-full py-3 rounded-lg bg-[#F37021] text-white font-semibold hover:bg-[#E65D0F] transition">
                Masuk
            </button>
        </form>

        <p class="text-xs text-slate-400 text-center mt-8">
            © {{ date('Y') }} {{config('ecustomer.companyName')}}
        </p>
    </div>
</div>

<script>
    window.ENV = { API_BASE_URL: "{{ env('API_BASE_URL', 'http://localhost:8080/api') }}" };
    window.API_BASE = window.ENV.API_BASE_URL;
    const API_BASE = window.API_BASE;

    document.addEventListener('DOMContentLoaded', function () {
        // Kalau sudah login, langsung ke daftar
        if (localStorage.getItem('adminAccessToken')) {
            window.location.href = '/admin/reset-requests';
            return;
        }

        const form = document.getElementById('adminLoginForm');
        const btn = document.getElementById('btnLogin');
        const alertBox = document.getElementById('alertBox');
        const alertText = document.getElementById('alertText');

        function showAlert(type, message) {
            alertBox.classList.remove('kt-alert-success', 'kt-alert-warning', 'kt-alert-error', 'hidden');
            alertBox.classList.add(type === 'success' ? 'kt-alert-success'
                : (type === 'warning' ? 'kt-alert-warning' : 'kt-alert-error'));
            alertText.textContent = message;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBox.classList.add('hidden');

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) {
                showAlert('error', 'Username dan password wajib diisi');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const res = await axios.post(`${API_BASE}/admin/login`, { username, password });
                const data = res.data?.responseData;
                localStorage.setItem('adminAccessToken', data.accessToken);
                localStorage.setItem('adminName', data.namaLengkap || data.username || 'Admin');
                window.location.href = '/admin/reset-requests';
            } catch (err) {
                const message = err.response?.data?.responseMessage || err.message || 'Login gagal.';
                showAlert('error', message);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Masuk';
            }
        });
    });
</script>
</body>
</html>
