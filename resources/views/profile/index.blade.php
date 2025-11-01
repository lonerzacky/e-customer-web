@extends('layouts.app')

@section('title', 'Profil Nasabah | '.config('app.name'))

@section('content')
    <div class="flex items-center gap-2 mb-6">
        <i class="ki-outline ki-user text-[#F36F21] text-2xl"></i>
        <h1 class="text-xl font-semibold text-[#003D73]">Profil Nasabah</h1>
    </div>

    <div id="profile" class="bg-white shadow-sm hover:shadow-md transition-all duration-300 rounded-xl border-l-4 border-[#F36F21] p-6 text-sm">
        <div class="text-gray-500">Memuat data...</div>
    </div>


@endsection

@push('scripts')
    <script>
        (async function () {
            const token = localStorage.getItem('accessToken');
            if (!token) return window.location.href = '/login';

            const rupiah = (n) => new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(n || 0);
            const tglIndo = (tgl) => {
                if (!tgl) return '-';
                const d = new Date(tgl);
                return d.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'});
            };

            try {
                const {data} = await axios.get('/secure/profile');
                if (data?.responseCode !== '00') {
                    notify('error', data?.responseMessage || 'Gagal memuat profil');
                    return;
                }

                const p = data.responseData || {};
                const gender = p.jenisKelamin === 'L' ? 'Laki-laki' : (p.jenisKelamin === 'P' ? 'Perempuan' : '-');
                const noKtp = p.noKtp || '-';
                const telpon = p.noHp ? p.noHp : '<span class="text-gray-400 italic">Belum terdaftar</span>';
                const email = p.email ? p.email : '<span class="text-gray-400 italic">Belum terdaftar</span>';
                const alamat = p.alamat || '<span class="text-gray-400 italic">Belum terdaftar</span>';

                document.getElementById('profile').innerHTML = `
            <div class="flex items-center gap-3 mb-6">
                <div class="w-14 h-14 rounded-full bg-[#F36F21]/10 flex items-center justify-center">
                    <i class="ki-outline ki-user text-[#F36F21] text-2xl"></i>
                </div>
                <div>
                    <div class="text-lg font-semibold text-[#003D73]">${p.namaLengkap || '-'}</div>
                    <div class="text-xs text-gray-400">ID Nasabah: <span class="text-[#003D73] font-medium">${p.nasabahId || '-'}</span></div>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-x-12 gap-y-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                <div class="space-y-2">
                    <table class="text-sm w-full">
                        <tr><td class="font-semibold w-36">Nama Lengkap</td><td>${p.namaLengkap || '-'}</td></tr>
                        <tr><td class="font-semibold">No. KTP</td><td>${noKtp}</td></tr>
                        <tr><td class="font-semibold">Tanggal Lahir</td><td>${tglIndo(p.tglLahir)}</td></tr>
                        <tr><td class="font-semibold">Jenis Kelamin</td><td>${gender}</td></tr>
                        <tr><td class="font-semibold">Telepon / HP</td><td>${telpon}</td></tr>
                        <tr><td class="font-semibold">Email</td><td>${email}</td></tr>
                    </table>
                </div>

                <div class="space-y-2 sm:pl-8 pt-3 sm:pt-0">
                    <table class="text-sm w-full">
                        <tr><td class="font-semibold w-40">Alamat</td><td>${alamat}</td></tr>
                        <tr><td class="font-semibold">Kode Kantor</td><td>${p.kodeKantor || '-'}</td></tr>
                        <tr><td class="font-semibold">Nama Kantor</td><td>${p.namaKantor || '-'}</td></tr>
                        <tr><td class="font-semibold">Kota Kantor</td><td>${p.kotaKantor || '-'}</td></tr>
                    </table>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-500">
                Terakhir diperbarui: ${tglIndo(p.updatedAt)}
            </div>
        `;
            } catch (e) {
                console.error(e);
                notify('error', e.message || 'Gagal memuat profil');
            }
        })();
    </script>
@endpush
