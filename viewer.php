<?php
include "header.php";
//This page will start with a GET to find the unique TechSmith code for a specific viewer
//Here is an example iframte:
/* <iframe style="width: 100%; height: 480px; border: 0;" src="https://boisestate.techsmithrelay.com/connector/embed/index/wxOo" width="300" height="150" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>
For this iframe, we want "wxOo"
*/

if(isset($_GET['id'])) $video = $_GET['id'];

//connect to db
$dsn = 'mysql:host=localhost;dbname=archives';
$username = 'root';
$password = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
); 

	try{
$db = new PDO($dsn, $username, $password, $options);
	} catch(Exception $e){
	echo "Unable to connect";
	echo $e->getMessage();
	exit;	
	}
	
$query = "SELECT * FROM techsmith WHERE ID=".$video.";";
	try{
	$results = $db->query($query);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}
//taking that array and using field names as the key. this will be used to build the results table
	$temp = $results->fetchALL(PDO::FETCH_ASSOC);

//Browsing more videos: Grabbing some data from the video to query other videos like it.
	$creator = $temp[0]['creator'];
	$date = substr($temp[0]['date'],0,3);
	$collection = $temp[0]['collection'];
	$subject = $temp[0]['subject'];

//This query will pull one random video with the same creator as the original video
$query_creator = "SELECT * FROM techsmith WHERE creator LIKE '%".$creator."%' ORDER BY RAND() LIMIT 1;";
	try{
	$result_creator = $db->query($query_creator);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}
	$same_creator = $result_creator->fetchALL(PDO::FETCH_ASSOC);

//This query will pull one random video with the same date as the original video
$query_date = "SELECT * FROM techsmith WHERE date LIKE '%".$date."%' ORDER BY RAND() LIMIT 1;";
	try{
	$result_date = $db->query($query_date);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}
	$same_date = $result_date->fetchALL(PDO::FETCH_ASSOC);

//This query will pull one random video from the same collection as the original video
$query_collection = "SELECT * FROM techsmith WHERE collection LIKE '%".$collection."%' ORDER BY RAND() LIMIT 1;";
	try{
	$result_collection = $db->query($query_collection);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}
	$same_collection = $result_collection->fetchALL(PDO::FETCH_ASSOC);

//This query will pull one random video from the same subject as the original video
$query_subject = "SELECT * FROM techsmith WHERE subject LIKE '%".$subject."%' ORDER BY RAND() LIMIT 1;";
	try{
	$result_subject = $db->query($query_collection);
	} catch(Exception $e){
		echo "Unable to connect to the database";
		exit;
		}
	$same_subject = $result_subject->fetchALL(PDO::FETCH_ASSOC);

//Video viewer and metadata below
foreach ($temp as $item){
	if($video = $item['ID']){
		echo '<h2>'.$item['title'].'</h2>';
		echo '<iframe style="width: 100%; height: 480px; border: 0;" src="https://boisestate.techsmithrelay.com/connector/embed/index/'.$item['ts_key'].'" title="'.$item['title'].'" width="300" height="150" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen"></iframe>';
echo '<table style="width:100%">';
echo '<tr>';
echo '<td>Creator</td>';
echo '<td><a href="https://archives.boisestate.edu/digital/media/?search='.$item['creator'].'">'.$item['creator'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Date</td>';
echo '<td>'.$item['date'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Description</td>';
echo '<td>'.$item['description'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Relation</td>';
echo '<td><a href="https://archives.boisestate.edu/digital/media/?search='.$item['relation'].'">'.$item['relation'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Collection</td>';
echo '<td><a href="https://archives.boisestate.edu/digital/media/?search='.$item['collection'].'">'.$item['collection'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Subject</td>';
echo '<td><a href="https://archives.boisestate.edu/digital/media/?search='.$item['subject'].'">'.$item['subject'].'</a></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Reference</td>';
echo '<td>'.$item['reference'].'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Streaming ID</td>';
echo '<td>'.$item['ID'].'</td>';
echo '</tr>';
echo '</table>';
	}else{
	echo 'not set';
	}
	
}

echo '<h3>Suggested Videos</h3>';
//Suggested video based on creator
		echo '<div style="float:left; margin-bottom:15px; height:150px;">';
			echo '<h4>Same Creator</h4>';
			echo '<a target="_blank" href="https://archives.boisestate.edu/digital/media/viewer/?id='.$same_creator[0]['ID'].'">';
			echo '<img src="https://archives.boisestate.edu/thumbs/'.$same_creator[0]['ID'].'.jpg" alt="'.$same_creator[0]['title'].'" width="150" height="100" style="border-radius: 15px; margin:5px">';
			echo '<br>';
			echo '<div style="line-height:1.1em; width:150px; padding:0px 0px 0px 8px">'.$same_creator[0]['title'].' ('.substr($same_creator[0]['date'],0,4).')</a></div>';
		echo '</div>';
//Suggested video based on decade
		echo '<div style="float:left; margin-bottom:15px; height:150px;">';
			echo '<h4>Same Decade</h4>';
			echo '<a target="_blank" href="https://archives.boisestate.edu/digital/media/viewer/?id='.$same_date[0]['ID'].'">';
			echo '<img src="https://archives.boisestate.edu/thumbs/'.$same_date[0]['ID'].'.jpg" alt="'.$same_date[0]['title'].'" width="150" height="100" style="border-radius: 15px; margin:5px">';
			echo '<br>';
			echo '<div style="line-height:1.1em; width:150px; padding:0px 0px 0px 8px">'.$same_date[0]['title'].' ('.substr($same_date[0]['date'],0,4).')</a></div>';
		echo '</div>';
//Suggested video based on collection
		echo '<div style="float:left; margin-bottom:15px; height:150px;">';
			echo '<h4>Same Collection</h4>';
			echo '<a target="_blank" href="https://archives.boisestate.edu/digital/media/viewer/?id='.$same_collection[0]['ID'].'">';
			echo '<img src="https://archives.boisestate.edu/thumbs/'.$same_collection[0]['ID'].'.jpg" alt="'.$same_collection[0]['title'].'" width="150" height="100" style="border-radius: 15px; margin:5px">';
			echo '<br>';
			echo '<div style="line-height:1.1em; width:150px; padding:0px 0px 0px 8px">'.$same_collection[0]['title'].' ('.substr($same_collection[0]['date'],0,4).')</a></div>';
		echo '</div>';
//Suggested video based on subject
		echo '<div style="float:left; margin-bottom:15px; height:150px;">';
			echo '<h4>Same Subject</h4>';
			echo '<a target="_blank" href="https://archives.boisestate.edu/digital/media/viewer/?id='.$same_subject[0]['ID'].'">';
			echo '<img src="https://archives.boisestate.edu/thumbs/'.$same_subject[0]['ID'].'.jpg" alt="'.$same_subject[0]['title'].'" width="150" height="100" style="border-radius: 15px; margin:5px">';
			echo '<br>';
			echo '<div style="line-height:1.1em; width:150px; padding:0px 0px 0px 8px">'.$same_subject[0]['title'].' ('.substr($same_subject[0]['date'],0,4).')</a></div>';
		echo '</div>';
?>