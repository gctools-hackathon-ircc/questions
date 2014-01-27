<?php
/**
 * Elgg questions plugin everyone page
 *
 * @package ElggQuestions
 */

gatekeeper();

if (!questions_is_expert()) {
	forward("questions/all");
}

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

elgg_register_title_button();

$site = elgg_get_site_entity();
$user = elgg_get_logged_in_user_entity();

$dbprefix = elgg_get_config("dbprefix");
$correct_answer_id = add_metastring("correct_answer");

$container_where = array();
if (check_entity_relationship($user->getGUID(), QUESTIONS_EXPERT_ROLE, $site->getGUID())) {
	$container_where[] = "(e.container_guid NOT IN (
		SELECT ge.guid
		FROM " . $dbprefix . "entities ge
		WHERE ge.type = 'group'
		AND ge.site_guid = " . $site->getGUID() . "
		AND ge.enabled = 'yes'
	))";
}

$group_options = array(
		"type" => "group",
		"limit" => false,
		"relationship" => QUESTIONS_EXPERT_ROLE,
		"relationship_guid" => $user->getGUID(),
		"callback" => "questions_row_to_guid"
);
$groups = elgg_get_entities_from_relationship($group_options);
if (!empty($groups)) {
	$container_where[] = "(e.container_guid IN (" . implode(",", $groups) . "))";
}

$container_where = "(" . implode(" OR ", $container_where) . ")";

$content = elgg_list_entities_from_metadata(array(
	'type' => 'object',
	'subtype' => 'question',
	'wheres' => array("NOT EXISTS (
				SELECT 1
				FROM " . $dbprefix . "entities e2
				JOIN " . $dbprefix . "metadata md ON e2.guid = md.entity_guid
				WHERE e2.container_guid = e.guid
				AND md.name_id = " . $correct_answer_id . ")"),
	'full_view' => false,
	'list_type_toggle' => false,
	'order_by_metadata' => array("name" => "solution_time")
));

if (!$content) {
	$content = elgg_echo('questions:none');
}

$title = elgg_echo('questions:todo');

$body = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter_context' => 'todo'
));

echo elgg_view_page($title, $body);
