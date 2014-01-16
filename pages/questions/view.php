<?php
/**
 * View a question
 *
 * @package ElggQuestions
 */

$question = get_entity(get_input('guid'));

$page_owner = elgg_get_page_owner_entity();

$crumbs_title = $page_owner->name;

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "questions/group/$page_owner->guid");
} else {
	elgg_push_breadcrumb($crumbs_title, "questions/owner/$page_owner->username");
}

$title = $question->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($question, array('full_view' => true));

$answers = "";

// add the answer marked as the correct answer first
$marked_answer = $question->getMarkedAnswer();
if ($marked_answer) {
	$answers .= elgg_view_entity($marked_answer);
}

// add the rest of the answers
$options = array(
	'type' => 'object',
	'subtype' => 'answer',
	'container_guid' => $question->guid,
	'count' => true,
	'limit' => false
);

if ($marked_answer) {
	// do not include the marked answer as it already  added to the output before
	$options["wheres"] = array("e.guid <> " . $marked_answer->getGUID());
}

if (elgg_is_active_plugin("likes")) {
	// order answers based on likes
	$dbprefix = elgg_get_config("dbprefix");
	$likes_id = add_metastring("likes");
	
	$options["selects"] = array("(SELECT count(a.name_id) as likes_count FROM " . $dbprefix . "annotations a WHERE a.entity_guid = e.guid and a.name_id = " . $likes_id . ") as likes_count");
	$options["order_by"] = "likes_count desc, e.time_created asc";
}

$answers .= elgg_list_entities($options);

$count = elgg_get_entities($options);
if ($marked_answer) {
	$count++;
}

$content .= elgg_view_module('info', "$count " . elgg_echo('answers'), elgg_view_menu('filter') . $answers);

if ($question->canWriteToContainer(0, 'object', 'answer')) {
	$user_icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'small');
	$add_form = elgg_view_form('object/answer/add', array(), array('container_guid' => $question->guid));
	
	$content .= elgg_view_module('info', elgg_echo('answers:addyours'), $add_form);
}

$body = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter' => '',
));

echo elgg_view_page($title, $body);
