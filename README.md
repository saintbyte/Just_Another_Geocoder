Just_Another_Geocoder
SELECT * 
FROM ways_nodes
LEFT JOIN nodes ON ways_nodes.node_id = nodes.node_id
WHERE way_id =13803966
