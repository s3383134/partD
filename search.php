<?php

require_once ("MiniTemplator.class.php");
require_once ("db_pdo.php");

function getSearch($tableName, $attributeName) {
	global $pdo;
	
	try {
	
		$query = "SELECT DISTINCT {$attributeName} FROM {$tableName} ORDER BY {$attributeName}";
		return $pdo->query($query);
		
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit; 
	}	
}

function generatePage(){
    $t = new MiniTemplator;

    $t->readTemplateFromFile ("search_template.htm");
	
    $result = getSearch("region", "region_name");
	while ($row = $result->fetch(PDO::FETCH_ASSOC)){
		$t->setVariable('regionName', $row['region_name']);
		
		$t->addBlock("regionName");
    }
    
	$result = getSearch("grape_variety", "variety");
	while ($row = $result->fetch(PDO::FETCH_ASSOC)){
		$t->setVariable('variety', $row['variety']);
		
		$t->addBlock("variety");
    }
	
	$result = getSearch("wine", "year");
	while ($row = $result->fetch(PDO::FETCH_ASSOC)){
		$t->setVariable('minYear', $row['year']);
		
		$t->addBlock("minYear");
    }
	
	$result = getSearch("wine", "year");
	while ($row = $result->fetch(PDO::FETCH_ASSOC)){
		$t->setVariable('maxYear', $row['year']);
		
		$t->addBlock("maxYear");
    }
	$t->generateOutput();
}

generatePage();

?>