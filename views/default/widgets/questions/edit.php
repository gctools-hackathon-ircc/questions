<?php
/**
 * Questions widget settings
 */

$widget = $vars['entity'];

?>
<div>
	<?php echo elgg_echo("widget:numbertodisplay"); ?>
	<?php echo elgg_view('input/text', array('name' => 'params[limit]', 'value' => $widget->limit)); ?>
</div>