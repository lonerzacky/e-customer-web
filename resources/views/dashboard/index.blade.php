@extends('layouts.app')

@section('title', 'Dashboard | '.config('app.name'))

@section('content')
    <h1 class="text-xl font-semibold mb-4 text-[#003D73]">Dashboard</h1>

    <div id="panel" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-gray-200 animate-pulse">
                <div class="h-3 bg-gray-200 rounded w-1/2 mb-3"></div>
                <div class="h-6 bg-gray-300 rounded w-2/3"></div>
            </div>
        @endfor
    </div>
@endsection

@push('scripts')
    <script>
        const hiddenModules = @json($hidemodule);

        (function () {

            const token = localStorage.getItem('accessToken');
            if (!token) return window.location.href = '/login';

            const rupiah = (n) => new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(n || 0);

            const renderCards = (d) => {
                const cards = [
                    {
                        key: 'tabungan',
                        title: 'Tabungan',
                        val: rupiah(d.totalTabungan),
                        sub: `${d.jumlahRekTabungan || 0} Rekening`,
                        href: '/rekening/tabungan',
                        icon: 'ki-outline ki-wallet',
                        color: '#F36F21',
                        gradient: 'from-[#F36F21]/20 to-[#FFD6B8]/10'
                    },
                    {
                        key: 'deposito',
                        title: 'Deposito',
                        val: rupiah(d.totalDeposito),
                        sub: `${d.jumlahRekDeposito || 0} Rekening`,
                        href: '/rekening/deposito',
                        icon: 'ki-outline ki-bank',
                        color: '#10B981',
                        gradient: 'from-[#10B981]/20 to-[#A7F3D0]/10'
                    },
                    {
                        key: 'kredit',
                        title: 'Kredit',
                        val: rupiah(d.totalKredit),
                        sub: `${d.jumlahRekKredit || 0} Rekening`,
                        href: '/rekening/kredit',
                        icon: 'ki-outline ki-credit-cart',
                        color: '#003D73',
                        gradient: 'from-[#003D73]/20 to-[#9BC7F3]/10'
                    },
                    {
                        key: 'portofolio',
                        title: 'Portofolio',
                        val: rupiah(d.totalPortofolio),
                        sub: 'Total Seluruh Produk',
                        href: '/dashboard',
                        icon: 'ki-outline ki-graph-up',
                        color: '#7C3AED',
                        gradient: 'from-[#7C3AED]/20 to-[#C4B5FD]/10'
                    }
                ];

                const visibleCards = cards.filter(c => !hiddenModules[c.key]);

                const panel = document.getElementById('panel');
                panel.innerHTML = visibleCards.map(c => `
        <a href="${c.href}"
           class="block bg-white rounded-xl shadow p-5 border-l-4 border-[${c.color}]
                  hover:-translate-y-1 hover:shadow-md hover:border-[#003D73]
                  transition-all duration-200 ease-in-out">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-lg bg-gradient-to-br ${c.gradient}">
                    <i class="${c.icon}" style="font-size:28px;color:${c.color};"></i>
                </div>
                <div>
                    <div class="text-sm text-[#003D73]/70 font-medium">${c.title}</div>
                    <div class="text-2xl font-bold mt-1 text-[#003D73]">${c.val}</div>
                    <div class="text-xs text-gray-500 mt-1">${c.sub}</div>
                </div>
            </div>
        </a>
    `).join('');
            };

            axios.get('/secure/dashboard')
                .then(({data}) => {
                    if (data?.responseCode === '00') {
                        renderCards(data.responseData);
                    } else {
                        notify('error', data?.responseMessage)
                    }
                })
                .catch((err) => {
                    notify('error', 'Dashboard error:', err)
                });
        })();
    </script>
@endpush
