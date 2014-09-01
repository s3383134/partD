<?php

require_once ("MiniTemplator.class.php");

function getSearch($tableName, $attributeName) {

    //conection: 
    $link = mysqli_connect("localhost","webadmin","password","winestore") or die("Error " . mysqli_error($link)); 

    //consultation: 
    $query = "SELECT DISTINCT {$attributeName} FROM {$tableName} ORDER BY {$attributeName}";

    //execute the query. 
    return $link->query($query);
	
}

function generatePage(){
    $t = new MiniTemplator;

    $t->readTemplateFromFile ("search_template.htm");

    $rows = getSearch("region", "region_name");
	while ($row =  mysqli_fetch_array($rows)){
		$t->setVariable('regionName', $row['region_name']);
		
		$t->addBlock("regionName");
    }
    
	$rows = getSearch("grape_variety", "variety");
	while ($row =  mysqli_fetch_array($rows)){
		$t->setVariable('variety', $row['variety']);
		
		$t->addBlock("variety");
    }
	
	$rows = getSearch("wine", "year");
	while ($row =  mysqli_fetch_array($rows)){
		$t->setVariable('minYear', $row['year']);
		
		$t->addBlock("minYear");
    }
	
	$rows = getSearch("wine", "year");
	while ($row =  mysqli_fetch_array($rows)){
		$t->setVariable('maxYear', $row['year']);
		
		$t->addBlock("maxYear");
    }
	$t->generateOutput();
}

generatePage();

?>