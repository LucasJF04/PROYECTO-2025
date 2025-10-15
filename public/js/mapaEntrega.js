
document.addEventListener('DOMContentLoaded', function () {
    const tipoEntrega = document.getElementById('tipo_entrega');
    const mapaGroup = document.getElementById('mapa_entrega_group');

    tipoEntrega.addEventListener('change', function() {
        if(this.value === 'domicilio') {
            mapaGroup.style.display = 'block';
            initMap();
        } else {
            mapaGroup.style.display = 'none';
        }
    });

    function initMap() {
        const latInput = document.getElementById('latitud');
        const lngInput = document.getElementById('longitud');

        const map = L.map('mapa_entrega').setView([-16.5, -68.15], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([-16.5, -68.15], {draggable:true}).addTo(map);

        latInput.value = -16.5;
        lngInput.value = -68.15;

        marker.on('dragend', function(e){
            latInput.value = e.target.getLatLng().lat;
            lngInput.value = e.target.getLatLng().lng;
        });

        map.on('click', function(e){
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;
        });
    }
});
