#!/usr/bin/php
<?php
require('./config.php');
require('./functions.php');
require('./geocode_functions.php');
init();
$sql = 'ALTER TABLE ways ADD surface BIGINT NOT NULL DEFAULT 0';
mysql_query($sql);
print mysql_error();
$sql = 'ALTER TABLE ways ADD surface_perfect BIGINT NOT NULL DEFAULT 0';
mysql_query($sql);
print mysql_error();
//lat=56.982829&lon=60.206881
//lat=56.967065&lon=60.23778
//2.568 km 1.596 mi
// Output 2571.487232779
/*
$width_start = array('lat'=> 56.982829,'lon'=> 60.206881 ,'point_elevation' =>0);
$width_end = array('lat'=> 56.967065,'lon'=> 60.23778 ,'point_elevation' =>0);

$width = get_distance_between_2_points($width_start, $width_end);
print $width."\n";
$width_metrs = intval($width);
exit();
*/
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
//  Считаем приблизительный размер обьекта
$width_start = array('lat'=> $row['maxlat'],'lon'=> $row['minlon'] ,'point_elevation' =>0);
$width_end = array('lat'=> $row['maxlat'],'lon'=> $row['maxlon'] ,'point_elevation' =>0);
$width = get_distance_between_2_points($width_start, $width_end);
print 'width:'.$width."\n";
$height_start = array('lat'=> $row['minlat'],'lon'=> $row['minlon'] ,'point_elevation' =>0);
$height_end = array('lat'=> $row['maxlat'],'lon'=> $row['minlon'] ,'point_elevation' =>0);
$height = get_distance_between_2_points($height_start, $height_end);
print 'height:'.$height."\n";
$surface = $width * $height;
print 'surface:'.$surface."\n";
$sql = 'UPDATE ways SET surface='.$surface.' WHERE way_id='.$row['way_id'];
mysql_query($sql);
//-----
// now perfect surface
//http://gospodaretsva.com/urok-34-ploshhad-mnogougolnika-2.html
//http://abak.pozitiv-r.ru/geomet/39-ploshhad-mnogougol-nika
//56.7771897 59.8879261 56.7788788 59.8877788 56.7778653 59.8850631 56.7783074 59.8851615 56.7792465 59.892865 56.7779085 59.8918918 56.77724 59.8873459 56.7787818 59.8906126  56.7783437 59.8928332 56.7790053 59.8933738 56.7788746 59.8879005
print "-----------------------------------------\n";
}
final_work();