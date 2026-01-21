@extends('layouts.app')

@section('title', 'Rekening Kredit | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold mb-4 text-[#003D73] flex items-center gap-2">
        <i class="ki-solid ki-wallet text-[#F36F21] text-lg"></i>
        Daftar Rekening Kredit
    </h1>

    <div class="bg-white shadow rounded-xl border-l-4 border-[#F36F21] p-6">
        <div id="loading" class="flex items-center gap-2 text-gray-500 text-sm italic">
            <i class="ki-solid ki-loading text-[#F36F21] animate-spin"></i>
            <span>Memuat data kredit...</span>
        </div>

        <div id="tableContainer" class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-[#003D73] text-white">
                <tr>
                    <th class="text-left px-4 py-2">No. Rekening</th>
                    <th class="text-left px-4 py-2">Nama Produk</th>
                    <th class="text-right px-4 py-2">Baki Debet</th>
                    <th class="text-center px-4 py-2">Status</th>
                    <th class="text-center px-4 py-2">Aksi</th>
                </tr>
                </thead>
                <tbody id="kreditTable" class="divide-y"></tbody>
            </table>
        </div>
        <div id="cardContainer" class="block md:hidden space-y-4"></div>

    </div>

@endsection

@push('scripts')
    <script>
        (async function () {
            const token = localStorage.getItem('accessToken');
            if (!token) return window.location.href = '/login';

            const loading = document.getElementById('loading');
            const tbody = document.getElementById('kreditTable');
            const cardContainer = document.getElementById('cardContainer');

            // tampilkan loading saja
            loading.classList.remove('hidden');

            try {
                const { data } = await axios.get('/secure/portofolio-kredit');

                if (data?.responseCode !== '00') {
                    notify('error', 'Gagal memuat data kredit');
                    return;
                }

                const list = data.responseData || [];

                if (!list.length) {
                    loading.innerHTML = `
                <i class="ki-solid ki-information-2 text-[#F36F21]"></i>
                <span>Tidak ada rekening kredit aktif.</span>
            `;
                    return;
                }

                // DESKTOP TABLE
                tbody.innerHTML = list.map(t => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 font-medium text-[#003D73]">${t.noRekening}</td>
                <td class="px-4 py-2">${t.namaProduk || '-'}</td>
                <td class="px-4 py-2 text-right font-semibold">${rupiah(t.saldoTerakhir)}</td>
                <td class="px-4 py-2 text-center">
                    <span class="px-2 py-1 text-xs rounded-full ${
                    t.statusRekening === 'AKTIF'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-600'
                }">
                        ${t.statusRekening || '-'}
                    </span>
                </td>
                <td class="px-4 py-2 text-center">
                    <a href="/rekening/kredit/detail?norek=${encodeURIComponent(t.noRekening)}"
                       class="text-[#F36F21] font-medium hover:text-[#003D73]">
                        Detail
                    </a>
                </td>
            </tr>
        `).join('');

                // MOBILE CARD
                cardContainer.innerHTML = list.map(t => `
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs text-gray-500">No. Rekening</p>
                        <p class="font-semibold text-[#003D73]">${t.noRekening}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full ${
                    t.statusRekening === 'AKTIF'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-600'
                }">
                        ${t.statusRekening || '-'}
                    </span>
                </div>

                <p class="text-sm text-gray-500">Produk</p>
                <p class="font-medium mb-2">${t.namaProduk || '-'}</p>

                <p class="text-sm text-gray-500">Baki Debet Terakhir</p>
                <p class="font-semibold mb-3">${rupiah(t.saldoTerakhir)}</p>

                <a href="/rekening/kredit/detail?norek=${encodeURIComponent(t.noRekening)}"
                   class="block text-center bg-[#F36F21] text-white rounded-lg py-2 text-sm font-medium">
                    Lihat Detail
                </a>
        `).join('');

                // SEMBUNYIKAN LOADING
                loading.classList.add('hidden');

            } catch (err) {
                console.error(err);
                loading.textContent = 'Gagal memuat data.';
            }
        })();
    </script>
@endpush
