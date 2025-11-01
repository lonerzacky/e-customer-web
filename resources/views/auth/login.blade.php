<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Nasabah | {{config('ecustomer.companyName')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="{{asset('theme/assets/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{asset('theme/assets/vendors/ktui/ktui.min.js')}}"></script>
    <script src="{{asset('theme/assets/js/core.bundle.js')}}"></script>
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

        #errorMessage {
            transition: opacity 0.2s ease;
        }

        .hidden {
            display: none;
        }

        .kt-alert-success {
            background-color: #ecfdf5; /* green-50 */
            border-color: #34d399; /* green-400 */
            color: #065f46; /* green-800 */
        }

        .kt-alert-warning {
            background-color: #fffbeb; /* amber-50 */
            border-color: #facc15; /* amber-400 */
            color: #78350f; /* amber-900 */
        }

        .kt-alert-error {
            background-color: #fef2f2; /* red-50 */
            border-color: #f87171; /* red-400 */
            color: #991b1b; /* red-800 */
        }

        .kt-alert {
            transition: all 0.3s ease;
            align-items: start;
        }

        .kt-alert-icon svg {
            stroke-width: 2;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row overflow-hidden">
    <!-- Left Panel -->
    <div class="md:w-1/2 bg-[var(--secondary)] text-white flex flex-col justify-center items-center p-8">
        <img src="{{asset('theme/assets/media/logo.png')}}" class="h-20 mb-4" alt="{{config('ecustomer.companyName')}}">
        <h1 class="text-2xl font-bold mb-2 text-center">{{config('app.name')}}
            - {{config('ecustomer.companyName')}}</h1>
        <p class="text-sm text-center text-blue-100">{{config('ecustomer.tagLine')}}</p>
        <div class="mt-8 text-sm text-center text-blue-200">
            <p>“Menjadi BPR yang kuat, dipercaya dan selalu dihati masyarakat di seluruh wilayah kerja Kabupaten Pati
                dan sekitarnya.”</p>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="md:w-1/2 p-8 flex flex-col justify-center">
        <h2 class="text-2xl font-bold text-[var(--secondary)] mb-1">Login Nasabah</h2>
        <p class="text-slate-500 mb-6">Masukkan ID Nasabah dan Password Anda</p>
        <div id="errorMessage"
             class="kt-alert hidden mb-4 flex items-center gap-2 rounded-lg border p-3">
            <div class="kt-alert-icon">
                <svg id="alertIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path id="alertIconPath1" d="M12 16v-4"></path>
                    <path id="alertIconPath2" d="M12 8h.01"></path>
                </svg>
            </div>
            <div class="kt-alert-title flex-1">&nbsp;</div>
            <div class="kt-alert-actions">
                <button type="button" class="kt-alert-close"
                        onclick="document.getElementById('errorMessage').classList.add('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-x">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nasabah ID</label>
                <input
                    type="text"
                    id="nasabahId"
                    name="nasabahId"
                    class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#F37021] focus:border-[#F37021]"
                    placeholder="Masukkan ID Nasabah"
                />
                <p class="text-xs text-red-600 mt-1 hidden" id="errNasabahId"></p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-[#F37021] focus:border-[#F37021]"
                    placeholder="Masukkan Password"
                />
                <p class="text-xs text-red-600 mt-1 hidden" id="errPassword"></p>
            </div>

            <button
                type="submit"
                id="btnLogin"
                class="w-full py-3 rounded-lg bg-[#F37021] text-white font-semibold hover:bg-[#E65D0F] transition"
            >
                Masuk
            </button>
        </form>

        <div
            id="errorMessage"
            class="hidden rounded-lg border border-red-300 bg-red-50 p-3 text-red-700 mt-4 flex items-center justify-between"
        >
            <span id="errorText"></span>
            <button onclick="this.parentElement.classList.add('hidden')" class="text-red-700 hover:text-red-900">
                ✕
            </button>
        </div>

        <p class="text-xs text-slate-400 text-center mt-8">
            © {{ date('Y') }} {{config('ecustomer.companyName')}}<br>
        </p>
    </div>
</div>

<script>
    window.ENV = {
        API_BASE_URL: "{{ env('API_BASE_URL', 'http://localhost:8080/api') }}"
    };
    const API_BASE = window.ENV.API_BASE_URL;

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('btnLogin');
        const errMsg = document.getElementById('errorMessage');
        const API_BASE = window.ENV?.API_BASE_URL || 'http://localhost:8080/api';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            ['nasabahId', 'password'].forEach(id => {
                const input = document.getElementById(id);
                const err = document.getElementById('err' + id.charAt(0).toUpperCase() + id.slice(1));
                input.classList.remove('border-red-500');
                err.classList.add('hidden');
            });
            errMsg.classList.add('hidden');

            const nasabahId = document.getElementById('nasabahId').value.trim();
            const password = document.getElementById('password').value.trim();

            let valid = true;

            if (nasabahId === '') {
                showError('nasabahId', 'ID Nasabah wajib diisi');
                valid = false;
            }
            if (password === '') {
                showError('password', 'Password wajib diisi');
                valid = false;
            } else if (password.length < 6) {
                showError('password', 'Password minimal 6 karakter');
                valid = false;
            }

            if (!valid) return;

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const res = await axios.post(`${API_BASE}/login`, {nasabahId, password});
                const data = res.data?.responseData;
                localStorage.setItem('accessToken', data.accessToken);
                localStorage.setItem('refreshToken', data.refreshToken);
                window.location.href = '/dashboard';
            } catch (err) {
                const message =
                    err.response?.data?.responseMessage ||
                    err.message ||
                    'Login gagal. Periksa ID dan Password Anda.';

                const code = err.response?.data?.responseCode;
                if (code === '00') showAlert('success', message);
                else if (code === '99') showAlert('warning', message);
                else showAlert('error', message);
            } finally {
                btn.disabled = false;
                btn.textContent = 'Masuk';
            }
        });

        function showError(id, message) {
            const input = document.getElementById(id);
            const err = document.getElementById('err' + id.charAt(0).toUpperCase() + id.slice(1));
            input.classList.add('border-red-500');
            err.textContent = message;
            err.classList.remove('hidden');
        }

        function showAlert(type = 'error', message = 'Terjadi kesalahan.') {
            const box = document.getElementById('errorMessage');
            const title = box.querySelector('.kt-alert-title');
            const path1 = box.querySelector('#alertIconPath1');
            const path2 = box.querySelector('#alertIconPath2');

            box.classList.remove('kt-alert-success', 'kt-alert-warning', 'kt-alert-error', 'hidden');

            switch (type) {
                case 'success':
                    box.classList.add('kt-alert-success');
                    path1.setAttribute('d', 'M5 13l4 4L19 7');
                    path2.setAttribute('d', '');
                    break;
                case 'warning':
                    box.classList.add('kt-alert-warning');
                    path1.setAttribute('d', 'M12 16h.01');
                    path2.setAttribute('d', 'M12 8h.01');
                    break;
                default:
                    box.classList.add('kt-alert-error');
                    path1.setAttribute('d', 'M12 16v-4');
                    path2.setAttribute('d', 'M12 8h.01');
                    break;
            }

            title.textContent = message;
        }
    });
</script>
</body>
</html>
