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

        })();

    </script>
@endpush
