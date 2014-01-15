<?php
/**
 * Add question page
 *
 * @package ElggQuestions
 */

$question_guid = get_input('guid');
$question = get_entity($question_guid);

if (!elgg_instanceof($question, 'object', 'question') || !$question->canEdit()) {
	register_error(elgg_echo('questions:unknown'));
	forward(REFERRER);
}

elgg_push_breadcrumb($question->title, $question->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$vars = array(
	'entity' => $question,
);

$content = elgg_view_form('object/question/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'title' => elgg_echo('edit'),
	'content' => $content,
	'filter' => ''
));

echo elgg_view_page(elgg_echo('edit'), $body);
