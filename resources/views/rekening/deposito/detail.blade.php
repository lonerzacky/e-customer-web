@extends('layouts.app')

@section('title', 'Detail Deposito | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold text-[#003D73] mb-2 flex items-center gap-2">
        <i class="ki-solid ki-document text-[#F36F21] text-lg"></i>
        Detail Deposito
    </h1>

    <div id="meta" class="text-sm text-slate-500 mb-5"></div>

    <div class="grid md:grid-cols-2 gap-4 mb-8">
        {{-- Informasi Rekening --}}
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#F36F21]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Informasi Rekening</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>No Rekening</dt>
                <dd class="col-span-2" id="noRek">-</dd>
                <dt>Produk</dt>
                <dd class="col-span-2" id="produk">-</dd>
                <dt>Suku Bunga</dt>
                <dd class="col-span-2" id="bunga">-</dd>
                <dt>Tgl Registrasi</dt>
                <dd class="col-span-2" id="tglReg">-</dd>
                <dt>Nominal</dt>
                <dd class="col-span-2" id="nominal">-</dd>
                <dt>Verifikasi</dt>
                <dd class="col-span-2" id="verif">-</dd>
            </dl>
        </div>

        {{-- Informasi Saldo --}}
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#003D73]">
            <h2 class="font-semibold mb-3 text-[#003D73]">Informasi Lainnya</h2>
            <dl class="grid grid-cols-3 gap-y-2">
                <dt>No Bilyet</dt>
                <dd class="col-span-2" id="noBilyet">-</dd>
                <dt>Status ARO</dt>
                <dd class="col-span-2" id="aro">-</dd>
                <dt>Tgl Valuta</dt>
                <dd class="col-span-2" id="tglMulai">-</dd>
                <dt>Jangka Waktu</dt>
                <dd class="col-span-2" id="jkw">-</dd>
                <dt>Tgl Jatuh Tempo</dt>
                <dd class="col-span-2" id="tglJt">-</dd>
                <dt>Perlakuan Bunga</dt>
                <dd class="col-span-2" id="perlakuanBunga">-</dd>
                <dt>Saldo Akhir</dt>
                <dd class="col-span-2 font-semibold text-[#003D73]" id="saldoAkhir">Rp 0</dd>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5 border-l-4 border-[#F36F21]">
        <h2 class="font-semibold mb-4 text-[#003D73] flex items-center gap-2">
            <i class="ki-solid ki-chart-line text-[#F36F21] text-lg"></i>
            Riwayat Deposito
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
            <button id="btnRiwayat"
                    class="bg-[#003D73] text-white px-4 py-1 rounded hover:bg-[#002a52] transition flex items-center gap-2">
                <i class="ki-solid ki-search-list text-white text-base"></i>
                <span>Tampilkan</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-[#003D73] text-white">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Tanggal</th>
                    <th class="px-4 py-2 text-left font-medium">Keterangan</th>
                    <th class="px-4 py-2 text-left font-medium">Kuitansi</th>
                    <th class="px-4 py-2 text-right font-medium">Pokok</th>
                    <th class="px-4 py-2 text-right font-medium">Bunga</th>
                    <th class="px-4 py-2 text-right font-medium">Pajak</th>
                    <th class="px-4 py-2 text-right font-medium">Adm</th>
                </tr>
                </thead>
                <tbody id="tbodyRiwayat" class="divide-y divide-gray-100">
                <tr>
                    <td colspan="7" class="text-center py-3 text-slate-400">Belum ada data</td>
                </tr>
                </tbody>
                <tfoot id="tfootRiwayat"></tfoot>
            </table>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between text-sm border-t pt-3">
        <button type="button"
                onclick="window.location.href='/rekening/deposito'"
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
                notify('warning', 'Nomor rekening tidak ditemukan')
                return window.location.href = '/rekening/deposito';
            }

            document.getElementById('meta').textContent = `No Rekening: ${norek}`;

            try {
                const {data} = await axios.get(`/secure/deposito/${encodeURIComponent(norek)}/detail`);
                const d = data?.responseData || {};
                document.getElementById('noRek').textContent = d.noRekening || '-';
                document.getElementById('produk').textContent = `${d.kodeProduk || '-'} - ${d.deskripsiProduk || '-'}`;
                document.getElementById('bunga').textContent = d.sukuBunga ? `${d.sukuBunga}%` : '-';
                document.getElementById('tglReg').textContent = tglIndo(d.tglRegistrasi) || '-';
                document.getElementById('nominal').textContent = rupiah(d.jmlDeposito) || '-';
                document.getElementById('verif').innerHTML = d.verifikasi === '1'
                    ? '<span class="text-green-600 font-semibold">Terverifikasi</span>'
                    : '<span class="text-gray-500 italic">Belum Verifikasi</span>';
                document.getElementById('noBilyet').textContent = d.noAlternatifRek || '-';
                document.getElementById('aro').textContent = d.aro || '-';
                document.getElementById('tglMulai').textContent = tglIndo(d.tglMulai) || '-';
                document.getElementById('jkw').textContent = d.jkw + " Bulan" || '-';
                document.getElementById('tglJt').textContent = tglIndo(d.tglJt) || '-';
                document.getElementById('perlakuanBunga').textContent = d.tabungOrTitipan || '-';
                document.getElementById('saldoAkhir').textContent = rupiah(d.saldoAkhir);
                if (d.lastSyncedAt) {
                    const dt = new Date(d.lastSyncedAt).toLocaleString('id-ID');
                    document.getElementById('syncedAt').textContent = `Sinkron terakhir: ${dt}`;
                }
            } catch (e) {
                console.error(e);
                notify('error', 'Gagal memuat detail rekening')
            }

            document.getElementById('btnRiwayat').addEventListener('click', async (e) => {
                e.preventDefault();

                const tglAwalInput = document.getElementById('tglAwal').value;
                const tglAkhirInput = document.getElementById('tglAkhir').value;

                if (!tglAwalInput || !tglAkhirInput) {
                    notify("warning", "Tanggal awal atau akhir belum dipilih!");
                    return;
                }

                const tglAwal = parseDMY(tglAwalInput);
                const tglAkhir = parseDMY(tglAkhirInput);
                if (tglAkhir < tglAwal) {
                    notify("warning", "Tanggal akhir tidak boleh lebih awal dari tanggal awal!");
                    return;
                }

                const tbody = document.getElementById('tbodyRiwayat');
                const tfoot = document.getElementById('tfootRiwayat');

                tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-slate-400">Memuat data...</td></tr>`;
                tfoot.innerHTML = '';

                try {
                    const params = new URLSearchParams({
                        tglAwal: toYmd(tglAwalInput),
                        tglAkhir: toYmd(tglAkhirInput),
                        kodeKantor: '001'
                    });

                    const {data} = await axios.get(`/secure/deposito/${encodeURIComponent(norek)}/mutasi?${params.toString()}`);
                    if (data?.responseCode !== '00') notify('error', 'Gagal memuat riwayat');

                    const r = data.responseData;
                    const mutasi = r.mutasi || [];

                    if (mutasi.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-slate-400">Tidak ada transaksi</td></tr>`;
                        tfoot.innerHTML = '';
                    } else {
                        // hitung total
                        const totalPokok = mutasi.reduce((sum, m) => sum + (parseFloat(m.pokokTrans) || 0), 0);
                        const totalBunga = mutasi.reduce((sum, m) => sum + (parseFloat(m.bungaTrans) || 0), 0);
                        const totalPajak = mutasi.reduce((sum, m) => sum + (parseFloat(m.pajakTrans) || 0), 0);
                        const totalAdm = mutasi.reduce((sum, m) => sum + (parseFloat(m.admTrans) || 0), 0);

                        // isi tbody
                        tbody.innerHTML = mutasi.map((m) => `
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2">${tglIndo(m.tglTrans) || '-'}</td>
          <td class="px-4 py-2">${m.keterangan || '-'}</td>
          <td class="px-4 py-2">${m.kuitansi || '-'}</td>
          <td class="px-4 py-2 text-right">${m.pokokTrans ? rupiah(m.pokokTrans) : '-'}</td>
          <td class="px-4 py-2 text-right">${m.bungaTrans ? rupiah(m.bungaTrans) : '-'}</td>
          <td class="px-4 py-2 text-right">${m.pajakTrans ? rupiah(m.pajakTrans) : '-'}</td>
          <td class="px-4 py-2 text-right">${m.admTrans ? rupiah(m.admTrans) : '-'}</td>
        </tr>
      `).join('');

                        // isi tfoot (total)
                        tfoot.innerHTML = `
        <tr class="bg-[#F8FAFC] border-t border-gray-200 font-semibold text-[#003D73]">
          <td colspan="3" class="px-4 py-2 text-right">Total</td>
          <td class="px-4 py-2 text-right">${rupiah(totalPokok)}</td>
          <td class="px-4 py-2 text-right">${rupiah(totalBunga)}</td>
          <td class="px-4 py-2 text-right">${rupiah(totalPajak)}</td>
          <td class="px-4 py-2 text-right">${rupiah(totalAdm)}</td>
        </tr>
      `;
                    }
                } catch (err) {
                    console.error(err);
                    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-3 text-red-400">Gagal memuat data</td></tr>`;
                    tfoot.innerHTML = '';
                }
            });


        })();

    </script>
@endpush
