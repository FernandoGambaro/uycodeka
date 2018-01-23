<?php
 include ("../conectar.php"); 

$codproveedor=$_GET["codproveedor"];
$cadena_busqueda=@$_GET['cadena_busqueda'];

$query="SELECT * FROM proveedores WHERE codproveedor='$codproveedor'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

  	$codprovincia=mysqli_result($rs_query, 0, "codprovincia");
  	$query_provincias="SELECT * FROM provincias WHERE codprovincia='$codprovincia'";
	$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);


$direccion=mysqli_result($rs_query, 0, "direccion")." , ". mysqli_result($res_provincias, 0, "nombreprovincia")." , UY ";

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Dirección Cliente</title>
    <meta charset="UTF-8">
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../map/styles.css" />


    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="../map/jquery.geocomplete.js"></script>

  </head>
  <body>
  <div style=" margin: auto; padding: 5px;">
  <div class="header" style=" margin: auto; padding: 5px; position: relative;">
<?php echo mysqli_result($rs_query, 0, "nombre");?></div>

    <div class="map_canvas" style=" margin: auto; padding: 5px;"></div>
    <form>
      <input id="geocomplete" style='width:45em' value="<?php echo $direccion;?>" />
      <input id="find" type="button" value="find"  style="display:none;"/>
          
      <a id="reset" href="#" style="display:none;">&nbsp;</a>
    </form>
<div id="logs">
</div>
    
    
    <script>
      $(function(){
      	var address1 = document.getElementById('find').value;
        $("#geocomplete").geocomplete({
          map: ".map_canvas",
          details: "form ",
			country: 'uy',
			address: address1,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			markerOptions: {
				draggable: true
			}
        });


        
        $("#geocomplete").bind("geocode:dragged", function(event, latLng){
	console.log('Dragged Lat: '+latLng.lat());
	console.log('Dragged Lng: '+latLng.lng());
	var map = $("#geocomplete").geocomplete("map");
    map.panTo(latLng);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latLng }, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				var road = results[0].address_components[1].long_name;
				var town = results[0].address_components[2].long_name;
				var county = results[0].address_components[3].long_name;
				var country = results[0].address_components[4].long_name;
				$('#logs').html(road+' '+town+' '+county+' '+country);
			}
		}
	});
        });
        
        
        $("#reset").click(function(){
          $("#geocomplete").geocomplete("resetMarker");
          $("#reset").hide();
          return false;
        });
        
        $("#find").click(function(){
          $("#geocomplete").trigger("geocode");
        }).click();
      });
    </script>
</div>    
  </body>
</html>