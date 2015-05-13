<?php if (!$isScript) { ?>
<input type="hidden" name="qtype" value="<?php echo QUIZ_TYPE_MATCH ?>" />

<fieldset>
	<legend><?php etpl('match_setting') ?></legend>
		<dl>
			<dt>
				<?php etpl('question_text') ?>
				<br />
			</dt>
			<dd>
				<textarea id="questiontext" name="questiontext" class="middle"><?php echo $this->p('questiontext') ?></textarea>
				<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('matching') ?></dt>
			<dd>
				<?php etpl('match_howto') ?>
				<div id="matchings"></div>
				<input type="button" value="<?php etpl('add') ?>" onclick="matching.addMatching()" class="form_btn_submit"/>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('match_view') ?></dt>
			<dd>
				<label><input type="checkbox" name="option[shuffleanswers]" value="1" <?php if (is_null($this->g('option', 'shuffleanswers')) || $this->g('option', 'shuffleanswers') == 1) { ?>checked="checked"<?php } ?> class="label"/><?php etpl('match_shuffle') ?></label>
			</dd>
		</dl>
</fieldset>
<script type="text/javascript">matching.number=0</script>
<?php foreach ($this->a('option', 'matchings') as $number => $m) { ?>
	<script type="text/javascript">matching.addMatching(<?php echo $number ?>, '<?php echo $m[0] ?>', '<?php echo $m[1] ?>')</script>
<?php } ?>
<?php if (!$this->g('option', 'matchings')) { ?>
	<?php for ($i = 0; $i < 3; $i++) { ?>
		<script type="text/javascript">matching.addMatching()</script>
	<?php } ?>
<?php } ?>

<?php } else { ?>

<script type="text/javascript">
	window.validate=matching.validate;
</script>

<?php } ?>