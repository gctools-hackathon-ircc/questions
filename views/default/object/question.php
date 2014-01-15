<?php
/**
 * Question entity view
 *
 * @package Questions
*/

$full = elgg_extract('full', $vars, false);
$question = elgg_extract('entity', $vars, false);

if (!$question) {
	return true;
}

$poster = $question->getOwnerEntity();

$poster_icon = elgg_view_entity_icon($poster, 'small');

$poster_text = elgg_echo('questions:asked', array($poster->name));

$tags = elgg_view('output/tags', array('tags' => $question->tags));
$date = elgg_view_friendly_time($question->time_created);

$answers_link = '';

$answer_options = array(
	'type' => 'object',
	'subtype' => 'answer',
	'container_guid' => $question->getGUID(),
	'count' => true,
);

$num_answers = elgg_get_entities($answer_options);
$answer_text = "";

if ($num_answers != 0) {
	$answer_options = array(
		'limit' => 1,
		'count' => false,
	);

	$last_answer = elgg_get_entities($answer_options);

	$poster = $last_answer[0]->getOwnerEntity();
	$answer_time = elgg_view_friendly_time($last_answer[0]->time_created);
	$answer_text = elgg_echo('questions:answered', array($poster->name, $answer_time));

	$answers_link = elgg_view('output/url', array(
		'href' => $question->getURL() . '#question-answers',
		'text' => elgg_echo('answers') . " ($num_answers)",
	));
}

$metadata = '';
// do not show the metadata and controls in widget view
if (!elgg_in_context('widgets')) {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'questions',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz'
	));
}

if ($full) {
	$subtitle = "$poster_text $date $answers_link";

	$params = array(
		'entity' => $question,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/summary', $params);
	
	$list_body .= elgg_view('output/longtext', array('value' => $question->description));
	
	// show a comment form like in the river
	$body_vars = array(
		'entity' => $question,
		'inline' => true
	);
	$list_body .= "<div class='elgg-river-item hidden' id='comments-add-" . $question->getGUID() . "'>";
	$list_body .= elgg_view_form('comments/add', array(), $body_vars);
	$list_body .= "</div>";
	
	echo elgg_view_image_block($poster_icon, $list_body);

} else {
	// brief view
	$subtitle = "$poster_text $date $answers_link <span class=\"questions-latest-answer\">$answer_text</span>";

	$params = array(
		'entity' => $question,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/summary', $params);

	echo elgg_view_image_block($poster_icon, $list_body);
}
