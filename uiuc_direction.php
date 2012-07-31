<?php session_start() ?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="project/style.css" />

<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>UIUC Campus Map: Optimized Directions</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
  var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  var origin = null;
  var destination = null;
  var waypoints = [];
  var markers = [];
  var directionsVisible = false;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var uiuc = new google.maps.LatLng(40.101, -88.227);
    var myOptions = {
      zoom:13,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      center: uiuc
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));

    doGeolocation();

    google.maps.event.addListener(map, 'click', function(event) {
      if (origin == null) {
        origin = event.latLng;
        addMarker(origin);
      } else if (destination == null) {
        destination = event.latLng;
        addMarker(destination);
      } else {
        if (waypoints.length < 9) {
          waypoints.push({ location: destination, stopover: true });
          destination = event.latLng;
          addMarker(destination);
        } else {
          alert("Maximum number of waypoints reached");
        }
      }
    });
  }

  function doGeolocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(positionSuccess, positionError);
    } else {
      positionError(-1);
    }
  }

  function positionError(err) {
    var msg;
    switch(err.code) {
      case err.UNKNOWN_ERROR:
        msg = "Unable to find your location";
        break;
      case err.PERMISSION_DENINED:
        msg = "Permission denied in finding your location";
        break;
      case err.POSITION_UNAVAILABLE:
        msg = "Your location is currently unknown";
        break;
      case err.BREAK:
        msg = "Attempt to find location took too long";
        break;
      default:
        msg = "Location detection not supported in browser";
    }
    document.getElementById('info').innerHTML = msg;
    return false;
  }

  function positionSuccess(position) {
    // Centre the map on the new location
    var coords = position.coords || position.coordinate || position;
    var latLng = new google.maps.LatLng(coords.latitude, coords.longitude);
    map.setCenter(latLng);
    map.setZoom(12);
    var marker = new google.maps.Marker({
      map: map,
      position: latLng,
      title: 'There you are!'
    });
    document.getElementById('info').innerHTML = 'Looking for <b>' +
        coords.latitude + ', ' + coords.longitude + '</b>...';

    // And reverse geocode.
    (new google.maps.Geocoder()).geocode({latLng: latLng}, function(resp) {
      var place = "You're around here somewhere!";
      if (resp[0]) {
        var bits = [];
        for (var i = 0, I = resp[0].address_components.length; i < I; ++i) {
          var component = resp[0].address_components[i];
          if (contains(component.types, 'political')) {
            bits.push('<b>' + component.long_name + '</b>');
          }
        }
        if (bits.length) {
          place = bits.join(' > ');
        }
        marker.setTitle(resp[0].formatted_address);
      }
      document.getElementById('info').innerHTML = place;
    });
  }

  function contains(array, item) {
    for (var i = 0, I = array.length; i < I; ++i) {
      if (array[i] == item) return true;
    }
    return false;
  }

  function addMarker(latlng) {
    markers.push(new google.maps.Marker({
      position: latlng, 
      map: map,
      icon: "http://maps.google.com/mapfiles/marker" + String.fromCharCode(markers.length + 65) + ".png"
    }));    
  }

  function calcRoute() {
    if (origin == null) {
      alert("Click on the map to add a start point");
      return;
    }
    
    if (destination == null) {
      alert("Click on the map to add an end point");
      return;
    }
    
    var mode;
    switch (document.getElementById("mode").value) {
      case "bicycling":
        mode = google.maps.DirectionsTravelMode.BICYCLING;
        break;
      case "driving":
        mode = google.maps.DirectionsTravelMode.DRIVING;
        break;
      case "walking":
        mode = google.maps.DirectionsTravelMode.WALKING;
        break;
    }
    
    var request = {
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        travelMode: mode,
        optimizeWaypoints: document.getElementById('optimize').checked,
        avoidHighways: document.getElementById('highways').checked,
        avoidTolls: document.getElementById('tolls').checked
    };
    
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }
    });
    
    clearMarkers();
    directionsVisible = true;
  }
  
  function updateMode() {
    if (directionsVisible) {
      calcRoute();
    }
  }
  
  function clearMarkers() {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(null);
    }
  }
  
  function clearWaypoints() {
    markers = [];
    origin = null;
    destination = null;
    waypoints = [];
    directionsVisible = false;
  }
  
  function reset() {
    clearMarkers();
    clearWaypoints();
    directionsDisplay.setMap(null);
    directionsDisplay.setPanel(null);
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("directionsPanel"));    
  }
</script>
</head>
<body onload="initialize()" style="font-family: sans-serif;">
  <div id="title"  style="font-family:arial;color:white;font-size:30px;background:#f6822b;right:20px">Username: <?php echo $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
  </div>
  <div>
    <img src="project/assets/uiuc_banner.jpg" alt="University of Ilinois" width="120" height="28" style="position: absolute; top:12px; left: 430px"/>
  </div>
  <div><a href='project/index2.php' style="font-family:arial;color:white;font-size:20px;background:#f6822b; position: absolute; top: 14px; left: 561px; text-decoration:none"> CS411 Project </a>
  </div>
  <div style="position: absolute; top: 14px; right: 20px; "> <a href= "mailto:dhe6@illinois.edu" style="font-family:arial;color:white;font-size:20px;background:#f6822b; text-decoration:none">Contact</a>
  </div>
<div style="position:relative; left:150px">
  <div>UIUC Campus Map: Optimized Direction -- Project HELP!</div>
  <table style="width: 400px">
    <tr>
      <td><input type="checkbox" id="optimize" checked />Optimize</td>
      <td><input type="checkbox" id="highways" checked />Avoid highways</td>
      <td><input type="checkbox" id="tolls" checked />Avoid tolls</td>
    </tr>
    <tr>
      <td>
        <select id="mode" onchange="updateMode()">
          <option value="bicycling">Bicycling</option>
          <option value="driving">Driving</option>
          <option value="walking">Walking</option>
        </select>
      </td>
      <td><input type="button" value="Reset" onclick="reset()" class="cupid-blue"/></td>
      <td><input type="button" value="Get Directions!" onclick="calcRoute()" class="cupid-blue" /></td>
    </tr>
  </table>
  <div style="position:relative; border: 1px; width: 680px; height: 500px;">
    <div id="map_canvas" style="border: 1px solid black; position:absolute; width:680px; height:500px"></div>
    <div id="directionsPanel" style="position:absolute; left: 700px; width:240px; height:500px; overflow: auto"></div>
  </div>
</div>
</body>
</html>

