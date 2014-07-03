<?php

include 'my_data.php';

require 'vendor/autoload.php';
use \Michelf\Markdown;

// We'll be outputting a xml
header('Content-type: text/xml');
//header('Content-Disposition: attachment; filename="drupal2wordpress_export.xml"');

$wp_post="";
$wp_tag="";
$wp_category="";


// Load header.xml
$tmp_header=file_get_contents("header.xml");

// Replace header string
$tmp_header=str_replace("%URL%", $blog_url, $tmp_header);
$wp_header=$tmp_header;

// Load tag.xml
$tmp_tag=file_get_contents("tag.xml");

// Load generator.xml
$wp_generator=file_get_contents("generator.xml");

// Load item
$tmp_item=file_get_contents("item.xml");

// Load item-tag.xml
$tmp_item_tag=file_get_contents("item-tag.xml");

// Load footer.xml
$wp_footer=file_get_contents("footer.xml");

// Connect 
mysql_connect("127.0.0.1", $user, $pass) or die(mysql_error());
mysql_select_db($db) or die(mysql_error());
mysql_query("SET NAMES 'utf8'");

// Tag query
$sql_tag="SELECT * FROM ".$db_prefix."taxonomy_term_data limit 2";

// Tags
$result_tag = mysql_query($sql_tag) or die (mysql_error());
$numrows_tag = mysql_numrows($result_tag);

// WP Tag
for ($i=0; $i< $numrows_tag; $i++) {
	$tag_id= mysql_result($result_tag,$i,"tid");
	$tag_name= mysql_result($result_tag,$i,"name");
	$tag_str=str_replace("%TID%", $tag_id,$tmp_tag);
	$tag_str=str_replace("%TAG%", $tag_name,$tag_str);	
	$wp_tag.=$tag_str."\n";
}

// Echo Combine
echo $wp_header;
echo $wp_tag;
echo $wp_generator;

// Node query
//$sql = "SELECT * FROM ".$db_prefix."node as n JOIN ".$db_prefix."field_data_body as fdb ON n.nid=fdb.entity_id";
$sql_node = "SELECT * FROM ".$db_prefix."node as n JOIN ".$db_prefix."field_data_body as fdb ON n.nid=fdb.entity_id 
				ORDER by nid DESC LIMIT 2";

// Nodes
$result_node = mysql_query($sql_node) or die (mysql_error());
$numrows_node = mysql_numrows($result_node);

// Loop over the nodes.
for ($i_node = 0; $i_node < $numrows_node; $i_node++)
{

	$type= htmlspecialchars(mysql_result($result_node,$i_node,"bundle"));

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
$title= mysql_result($result_node,$i_node,"title");
$created= date("r", mysql_result($result_node,$i_node,"created"));
$updated= date("r", mysql_result($result_node,$i_node,"changed"));

$body_md = mysql_result($result_node,$i_node,"body_value");
$body_html = Markdown::defaultTransform($body_md);

// WP Post
if ($type=="post") {

	$item_str=str_replace("%NID%", $nid, $tmp_item);
	$item_str=str_replace("%URL%", $blog_url, $item_str);
	$item_str=str_replace("%TITLE%", $title, $item_str);
	$item_str=str_replace("%AUTHOR%", $global_author_name, $item_str);
	$item_str=str_replace("%BODY%", $body_html, $item_str);
	$item_str=str_replace("%POSTDATE%", $created, $item_str);
	$item_str=str_replace("%PUBDATE%", $created, $item_str);

	// Item Tag
	$sql_node_tag="select td.tid,td.name from ".$db_prefix."taxonomy_index as ti, ".$db_prefix."taxonomy_term_data as td where ti.tid=td.tid and ti.nid=".$nid;
	$result_node_tag = mysql_query($sql_node_tag) or die (mysql_error());
	$numrows_node_tag = mysql_numrows($result_node_tag);

	$itemtag_str="";
	$wp_itemtag_str="";

	for ($itag=0;$itag<$numrows_node_tag;$itag++) {
		$node_tag_id=mysql_result($result_node_tag, $itag, "tid");
		$node_tag_name=mysql_result($result_node_tag, $itag, "name");
		$itemtag_str=str_replace("%TAG%", $node_tag_name, $tmp_item_tag);
		$wp_itemtag_str.=$itemtag_str."\n";
	}

	$item_str=str_replace("%ITEMTAG%", $wp_itemtag_str, $item_str);

	$wp_post.=$item_str;

}



echo $wp_post;


}

echo $wp_footer;