<?php

function questions_init() {
	
	add_subtype("object", 'question', 'ElggQuestion');
	update_subtype("object", 'question', 'ElggQuestion');
	
	add_subtype("object", 'answer', 'ElggAnswer');
	update_subtype("object", 'answer', 'ElggAnswer');
	
	elgg_extend_view("css/elgg", "questions/css");
	elgg_extend_view("js/elgg", "questions/js");
	
	elgg_register_menu_item("site", array(
		"name" => 'questions',
		"text" => elgg_echo('questions'),
		"href" => "/questions/all",
	));
	
	elgg_register_entity_type("object", 'questions');
	elgg_register_widget_type('questions', elgg_echo("widget:questions:title"), elgg_echo("widget:questions:description"));
	
	$actions_base = dirname(__FILE__) . '/actions/object/question';
	elgg_register_action("object/question/save", "$actions_base/save.php");
	elgg_register_action("object/question/delete", "$actions_base/delete.php");
	
	$plugin_dir = dirname(__FILE__);

	elgg_register_page_handler('questions', 'questions_page_handler');
	
	$actions_base = "$plugin_dir/actions/object/answer";
	elgg_register_action('object/answer/add', "$actions_base/save.php");
	elgg_register_action('object/answer/edit', "$actions_base/save.php");
	
	elgg_register_plugin_hook_handler("register", "menu:owner_block", 'questions_owner_block_menu_handler');
	elgg_register_plugin_hook_handler("register", "menu:user_hover", 'questions_user_hover_menu_handler');
	elgg_register_plugin_hook_handler("register", 'menu:entity', 'questions_entity_menu_handler');
	elgg_register_plugin_hook_handler("notify:entity:message", "object", 'questions_notify_message_handler');
	
	add_group_tool_option('questions', elgg_echo("questions:enable"), true);
	elgg_extend_view("groups/tool_latest", "questions/group_module");
}

elgg_register_event_handler('init', 'system', 'questions_init');
