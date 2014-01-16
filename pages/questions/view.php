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

$options = array(
	'type' => 'object',
	'subtype' => 'answer',
	'container_guid' => $question->guid,
	'count' => true
);

if (elgg_is_active_plugin("likes")) {
	// order answers based on likes
	$dbprefix = elgg_get_config("dbprefix");
	$likes_id = add_metastring("likes");
	
	$options["selects"] = array("(SELECT count(a.name_id) as likes_count FROM " . $dbprefix . "annotations a WHERE a.entity_guid = e.guid and a.name_id = " . $likes_id . ") as likes_count");
	$options["order_by"] = "likes_count desc, e.time_created asc";
}

$answers = elgg_list_entities($options);
$count = elgg_get_entities($options);

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
