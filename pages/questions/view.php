<?php
/**
 * View a question
 *
 * @package ElggQuestions
 */

$guid = (int) get_input('guid');
$question = get_entity($guid);

// make sure we have a question
if (empty($question) || !elgg_instanceof($question, "object", "question")) {
	register_error(elgg_echo("ClassException:ClassnameNotClass", array($guid, elgg_echo("item:object:question"))));
	forward(REFERER);
}

// set page owner
elgg_set_page_owner_guid($question->getContainerGUID());
$page_owner = $question->getContainerEntity();

// set breadcrumb
$crumbs_title = $page_owner->name;

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "questions/group/$page_owner->guid");
} else {
	elgg_push_breadcrumb($crumbs_title, "questions/owner/$page_owner->username");
}

$title = $question->title;

elgg_push_breadcrumb($title);

// build page elements
$title_icon = "";

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
	
	$options["selects"] = array(
		"(SELECT count(a.name_id) AS likes_count
		FROM " . $dbprefix . "annotations a
		WHERE a.entity_guid = e.guid
		AND a.name_id = " . $likes_id . ") AS likes_count");
	$options["order_by"] = "likes_count desc, e.time_created asc";
}

$answers .= elgg_list_entities($options);

$count = elgg_get_entities($options);
if ($marked_answer) {
	$count++;
}

$content .= elgg_view_module('info', "$count " . elgg_echo('answers'), $answers, array("class" => "mtm"));

// add answer form
if (($question->getStatus() == "open") && $question->canWriteToContainer(0, 'object', 'answer')) {
	
	$add_form = elgg_view_form('object/answer/add', array(), array('container_guid' => $question->guid));
	
	$content .= elgg_view_module('info', elgg_echo('answers:addyours'), $add_form);
} elseif ($question->getStatus() == "closed") {
	// add an icon to show this question is closed
	$title_icon = elgg_view_icon("lock-closed");
}

$body = elgg_view_layout('content', array(
	'title' => $title_icon . $title,
	'content' => $content,
	'filter' => '',
));

echo elgg_view_page($title, $body);
