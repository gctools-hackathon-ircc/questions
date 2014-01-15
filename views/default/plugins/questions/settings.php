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