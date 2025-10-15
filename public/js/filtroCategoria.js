document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('categoriaSelect');
    if (!select) return;

    select.addEventListener('change', function() {
        const categoria = this.value;
        const url = new URL(window.location.href);

        if (categoria) {
            url.searchParams.set('categoria', categoria);
        } else {
            url.searchParams.delete('categoria');
        }

        const searchInput = document.getElementById('search');
        if (searchInput && searchInput.value.trim() !== '') {
            url.searchParams.set('search', searchInput.value);
        }

        window.location.href = url.toString();
    });
});
