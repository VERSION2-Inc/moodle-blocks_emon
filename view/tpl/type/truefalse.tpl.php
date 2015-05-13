<?php if (!$isScript) { ?>
<input type="hidden" name="qtype" value="<?php echo QUIZ_TYPE_TRUEFALSE ?>" />

<fieldset>
	<legend><?php etpl('truefalse_setting') ?></legend>
		<dl>
			<dt><?php etpl('question_text') ?></dt>
			<dd>
				<textarea id="questiontext" name="questiontext" class="middle"><?php $this->p('questiontext') ?></textarea>
				<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('correct') ?></dt>
			<dd>
				<table>
					<tr>
						<td colspan="2" class="moodle_truefalse_td">
							<input type="radio" id="true" name="option[correct]" value="0" <?php if (!$this->g('option', 'correct')) { ?>checked="checked"<?php } ?>/>
							<label for="true"><span class="moodle_true"><?php etpl('truefalse_true') ?></span></label>
							<input type="radio" id="false" name="option[correct]" value="1" <?php if ($this->g('option', 'correct') == 1) { ?>checked="checked"<?php } ?>/>
							<label for="false"><span class="moodle_false"><?php etpl('truefalse_false') ?></span></label>
						</td>
					</tr>
					<tr>
						<td class="moodle_truefalse_td" style="width:10em;"><?php etpl('truefalse_true_feedback') ?></td>
						<td class="moodle_truefalse_td"><input type="text" name="option[feedbacktrue]" value="<?php $this->p('option', 'feedbacktrue') ?>" style="width:30em;" /></td>
					</tr>
					<tr>
						<td class="moodle_truefalse_td" style="width:10em;"><?php etpl('truefalse_false_feedback') ?></td>
						<td class="moodle_truefalse_td"><input type="text" name="option[feedbackfalse]" value="<?php $this->p('option', 'feedbackfalse') ?>" style="width:30em;" /></td>
					</tr>
				</table>
			</dd>
		</dl>
</fieldset>

<?php } else { ?>

<script type="text/javascript">
	window.validate = function(){return true;};
</script>

<?php } ?>