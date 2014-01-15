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
}
