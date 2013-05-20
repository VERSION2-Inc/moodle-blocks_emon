<?php if (!$isScript) { ?>
<input type="hidden" id="qtype" name="qtype" value="<?php echo QUIZ_TYPE_MULTICHOICE ?>" />

<fieldset>
	<legend><?php etpl('multichoice_setting') ?></legend>
		<dl>
			<dt>
				<?php etpl('question_text') ?>
				<br />
			</dt>
			<dd>
				<textarea id="questiontext" name="questiontext" class="middle"><?php echo trim($this->g('questiontext')) ?></textarea>
				<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('choices') ?></dt>
			<dd>
				<?php etpl('choice_detail') ?><br />
				<textarea id="question_body" name="question_body" class="middle" onchange="multichoice.setMultichoices(this.value, false)" onkeyup="multichoice.setMultichoices(this.value, false)"><?php echo $this->p('question_body') ?></textarea>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('view_order') ?></dt>
			<dd>
				<label><input type="checkbox" id="random_enable" name="option[shuffleanswers]" value="1" <?php if ($this->g('option', 'shuffleanswers')) { ?>checked="checked" <?php } ?>class="label"/><?php etpl('choice_shuffle') ?></label>
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
	<script type="text/javascript">multichoice.initMultichoice()</script>
	<?php foreach ($this->a('option', 'corrects') as $number => $o) { ?>
		<script type="text/javascript">multichoice.setMultichoiceCorrect(<?php echo $number ?>)</script>
	<?php } ?>
	<script type="text/javascript">multichoice.prev_mode=undefined</script>
	<script type="text/javascript">multichoice.setMultichoices($('#question_body').val(), false)</script>
	<script type="text/javascript">window.validate=multichoice.validate</script>
<?php } ?>