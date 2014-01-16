<?php
$answer = $vars['entity'];

$image = elgg_view_entity_icon(get_entity($answer->owner_guid), 'small');

$correct_answer = $answer->getCorrectAnswerMetadata();

if ($correct_answer) {
	$owner = $correct_answer->getOwnerEntity();
	$owner_name = htmlspecialchars($owner->name);
	
	$timestamp = htmlspecialchars(date(elgg_echo('friendlytime:date_format'), $correct_answer->time_created));
	
	$title = elgg_echo("questions:answer:checkmark:title", array($owner_name, $timestamp));
	
	$image .= "<div class='questions-checkmark' title='$title'></div>";
}

$entity_menu = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'answers',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz'
));

$body = elgg_view('output/longtext', array('value' => $answer->description));

$comment_count = $answer->countComments();

$comment_options = array(
		'guid' => $answer->getGUID(),
		'annotation_name' => 'generic_comment',
		'limit' => false
);

$comments = elgg_get_annotations($comment_options);

if ($comments) {
	$body .= "<span class='elgg-river-comments-tab'>" . elgg_echo('comments') . "</span>";
	$body .= elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments'));
}

// show a comment form like in the river
$body_vars = array(
	'entity' => $answer,
	'inline' => true
);
$body .= "<div class='elgg-river-item hidden' id='comments-add-" . $answer->getGUID() . "'>";
$body .= elgg_view_form('comments/add', array(), $body_vars);
$body .= "</div>";

$params = array(
	'entity' => $answer,
	'metadata' => $entity_menu,
	'content' => $body
);

$summary = elgg_view('page/components/summary', $params);

echo elgg_view_image_block($image, $summary);
