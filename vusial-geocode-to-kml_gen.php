#!/usr/bin/php
<?php
require('./config.php');
require('./functions.php');
require('./geocode_functions.php');
init();
$lat=56.793561; $lon=60.580872; 
// Постовского 10 56.793561&lon=60.580872
//$lat=56.801102; $lon=60.616813; // Авиционная 63а
//$lat=56.812062; $lon=60.649080; // ЦПКИО
//$lat=56.826946; $lon=60.631313; // Метеогорка
//$lat=56.834067; $lon=60.684958; // Сиренвый бульвар 1а
//$lat=56.857818; $lon=60.707617; // Озеро Шарташ
//$lat=56.893957; $lon=60.637536; // Краснофлотцев 39а
//$lat=56.808585; $lon=59.928017; // Ревда
//$lat=56.904878; $lon=59.948959; // Первоуральск
function kml_placemark_begin()
{
    $kml = '';
    $kml .= '<Placemark>'."\r\n";
    $kml .= '<styleUrl>#yellowLineGreenPoly</styleUrl>'."\r\n";
    $kml .= '<LineString>'."\r\n";
    //$kml .= '<extrude>1</extrude>'."\r\n";
    //$kml .= '<tessellate>1</tessellate>'."\r\n";
    $kml .= '<altitudeMode>absolute</altitudeMode>'."\r\n";
    $kml .= '<coordinates>'."\r\n";
    return $kml;
}
function kml_placemark_end()
{
    $kml = '';
    $kml .= '</coordinates>'."\r\n";
    $kml .= '</LineString>'."\r\n";
    $kml .= '</Placemark>'."\r\n";
    return $kml;
}
$kml_template = '<'.'?'.'xml version="1.0" encoding="UTF-8"'.'?'.'>'."\r\n";
$kml_template .= '<kml xmlns="http://www.opengis.net/kml/2.2">'."\r\n";
$kml_template .= '<Document>'."\r\n";
$kml_template .= '<name>Test</name>'."\r\n";
$kml_template .= '<description>Test</description>'."\r\n";
$kml_template .= '<Style id="yellowLineGreenPoly">'."\r\n";
$kml_template .= '<LineStyle>'."\r\n";
$kml_template .= '<color>7f00ffff</color>'."\r\n";
$kml_template .= '<width>4</width>'."\r\n";
$kml_template .= '</LineStyle>'."\r\n";
$kml_template .= '<PolyStyle>'."\r\n";
$kml_template .= '<color>7f00ff00</color>'."\r\n";
$kml_template .= '</PolyStyle>'."\r\n";
$kml_template .= '</Style>'."\r\n";
$alt = '2000';
$result = location_tree_src_fast_get($lat,$lon);
foreach($result as $item)
{
 $kml_template .= kml_placemark_begin();
 //$kml_template .= $row['lon'].','.$row['latM@M@'].','.$alt."\r\n";
 $kml_template .= '';
 var_dump($item);
 print "\n\n";
 $kml_template .= kml_placemark_end();
}
print "=======================\n";
$result = location_tree_src_fine_get($lat,$lon);
foreach($result as $item)
{
 print $item['way_id']."\n";
}
$kml_template .= '</Document>'."\r\n";
$kml_template .= '</kml>'."\r\n";
file_put_contents('1.kml',$kml_template);
