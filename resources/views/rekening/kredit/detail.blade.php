@extends('layouts.app')

@section('title', 'Detail Kredit | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold text-[#003D73] mb-2 flex items-center gap-2">
        <i class="ki-solid ki-document text-[#F36F21] text-lg"></i>
        Detail Kredit
    </h1>

    <div id="meta" class="text-sm text-slate-500 mb-5"></div>

    <div class="grid md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#F36F21]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Informasi Kredit</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>No Rekening</dt>
                <dd class="col-span-2" id="noRek">-</dd>
                <dt>No SPK</dt>
                <dd class="col-span-2" id="noSpk">-</dd>
                <dt>Produk</dt>
                <dd class="col-span-2" id="produk">-</dd>
                <dt>Suku Bunga</dt>
                <dd class="col-span-2" id="bunga">-</dd>
                <dt>Tgl Realisasi</dt>
                <dd class="col-span-2" id="tglRealisasi">-</dd>
                <dt>Tgl Jatuh Tempo</dt>
                <dd class="col-span-2" id="tglJt">-</dd>
                <dt>Jangka Waktu</dt>
                <dd class="col-span-2" id="jkw">-</dd>
                <dt>Periode Tagihan</dt>
                <dd class="col-span-2" id="periodeTagihan">-</dd>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#003D73]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Detail Pembiayaan</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>No Alternatif</dt>
                <dd class="col-span-2" id="noAlt">-</dd>
                <dt>Nominal Pinjaman</dt>
                <dd class="col-span-2" id="nominal">-</dd>
                <dt>Jenis Kredit</dt>
                <dd class="col-span-2" id="jenisKredit">-</dd>
                <dt>Status</dt>
                <dd class="col-span-2" id="status">-</dd>
                <dt>Verifikasi</dt>
                <dd class="col-span-2" id="verif">-</dd>
                <dt>Saldo Akhir</dt>
                <dd class="col-span-2 font-semibold text-[#003D73]" id="saldoAkhir">Rp 0</dd>
            </dl>
        </div>
    </div>

    <div class="bg-white border-l-4 border-[#F36F21] rounded-xl shadow p-5 mb-8">
        <h2 class="text-lg font-semibold flex items-center gap-2 mb-3 text-[#003D73]">
            <i class="ki-solid ki-chart-line text-[#F36F21] text-lg"></i>
            Riwayat Kredit
        </h2>

        <div class="flex flex-wrap items-end gap-4 mb-4 text-sm">
            <!-- Tgl Awal -->
            <div class="flex flex-col">
                <label for="tglAwal" class="text-xs text-gray-500 mb-1">Tgl Awal</label>
                <input type="text" id="tglAwal"
                       class="flatpickr-input border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#003D73] focus:border-[#003D73] transition"
                       placeholder="Pilih tanggal" />
            </div>

            <!-- Tgl Akhir -->
            <div class="flex flex-col">
                <label for="tglAkhir" class="text-xs text-gray-500 mb-1">Tgl Akhir</label>
                <input type="text" id="tglAkhir"
                       class="flatpickr-input border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#003D73] focus:border-[#003D73] transition"
                       placeholder="Pilih tanggal" />
            </div>

            <!-- Tipe -->
            <div class="flex flex-col">
                <label for="typeRiwayat" class="text-xs text-gray-500 mb-1">Tipe</label>
                <select id="typeRiwayat"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#003D73] focus:border-[#003D73] transition">
                    <option value="1">Jadwal</option>
                    <option value="2">Angsuran</option>
                    <option value="3" selected>Realisasi + Jadwal + Angsuran</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="flex flex-col">
                <label class="block text-xs text-transparent mb-1 select-none">.</label>
                <button id="btnTampil"
                        class="h-[38px] bg-[#003D73] text-white font-medium px-4 rounded-lg hover:bg-[#002a52] transition flex items-center justify-center gap-2">
                    <i class="ki-solid ki-search-list text-white text-base"></i>
                    <span>Tampilkan</span>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-[#003D73] text-white">
                <tr>
                    <th rowspan="2" class="px-3 py-2 text-left">No</th>
                    <th rowspan="2" class="px-3 py-2 text-left">Tgl Trans</th>
                    <th rowspan="2" class="px-3 py-2 text-left">Uraian</th>
                    <th rowspan="2" class="px-3 py-2 text-center">Kode</th>
                    <th rowspan="2" class="px-3 py-2 text-right">Dropping/<br>Realisasi</th>
                    <th colspan="2" class="px-3 py-2 text-center">Tagihan / Jadwal Pembayaran</th>
                    <th colspan="3" class="px-3 py-2 text-center">Angsuran</th>
                    <th rowspan="2" class="px-3 py-2 text-right">Total<br>Angsuran</th>
                    <th rowspan="2" class="px-3 py-2 text-right">Baki<br>Debet</th>
                    <th colspan="2" class="px-3 py-2 text-center">Tunggakan</th>
                </tr>
                <tr>
                    <th class="px-3 py-1 text-right">Pokok</th>
                    <th class="px-3 py-1 text-right">Bunga</th>
                    <th class="px-3 py-1 text-right">Pokok</th>
                    <th class="px-3 py-1 text-right">Bunga</th>
                    <th class="px-3 py-1 text-right">Denda</th>
                    <th class="px-3 py-1 text-right">Pokok</th>
                    <th class="px-3 py-1 text-right">Bunga</th>
                </tr>
                </thead>
                <tbody id="tbodyRiwayat" class="divide-y divide-gray-100 bg-white text-gray-700">
                <tr>
                    <td colspan="14" class="text-center py-3 text-gray-400">Belum ada data</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="footerRiwayat" class="text-sm text-gray-500 mt-2"></div>
    </div>

    <div class="mt-6 flex items-center justify-between text-sm border-t pt-3">
        <button type="button"
                onclick="window.location.href='/rekening/kredit'"
                class="flex items-center gap-2 text-[#003D73] font-medium hover:bg-[#F5F8FB] hover:text-[#002a52] border border-[#003D73]/20 px-3 py-1.5 rounded-lg transition">
            <i class="ki-solid ki-arrow-left text-[#F36F21] text-base"></i>
            <span>Kembali ke daftar</span>
        </button>

        <div id="syncedAt" class="flex items-center gap-2 text-gray-400 italic">
            <i class="ki-solid ki-time text-gray-400 text-base"></i>
            <span>Sinkron terakhir: 01 Nov 2025 10:42</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (async function () {

            document.addEventListener('DOMContentLoaded', function() {
                const now = new Date();
                const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                flatpickr("#tglAwal", {
                    altInput: true,
                    altFormat: "d-m-Y",
                    dateFormat: "Y-m-d",
                    defaultDate: startOfMonth,
                    locale: "id"
                });

                flatpickr("#tglAkhir", {
                    altInput: true,
                    altFormat: "d-m-Y",
                    dateFormat: "Y-m-d",
                    defaultDate: endOfMonth,
                    locale: "id"
                });
            });

            const norek = getParam('norek');
            if (!norek) return window.location.href = '/rekening/kredit';

            const meta = document.getElementById('meta');
            meta.textContent = `No Rekening: ${norek}`;

            try {
                const {data} = await axios.get(`/secure/kredit/${encodeURIComponent(norek)}/detail`);
                const d = data?.responseData || {};

                document.getElementById('noRek').textContent = d.noRekening || '-';
                document.getElementById('noSpk').textContent = d.noSpk || '-';
                document.getElementById('produk').textContent = d.kodeProduk || '-';
                document.getElementById('bunga').textContent = d.sukuBungaPerTahun ? `${d.sukuBungaPerTahun}%` : '-';
                document.getElementById('tglRealisasi').textContent = tglIndo(d.tglRealisasi);
                document.getElementById('tglJt').textContent = tglIndo(d.tglJatuhTempo);
                document.getElementById('jkw').textContent = `${d.jmlAngsuran || '-'} Bulan`;
                document.getElementById('periodeTagihan').textContent = d.tglTagihan ? `Tgl ${d.tglTagihan}` : '-';
                document.getElementById('noAlt').textContent = d.noAlternatif || '-';
                document.getElementById('nominal').textContent = rupiah(d.jmlPinjaman);
                document.getElementById('jenisKredit').textContent = mapJenis(d.typeKredit);
                document.getElementById('status').innerHTML = mapStatus(d.status);
                document.getElementById('verif').innerHTML = d.verifikasi === '1'
                    ? '<span class="text-green-600 font-semibold">Terverifikasi</span>'
                    : '<span class="text-gray-500 italic">Belum Verifikasi</span>';
                document.getElementById('saldoAkhir').textContent = rupiah(d.saldoAkhir);

                if (d.lastSyncedAt) {
                    document.getElementById('syncedAt').textContent = `Sinkron terakhir: ${new Date(d.lastSyncedAt).toLocaleString('id-ID')}`;
                }
            } catch (e) {
                console.error(e);
                notify('error', 'Gagal memuat data kredit');
            }

            const btnTampil = document.getElementById('btnTampil');
            const tbody = document.getElementById('tbodyRiwayat');
            const footer = document.getElementById('footerRiwayat');

            btnTampil.addEventListener('click', async () => {
                const tglAwal = document.getElementById('tglAwal').value;
                const tglAkhir = document.getElementById('tglAkhir').value;
                const type = document.getElementById('typeRiwayat').value;
                if (!tglAwal || !tglAkhir) return alert('Isi tanggal awal dan akhir');

                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-gray-400">Memuat data...</td></tr>`;
                footer.textContent = '';

                try {
                    const params = new URLSearchParams({
                        tglHitung: tglAkhir,
                        type,
                        kodeKantor: '001'
                    });

                    const {data} = await axios.get(`/secure/kredit/${encodeURIComponent(norek)}/riwayat?${params.toString()}`);
                    if (data?.responseCode !== '00') notify('error','Gagal memuat riwayat');

                    const list = data.responseData || [];
                    if (!list.length) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-gray-400">Tidak ada data</td></tr>`;
                        return;
                    }

                    tbody.innerHTML = list.map((r, i) => `
<tr class="hover:bg-gray-50">
    <td class="px-3 py-1 text-center">${i + 1}</td>
    <td class="px-3 py-1">${tglIndo(r.tglTrans)}</td>
    <td class="px-3 py-1">${r.keterangan || '-'}</td>
    <td class="px-3 py-1 text-center">${r.myKodeTrans || '-'}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.realisasi)}</td>

    <!-- Tagihan -->
    <td class="px-3 py-1 text-right">${rupiah(r.tagihanPokok)}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.tagihanBunga)}</td>

    <!-- Angsuran -->
    <td class="px-3 py-1 text-right">${rupiah(r.angsuranPokok)}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.angsuranBunga)}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.angsuranDenda)}</td>

    <!-- Total + Baki -->
    <td class="px-3 py-1 text-right font-semibold">${rupiah(r.totalAngsuran)}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.bakiDebet)}</td>

    <!-- Tunggakan -->
    <td class="px-3 py-1 text-right">${rupiah(r.tunggakanPokok)}</td>
    <td class="px-3 py-1 text-right">${rupiah(r.tunggakanBunga)}</td>
</tr>
`).join('');

                    footer.innerHTML = `
Saldo Awal: <span class="font-semibold">${rupiah(list[0]?.realisasi || 0)}</span> |
Saldo Akhir: <span class="font-semibold">${rupiah(list[list.length - 1]?.bakiDebet || 0)}</span> |
Total Transaksi: <span class="font-semibold">${list.length}</span>
`;

                } catch (err) {
                    console.error(err);
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-red-400">Gagal memuat data</td></tr>`;
                }
            });

            // === Helper ===
            function mapJenis(code) {
                return {100: 'Kredit Flat', 310: 'Kredit Bunga di Akhir', 700: 'Kredit Anuitas'}[code] || '-';
            }

            function mapStatus(s) {
                return {
                    '1': '<span class="text-green-600 font-semibold">Aktif</span>',
                    '2': '<span class="text-yellow-600 font-semibold">Macet</span>',
                    '3': '<span class="text-red-600 font-semibold">Lunas</span>'
                }[s] || '-';
            }
        })();
    </script>
@endpush
