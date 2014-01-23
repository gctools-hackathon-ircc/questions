<?php

/**
 * Handles all question pages
 *
 * @param array $segments
 */
function questions_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/questions";

	switch ($segments[0]) {
		case "all":
			include "$pages/all.php";
			break;

		case "updated":
			include "$pages/updated.php";
			break;
			
		case "todo":
			include "$pages/todo.php";
			break;

		case "owner":
			include "$pages/owner.php";
			break;

		case "view":
			set_input('guid', $segments[1]);
			include "$pages/view.php";
			break;

		case "add":
			gatekeeper();
			include "$pages/add.php";
			break;

		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;

		case 'group':
			group_gatekeeper();
			include "$pages/owner.php";
			break;

		default:
			forward("questions/all");
			return false;
	}

	return true;
}

/**
 * Handles all answer pages
 *
 * @param array $segments
 */
function answers_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "questions/all");

	$pages = dirname(dirname(__FILE__)) . "/pages/answers";

	switch ($segments[0]) {
		case "edit":
			gatekeeper();
			set_input('guid', $segments[1]);
			include "$pages/edit.php";
			break;
		default:
			forward("questions/all");
			return false;
	}

	return true;
}
