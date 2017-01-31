<?php

namespace ColdTrick\Questions;

class Cron {
	
	/**
	 * Automaticly close open questions after x days
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param mixed  $return_value current return value
	 * @param mixed  $params       supplied params
	 *
	 * @return void
	 */
	public static function autoCloseQuestions($hook, $type, $return_value, $params) {
		
		$auto_close_days = (int) elgg_get_plugin_setting('auto_close_time', 'questions');
		if ($auto_close_days < 1) {
			return;
		}
		
		$time = (int) elgg_extract('time', $params, time());
		
		// ignore access
		$ia = elgg_set_ignore_access(true);
		
		// get open questions last modified more than x days ago
		$batch = new \ElggBatch('elgg_get_entities_from_metadata', [
			'type' => 'object',
			'subtype' => \ElggQuestion::SUBTYPE,
			'limit' => false,
			'metadata_name_value_pairs' => [
				'status' => 'open',
			],
			'modified_time_upper' => $time - ($auto_close_days * 24 * 60 * 60),
		]);
		$batch->setIncrementOffset(false);
		
		/* @var $question \ElggQuestion */
		foreach ($batch as $question) {
			// close the question
			$question->close();
		}
		
		// restore access
		elgg_set_ignore_access($ia);
	}
}
