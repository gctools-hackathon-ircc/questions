<?php

$group_guid = (int) get_input('group_guid');
$solution_time = (int) get_input('solution_time');
$who_can_ask = get_input('who_can_ask');

if (empty($group_guid)) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

elgg_entity_gatekeeper($group_guid, 'group');
$group = get_entity($group_guid);
if (!$group->canEdit()) {
	register_error(elgg_echo('actionunauthorized'));
	forward(REFERER);
}

// save the settings
if (questions_can_groups_set_solution_time()) {
	$group->setPrivateSetting('questions_solution_time', $solution_time);
}

if (questions_experts_enabled()) {
	$group->setPrivateSetting('questions_who_can_ask', $who_can_ask);
}

system_message(elgg_echo('questions:action:group_settings:success'));

forward($group->getURL());
