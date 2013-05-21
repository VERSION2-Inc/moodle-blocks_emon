<?php if (!$isScript) { ?>
<input type="hidden" name="qtype" value="<?php echo QUIZ_TYPE_CHOICE ?>" />

<fieldset>
	<legend><?php etpl('choice') ?></legend>
		<dl>
			<dt><?php etpl('question_text') ?></dt>
			<dd>
				<textarea id="questiontext" name="questiontext" class="middle"><?php echo trim($this->g('questiontext')) ?></textarea>
				<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('choices') ?></dt>
			<dd>
				<?php etpl('choice_detail') ?><br />
				<textarea id="question_body" name="question_body" class="middle" onchange="choice.setChoices(this.value,undefined)" onkeyup="choice.setChoices(this.value,undefined)"><?php $this->p('question_body') ?></textarea>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('view_order') ?></dt>
			<dd>
				<label><input type="checkbox" id="random_enable" name="option[shuffleanswers]" value="1" <?php if ($this->g('option', 'shuffleanswers')) { ?>checked="checked"<?php } ?> class="label"/><?php etpl('choice_shuffle') ?></label>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('correct') ?></dt>
			<dd>
				<?php etpl('choice_correct') ?><br />
				<div id="choices"></div>
			</dd>
		</dl>
</fieldset>
<?php } else { ?>
<script type="text/javascript">
	choice.prev_body=undefined;
	choice.setChoices($('#question_body').val(), '<?php $this->p('option', 'correct') ?>');
	window.validate = choice.validate;
</script>
<?php } ?>
