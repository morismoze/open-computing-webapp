let req;
let detailsCnt;
let mapCnt;

function changeColor(row) {
    row.style.backgroundColor = "#B0E0E6";
}

function returnColor(row) {
    row.style.backgroundColor = "#ffffff";
}

function getDetails(element) {
    resizeNav();
    createMapCnt(element.getAttribute('wrapi_lat'), element.getAttribute('wrapi_lon'), element.getAttribute('napi_lat'), element.getAttribute('napi_lon'), element.getAttribute('name'), element.getAttribute('handle'));
    createDetailsCnt();
    createLoadingSpinner();
    
    let id = element.getAttribute('row_id');
    if (window.XMLHttpRequest) { // FF, Safari, Opera, IE7+
        // stvaranje novog objekta
        req = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 6+
        req = new ActiveXObject("Microsoft.XMLHTTP"); //ActiveX
    }

    if (req) { 
        // uspješno stvoren objekt
        req.onreadystatechange = responseFunc;
        req.open("GET", `detalji.php?id=${id}`, true); // metoda, URL, asinkroni način
        req.send(null); //slanje (null za GET, podaci za POST)
    } 
}

function resizeNav() {
    document.getElementById('nav').setAttribute('style', 'margin-top: 0; margin-bottom: 0; height: 45%;');
    for (const el of document.getElementsByClassName('details')) {
        el.style.cssText = "padding-top: 15px;"
    }
}

function createDetailsCnt() {
    detailsCnt = document.getElementById("detailsCnt");
    if(detailsCnt == null) {
        detailsCnt = document.createElement("div");
        detailsCnt.setAttribute('id', 'detailsCnt');
        detailsCnt.style.cssText = "width: 95%; height: 52%; margin: auto; color: #000000; font-size: 1em; text-align: center; border: 2px dashed #505050; border-radius: 15px; background-color: #ffffff; font-weight: bold;";
        document.getElementById('left-inner-cnt').append(detailsCnt);
    } else {
        detailsCnt.innerHTML = "";
    }
}

var mymap;
function createMapCnt(wrapi_lat, wrapi_lon, napi_lat, napi_lon, name, handle) {
    const coordinates = [[wrapi_lat, wrapi_lon], [napi_lat, napi_lon]];
    const configObject = {color: 'red'};

    mapCnt = document.getElementById("mapCnt");
    if(mapCnt == null) {
        mapCnt = document.createElement("div");
        mapCnt.setAttribute('id', 'mapCnt');
        mapCnt.style.cssText = "width: 600px; height: 150px; margin: auto;";
        document.getElementById('search-title-div').appendChild(mapCnt);
    } else {
        if (mymap !== undefined && mymap !== null) {
            mymap.remove();
        }
    }

    mymap = L.map('mapCnt').setView([51.505, -0.09], 1);
    var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(mymap);

    var marker1 = L.marker([wrapi_lat, wrapi_lon]).addTo(mymap);
    var marker2 = L.marker([napi_lat, napi_lon]).addTo(mymap);
    var link = `https://en.wikipedia.org/wiki/${handle}`;
    marker1.bindPopup(`Naziv: ${name}<br/>Širina(W): ${wrapi_lat}<br/>Dužina(W): ${wrapi_lon}<br/><a href='${link}' target='_blank'>Službena stranica</a>`).openPopup();
    marker2.bindPopup(`Naziv: ${name}<br/>Širina(N): ${napi_lat}<br/>Dužina(N): ${napi_lon}<br/><a href='${link}' target='_blank'>Službena stranica</a>`).openPopup();

    var polyline = L.polyline(coordinates, configObject).addTo(mymap);
    mymap.fitBounds(polyline.getBounds());

}

function createLoadingSpinner() {
    let loading = document.createElement('img');
    loading.setAttribute('src', './images/Spinning_wheel_throbber.gif');
    loading.style.marginTop = "2em";    
    detailsCnt.appendChild(loading);
}

function responseFunc() {
    if (req.readyState == 4) { // primitak odgovora
        if (req.status == 200) {
            // css not refreshing in apache xampp
            detailsCnt.innerHTML = req.responseText;
        } else { 
            alert("Something went wrong: " + req.statusText);
        }
    }
}