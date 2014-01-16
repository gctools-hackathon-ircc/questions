<?php

class ElggAnswer extends ElggObject {
	
	function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'answer';
	}

	public function getURL() {
		$container_entity = $this->getContainerEntity();
		
		$url = $container_entity->getURL() . "#answer-" . $this->guid;
		
		return $url;
	}
	
	public function getCorrectAnswerMetadata() {
		$result = false;
		
		$options = array(
				"metadata_name" => "correct_answer",
				"guid" => $this->guid
			);
		
		$metadata = elgg_get_metadata($options);
		if ($metadata) {
			$result = $metadata[0];
		}
		
		return $result;
	}
}
