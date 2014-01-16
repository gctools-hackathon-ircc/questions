<?php

class ElggQuestion extends ElggObject {
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'question';
	}

	public function getAnswers(array $options = array()) {
		$defaults = array(
			'order_by' => 'time_created asc',
		);

		$overrides = array(
			'type' => 'object',
			'subtype' => 'answer',
			'container_guid' => $this->guid,
		);

		return elgg_get_entities(array_merge($defaults, $options, $overrides));
	}

	public function listAnswers(array $options = array()) {
		return elgg_list_entities($options, array($this, 'getAnswers'));
	}
	
	public function getURL() {
		$url = "questions/view/" . $this->guid . "/" . elgg_get_friendly_title($this->title);
		
		return elgg_normalize_url($url);
	}
	
	/**
	 * Get the answer that was marked as the correct answer
	 *
	 * @return bool|ElggAnswer the answer or false if non are marked
	 */
	public function getMarkedAnswer() {
		$result = false;
		
		$options = array(
			"type" => "object",
			"subtype" => "answer",
			"limit" => 1,
			"container_guid" => $this->getGUID(),
			"metadata_name_value_pairs" => array(
				"name" => "correct_answer",
				"value" => true
			)
		);
		
		$answers = elgg_get_entities_from_metadata($options);
		if (!empty($answers)) {
			$result = $answers[0];
		}
		
		return $result;
	}
}
