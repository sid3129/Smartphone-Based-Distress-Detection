<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <title>Cooperative analysis</title>
	<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0;
        padding: 0;
      }

    </style>
	
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

<!--Distance using Haversine Formula -->
<script type="text/javascript">
	var Rm = 3961; // mean radius of the earth (miles) at 39 degrees from the equator
	var Rk = 6373; // mean radius of the earth (km) at 39 degrees from the equator
		
	//Finds Distance
	function findDistance(lat1,lon1,lat2,lon2) 
	{
		var t1, n1, t2, n2, lat1, lon1, lat2, lon2, dlat, dlon, a, c, dm, dk, mi, km;
		
		// get values for lat1, lon1, lat2, and lon2
		t1 = lat1;
		n1 = lon1;
		t2 = lat2;
		n2 = lon2;
		
		// convert coordinates to radians
		lat1 = deg2rad(t1);
		lon1 = deg2rad(n1);
		lat2 = deg2rad(t2);
		lon2 = deg2rad(n2);
		
		// find the differences between the coordinates
		dlat = lat2 - lat1;
		dlon = lon2 - lon1;
		
		// here's the heavy lifting
		a  = Math.pow(Math.sin(dlat/2),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(dlon/2),2);
		c  = 2 * Math.atan2(Math.sqrt(a),Math.sqrt(1-a)); // great circle distance in radians
		dm = c * Rm; // great circle distance in miles
		dk = c * Rk; // great circle distance in km
		
		// round the results down to the nearest 1/1000
		mi = round(dm);
		km = round(dk);
		
		// display the result
		return km;
	}
	
	
	// convert degrees to radians
	function deg2rad(deg) 
	{
		rad = deg * Math.PI/180; // radians = degrees * pi/180
		return rad;
	}
	
	
	// round to the nearest 1/1000
	function round(x) 
	{
		return Math.round( x * 1000) / 1000;
	}
	</script>
	
<!--Sorting-->
<script type="text/javascript">
	//Sorting Function
function dynamicSort(property) 
{
	var sortOrder = 1;
	if(property[0] === "-")
	{
		sortOrder = -1;
		property = property.substr(1);
	}
	return function (a,b)
	{
		var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
		return result * sortOrder;
	}
}	
	</script>
	
<!--Algorithm-->
<script type="text/javascript">
//cooperative algorithm
function  decide_cooperation(users)
{
	for(var ctr=0;ctr<users.length;ctr++)
	{
		//Finding Masters And Slave	
		for(var i=0;i<users[ctr].length;i++)
		{
			var min_distance = 1;
			var master = -1;
			
			//checking user should be active
			if(users[ctr][i]['is_active'].trim()!="yes")  
			continue;
		
			//A master cannot look for another master
			if(users[ctr][i]['coop_status']=="master")
			continue;
		
			for(var j=i+1;j<users[ctr].length;j++)
			{
				//candidate should be active
				if(users[ctr][j]['is_active'].trim()!="yes")  
				continue;
			
				//coop candidate should not be a slave already 
				if(users[ctr][j]['coop_status']!="slave")
				{
					var dist = findDistance(users[ctr][i]['latitude'],users[ctr][i]['longitude'],users[ctr][j]['latitude'],users[ctr][j]['longitude']);
					
					if(dist<=0.010)
						{
							
							if(dist<=min_distance)
								{
									min_distance = dist;
									master = j
								}
						}
				}	
			}
			
			if(min_distance!=-1 && master!=-1)
			{
				users[ctr][i]['coop_status'] = "slave";
				users[ctr][i]['master_id'] = users[ctr][master]['user_id'] ;
				users[ctr][master]['coop_status'] = "master";
			}
			else
			{
				users[ctr][i]['coop_status'] = "cant_find_a_master";
			}
		}
	}
		return users;
}	
</script>

<!--Grid Distribution-->
<script type="text/javascript">
//Assigns grid numbers according to latitude and longitude
function assign_grid_number(lat,lng,lower_x,lower_y,x,y)
{
	//latitude +90 to -90         x
	//longitude +180 to -180      y

	//a means positive
	//b means negative
	

	var cord1;
	if(lower_x<10.0)
		cord1 = "0"+lower_x;
	else
		cord1 = lower_x;
	
	if(lat<0.0)
		cord1 = "a"+cord1;
	else
		cord1 = "b"+cord1;
	
	//*********************************
	var cord2;
	if(lower_y>=100.0)
		cord2 = lower_y;
	else
		{
			if(lower_y>=10.0)
			cord2 = "0"+lower_y;
			else
			cord2 = "00"+lower_y;
		}
	if(lng<0.0)
		cord2 = "a"+cord2;
	else
		cord2 = "b"+cord2;
	
	//*********************************
	var cord3;
	if(x<10.0)
		cord3= "0"+x;
	else
		cord3= x;
	
	//*********************************
	var cord4;
	if(y<10.0)
		cord4= "0"+y;
	else
		cord4= y;
	
	
	var final = cord1 + "," + cord2 + "," + cord3 + "," + cord4;
	return final;
}

