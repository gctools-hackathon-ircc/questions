<?php
/**
 *	Questions widget content
 **/

$widget = $vars["entity"];

$limit = (int) $widget->limit;
if ($limit < 1) {
	$limit = 5;
}

$options = array(
	"type" => "object",
	"subtype" => "question",
	"limit" => $limit,
);

if ($widget->context != "index") {
	$options["container_guid"] = $widget->getOwnerGUID();
}

$content = elgg_list_entities($options);
if (empty($content)) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("questions:none")));
}

echo $content;
