<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Reset Password | {{config('ecustomer.companyName')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="{{asset('theme/assets/css/styles.css')}}" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Harus didefinisikan SEBELUM admin-axios.js (yang memakai window.API_BASE untuk baseURL)
        window.ENV = { API_BASE_URL: "{{ env('API_BASE_URL', 'http://localhost:8080/api') }}" };
        window.API_BASE = window.ENV.API_BASE_URL;
    </script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/jspdf.umd.min.js')}}"></script>
    <script src="{{asset('js/admin-axios.js')}}"></script>
    <style>
        :root { --primary: #F37021; --secondary: #003366; }
        body { font-family: 'Inter', sans-serif; background-color: #F1F5F9; }
        .hidden { display: none; }
    </style>
</head>
<body class="min-h-screen">
<script>
    // Guard sedini mungkin (API_BASE sudah diset di <head>)
    if (!localStorage.getItem('adminAccessToken')) {
        window.location.href = '/admin/login';
    }
</script>

<!-- Header -->
<header class="bg-[var(--secondary)] text-white">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{asset('theme/assets/media/logo.png')}}" class="h-9" alt="logo">
            <div>
                <div class="font-bold leading-tight">Admin Panel</div>
                <div class="text-xs text-blue-100">{{config('ecustomer.companyName')}}</div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm" id="adminName"></span>
            <button id="btnLogout"
                    class="text-sm bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg transition">
                Logout
            </button>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-[var(--secondary)]">Permintaan Reset Password</h1>
        <button id="btnRefresh"
                class="text-sm border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg transition">
            ↻ Muat ulang
        </button>
    </div>

    <div id="loading" class="text-center py-10 text-slate-500">Memuat data...</div>

    <div id="tableContainer" class="hidden bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="text-left px-4 py-3">Nasabah ID</th>
                <th class="text-left px-4 py-3">Nama</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-left px-4 py-3">Waktu Permintaan</th>
                <th class="text-center px-4 py-3">Aksi</th>
            </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>

    <div id="emptyState" class="hidden text-center py-10 text-slate-500 bg-white rounded-xl shadow">
        Tidak ada permintaan menunggu.
    </div>
</main>

<!-- Modal hasil approve -->
<div id="resultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-bold text-emerald-700">Password Berhasil Direset</h3>
            <button type="button" id="btnResultClose" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <p class="text-sm text-slate-500 mb-4">
            Sampaikan password baru berikut ke nasabah. Nasabah wajib mengganti password saat login pertama.
        </p>
        <div class="space-y-2 text-sm bg-slate-50 rounded-lg p-4">
            <div class="flex justify-between"><span class="text-slate-500">Nasabah ID</span><span id="rNasabahId" class="font-medium"></span></div>
            <div class="flex justify-between"><span class="text-slate-500">Nama</span><span id="rNama" class="font-medium"></span></div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Password Baru</span>
                <span id="rPassword" class="font-mono font-bold text-lg text-[var(--secondary)]"></span>
            </div>
        </div>
        <div class="flex gap-2 mt-5">
            <button type="button" id="btnDownloadPdf"
                    class="flex-1 py-2.5 rounded-lg bg-[#F37021] text-white font-semibold hover:bg-[#E65D0F] transition">
                Unduh PDF
            </button>
            <button type="button" id="btnDone"
                    class="flex-1 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50 transition">
                Selesai
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loading = document.getElementById('loading');
        const tableContainer = document.getElementById('tableContainer');
        const tableBody = document.getElementById('tableBody');
        const emptyState = document.getElementById('emptyState');

        document.getElementById('adminName').textContent = localStorage.getItem('adminName') || 'Admin';

        document.getElementById('btnLogout').addEventListener('click', () => {
            localStorage.removeItem('adminAccessToken');
            localStorage.removeItem('adminName');
            window.location.href = '/admin/login';
        });
        document.getElementById('btnRefresh').addEventListener('click', loadRequests);

        function fmtTgl(s) {
            if (!s) return '-';
            const d = new Date(s);
            if (isNaN(d)) return s;
            return d.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
        }

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => (
                { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
            ));
        }

        async function loadRequests() {
            loading.classList.remove('hidden');
            tableContainer.classList.add('hidden');
            emptyState.classList.add('hidden');

            try {
                const { data } = await axios.get('/admin/reset-requests?status=PENDING');
                if (data.responseCode !== '00') {
                    alert(data.responseMessage || 'Gagal memuat data');
                    return;
                }
                const rows = data.responseData || [];
                loading.classList.add('hidden');

                if (rows.length === 0) {
                    emptyState.classList.remove('hidden');
                    return;
                }

                tableBody.innerHTML = rows.map(r => `
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 font-medium">${esc(r.nasabahId)}</td>
                        <td class="px-4 py-3">${esc(r.namaLengkap || '-')}</td>
                        <td class="px-4 py-3">${esc(r.email || '-')}</td>
                        <td class="px-4 py-3">${esc(fmtTgl(r.requestedAt))}</td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <button data-approve="${r.id}"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3 py-1.5 rounded-lg transition">Approve</button>
                            <button data-reject="${r.id}"
                                class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg transition ml-1">Tolak</button>
                        </td>
                    </tr>
                `).join('');
                tableContainer.classList.remove('hidden');

                tableBody.querySelectorAll('[data-approve]').forEach(b =>
                    b.addEventListener('click', () => approve(b.getAttribute('data-approve'), b)));
                tableBody.querySelectorAll('[data-reject]').forEach(b =>
                    b.addEventListener('click', () => reject(b.getAttribute('data-reject'), b)));
            } catch (err) {
                loading.classList.add('hidden');
                const msg = err.response?.data?.responseMessage || err.message || 'Gagal memuat data';
                alert(msg);
            }
        }

        async function approve(id, btn) {
            if (!confirm('Setujui permintaan ini dan generate password baru?')) return;
            btn.disabled = true;
            try {
                const { data } = await axios.post(`/admin/reset-requests/${id}/approve`);
                if (data.responseCode !== '00') {
                    alert(data.responseMessage || 'Gagal approve');
                    return;
                }
                showResult(data.responseData);
                loadRequests();
            } catch (err) {
                const msg = err.response?.data?.responseMessage || err.message || 'Gagal approve';
                alert(msg);
            } finally {
                btn.disabled = false;
            }
        }

        async function reject(id, btn) {
            const note = prompt('Alasan penolakan (opsional):', '');
            if (note === null) return; // batal
            btn.disabled = true;
            try {
                const { data } = await axios.post(`/admin/reset-requests/${id}/reject`, { note });
                if (data.responseCode !== '00') {
                    alert(data.responseMessage || 'Gagal menolak');
                    return;
                }
                loadRequests();
            } catch (err) {
                const msg = err.response?.data?.responseMessage || err.message || 'Gagal menolak';
                alert(msg);
            } finally {
                btn.disabled = false;
            }
        }

        // ===== Modal hasil + PDF =====
        const resultModal = document.getElementById('resultModal');
        let lastResult = null;

        function showResult(d) {
            lastResult = d;
            document.getElementById('rNasabahId').textContent = d.nasabahId || '-';
            document.getElementById('rNama').textContent = d.namaLengkap || '-';
            document.getElementById('rPassword').textContent = d.newPassword || '-';
            resultModal.classList.remove('hidden');
        }
        function closeResult() { resultModal.classList.add('hidden'); }

        document.getElementById('btnResultClose').addEventListener('click', closeResult);
        document.getElementById('btnDone').addEventListener('click', closeResult);
        resultModal.addEventListener('click', (e) => { if (e.target === resultModal) closeResult(); });

        document.getElementById('btnDownloadPdf').addEventListener('click', () => {
            if (!lastResult) return;
            downloadPdf(lastResult);
        });

        function downloadPdf(d) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit: 'pt', format: 'a4' });
            const company = @json(config('ecustomer.companyName'));
            const now = new Date().toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });

            const left = 56;
            let y = 70;

            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text(company, left, y);

            y += 22;
            doc.setFontSize(13);
            doc.text('Informasi Reset Password', left, y);

            y += 8;
            doc.setDrawColor(180);
            doc.line(left, y, 539, y);

            y += 28;
            doc.setFontSize(11);
            doc.setFont('helvetica', 'normal');

            const row = (label, value) => {
                doc.setFont('helvetica', 'normal');
                doc.text(label, left, y);
                doc.setFont('helvetica', 'bold');
                doc.text(': ' + String(value ?? '-'), left + 130, y);
                y += 22;
            };

            row('Tanggal', now);
            row('Nasabah ID', d.nasabahId);
            row('Nama', d.namaLengkap);
            row('Password Baru', d.newPassword);

            y += 14;
            doc.setFont('helvetica', 'italic');
            doc.setFontSize(10);
            doc.setTextColor(120);
            const note = 'Catatan: Demi keamanan, nasabah WAJIB mengganti password ini saat login pertama.';
            doc.text(doc.splitTextToSize(note, 483), left, y);

            doc.save(`reset-password-${d.nasabahId || 'nasabah'}.pdf`);
        }

        loadRequests();
    });
</script>
</body>
</html>
