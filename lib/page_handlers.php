<?php

function questions_page_handler($segments) {
	elgg_push_breadcrumb(elgg_echo('questions'), "/questions/all");

	$pages = dirname(__FILE__) . "/pages/questions";

	switch ($segments[0]) {
		case "all":
			include "$pages/all.php";
			break;

		case "owner":
			include "$pages/owner.php";
			break;

		case "friends":
			gatekeeper();
			include "$pages/friends.php";
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
			return false;
	}

	elgg_pop_context();

	return true;
}