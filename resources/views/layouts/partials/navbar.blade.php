<nav x-data="{ open:false }" class="bg-[#003D73] text-white shadow">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex h-14 items-center justify-between">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{asset('theme/assets/media/logo.png')}}" class="h-7" alt="KBPR">
                <span
                    class="font-semibold text-lg tracking-wide">{{config('ecustomer.companyName')}} | {{config('app.name')}}</span>
            </a>

            {{-- Desktop menu --}}
            <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="hover:text-[#F36F21] {{ request()->routeIs('dashboard') ? 'text-[#F36F21]' : '' }}">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('profile') }}"
                       class="hover:text-[#F36F21] {{ request()->routeIs('profile') ? 'text-[#F36F21]' : '' }}">
                        Profil
                    </a>
                </li>

                {{-- 🔥 TABUNGAN --}}
                @if(!$hidemodule['tabungan'])
                    <li>
                        <a href="{{ route('tabungan.index') }}"
                           class="hover:text-[#F36F21] {{ request()->is('rekening/tabungan*') ? 'text-[#F36F21]' : '' }}">
                            Tabungan
                        </a>
                    </li>
                @endif

                {{-- 🔥 DEPOSITO --}}
                @if(!$hidemodule['deposito'])
                    <li>
                        <a href="{{ route('deposito.index') }}"
                           class="hover:text-[#F36F21] {{ request()->is('rekening/deposito*') ? 'text-[#F36F21]' : '' }}">
                            Deposito
                        </a>
                    </li>
                @endif

                {{-- 🔥 KREDIT --}}
                @if(!$hidemodule['kredit'])
                    <li>
                        <a href="{{ route('kredit.index') }}"
                           class="hover:text-[#F36F21] {{ request()->is('rekening/kredit*') ? 'text-[#F36F21]' : '' }}">
                            Kredit
                        </a>
                    </li>
                @endif

                <li>
                    <button id="btnLogout" class="hover:text-[#F36F21] underline">Logout</button>
                </li>
            </ul>

            {{-- Mobile hamburger --}}
            <button class="md:hidden inline-flex items-center" @click="open = !open" aria-label="menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile dropdown --}}
    <div class="md:hidden" x-show="open" x-transition>
        <div class="px-4 pb-4 space-y-2 font-medium">
            <a href="{{ route('dashboard') }}" class="block py-2 hover:text-[#F36F21]">Dashboard</a>
            <a href="{{ route('profile') }}" class="block py-2 hover:text-[#F36F21]">Profil</a>
            <a href="{{ route('tabungan.index') }}" class="block py-2 hover:text-[#F36F21]">Tabungan</a>
            <a href="{{ route('deposito.index') }}" class="block py-2 hover:text-[#F36F21]">Deposito</a>
            <a href="{{ route('kredit.index') }}" class="block py-2 hover:text-[#F36F21]">Kredit</a>
            <button id="btnLogoutMobile" class="underline mt-2 hover:text-[#F36F21]">Logout</button>
        </div>
    </div>
</nav>


{{-- Logout --}}
<script>
    const firstLogin = document.cookie.includes("is_first_login=1");

    if (firstLogin) {
        document.querySelectorAll('a').forEach(link => {
            const href = link.getAttribute('href');

            if (!href) return;
            if (href === "/profile") return;
            if (href.startsWith("/profile?")) return;

            link.addEventListener('click', e => {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Wajib Ganti Password',
                    text: 'Anda harus mengganti password terlebih dahulu sebelum mengakses menu lain.',
                    confirmButtonColor: '#003D73',
                    confirmButtonText: 'Ganti Password Sekarang'
                }).then(() => {
                    // Trigger event Alpine
                    window.dispatchEvent(new CustomEvent('force-open-change-pass'));

                    if (!window.location.search.includes('forceChangePass=1')) {
                        window.location.href = "/profile?forceChangePass=1";
                    }
                });
            });
        });
    }

    function doLogout() {
        try {
            axios.post('/logout');
        } catch (_) {
        }
        localStorage.removeItem('accessToken');
        localStorage.removeItem('refreshToken');
        document.cookie = "jwt_exists=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";
        document.cookie = "is_first_login=0;path=/;";
        window.location.href = '/login';
    }

    document.getElementById('btnLogout')?.addEventListener('click', doLogout);
    document.getElementById('btnLogoutMobile')?.addEventListener('click', doLogout);
</script>
