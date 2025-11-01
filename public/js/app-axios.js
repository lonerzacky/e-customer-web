(function () {
    const API_BASE = 'http://localhost:8080/api';
    axios.defaults.baseURL = API_BASE;

    let isRefreshing = false;
    let refreshSubscribers = [];

    // 👉 Helper untuk mendaftarkan callback saat refresh selesai
    function subscribeTokenRefresh(cb) {
        refreshSubscribers.push(cb);
    }

    // 👉 Panggil semua callback ketika token baru udah didapat
    function onRefreshed(token) {
        refreshSubscribers.forEach(cb => cb(token));
        refreshSubscribers = [];
    }

    // === REQUEST INTERCEPTOR ===
    axios.interceptors.request.use(config => {
        const token = localStorage.getItem('accessToken');
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    });

    // === RESPONSE INTERCEPTOR ===
    axios.interceptors.response.use(
        resp => resp,
        async err => {
            const original = err.config;

            if (err.response && err.response.status === 401 && !original._retry) {
                original._retry = true;

                // 🔄 Kalau sedang refresh, tunggu hasilnya
                if (isRefreshing) {
                    return new Promise(resolve => {
                        subscribeTokenRefresh(token => {
                            original.headers.Authorization = 'Bearer ' + token;
                            resolve(axios(original));
                        });
                    });
                }

                // 🚀 Jalankan proses refresh baru
                isRefreshing = true;

                const refreshToken = localStorage.getItem('refreshToken');
                if (refreshToken) {
                    try {
                        console.log('🔄 Access token expired — refreshing...');
                        const r = await axios.post(`${API_BASE}/refresh`, { refreshToken });
                        const newToken = r.data?.responseData?.accessToken;

                        if (newToken) {
                            // Simpan token baru
                            localStorage.setItem('accessToken', newToken);
                            axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;

                            // Jalankan semua callback pending request
                            onRefreshed(newToken);
                            isRefreshing = false;

                            // Ulangi request asli
                            original.headers.Authorization = `Bearer ${newToken}`;
                            return axios(original);
                        }
                    } catch (e) {
                        console.error('❌ Refresh token gagal:', e);
                    }
                }

                // ❌ Kalau refresh gagal → logout total
                isRefreshing = false;
                localStorage.clear();
                document.cookie = "jwt_exists=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";
                window.location.href = '/login';
            }

            return Promise.reject(err);
        }
    );

    // 🌍 Jadikan axios global
    window.axios = axios;
})();
