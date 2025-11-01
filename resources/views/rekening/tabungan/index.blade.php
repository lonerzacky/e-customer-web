@extends('layouts.app')

@section('title', 'Rekening Tabungan | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold mb-4 text-[#003D73] flex items-center gap-2">
        <i class="ki-solid ki-wallet text-[#F36F21] text-lg"></i>
        Daftar Rekening Tabungan
    </h1>

    <div class="bg-white shadow rounded-xl border-l-4 border-[#F36F21] p-6">
        <div id="loading" class="flex items-center gap-2 text-gray-500 text-sm italic">
            <i class="ki-solid ki-loading text-[#F36F21] animate-spin"></i>
            <span>Memuat data tabungan...</span>
        </div>
        <div id="tableContainer" class="hidden overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-[#003D73] text-white">
                <tr>
                    <th class="text-left px-4 py-2 font-medium">No. Rekening</th>
                    <th class="text-left px-4 py-2 font-medium">Nama Produk</th>
                    <th class="text-right px-4 py-2 font-medium">Saldo Akhir</th>
                    <th class="text-center px-4 py-2 font-medium">Status</th>
                    <th class="text-center px-4 py-2 font-medium">Aksi</th>
                </tr>
                </thead>
                <tbody id="tabunganTable" class="divide-y divide-gray-100">
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (async function () {
            const token = localStorage.getItem('accessToken');
            if (!token) return window.location.href = '/login';

            const rupiah = (n) => `Rp ${Number(n || 0).toLocaleString('id-ID')}`;

            try {
                const { data } = await axios.get('/secure/portofolio-tabungan');
                if (data?.responseCode !== '00') notify('error','Gagal memuat data tabungan');

                const list = data.responseData || [];
                const tbody = document.getElementById('tabunganTable');
                const loading = document.getElementById('loading');
                const tableContainer = document.getElementById('tableContainer');

                loading.innerHTML = `<i class="ki-solid ki-loading text-[#F36F21] animate-spin"></i>
                <span>Memuat data tabungan...</span>`;

                if (!list.length) {
                    loading.innerHTML = `<i class="ki-solid ki-information-2 text-[#F36F21]"></i>
                    <span>Tidak ada rekening tabungan aktif.</span>`;
                    return;
                }

                loading.classList.add('hidden');
                tableContainer.classList.remove('hidden');

                tbody.innerHTML = list.map(t => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-[#003D73] font-medium">${t.noRekening}</td>
                <td class="px-4 py-2">${t.namaProduk || '-'}</td>
                <td class="px-4 py-2 text-right font-semibold">${rupiah(t.saldoTerakhir)}</td>
                <td class="px-4 py-2 text-center">
                    <span class="px-2 py-1 text-xs rounded-full ${t.statusRekening === 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                        ${t.statusRekening || '-'}
                    </span>
                </td>
                <td class="px-4 py-2 text-center">
                   <a href="/rekening/tabungan/detail?norek=${encodeURIComponent(t.noRekening || t.no_rekening)}"
   class="inline-flex items-center gap-1 text-[#F36F21] hover:text-[#003D73] text-sm font-medium transition">
    <i class="ki-outline ki-eye text-base"></i>
    Detail
</a>
                </td>
            </tr>
        `).join('');
            } catch (err) {
                console.error('Error tabungan:', err);
                document.getElementById('loading').textContent = 'Gagal memuat data. Silakan login ulang.';
            }
        })();
    </script>
@endpush
