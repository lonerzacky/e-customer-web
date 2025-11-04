@extends('layouts.app')

@section('title', 'Profil Nasabah | '.config('app.name'))

@section('content')
    <div x-data="{ showChangePass: false }">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <i class="ki-outline ki-user text-[#F36F21] text-2xl"></i>
                <h1 class="text-xl font-semibold text-[#003D73]">Profil Nasabah</h1>
            </div>
            <button
                @click="showChangePass = true"
                class="flex items-center gap-2 bg-[#003D73] text-white px-3 py-1.5 rounded-lg hover:bg-[#002a52] transition text-sm">
                <i class="ki-solid ki-lock text-white text-base"></i>
                <span>Ganti Password</span>
            </button>
        </div>

        <div id="profile"
             class="bg-white shadow-sm hover:shadow-md transition-all duration-300 rounded-xl border-l-4 border-[#F36F21] p-6 text-sm">
            <div class="text-gray-500">Memuat data...</div>
        </div>

        {{-- MODAL GANTI PASSWORD --}}
        <div
            x-show="showChangePass"
            x-transition
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            style="display: none;"
        >
            <div
                @click.away="showChangePass = false"
                class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md text-sm"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-[#003D73] flex items-center gap-2">
                        <i class="ki-solid ki-lock text-[#F36F21] text-lg"></i>
                        Ganti Password
                    </h2>
                    <button @click="showChangePass = false" class="text-gray-400 hover:text-gray-600">
                        <i class="ki-outline ki-cross text-lg"></i>
                    </button>
                </div>

                <form id="formChangePassword" class="space-y-3">
                    <div>
                        <label class="text-gray-600">Password Lama</label>
                        <input type="password" id="oldPassword"
                               class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-[#003D73]" required/>
                    </div>
                    <div>
                        <label class="text-gray-600">Password Baru</label>
                        <input type="password" id="newPassword"
                               class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-[#003D73]" required/>
                    </div>
                    <div>
                        <label class="text-gray-600">Konfirmasi Password Baru</label>
                        <input type="password" id="confirmPassword"
                               class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-[#003D73]" required/>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" @click="showChangePass = false"
                                class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-[#003D73] text-white hover:bg-[#002a52] flex items-center gap-2">
                            <i class="ki-solid ki-check text-white text-base"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (async function () {
                const token = localStorage.getItem('accessToken');
                if (!token) return window.location.href = '/login';

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
            }


        )();

        document.getElementById('formChangePassword').addEventListener('submit', async (e) => {
            e.preventDefault();
            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                notify("warning", "Konfirmasi password tidak sama dengan password baru!");
                return;
            }

            try {
                const token = localStorage.getItem('accessToken');
                const {data} = await axios.post('/change-password',
                    {oldPassword, newPassword},
                    {headers: {Authorization: `Bearer ${token}`}}
                );

                if (data.responseCode === '00') {
                    notify("success", data.responseMessage || "Password berhasil diubah");

                    localStorage.removeItem('accessToken');
                    localStorage.removeItem('refreshToken');
                    document.cookie = "jwt_exists=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";

                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    notify("warning", data.responseMessage || "Gagal mengubah password");
                }
            } catch (err) {
                console.error("Change password error:", err);

                const msg = err.response?.data?.responseMessage
                    || err.response?.data?.message
                    || err.message
                    || "Terjadi kesalahan tak terduga saat mengubah password";

                notify("error", msg);
            }
        });
    </script>
@endpush
