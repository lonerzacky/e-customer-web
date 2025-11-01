window.fmt = function (n) {
    return Number(n || 0).toLocaleString('id-ID', {minimumFractionDigits: 2});
};

// Format ke Rupiah (Rp)
window.rupiah = function (n) {
    return `Rp ${fmt(n)}`;
};

window.tglIndo = function (dateStr) {
    if (!dateStr) return '-';
    try {
        return new Date(dateStr).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric'
        });
    } catch {
        return dateStr;
    }
};

window.toYmd = function (dateStr) {
    const [day, month, year] = dateStr.split('-');
    if (!day || !month || !year) return '';
    return `${year}-${month}-${day}`;
}

window.parseDMY = function (dateStr) {
    const [day, month, year] = dateStr.split('-').map(Number);
    return new Date(year, month - 1, day);
}

window.getParam = function (key) {
    return new URLSearchParams(window.location.search).get(key);
};

window.notify = function (type = 'error', message = 'Terjadi kesalahan', title = '') {
    const colors = {
        success: '#10B981',
        error: '#EF4444',
        warning: '#F59E0B',
        info: '#3B82F6'
    };

    Swal.fire({
        title: title || (type === 'success' ? 'Berhasil' : 'Oops...'),
        text: message,
        icon: type,
        confirmButtonColor: colors[type],
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
};
