#!/usr/bin/php
<?php
require('./config.php');
require('./functions.php');
require('./geocode_functions.php');
init();
$sql = 'ALTER TABLE ways ADD surface BIGINT NOT NULL DEFAULT 0';
mysql_query($sql);
print mysql_error();


$sql = 'SELECT * FROM  ways WHERE  type ="area"';
$qh = mysql_query($sql);
while ($row = mysql_fetch_array($qh, MYSQL_ASSOC))
{
/*
maxlat
maxlon
minlat
minlon
$gps_1['lat'] - latitude (широта)
$gps_1['lon'] - longitude (долгота)
$gps_1['point_elevation'] (высота точки) // == 0 if this is sea. but must be defined!
*/
$width_start = array('lat'=> $row['maxlat'],'lon'=> ,'point_elevation' =>0);
$width_end = array();

$width = get_distance_between_2_points($width_start, $width_end);
}
final_work();