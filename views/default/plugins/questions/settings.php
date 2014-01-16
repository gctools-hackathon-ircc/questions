<?php
/**
 * All plugin settings can be configured by this view
 *
 */

$plugin = elgg_extract("entity", $vars);

$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes")
);

$personal_access_options = array(
	"user_defined" => elgg_echo("questions:settings:access:options:user"),
	ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
	ACCESS_PUBLIC => elgg_echo("PUBLIC")
);

$group_access_options = array(
	"user_defined" => elgg_echo("questions:settings:access:options:user"),
	"group_acl" => elgg_echo("questions:settings:access:options:group"),
	ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
	ACCESS_PUBLIC => elgg_echo("PUBLIC")
);

// adding expert roles
$expert_options = "<div>";
$expert_options .= elgg_echo("questions:settings:experts:enable");
$expert_options .= elgg_view("input/dropdown", array("name" => "params[experts_enabled]", "value" => $plugin->experts_enabled, "options_values" => $noyes_options, "class" => "mls"));
$expert_options .= "<div class='elgg-subtext'>" . elgg_echo("questions:settings:experts:enable:description") . "</siv>";
$expert_options .= "</div>";

$expert_options .= "<div>";
$expert_options .= elgg_echo("questions:settings:experts:answer");
$expert_options .= elgg_view("input/dropdown", array("name" => "params[experts_answer]", "value" => $plugin->experts_answer, "options_values" => $noyes_options, "class" => "mls"));
$expert_options .= "</div>";

$expert_options .= "<div>";
$expert_options .= elgg_echo("questions:settings:experts:mark");
$expert_options .= elgg_view("input/dropdown", array("name" => "params[experts_mark]", "value" => $plugin->experts_mark, "options_values" => $noyes_options, "class" => "mls"));
$expert_options .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:experts:title"), $expert_options);

// access options
$access_options = "<div>";
$access_options .= elgg_echo("questions:settings:access:personal");
$access_options .= elgg_view("input/access", array("name" => "params[access_personal]", "value" => $plugin->access_personal, "options_values" => $personal_access_options, "class" => "mls"));
$access_options .= "</div>";

$access_options .= "<div>";
$access_options .= elgg_echo("questions:settings:access:group");
$access_options .= elgg_view("input/access", array("name" => "params[access_group]", "value" => $plugin->access_group, "options_values" => $group_access_options, "class" => "mls"));
$access_options .= "</div>";

echo elgg_view_module("inline", elgg_echo("questions:settings:access:title"), $access_options);
