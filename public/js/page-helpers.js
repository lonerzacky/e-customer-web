const API_BASE = window.API_BASE;
axios.defaults.baseURL = API_BASE;

function getParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}

function fmt(x) {
    return Number(x || 0).toLocaleString('id-ID');
}

// axios base & interceptors (kalau belum include)
if (typeof axios !== 'undefined') {
    axios.defaults.baseURL = API_BASE;
    axios.interceptors.request.use(cfg => {
        const t = localStorage.getItem('accessToken');
        if (t) cfg.headers.Authorization = `Bearer ${t}`;
        return cfg;
    });
}
