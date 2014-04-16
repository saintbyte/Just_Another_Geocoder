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
/*
//http://habrahabr.ru/post/143277/
$coords = array(
array('lng'=> 66.6634, 'lat' => '66.4433'),
array('lng'=> 66.6534, 'lat' => '66.4433'),
array('lng'=> 66.6434, 'lat' => '66.4433'), 
array('lng'=> 66.6334, 'lat' => '66.4433'),
);
$in = into_poly(66.4455, 66.2255, &$coords, $x='lng', $y='lat');
*/ 
function into_poly($sx, $sy, &$coords, $x='x', $y='y')
{
    $pj=0;
    $pk=0;
    $wrkx=0;
    $yu = 0;
    $yl = 0;
    $n = count($coords);
    for ($pj=0; $pj<$n; $pj++)
    {
        $yu = $coords[$pj][$y]>$coords[($pj+1)%$n][$y]?$coords[$pj][$y]:$coords[($pj+1)%$n][$y];
        $yl = $coords[$pj][$y]<$coords[($pj+1)%$n][$y]?$coords[$pj][$y]:$coords[($pj+1)%$n][$y];
        if ($coords[($pj+1)%$n][$y] - $coords[$pj][$y])
            $wrkx = $coords[$pj][$x] + ($coords[($pj+1)%$n][$x] - $coords[$pj][$x])*($sy - $coords[$pj][$y])/($coords[($pj+1)%$n][$y] - $coords[$pj][$y]);
        else
            $wrkx = $coords[$pj][$x];
        if ($yu >= $sy)
            if ($yl < $sy)
            {
                if ($sx > $wrkx)
                    $pk++;
                if (abs($sx - $wrkx) < 0.00001) return 1;
            }
        if ((abs($sy - $yl) < 0.00001) && (abs($yu - $yl) < 0.00001) && (abs(abs($wrkx - $coords[$pj][$x]) + abs($wrkx - $coords[($pj+1)%$n][$x]) - abs($coords[$pj][$x] - $coords[($pj+1)%$n][$x])) < 0.0001))
            return 1;
    }
    if ($pk%2)
        return 1;
    else
        return 0;
}




/*
 Быстро получает примерные отношения 
*/
function location_tree_src_fast_get($lat,$lon)
{
  global $_config;
  if ($_config['store_type'] == 'mysql')
  {
    $sql = 'SELECT * FROM ways WHERE  type="area" AND ((maxlat >= '.$lat.' AND minlat <= '.$lat.'  ) AND ( maxlon>='.$lon.' AND minlon<='.$lon.')) ORDER BY surface ASC  ';
    $qh = mysql_query($sql);
    $result = array();
    while($row = mysql_fetch_array($qh))
    {
     $result[] = $row;
    }
    return $result;
  }
}
function ways_wayid_to_key($ways)
{
  $result = array();
}
function location_tree_src_fine_get($lat,$lon)
{
 $fast_res = location_tree_src_fast_get($lat,$lon);
 $ways = array();
 foreach($fast_res as $way)
 {
 $ways[] = $way['way_id'];
 }
 $ways_data = ways_nodes_locations_get($ways);
 $ways_data = ways_wayid_to_key($ways_data);
 foreach($fast_res as $way)
 {
   $way_points = array();
 }

 var_dump($ways_data);
}