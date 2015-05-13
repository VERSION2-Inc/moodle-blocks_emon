<?php if (!$isScript) { ?>
<input type="hidden" name="qtype" value="<?php echo QUIZ_TYPE_TEXT ?>" />

<fieldset>
	<legend><?php etpl('text_setting') ?></legend>
	<dl>
		<dt><?php etpl('question_text') ?></dt>
		<dd>
			<textarea id="questiontext" name="questiontext"><?php $this->p('questiontext') ?></textarea>
			<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
		</dd>
	</dl>
	<dl>
		<dt><?php etpl('text_case') ?></dt>
		<dd>
			<label><input type="radio" id="usecase_true" name="option[usecase]" value="1" <?php if ($this->g('option', 'usecase') == 1) {?>checked="checked"<?php } ?> class="label"/><?php etpl('text_case_true') ?></label>
			<label><input type="radio" id="usecase_false" name="option[usecase]" value="0" <?php if ($this->g('option', 'usecase') == 0) {?>checked="checked"<?php } ?> class="label"/><?php etpl('text_case_false') ?></label>
		</dd>
	</dl>
	<dl>
		<dt><?php etpl('correct') ?></dt>
		<dd>
			<?php $count = 1; foreach ($this->a('option', 'answers') as $number => $a) { ?>
				<input type="text" id="answers_<?php echo $count ?>" name="option[answer][<?php echo $count ?>]" value="<?php echo $a['answer'] ?>" style="width:36em;" />
				<br />
			<?php $count++; } ?>
			<div id="answer_layer"></div>
			<input type="button" value="<?php etpl('add') ?>" onclick="text.addTextAnswer()" class="form_btn_submit"/> 
		</dd>
	</dl>
</fieldset>

<?php } else { ?>
<script type="text/javascript">text.initText(<?php echo count($this->g('option', 'answers')) + 1; ?>)</script>
<script type="text/javascript">window.validate=text.validate</script>
<?php } ?>