<?php

include 'my_data.php';

require 'vendor/autoload.php';
use \Michelf\Markdown;

// We'll be outputting a xml
//header('Content-type: text/xml');
//header('Content-Disposition: attachment; filename="drupal2wordpress_export.xml"');


//$sql = "SELECT * FROM ".$db_prefix."node as n JOIN ".$db_prefix."field_data_body as fdb ON n.nid=fdb.entity_id";
$sql = "SELECT * FROM ".$db_prefix."node as n JOIN ".$db_prefix."field_data_body as fdb ON n.nid=fdb.entity_id ORDER by nid DESC LIMIT 604";

// Loop over the nodes.
for ($i_node = 0; $i_node < $numrows_node; $i_node++)
{

	$type= htmlspecialchars(mysql_result($result_node,$i_node,"type"));


	// Check type 
	switch ($type) {
		case "article":
			$type = "post";
		break;
	     case "review":
			$type = "post";
		break;
	     case "howto":
			$type = "post";
		break;
	     case "news":
			$type = "post";
		break;
	    case "blog":
			$type = "post";
		break;
	    default:
	    		$type="page";
	 	break;
	}

$nid = mysql_result($result_node, $i_node, "nid");
$title= htmlspecialchars(mysql_result($result_node,$i_node,"title"));
$created= date("c", mysql_result($result_node,$i_node,"created"));
$updated= date("c", mysql_result($result_node,$i_node,"changed"));

$body_md = mysql_result($result_node,$i_node,"body_value");

$body_html = Markdown::defaultTransform($body_md);






}

