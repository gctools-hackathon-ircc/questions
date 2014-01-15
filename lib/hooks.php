<?php

function questions_owner_block_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity instanceof ElggGroup && $entity->questions_enable != 'no') {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/group/$entity->guid/all",
				'text' => elgg_echo('questions:group'),
		));
	} elseif ($entity instanceof ElggUser) {
		$items[] = ElggMenuItem::factory(array(
				'name' => 'questions',
				'href' => "/questions/owner/$entity->username",
				'text' => elgg_echo('questions'),
		));
	}

	return $items;
}

function questions_entity_menu_handler($hook, $type, $items, $params) {
	$entity = $params['entity'];

	if ($entity->getSubtype() == 'question' || $entity->getSubtype() == 'answer') {
		if ($entity->canAnnotate(0, 'generic_comment')) {
			$items[] = ElggMenuItem::factory(array(
					'name' => 'comment',
					'rel' => 'toggle',
					'link_class' => 'elgg-toggler',
					'href' => "#comments-add-$entity->guid",
					'text' => elgg_view_icon('speech-bubble'),
					'priority' => 600,
			));
		}
	}

	return $items;
}

function questions_notify_message_handler($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'question')) {
		$descr = $entity->description;
		$title = $entity->title;
		$url = $entity->getURL();
		$owner = $entity->getOwnerEntity();
		$via = elgg_echo("questions:via");

		if ($method == 'sms') {
			//shortening the url for sms
			$url = elgg_get_site_url() . "view/$entity->guid";
			return "$owner->name $via: $url ($title)";
		}

		if ($method == 'email') {
			return "$owner->name $via: $title \n\n $descr \n\n $url";
		}

		if ($method == 'web') {
			return "$owner->name $via: $title \n\n $descr \n\n $url";
		}
	}

	return null;
}