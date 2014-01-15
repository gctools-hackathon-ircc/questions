<?php

$question = elgg_extract('entity', $vars);

if (!$question) {
	$question = new ElggQuestion();
	$question->container_guid = elgg_get_page_owner_guid();
	$question->access_id = ACCESS_DEFAULT;
}

$title = array(
	'name' => 'title',
	'id' => 'question_title',
	'value' => elgg_get_sticky_value('question', 'title', $question->title),
);

$description = array(
	'name' => 'description',
	'id' => 'question_description',
	'value' => elgg_get_sticky_value('question', 'description', $question->description),
);

$tags = array(
	'name' => 'tags',
	'id' => 'question_tags',
	'value' => elgg_get_sticky_value('question', 'tags', $question->tags),
);

$access_id = array(
	'name' => 'access_id',
	'id' => 'question_access_id',
	'value' => (int) elgg_get_sticky_value('question', 'access_id', $question->access_id),
);

// clear sticky form
elgg_clear_sticky_form('question');
?>

<div>
	<label for="question_title"><?php echo elgg_echo('object:question:title'); ?></label>
	<?php echo elgg_view('input/text', $title); ?>
</div>
<div>
	<label for="question_description"><?php echo elgg_echo('object:question:description'); ?></label>
	<?php echo elgg_view('input/longtext', $description); ?>
</div>
<div>
	<label for="question_tags"><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', $tags); ?>
</div>

<?php
if (elgg_view_exists('input/categories')) {
	echo elgg_view('input/categories', $vars);
}
?>

<div>
	<label for="question_access_id"><?php echo elgg_echo('access'); ?></label>
	<?php echo elgg_view('input/access', $access_id); ?>
</div>

<div>
<?php
	echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $question->container_guid));
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $question->guid));
	echo elgg_view('input/submit', array('value' => elgg_echo('submit')));
?>
</div>