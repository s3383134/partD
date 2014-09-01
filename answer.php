<?php

require_once ("MiniTemplator.class.php");

function displayWines($wineName, $wineryName, $regionName, $grapeVariety, $year){
    // Conection: 
    $link = mysqli_connect("localhost","webadmin","password","winestore"); 

    //consultation: 
    $query = "SELECT 
				w.wine_id, 
				w.wine_name, 
				wy.winery_name, 
				r.region_name, 
				w.year, 
				gv.variety,
				inv.cost,
				inv.on_hand, 
				(SELECT SUM(items.qty) FROM items WHERE items.wine_id = w.wine_id) AS total_sold,
				(SELECT SUM(items.price) FROM items WHERE items.wine_id = w.wine_id) AS revenue
			FROM 
				grape_variety AS gv
					JOIN 
						wine_variety ON gv.variety_id = wine_variety.variety_id
					JOIN wine AS w ON wine_variety.wine_id =  w.wine_id
					JOIN winery AS wy ON w.winery_id = wy.winery_id
					JOIN region AS r ON wy.region_id = r.region_id 
					JOIN inventory AS inv ON w.wine_id = inv.wine_id"; 
			
	// if statements to check values

	if (!empty($wineName)) 
		$query .= " AND w.wine_name LIKE \"%$wineName%\"";
	
	if (!empty($wineryName)) 
		$query .= " AND wy.winery_name LIKE \"%$wineryName%\"";
	
	if (!empty($regionName)) 
		$query .= " AND r.region_name = \"$region\"";
		
	if (!empty($grapeVariety)) 
		$query .= " AND gv.variety = \"$grapeVariety\"";
		
	if (!empty($minYear)) 
		$query .= " AND w.year >= \"$minYear\"";
		
	if (!empty($maxYear)) 
		$query .= " AND w.year <= \"$maxYear\"";
	
	if (!empty($minStock)) 
		$query .= " AND inv.on_hand >= \"$minStock\"";
		
	if (!empty($maxYear)) 
		$query .= " AND inv.on_hand <= \"$maxStock\"";
		
	if (!empty($minPrice)) 
		$query .= " AND inv.cost >= \"$minPrice\"";
		
	if (!empty($maxPrice)) 
		$query .= " AND inv.cost <= \"$maxPrice\"";
		
	// Finish query 
	$query .= "
		ORDER BY w.wine_name";

		
	echo $query; 
    //execute the query. 
    return $link->query($query);
}
echo $wineName; 
function generatePage(){
    $t = new MiniTemplator;

	$t->readTemplateFromFile ("answer_template.htm");
	
	// GET variables
	$wineName = $_GET['wineName']; 
	$wineryName = $_GET['wineryName'];
	$regionName = $_GET['regionName']; 
	$grapeVariety = $_GET['grapeVariety']; 
	$minYear = $_GET['minYear'];
	$maxYear = $_GET['maxYear'];
	$minStock = $_GET['minStock'];
	$maxStock = $_GET['maxStock'];
	$minPrice = $_GET['minPrice'];
	$maxPrice = $_GET['maxPrice'];
	
	
	// Set specified variables
	$rows = displayWines($$wineName, $wineryName, $regionName, $grapeVariety, $year);
		
	while ($row =  mysqli_fetch_array($rows)){
	$t->setVariable('wineID',$row['wine_id']);
	$t->setVariable('wineName', $row['wine_name']);
	$t->setVariable('regionName', $row['region_name']);
	$t->setVariable('grapeVariety', $row['variety']);
	$t->setVariable('year', $row['year']);
	$t->setVariable('cost', $row['cost']);
	$t->setVariable('stock', $row['on_hand']);
	$t->setVariable('totalSold', $row['total_sold']);
	$t->setVariable('revenue', $row['revenue']);
	
	$t->addBlock("wines"); 
	}
	$t->generateOutput();

}
generatePage();
	
?>