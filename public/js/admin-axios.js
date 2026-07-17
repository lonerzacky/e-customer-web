// Axios ringan khusus halaman ADMIN.
// Terpisah dari app-axios.js (yang untuk nasabah): pakai key `adminAccessToken`,
// tanpa refresh token — kalau token kedaluwarsa (401) admin diarahkan login ulang.
(function () {
    const API_BASE = window.API_BASE || (window.ENV && window.ENV.API_BASE_URL) || 'http://localhost:8080/api';
    axios.defaults.baseURL = API_BASE;

    // === REQUEST INTERCEPTOR ===
    axios.interceptors.request.use(config => {
        const token = localStorage.getItem('adminAccessToken');
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    });

    // === RESPONSE INTERCEPTOR ===
    axios.interceptors.response.use(
        resp => resp,
        err => {
            const status = err.response && err.response.status;
            const onLoginPage = window.location.pathname.startsWith('/admin/login');
            if (status === 401 && !onLoginPage) {
                localStorage.removeItem('adminAccessToken');
                localStorage.removeItem('adminName');
                window.location.href = '/admin/login';
            }
            return Promise.reject(err);
        }
    );

    window.axios = axios;
})();