function readText()
{
<?php $txt_file    = file_get_contents('locations.txt');?>
var output = <?= json_encode($txt_file,JSON_HEX_APOS); ?>;		

output=output.split("\n");

var data=[];	
	
for(var i=1;i<output.length;i++)
	{
	str=output[i];
	str=str.split("|");
	
	var user_id = str[0];
	var latitude = str[1];
	var longitude = str[2];
	var battery = str[3];
	var is_active = str[4];
	data.push({'user_id':user_id,'latitude':latitude,'longitude':longitude,'battery':battery,'is_active':is_active});
	}    
return data;	
} 

//main function that executes all the code related to grid analysis and returns an object of data sorted in order of grid_number   
function analyse_grid()
{	
var coordinates= readText();

var grid=[];

for(var i=0;i<coordinates.length;i++)
{
	var const_x=Math.floor(Math.abs(coordinates[i]['latitude']));
	var const_y=Math.floor(Math.abs(coordinates[i]['longitude']));
			
	var x =Math.abs(coordinates[i]['latitude']);
	x-=const_x;
	x*=100;
	x=Math.floor(x);
				
	var y =Math.abs(coordinates[i]['longitude']);
	y-=const_y;
	y*=100;
		
	y=Math.floor(y);
	var grid_number = 	assign_grid_number(coordinates[i]['latitude'],
					coordinates[i]['longitude'],
					const_x,
					const_y,
					x,
					y);
	grid.push({	
				'user_id' : coordinates[i]['user_id'],	
				'latitude' : coordinates[i]['latitude'],	//current location
				'longitude' : coordinates[i]['longitude'],	//current location
				'grid_number': grid_number,
				'coop_status':"idle",    	//idle,master,slave
				'is_active':coordinates[i]['is_active'],		  	//yes,no
				'battery': coordinates[i]['battery'],	//battery level
				'master_pos':-1,
				'master_id':""			  	
				})			
}	
grid.sort(dynamicSort("grid_number"));
return grid;
}
</script>
	
<!--Main script-->
<script type="text/javascript">
var nodes=analyse_grid();
var info = [];
var temp=[];

temp.push(nodes[0]);
for(var i=1;i<nodes.length;i++)
{	
	if(nodes[i]['grid_number']==nodes[i-1]['grid_number'])
	{
		temp.push(nodes[i]);
	}
	else
	{
		//Sorting By Battery level in ascending order
		temp.sort(dynamicSort("battery"));
		info.push(temp);
		temp=[];
		temp.push(nodes[i]);
	}
}
//Sorting By Battery level in ascending order
temp.sort(dynamicSort("battery"));
info.push(temp);

//calls function decide_cooperation 
var users = decide_cooperation(info);

function get_markers()
{	
	var masters = [];
	var slaves = [];
	var cant_find_a_master = [];

	for(var ctr=0;ctr<users.length;ctr++)
		{
		for(var i=0;i<users[ctr].length;i++)
			{
			if(users[ctr][i]['coop_status']=="master")
				{
				masters.push({	latitude : users[ctr][i]['latitude'],longitude: users[ctr][i]['longitude'],user_id : users[ctr][i]['user_id']});				
				}
			else if(users[ctr][i]['coop_status']=="slave")
				{
				slaves.push({	latitude : users[ctr][i]['latitude'],longitude: users[ctr][i]['longitude'],user_id : users[ctr][i]['user_id']});				
				}
			else if(users[ctr][i]['coop_status']=="cant_find_a_master")
				{
				cant_find_a_master.push({	latitude : users[ctr][i]['latitude'],longitude: users[ctr][i]['longitude'],user_id : users[ctr][i]['user_id']});				
				}
			}
		}
	var final = [];
	final.push(masters);
	final.push(slaves);
	final.push(cant_find_a_master);
	return final;
}
</script>	

<!--Creating Map-->
<script>
function initialize() {
  var mapOptions = {
    zoom: 15,
    center: new google.maps.LatLng(28.545875, 77.273116)
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
								
	var points = get_markers();
	

   setMarkers(map, points[0],'master.png');
   setMarkers(map, points[1],'slave.png');
   setMarkers(map, points[2],'cant.png');
}

google.maps.event.addDomListener(window, 'load', initialize);

function setMarkers(map, locations,img_path) {
	var iconBase = 'images/';
	for (var i = 0; i < locations.length; i++) 
		{
			
		var point = locations[i];
		
		var myLatLng = new google.maps.LatLng(point['latitude'], point['longitude']);
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			labelContent : point['user_id'],
			labelAnchor: new google.maps.Point(point['latitude'], point['longitude']),
            labelClass: 'labels', // the CSS class for the label,
            labelInBackground: false,
			icon: iconBase + img_path
			});
		}
}
</script>
	
</head>
<body>
	<div id="map-canvas"></div>
</body>
</html>

