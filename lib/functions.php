<?php
/**
 * All helper functions for the questions plugin can be found in this file.
 */

/**
 * This function checks if expert roles are enabled in the plugin settings
 *
 * @return bool true is enabled, false otherwise
 */
function questions_experts_enabled() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		$setting = elgg_get_plugin_setting("experts_enabled", "questions");
		if ($setting == "yes") {
			$result = true;
		}
	}
	
	return $result;
}
