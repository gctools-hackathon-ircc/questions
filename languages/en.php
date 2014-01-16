<?php
add_translation('en', array(
	'answers' => 'Answers',
	'answers:addyours' => 'Add Your Answer',
	
	/**
	 * General stuff
	 */
	'item:object:answer' => "Answers",
	'item:object:question' => "Questions",
	
	/**
	 * Menu items
	 */
	'questions:menu:user_hover:make_expert' => "Make Questions expert",
	'questions:menu:user_hover:remove_expert' => "Remove Questions expert",
	
	'questions:menu:entity:answer:mark' => "This is correct",
	'questions:menu:entity:answer:unmark' => "No longer correct",
	
	'river:create:object:question' => '%s asked question %s',
	'river:create:object:answer' => '%s provided an answered for the question %s',
		
	'questions' => 'Questions',
	'questions:asked' => 'Asked by %s',
	'questions:answered' => 'Last answered by %s %s',

	'questions:everyone' => 'All Questions',
	'questions:add' => 'Add a Question',
	'questions:owner' => "%s's Questions",
	'questions:none' => "No questions have been submitted yet.",
	'questions:friends' => "Friends' Questions",
	'questions:group' => 'Group questions',
	'questions:enable' => 'Enable group questions',

	'object:question:title' => 'Question',
	'object:question:description' => "Details",
	
	/**
	 * answers
	 */
	'questions:answer:edit' => "Update answer",
	'questions:answer:checkmark:title' => "%s marked this as the correct answer on %s",
		
	/**
	 * plugin settings
	 */
	'questions:settings:experts:title' => "Q&A expert settings",
	'questions:settings:experts:enable' => "Enable expert roles",
	'questions:settings:experts:enable:description' => "Experts have special privilages and can be assigned by site administrators and group owners.",
	'questions:settings:experts:answer' => "Only experts can answer a question",
	'questions:settings:experts:mark' => "Only experts can mark an answer as the correct answer",
	
	/**
	 * Widgets
	 */

	'widget:questions:title' => "Questions",
	'widget:questions:description' => "You can view the status of your questions.",
	
	/**
	 * Actions
	 */
	
	'questions:action:answer:save:error:container' => "You do not have permission to answer that question!",
	'questions:action:answer:save:error:body' => "A body is required: %s, %s",
	'questions:action:answer:save:error:save' => "There was a problem saving your answer!",
	
	'questions:action:answer:toggle_mark:error:not_allowed' => "You're not allowed to mark answers as the correct answer",
	'questions:action:answer:toggle_mark:error:duplicate' => "There already is a correct answer to this question",
	'questions:action:answer:toggle_mark:success:mark' => "The answer is marked as the correct answer",
	'questions:action:answer:toggle_mark:success:unmark' => "The answer is no longer marked as the correct answer",
	
	'questions:action:question:save:error:container' => "You do not have permission to answer that question!",
	'questions:action:question:save:error:body' => "A title and description are required: %s, %s, %s",
	'questions:action:question:save:error:save' => "There was a problem saving your question!",
	
	'questions:action:toggle_expert:success:make' => "%s is now a questions expert for %s",
	'questions:action:toggle_expert:success:remove' => "%s is no longer a questions expert for %s",
));
