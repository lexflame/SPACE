let map, allMarkers = [];

document.addEventListener("DOMContentLoaded", ()=>{
  // tinymce.init({ selector:'#marker_description', menubar:false, skin:'oxide-dark', content_css:'dark' });
  initMap();
  loadMarkers();

  document.querySelectorAll('.layer-toggle').forEach(cb=>{
    cb.addEventListener('change',()=>{renderMarkers(); updateLayer(cb);});
  });
  document.querySelectorAll('.category-toggle').forEach(cb=>{
    cb.addEventListener('change',()=>renderMarkers());
  });
  document.getElementById('addMarkerBtn').onclick = ()=>{ setAddMarkerMode(); };
  document.getElementById('markerForm').onsubmit = function(e){
    e.preventDefault();
    tinymce.triggerSave();
    let fd = new FormData(this);
    fetch('/mapeditor/saveMarker',{method:'POST',body:fd}).then(r=>r.json()).then(data=>{
      loadMarkers();
      bootstrap.Modal.getInstance(document.getElementById('markerModal')).hide();
    });
  };
  document.getElementById('historyBtn').onclick = function(){
    let id = document.getElementById('marker_id').value;
    showMarkerHistory(id);
  };
});

function initMap(){
    map = L.map('leafletMap').setView([0,0],2);
    path = document.getElementById('mapArea').getAttribute('path');
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.tileLayer('http://192.168.50.19/uploads/'+path).addTo(map);
    map.on('click', function(e){
        if(window.addMarker) {
            openMarkerModal({x: e.latlng.lng, y: e.latlng.lat});
            window.addMarker = false;
        }
    });
}
function setAddMarkerMode(){window.addMarker = true;}
function loadMarkers(){
    let mapID = document.querySelector('[name=map_id]').value;
    fetch('/mapeditor/getMarkers/'+mapID)
      .then(r=>r.json()).then(arr=>{allMarkers = arr; renderMarkers();});
}
function renderMarkers(){
    if(map._layers) { // удалить все старые метки (кроме базового слоя)
        for(let i in map._layers) {
            let l = map._layers[i];
            if(l instanceof L.Marker) map.removeLayer(l);
        }
    }
    let visibleLayers = Array.from(document.querySelectorAll('.layer-toggle:checked')).map(cb=>cb.dataset.layer);
    let visibleCats = Array.from(document.querySelectorAll('.category-toggle:checked')).map(cb=>cb.value);
    allMarkers.forEach(m=>{
        if(visibleLayers.includes(m.layer_id+"") && visibleCats.includes(m.category_id+"")){
            let icon = L.divIcon({ 
                html: `<i class="${m.icon||'fa-solid fa-map-marker'} fa-2x" style="color:${m.icon_color}; font-size:${m.icon_size||1}em"></i>`, className:""
            });
            let markerObj = L.marker([m.y, m.x], {icon: icon, draggable:true}).addTo(map);
            markerObj.on('click',()=>openMarkerModal(m));
            markerObj.on('dragend', function(e){
                let coords = markerObj.getLatLng();
                fetch('/mapeditor/moveMarker',{
                    method:'POST',
                    body:JSON.stringify({id:m.id, x:coords.lng, y:coords.lat}),
                    headers:{'Content-Type':'application/json'}
                }).then(()=>loadMarkers());
            });
        }
    });
}
function updateLayer(cb){
    let layerID = cb.dataset.layer;
    fetch('/mapeditor/setLayerVisibility',{method:'POST',body:JSON.stringify({layer_id:layerID, visible:cb.checked?1:0}),headers:{'Content-Type':'application/json'}});
}

function openMarkerModal(marker){
    document.getElementById('marker_title').value = marker.title||'';
    document.getElementById('marker_id').value = marker.id||'';
    document.getElementById('marker_category').value = marker.category_id||'';
    document.getElementById('marker_layer').value = marker.layer_id||'';
    document.getElementById('marker_icon').value = marker.icon||'fa-solid fa-star';
    document.getElementById('marker_icon_color').value = marker.icon_color||'#FFD700';
    document.getElementById('marker_icon_size').value = marker.icon_size||1.0;
    tinymce.get('marker_description').setContent(marker.description||"");
    document.getElementById('marker_x').value = marker.x||'';
    document.getElementById('marker_y').value = marker.y||'';
    loadMarkerImages(marker.id||'');
    document.getElementById('historyBox').innerHTML = '';
    new bootstrap.Modal(document.getElementById('markerModal')).show();
}
function loadMarkerImages(marker_id){
    let slider = document.getElementById('sliderImages');
    slider.innerHTML = '';
    if(!marker_id) return;
    fetch('/mapeditor/getMarkerImages/'+marker_id)
        .then(r=>r.json()).then(images=>{
        images.forEach((img,i)=>{
            let div = document.createElement('div');
            div.className = 'carousel-item'+(i==0?' active':'');
            div.innerHTML = `<img src="${img.image_path}" class="d-block w-100">`;
            slider.appendChild(div);
        });
    });
}
function showMarkerHistory(markerId){
    fetch('/mapeditor/getHistory/'+markerId)
        .then(r=>r.json()).then(data=>{
        let h = data.map(row=>
         `<div><b>${row.action}</b> (${row.created_at}):<pre>` +
         JSON.stringify(JSON.parse(row.changes), null, 2) +
         '</pre></div>'
        ).join('');
        document.getElementById('historyBox').innerHTML = h;
    });
}
