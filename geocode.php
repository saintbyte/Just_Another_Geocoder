#!/usr/bin/php
<?php
require('./config.php');
require('./functions.php');
require('./geocode_functions.php');
init();
$lat=56.793440; $lon=60.580909; // Постовского 16
//$lat=56.801102; $lon=60.616813; // Авиционная 63а
//$lat=56.812062; $lon=60.649080; // ЦПКИО
//$lat=56.826946; $lon=60.631313; // Метеогорка
//$lat=56.834067; $lon=60.684958; // Сиренвый бульвар 1а
//$lat=56.857818; $lon=60.707617; // Озеро Шарташ
//$lat=56.893957; $lon=60.637536; // Краснофлотцев 39а
//$lat=56.808585; $lon=59.928017; // Ревда
//$lat=56.904878; $lon=59.948959; // Первоуральск
$result = location_tree_src_get($lat,$lon);
var_dump($result);
