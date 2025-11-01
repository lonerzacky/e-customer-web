@extends('layouts.app')

@section('title', 'Detail Tabungan | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold text-[#003D73] mb-2 flex items-center gap-2">
        <i class="ki-solid ki-document text-[#F36F21] text-lg"></i>
        Detail Tabungan
    </h1>

    <div id="meta" class="text-sm text-slate-500 mb-5"></div>

    <div class="grid md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#F36F21]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Informasi Rekening</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>No Rekening</dt>
                <dd class="col-span-2" id="noRek">-</dd>
                <dt>Produk</dt>
                <dd class="col-span-2" id="produk">-</dd>
                <dt>Jenis</dt>
                <dd class="col-span-2" id="jenis">-</dd>
                <dt>Suku Bunga</dt>
                <dd class="col-span-2" id="bunga">-</dd>
                <dt>Tgl Register</dt>
                <dd class="col-span-2" id="tglReg">-</dd>
                <dt>Verifikasi</dt>
                <dd class="col-span-2" id="verif">-</dd>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#003D73]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Informasi Saldo</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>Saldo Akhir</dt>
                <dd class="col-span-2 font-semibold text-[#003D73]" id="saldoAkhir">Rp 0</dd>
                <dt>Saldo Blokir</dt>
                <dd class="col-span-2" id="saldoBlokir">Rp 0</dd>
                <dt>Minimum</dt>
                <dd class="col-span-2" id="minSaldo">Rp 0</dd>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#F36F21]">
        <h2 class="font-semibold mb-4 text-[#003D73] flex items-center gap-2">
            <i class="ki-solid ki-chart-line text-[#F36F21] text-lg"></i>
            Rekening Koran
        </h2>

        <div class="flex flex-wrap gap-3 items-center mb-4 text-sm">
            <div class="flex items-center gap-4">
                <div class="fv-row">
                    <label class="form-label text-slate-600">Tgl Awal</label>
                    <input type="text" id="tglAwal" class="form-control form-control-sm border rounded px-2 py-1"
                           placeholder="Pilih tanggal"/>
                </div>
                <div class="fv-row">
                    <label class="form-label text-slate-600">Tgl Akhir</label>
                    <input type="text" id="tglAkhir" class="form-control form-control-sm border rounded px-2 py-1"
                           placeholder="Pilih tanggal"/>
                </div>
            </div>
            <button id="btnKoran"
                    class="bg-[#003D73] text-white px-4 py-1 rounded hover:bg-[#002a52] transition flex items-center gap-2">
                <i class="ki-solid ki-search-list text-white text-base"></i>
                <span>Tampilkan</span>
            </button>
        </div>

        <div id="saldoHeader"></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-[#003D73] text-white">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Tanggal</th>
                    <th class="px-4 py-2 text-left font-medium">Keterangan</th>
                    <th class="px-4 py-2 text-right font-medium">Setoran</th>
                    <th class="px-4 py-2 text-right font-medium">Penarikan</th>
                    <th class="px-4 py-2 text-right font-medium">Saldo</th>
                </tr>
                </thead>
                <tbody id="tbodyKoran" class="divide-y divide-gray-100">
                <tr>
                    <td colspan="5" class="text-center py-3 text-slate-400">Belum ada data</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div id="footerKoran" class="text-xs text-slate-500 mt-3"></div>
    </div>

    <div class="mt-6 flex items-center justify-between text-sm border-t pt-3">
        <button type="button"
                onclick="window.location.href='/rekening/tabungan'"
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
            document.addEventListener('DOMContentLoaded', function () {
                const now = new Date();
                const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                flatpickr("#tglAwal", {
                    dateFormat: "d-m-Y",
                    defaultDate: startOfMonth,
                    locale: "id",
                    allowInput: true,
                    onChange: function (selectedDates, dateStr, instance) {
                        document.querySelector("#tglAkhir")._flatpickr.set('minDate', dateStr);
                    }
                });

                flatpickr("#tglAkhir", {
                    dateFormat: "d-m-Y",
                    defaultDate: endOfMonth,
                    locale: "id",
                    allowInput: true
                });
            });
            const norek = getParam('norek');
            if (!norek) {
                notify('warning', 'Nomor rekening tidak ditemukan');
                return window.location.href = '/tabungan';
            }

            document.getElementById('meta').textContent = `No Rekening: ${norek}`;

            try {
                const {data} = await axios.get(`/secure/tabungan/${encodeURIComponent(norek)}/detail`);
                const d = data?.responseData || {};
                document.getElementById('noRek').textContent = d.noRekening || '-';
                document.getElementById('produk').textContent = `${d.kodeProduk || '-'} - ${d.deskripsiProduk || '-'}`;
                document.getElementById('jenis').textContent = `${d.kodeJenis || '-'} - ${d.deskripsiJenis || '-'}`;
                document.getElementById('bunga').textContent = d.sukuBunga ? `${d.sukuBunga}%` : '-';
                document.getElementById('tglReg').textContent = tglIndo(d.tglRegister) || '-';
                document.getElementById('verif').innerHTML = d.verifikasi === '1'
                    ? '<span class="text-green-600 font-semibold">Terverifikasi</span>'
                    : '<span class="text-gray-500 italic">Belum Verifikasi</span>';
                document.getElementById('saldoAkhir').textContent = rupiah(d.saldoAkhir);
                document.getElementById('saldoBlokir').textContent = rupiah(d.saldoBlokir);
                document.getElementById('minSaldo').textContent = rupiah(d.minimum);
                if (d.lastSyncedAt) {
                    const dt = new Date(d.lastSyncedAt).toLocaleString('id-ID');
                    document.getElementById('syncedAt').textContent = `Sinkron terakhir: ${dt}`;
                }
            } catch (e) {
                console.error(e);
                notify('error', 'Gagal memuat detail rekening');
            }

            document.getElementById('btnKoran').addEventListener('click', async (e) => {
                e.preventDefault();

                const tglAwalInput = document.getElementById('tglAwal').value;
                const tglAkhirInput = document.getElementById('tglAkhir').value;

                if (!tglAwalInput || !tglAkhirInput) {
                    notify("warning", "Tanggal awal atau akhir belum dipilih!")
                    return;
                }

                const tglAwal = parseDMY(tglAwalInput);
                const tglAkhir = parseDMY(tglAkhirInput);

                if (tglAkhir < tglAwal) {
                    notify("warning", "Tanggal akhir tidak boleh lebih awal dari tanggal awal!");
                    return;
                }

                const sameMonth = tglAwal.getMonth() === tglAkhir.getMonth() &&
                    tglAwal.getFullYear() === tglAkhir.getFullYear();

                if (!sameMonth) {
                    notify("warning", "Rentang tanggal hanya boleh dalam bulan yang sama!");
                    return;
                }

                const diffTime = Math.abs(tglAkhir - tglAwal);
                const diffDays = diffTime / (1000 * 60 * 60 * 24);
                if (diffDays > 31) {
                    notify("warning", "Rentang tanggal tidak boleh lebih dari 1 bulan!");
                    return;
                }
                const tbody = document.getElementById('tbodyKoran');
                const footer = document.getElementById('footerKoran');
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-slate-400">Memuat data...</td></tr>`;
                footer.textContent = '';

                try {
                    const params = new URLSearchParams({
                        tglAwal: toYmd(tglAwalInput),
                        tglAkhir: toYmd(tglAkhirInput),
                        kodeKantor: '001'
                    });

                    const {data} = await axios.get(
                        `/secure/tabungan/${encodeURIComponent(norek)}/mutasi?${params.toString()}`
                    );

                    if (data?.responseCode !== '00') throw new Error('Gagal memuat rekening koran');

                    const r = data.responseData;
                    const mutasi = r.mutasi || [];

                    if (mutasi.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-slate-400">Tidak ada transaksi</td></tr>`;
                    } else {
                        let rows = `
                <tr class="bg-slate-50 text-[#003D73] font-semibold border-t">
                    <td colspan="4" class="px-4 py-2 italic text-slate-600">Saldo Awal</td>
                    <td class="px-4 py-2 text-right">${rupiah(r.saldoAwal)}</td>
                </tr>
            `;

                        rows += mutasi.map((m) => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">${m.tglTrans || '-'}</td>
                    <td class="px-4 py-2">${m.keterangan || '-'}</td>
                    <td class="px-4 py-2 text-right">${m.setoran ? rupiah(m.setoran) : '-'}</td>
                    <td class="px-4 py-2 text-right">${m.penarikan ? rupiah(m.penarikan) : '-'}</td>
                    <td class="px-4 py-2 text-right font-semibold">${rupiah(m.saldoAkhir)}</td>
                </tr>
            `).join('');

                        const totalSetoran = mutasi.reduce((sum, m) => sum + (m.setoran || 0), 0);
                        const totalPenarikan = mutasi.reduce((sum, m) => sum + (m.penarikan || 0), 0);

                        rows += `
                <tr class="bg-slate-100 text-[#003D73] font-semibold border-t">
                    <td colspan="2" class="px-4 py-2 italic">Jumlah</td>
                    <td class="px-4 py-2 text-right">${rupiah(totalSetoran)}</td>
                    <td class="px-4 py-2 text-right">${rupiah(totalPenarikan)}</td>
                    <td class="px-4 py-2 text-right">${rupiah(r.saldoAkhir)}</td>
                </tr>
            `;

                        tbody.innerHTML = rows;
                    }

                    footer.innerHTML = `
            Saldo Awal: <span class="font-semibold">${rupiah(r.saldoAwal)}</span> |
            Saldo Akhir: <span class="font-semibold">${rupiah(r.saldoAkhir)}</span> |
            Total Transaksi: <span class="font-semibold">${mutasi.length}</span>
        `;
                } catch (e) {
                    console.error(e);
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-3 text-red-400">Gagal memuat data</td></tr>`;
                }
            });
        })();

    </script>
@endpush
