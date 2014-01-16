<?php

$question = elgg_extract('entity', $vars);
$editing = true;
$container_options = false;

if (!$question) {
	$editing = false;
	
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
	<label for="question_title"><?php echo elgg_echo('questions:edit:question:title'); ?></label>
	<?php echo elgg_view('input/text', $title); ?>
</div>
<div>
	<label for="question_description"><?php echo elgg_echo('questions:edit:question:description'); ?></label>
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
	<label for="question_access_id"><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', $access_id); ?>
</div>

<?php

if (!$editing || (questions_experts_enabled() && questions_is_expert(elgg_get_page_owner_entity()))) {
	if (elgg_is_active_plugin("groups")) {
		$group_options = array(
			"type" => "group",
			"limit" => false,
			"joins" => array("JOIN " . elgg_get_config("dbprefix") . "groups_entity ge ON e.guid = ge.guid"),
			"order_by" => "ge.name ASC"
		);
		
		if (!$editing) {
			$owner = elgg_get_logged_in_user_entity();
			
			$group_options["relationship"] = "member";
			$group_options["relationship_guid"] = elgg_get_logged_in_user_guid();
		} else {
			$owner = $question->getOwnerEntity();
		}
		
		$groups = elgg_get_entities_from_relationship($group_options);
		if (!empty($groups)) {
			$container_options = true;
			
			$select = "<select name='container_guid' class='elgg-input-dropdown' id='questions-container-guid'>";
			
			// add user to the list
			$selected = "";
			if ($owner->getGUID() == $question->getContainerGUID()) {
				$selected = "selected='selected'";
			}
			$select .= "<option value='" . $owner->getGUID() . "' " . $selected . ">" . $owner->name . "</option>";
			
			// add groups
			$select .= "<optgroup label='" . htmlspecialchars(elgg_echo("groups"), ENT_QUOTES, "UTF-8", false) . "'>";
			foreach ($groups as $group) {
				$selected = "";
				if ($group->getGUID() == $question->getContainerGUID()) {
					$selected = "selected='selected'";
				}
				$select .= "<option value='" . $group->getGUID() . "' " . $selected . ">" . $group->name . "</option>";
			}
			$select .= "</optgroup>";
			
			$select .= "</select>";
			
			?>
			<div>
				<label for="questions-container-guid"><?php echo elgg_echo("questions:edit:question:container"); ?></label><br />
				<?php echo $select; ?>
			</div>
			<?php
		}
	}
}

?>

<div class="elgg-foot">
<?php

if (!$container_options) {
	echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $question->container_guid));
}
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $question->guid));
echo elgg_view('input/submit', array('value' => elgg_echo('submit')));

?>
</div>