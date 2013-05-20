<div style="float: right;">
	<a href="#"
		onclick="closeQuestionForm(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>);return false;"><img
		src="resources/images/icon_021.gif" /> <?php etpl('close') ?> </a>
</div>

<div id="question_editor">
	<div class="form_box_quiz">
		<form id="qform_<?php $this->p('questionid') ?>"
			action="../api/ajax_save_question.php" method="post"
			enctype="multipart/form-data"
			onsubmit="sendQuestionForm('#qform_<?php $this->p('questionid') ?>');return false;">
			<input type="hidden" name="action" value="ajax_save_question" /> <input
				type="hidden" id="questionid" name="questionid"
				value="<?php $this->p('questionid') ?>" /> <input type="hidden"
				name="course" value="<?php $this->p('course') ?>" /> <input
				type="hidden" id="cmid" name="cmid"
				value="<?php $this->p('cmid') ?>" /> <input type="hidden"
				id="page_number" name="page_number"
				value="<?php $this->p('page_number') ?>" /> <input type="hidden"
				id="copy" name="copy" value="" /> <input type="hidden" id="itemid"
				name="itemid" value="<?php $this->p('itemid') ?>"?>

			<?php if ($this->g('question_number')) { ?>
			<input type="hidden" name="question_number"
				value="<?php $this->p('question_number') ?>" />
			<?php } ?>

			<?php if (!$this->g('modify') && !$this->g('create') && !$this->g('qtype')) { ?>
			<?php etpl('click_question_type') ?>
			<br />
			<p>
				<a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_CHOICE ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('choice') ?>
				</a> | <a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_MULTICHOICE ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('multichoice') ?>
				</a> | <a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_TEXT ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('input') ?>
				</a> <br /> <a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_FILL ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('cloze') ?>
				</a> | <a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_TRUEFALSE ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('truefalse') ?>
				</a> | <a href="#"
					onclick="changeQuestionType(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, '<?php echo QUIZ_TYPE_MATCH ?>', <?php echo intval($this->g('question_number')) ?>);return false;"><?php etpl('matching') ?>
				</a>
			</p>
			<br />
			<?php } ?>

			<?php if ($this->g('qtype')) { ?>
			<fieldset>
				<legend>
					<?php etpl('setting_question') ?>
				</legend>
				<dl>
					<dt>
						<?php etpl('question_bank_category') ?>
					</dt>
					<dd>
						<select name="category">
							<?php foreach ($this->g('categories') as $category => $c) { ?>
							<optgroup label="<?php echo $category ?>">
								<?php foreach ($c as $categoryId => $cc) { ?>
								<option value="<?php echo $categoryId ?>,<?php echo $cc[1] ?>"
								<?php if ($category == $categoryId) { ?> selected="selected"
								<?php } ?>>
									<?php echo $cc[0] ?>
								</option>
								<?php } ?>
							</optgroup>
							<?php } ?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<?php etpl('question_name') ?>
					</dt>
					<dd>
						<input type="text" name="name" value="<?php $this->p('name') ?>"
							style="width: 30em;" />
					</dd>
				</dl>
			</fieldset>
			<?php } ?>

			<?php $isScript = false; ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_CHOICE) { 
				include(dirname(__FILE__) . '/type/choice.tpl.php');
} ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_MULTICHOICE) { 
				include(dirname(__FILE__) . '/type/multichoice.tpl.php');
} ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_TEXT) { 
				include(dirname(__FILE__) . '/type/text.tpl.php');
} ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_FILL) { 
				include(dirname(__FILE__) . '/type/fill.tpl.php');
} ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_TRUEFALSE) { 
				include(dirname(__FILE__) . '/type/truefalse.tpl.php');
} ?>
			<?php if ($this->g('qtype',null) == QUIZ_TYPE_MATCH) { 
				include(dirname(__FILE__) . '/type/match.tpl.php');
} ?>

			<?php if ($this->g('qtype')) { ?>
			<fieldset>
				<legend>
					<?php etpl('grade_setting') ?>
				</legend>
				<?php if ($this->g('qtype') != QUIZ_TYPE_FILL) { ?>
				<dl id="ispoint">
					<dt>
						<?php etpl('grade_default') ?>
					</dt>
					<dd>
						<input type="text" name="defaultgrade"
							value="<?php $this->p('defaultgrade') ?>" style="width: 2em;" />
					</dd>
				</dl>
				<?php } ?>
				<dl>
					<dt>
						<?php etpl('general_feedback') ?>
					</dt>
					<dd>
						<textarea name="generalfeedback" class="middle"
							style="height: 50px;"><?php $this->p('generalfeedback') ?></textarea>
					</dd>
				</dl>
			</fieldset>

			<div id="submit_button">
				<?php if ($this->g('questionid')) { ?>
				<input type="submit" value="<?php etpl('save_question') ?>"
					class="submit" onclick="$('#copy').val('');" /> <input
					type="submit" value="<?php etpl('save_new_question') ?>"
					class="submit" onclick="$('#copy').val('copy');" />
				<?php } else { ?>
				<input type="button" value="<?php etpl('save_question') ?>"
					class="submit"
					onclick="sendQuestionForm('#qform_<?php $this->p('questionid') ?>');return false;" />
				<?php } ?>
			</div>
			<?php } ?>
		</form>
	</div>
	<?php $isScript = true; ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_CHOICE) { 
		include(dirname(__FILE__) . '/type/choice.tpl.php');
} ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_MULTICHOICE) { 
		include(dirname(__FILE__) . '/type/multichoice.tpl.php');
} ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_TEXT) { 
		include(dirname(__FILE__) . '/type/text.tpl.php');
} ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_FILL) { 
		include(dirname(__FILE__) . '/type/fill.tpl.php');
} ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_TRUEFALSE) { 
		include(dirname(__FILE__) . '/type/truefalse.tpl.php');
} ?>
	<?php if ($this->g('qtype') == QUIZ_TYPE_MATCH) { 
		include(dirname(__FILE__) . '/type/match.tpl.php');
} ?>
	<div style="clear: both;"></div>
</div>

<script type="text/javascript">
	if (!$('textarea[name="questiontext"]').val()) {
		$('textarea[name="questiontext"]').val('<p></p>');
	}
	tinyMCE.init({
		// General options
		mode : "exact",
		elements: 'questiontext',
		theme : "advanced",
		convert_urls : 0,
		theme_advanced_buttons1 : "fontsizeselect,bold,italic,underline,forecolor,|,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,|,charmap,table,|,undo,redo,link,unlink,|,cleanup,iespell,fullscreen,code",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],iframe[src|width|height|name|align]",
		plugins : "table,iespell,inlinepopups,fullscreen,noneditable,tabfocus,contextmenu",
		forced_root_block : '',
		force_br_newlines : true,
		force_p_newlines : false,
		accessibility_focus : false,
	    preformatted : true,
	    remove_linebreaks : true
});
</script>
<?php if ($this->g('qtype')) { ?>
<div id="uploader"
	style="padding: 0px; margin: 0px;"
	cmid="<?php $this->g('cmid',null) ?>"
	qtype="<?php $this->g('qtype',null) ?>"
	, session_name="<?php echo session_name() ?>"
	session_id="<?php echo session_id() ?>"></div>
<?php } ?>