<div class="form-group <?php if ($field['error']) echo 'error'; ?>">
	<div class="col-sm-4">
		<?php echo View::factory('formmanager/html/label', array('field' => $field))->render(); ?>
		<?php if (isset($field['prefix'])) echo $field['prefix']; ?>
