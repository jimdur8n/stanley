<?php
//Establish MySQL connection variables
$dsn = 'mysql:host=localhost;dbname=archives';
$username = 'root';
$password = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);
//home url
$home = "localhost/stanley/digital/media/"

//Getting category selection and search terms
$search = "";
$topic_button = "";
$page = 1;
if(isset($_GET['topic_button'])) $topic_button = $_GET['topic_button'];
if(isset($_GET['search'])) $search = $_GET['search'];
if(isset($_GET['pg'])) $page= $_GET['pg'];


//Pagination
$offset = 0;
$page_result = 20;

if($page)
{
 $page_value = $page;
 if($page_value > 1)
 {	
  $offset = ($page_value - 1) * $page_result;
 }
}

//Logging into the database
	try{
$db = new PDO($dsn, $username, $password, $options);
	} catch(Exception $e){
	echo "Unable to connect";
	echo $e->getMessage();
	exit;	
	}

//The start of a variable for each collection or category in techsmith table
$queryCol = "SELECT DISTINCT collection FROM techsmith ORDER BY collection ASC;";

//Selecting videos with search terms in the title or description, and with the collection picked.
$query = "SELECT * FROM techsmith WHERE (description LIKE '%".$search."%' OR title LIKE '%".$search."%' OR collection LIKE '%".$search."%' OR subject LIKE '%".$search."%' OR relation LIKE '%".$search."%' OR reference LIKE '%".$search."%') AND collection LIKE '%".$topic_button."%' ORDER BY date ASC limit ".$offset.", ".$page_result.";";

//Grab the count of the query
$queryCount = "SELECT COUNT(*) AS count FROM techsmith WHERE (description LIKE '%".$search."%' OR title LIKE '%".$search."%' OR collection LIKE '%".$search."%' OR subject LIKE '%".$search."%' OR relation LIKE '%".$search."%' OR reference LIKE '%".$search."%') AND collection LIKE '%".$topic_button."%';";


	try{
	$results = $db->query($query);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}

//collect count of videos		
try{
	$results_count = $db->query($queryCount);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}

//collect all collection names
	try{
	$col_data = $db->query($queryCol);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}

//put collection data into an array
	$collections = $col_data->fetchALL(PDO::FETCH_ASSOC);

//taking that array and using field names as the key.
	$temp = $results->fetchALL(PDO::FETCH_ASSOC);

//put count data into an array
	$count = $results_count->fetchALL(PDO::FETCH_ASSOC);

//Search Bar
echo '<form action="" method="get">';
echo '<h2>Search</h2>';
echo '<input type="text" title="search videos" name="search" style="width:250px;"></input> ';
echo " Topic:";
echo '<select name="topic_button" title="select a topic">';
	echo '<option value="">ALL</option>';
	foreach($collections as $collection){
	echo '<option value="'.$collection["collection"].'">'.$collection["collection"].'</option>';
	}
echo '</select>';
echo '<input type="submit" value="Select" style="margin-left: 6px; margin-bottom: 0px;"/>';
echo '</form>';
echo '<a href=".">Browse All</a>';
echo '<br>';


//Title of List
if($topic_button == ""){
	echo '<h3>All Videos</h3>';
}else{
echo '<h3>'.$topic_button.'</h3>';
}
echo (int)$count[0]['count']." videos";

//Pagination top
echo '<div style="text-align:right">';
$pagecount = (int)$count[0]['count']; // Total number of rows
$num = $pagecount/$page_result;

if($page > 1)
{
 echo '<a href="?search='.$search.'&topic_button='.$topic_button.'&pg='.($page - 1).'"> Prev </a>';
}
for($i = 1 ; $i-1 <= $num ; $i++)
{
if($i == $page){
	echo '<strong>'.$page.'</strong>';	
	}else{
 echo '<a href="?search='.$search.'&topic_button='.$topic_button.'&pg='.$i.'"> '. $i .' </a>';
	}
}
if($num > $page)
{
 echo '<a href="?search='.$search.'&topic_button='.$topic_button.'&pg='.($page + 1).'"> Next </a>';
}
echo "</div>";
//Results list of videos with thumbnails

foreach ($temp as $item){
		echo '<div style="float:left; margin-bottom:15px; height:150px;">';
		echo '<a target="_blank" href="https://archives.boisestate.edu/digital/media/viewer/?id='.$item['ID'].'">';
		echo '<img src="https://archives.boisestate.edu/thumbs/'.$item['ID'].'.jpg" alt="'.$item['title'].'" width="150" height="100" style="border-radius: 15px; margin:5px">';
		echo '<br>';
		echo '<div style="line-height:1.1em; width:150px; padding:0px 0px 0px 8px">'.$item['title'].' ('.substr($item['date'],0,4).')</a></div>';
		echo '</div>';
		}



?>