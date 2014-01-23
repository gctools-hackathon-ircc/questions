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
$status_id = add_metastring("status");
$closed_id = add_metastring("closed");

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

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'question',
	'wheres' => array("NOT EXISTS (
				SELECT 1
				FROM " . $dbprefix . "metadata md
				WHERE md.entity_guid = e.guid
				AND md.name_id = " . $status_id . "
				AND md.value_id = " . $closed_id . ")", $container_where),
	'full_view' => false,
	'list_type_toggle' => false,
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
