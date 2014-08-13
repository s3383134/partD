<!DOCTYPE HTML PUBLIC
"-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Answer Page</title>
</head>
<body bgcolor="white">

<?php 

	function showerror() {
		die("Error " . mysql_errno() . " : " . mysql_error());
	}
	
	require 'db.php';

	// Show all wines in a region in a <table>
	function displayWines($connection, $query, $wineName) {
    // Run the query on the server
		if (!($result = @ mysql_query ($query, $connection))) {
			showerror();
		}

    // Find out how many rows are available
		$rowsFound = @ mysql_num_rows($result);

		// If the query has results ...
		if ($rowsFound > 0) {
			// ... print out a header
			print "You searched for $wineName with a region of $wineryName <br><br>";

			// and start a <table>.
			print "\n<table>\n<tr>" .
				"\n\t<th>Wine ID</th>" .
				"\n\t<th>Wine Name</th>" .
				"\n\t<th>Winery Name</th>" . 
				"\n\t<th>Year</th>\n</tr>"; 
			
			// Fetch each of the query rows
			while ($row = @ mysql_fetch_array($result)) {
			// Print one row of results
			print "\n<tr>\n\t<td>{$row["wine_id"]}</td>" .
				"\n\t<td>{$row["wine_name"]}</td>" .
				"\n\t<td>{$row["winery_name"]}</td>" .
				"\n\t<td>{$row["year"]}</td>\n</tr>"; 
			} //end while loop body
			
			//finish table 
			print "\n</table>"; 
		} //end if $rowsFound body 
		
		//Report how many rows were found
		print "<br>{$rowsFound} records found matching your criteria<br>"; 
	} //end of function
	
	// Connect to the MySQL server
	if (!($connection = @ mysql_connect(DB_HOST, DB_USER, DB_PW))) {
		die("Could not connect");
	}
	
	//get user data 
	$wineName = $_GET['wineName']; 
	$wineryName = $_GET['wineryName'];
	
	if (!mysql_select_db(DB_NAME, $connection)) {
		showerror();
	}
		
	//start a query 
	$query = "SELECT wine_id, wine_name, winery_name, year 
	FROM wine, winery 
	WHERE wine.winery_id = winery.winery_id"; 
	
	if (!empty($wineName)) {
		$query .= " AND wine_name = '{$wineName}'";
	}
	
	if (!empty($wineryName)) {
		$query .= " AND winery_name = '{$wineryName}'";
	}
	
	//order the list 
	$query .= " ORDER BY wine_name"; 
	
	//run query, show results 
	displayWines($connection, $query, $wineName); 
	
?>

</body>
</html>