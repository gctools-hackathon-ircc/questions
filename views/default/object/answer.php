<?php
$answer = $vars['entity'];

$image = elgg_view_entity_icon(get_entity($answer->owner_guid), 'small');

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
		'limit' => 3,
		'order_by' => 'n_table.time_created desc'
);

$comments = elgg_get_annotations($comment_options);

if ($comments) {
	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	$body .= "<span class='elgg-river-comments-tab'>" . elgg_echo('comments') . "</span>";
	$body .= elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments'));

	if ($comment_count > count($comments)) {
		$num_more_comments = $comment_count - count($comments);
		$url = $object->getURL();
		$params = array(
				'href' => $url,
				'text' => elgg_echo('river:comments:more', array($num_more_comments)),
				'is_trusted' => true,
		);
		$link = elgg_view('output/url', $params);
		$body .= "<div class=\"elgg-river-more\">$link</div>";
	}
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
