<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  	<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBB_bF_BN2tw__iM3HCASuZTiro8U9QPnM"></script>
	<link rel="stylesheet" href="/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/css/custom.css" />
	<script type="text/javascript" src="/js/gmaps.js"></script>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container">
	<div class="card">
	    <div class="card-header">
	    	<strong>Laravel App Demo for Google Map</strong>
	    </div>
		<div class="card-body">

			<div class="row">
				<div class="col-md-7">
					<div id="map"></div>
				</div>
				<div class="col-md-5">
					<div class="card">
					  <div class="card-body">
					    <form>
						    <div class="form-group">
						      <label for="name">Name:</label>
						      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
						    </div>

						    <div class="form-group">
							    <label for="name">Gender:</label>
							    <div class="form-check form-check-inline">
								  <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Male" checked>
								  <label class="form-check-label" for="exampleRadios1">
								    Male
								  </label>
								</div>
								<div class="form-check form-check-inline">
								  <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Female">
								  <label class="form-check-label" for="exampleRadios1">
								    Female
								  </label>
								</div>
						    </div>

						    <div class="form-group">
						      <label for="name">Age:</label>
						      <input type="text" class="form-control" id="age" name="age" placeholder="Enter Age">
						    </div>

						    <div class="form-group">
						      <label for="name">Language:</label>
						      <input type="text" class="form-control" id="language" name="language" placeholder="Enter Language">
						    </div>
						    <div class="form-group">
						      <label for="name">Latitude:</label>
						      <input type="text" class="form-control" id="lat" name="language" placeholder="Enter Lat" disabled="">
						    </div>

						    <div class="form-group">
						      <label for="name">Longitude:</label>
						      <input type="text" class="form-control" id="long" name="language" placeholder="Enter longitude" disabled="">
						    </div>

						  <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
						</form>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    var map;
    var data = {!! json_encode($data->toArray()) !!};
    var dicon = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
    var ricon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';

    navigator.geolocation.getCurrentPosition(showPosition);

    function showPosition(position) {
    	$("#lat").val(position.coords.latitude);
    	$("#long").val(position.coords.longitude);

    	lastMarker = map.addMarker({
		    lat: $("#lat").val(),
	        lng: $("#long").val(),
	        icon:ricon,
	        animation:google.maps.Animation.BOUNCE
		  });
	}

    $(document).ready(function(){
      map = new GMaps({
        el: '#map',
        lat: 21.1591425,
        lng: 72.6822088,
        zoom: 7,
        click: function(e){
        	$("#lat").val(e.latLng.lat());
    		$("#long").val(e.latLng.lng());

    		marker = map.addMarker({
			    lat: e.latLng.lat(),
		        lng: e.latLng.lng(),
		        icon:ricon,
		        animation:google.maps.Animation.BOUNCE
			  });

    		if(lastMarker){
                lastMarker.setMap(null);
            }

            lastMarker = marker;
            map.refresh();
        }
      });

      

      // Initialize
      if (!jQuery.isEmptyObject(data)) {
      	$.each(data,function(k, val){
      		var content = createContent(val.name, val.gender, val.age, val.language);
      		map.addMarker({
		        lat: val.lat,
		        lng: val.long,
		        title: val.name,
		        icon: dicon,
		        infoWindow: {
		          content: content
		        }
		      });
      	});

      	map.refresh();
      }

    });

    $(".btn").click(function(e){
    	e.preventDefault();

    	var name = $("input[name='name']").val();
    	var age = $("input[name='age']").val();
    	var language = $("input[name='language']").val();
    	var gender = $("input[type=radio]:checked").val();

    	if (name != '' && age != '' && language != '') {

    		var content = createContent(name, gender, age, language);

	    	$.ajax({
              type: "POST",
              dataType: "json",
              url: '/map',
              data: {
              	'_token': $('meta[name="csrf-token"]').attr('content'),
              	name:name,
              	age:age,
              	language: language,
                gender:gender,
                lat: $("#lat").val(),
                long: $("#long").val()
               },
              success: function(data){
                console.log(data);
			    	map.addMarker({
				        lat: $("#lat").val(),
				        lng: $("#long").val(),
				        title: name,
				        icon: dicon,
				        infoWindow: {
				          content: content
				        }
				      });
			    	map.refresh();
			    	alert("Physician added successfully.");
              }
            });

    	}else{
    		alert("Please fill a data first.");
    	}
    });

    function createContent(name, gender, age, language) {
    	var content = '<p><strong>Name:</strong> '+name+'</br>';
    	content = content + '<strong>Gender:</strong> '+gender+'</br>';
    	content = content + '<strong>Age:</strong> '+age+'</br>';
    	content = content + '<strong>Language:</strong> '+language+'</p>';

    	return content;
    }
  </script>

</body>
</html>