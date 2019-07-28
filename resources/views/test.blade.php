<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

</head>
<body>
<body onload="initialize();">
<div id="map_canvas" style="width:70%; height:100%; float:left"></div>
<div id="dirs" style="width:30%;height:100%;float:right;">
    <button onclick="calcRoute();">press me</button>
    <div id="directionsPanel" style="float:right;width:90%;height :100%; border-style:solid; border-width:5px;"></div>
</div>

<script type="text/javascript">
    var directionDisplay;
    var directionsService = new google.maps.DirectionsService();
    var map;


    </script>
</body>

</body>
</html>