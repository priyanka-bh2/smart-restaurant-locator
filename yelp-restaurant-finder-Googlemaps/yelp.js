var gmap;
var markersarray = [];  
var popwindow;          

function initialize() {
    var initialCenter = { lat: 32.75, lng: -97.13 };
    gmap = new google.maps.Map(document.getElementById("googlemap"), {
        center: initialCenter,
        zoom: 16
    });
    popwindow = new google.maps.InfoWindow();
}

function findRestaurants() {
    var searchterms = document.getElementById("searchterms").value;
    clearpoints();  
    var center = gmap.getCenter();
    var latitude = center.lat();
    var longitude = center.lng();
    var apilink = `proxy.php?term=${encodeURIComponent(searchterms)}&latitude=${latitude}&longitude=${longitude}&limit=10`;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", apilink, true);
    xhr.setRequestHeader("Accept", "application/json");

    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var json = JSON.parse(this.responseText);
            console.log(json); 

            if (!json.businesses || json.businesses.length === 0) {
                alert("No restaurants found for the given search terms.");
                return;
            }
            json.businesses.forEach((restaurant, index) => {
                var pos = {
                    lat: restaurant.coordinates.latitude,
                    lng: restaurant.coordinates.longitude
                };
                var marker = new google.maps.Marker({
                    position: pos,
                    label: (index + 1).toString(),  
                    map: gmap  
                });

                markersarray.push(marker);  
                google.maps.event.addListener(marker, 'click', function () {
                    popwindow.close();

                    var moreinfo = `
                        <div>
                            <img src="${restaurant.image_url}" alt="${restaurant.name}" style="width:150px;"><br>
                            <b>${restaurant.name}</b><br>
                            Rating: ${restaurant.rating} / 5
                        </div>
                    `;
                    popwindow.setContent(moreinfo);
                    popwindow.open(gmap, marker);
                });
            });
        } else if (this.readyState == 4) {
            console.log("Error in the API request", this.status, this.responseText);
        }
    };
    xhr.send(null);
}


function clearpoints() {
    for (var i = 0; i < markersarray.length; i++) {
        markersarray[i].setMap(null);  
    }
    markersarray = [];  
}
