<?php
define('COORDINATES_FORMAT', 'WGS84'); 
define('MAJOR_AXIS', 6378137.0); //meters 
define('MINOR_AXIS', 6356752.3142); //meters 
define('MAJOR_AXIS_POW_2', pow(MAJOR_AXIS, 2)); //meters 
define('MINOR_AXIS_POW_2', pow(MINOR_AXIS, 2)); //meters 

/* 
$gps_1['lat'] - latitude (широта) 
$gps_1['lon'] - longitude (долгота) 
$gps_1['point_elevation'] (высота точки) // == 0 if this is sea. but must be defined! 

*/ 

//get arrays with gps coordinates, returns earth terrestrial distance between 2 points 
function get_distance_between_2_points($gps_1, $gps_2, $decart=false) 
{ 
    if(!$decart) 
    { 
        $true_angle_1 = get_true_angle($gps_1); 
        $true_angle_2 = get_true_angle($gps_2); 
         
        $point_radius_1 = get_point_radius($gps_1, $true_angle_1); 
        $point_radius_2 = get_point_radius($gps_2, $true_angle_2); 
         
        $earth_point_1_x = $point_radius_1 * cos(deg2rad($true_angle_1)); 
        $earth_point_1_y = $point_radius_1 * sin(deg2rad($true_angle_1)); 
         
        $earth_point_2_x = $point_radius_2 * cos(deg2rad($true_angle_2)); 
        $earth_point_2_y = $point_radius_2 * sin(deg2rad($true_angle_2)); 
         
        $x = get_distance_between_2_points(array('lat'=>$earth_point_1_x, 'lon'=>$earth_point_1_y), array('lat'=>$earth_point_2_x, 'lon'=>$earth_point_2_y), true); 
        $y = pi() *  (  ($earth_point_1_x + $earth_point_2_x) / 360 ) * ( $gps_1['lon'] - $gps_2['lon'] ); 

        return sqrt( pow($x,2) + pow($y,2) ); 
    } 
    else 
    { 
        return sqrt(pow(($gps_1['lat'] - $gps_2['lat']), 2) + pow(($gps_1['lon'] - $gps_2['lon']), 2)); 
    } 
} 

//returns degree's decimal measure, getting degree, minute and second 
function get_decimal_degree($deg=0, $min=0, $sec=0) 
{ 
    return ($deg<0) ? (-1*(abs($deg) + (abs($min)/60) + (abs($sec)/3600))) : (abs($deg) + (abs($min)/60) + (abs($sec)/3600)); 
} 

// get point, returns true angle 
function get_true_angle($gps) 
{ 
    return atan(    (  (MINOR_AXIS_POW_2 / MAJOR_AXIS_POW_2) * tan(deg2rad( $gps['lat']))    )  ) * 180/pi();  
} 

//get point and true angle, returns radius of small circle (radius between meridians)  
function get_point_radius($gps, $true_angle) 
{ 
    return (1 / sqrt((pow(cos(deg2rad($true_angle)), 2) / MAJOR_AXIS_POW_2) + (pow(sin(deg2rad($true_angle)), 2) / MINOR_AXIS_POW_2))) + $gps['point_elevation'];  
} 



function check_lat($lat) 
{
    if($lat>=0 && $lat<=90)       return 'north'; 
    else
	if($lat>=-90 && $lat<=0)  return 'south'; 
    return false; 
} 

function check_lon($lon) 
{ 
    if($lon>=0 && $lon<=180)  return 'east'; 
    else
       if($lon>=-180 && $lon<=0)  return 'west'; 
    return false; 
} 

function IsPointInside($polygon, $point) {
  $size = count($polygon);
  if($size <= 1) return false;
  $intersections_num = 0;
  $prev = $size - 1;
  $prev_under = $polygon[$prev]["y"] < $point["y"];

  for($i = 0; $i < $size; $i++) {
    $cur_under = $polygon[$i]["y"] < $point["y"];

    $a["x"] = $polygon[$prev]["x"] - $point["x"];
    $a["y"] = $polygon[$prev]["y"] - $point["y"];
    $b["x"] = $polygon[$i]["x"]  - $point["x"];
    $b["y"] = $polygon[$i]["y"]  - $point["y"];
    
    $t = ($a["x"]*($b["y"] - $a["y"]) - $a["y"]*($b["x"] - $a["x"]));
    if($cur_under && !$prev_under) {
      if($t > 0) $intersections_num += 1;
    }
    
    if(!$cur_under && $prev_under){
      if($t < 0) $intersections_num += 1;
    }

    $prev = $i;        
    $prev_under = $cur_under;        
  }
  return $intersections_num;
}

function location_tree_src_get($lat,$lon)
{
  global $_config;
  if ($_config['store_type'] == 'mysql')
  {
    $sql = 'SELECT * FROM ways WHERE  type="area" AND ((maxlat >= '.$lat.' AND minlat <= '.$lat.'  ) AND ( maxlon>='.$lon.' AND minlon<='.$lon.')) ORDER BY surface ASC  ';
    $qh = mysql_query($sql);
    while($row = mysql_fetch_array($qh))
    {
      var_dump($row);
    }
    print $sql;
  }
}


