<?php if (!$isScript) { ?>
<input type="hidden" name="qtype" value="<?php echo QUIZ_TYPE_FILL ?>" />

<fieldset>
	<legend><?php etpl('cloze_setting') ?></legend>
		<dl>
			<dt><?php etpl('cloze_text') ?></dt>
			<dd>
				<?php etpl('cloze_howto') ?><br />
				・<img src="resources/images/icon_input.gif" /><?php etpl('cloze_input') ?><img src="resources/images/icon_select.gif" /><?php etpl('cloze_select') ?><br />
				・<?php etpl('cloze_select_howto') ?><br />
				・<span class="message"><?php etpl('cloze_modified_moodle') ?></span><br />
				<textarea id="filltext" name="option[filltext]"><?php $this->p('option', 'filltext') ?></textarea>
				<?php include(dirname(__FILE__) . '/media_files.tpl.php') ?>
			</dd>
		</dl>
		<dl>
			<dt><?php etpl('correct_and_point') ?></dt>
			<dd>
				<div id="answer_layer"><?php etpl('cloze_correct_keyword') ?></div>
			</dd>
		</dl>

</fieldset>

<?php } else { ?>
<script type="text/javascript">fill.fillCount=1;</script>
<?php
	$types = $this->g('option', 'types');
	$points = $this->g('option', 'points');
	$usecases = $this->g('option', 'usecases');	
?>
<?php foreach ($this->a('option', 'answers') as $number => $a) { ?>
	<script type="text/javascript">fill.setFillAnswerLayer('<?php echo $this->escapejs($a) ?>','<?php echo $types[$number]; ?>','<?php echo intval($points[$number]); ?>',<?php echo isset($usecases[$number]) ? $usecases[$number] : 'false'; ?>);</script>
<?php } ?>
<script type="text/javascript">fill.sortFill();</script>
<script type="text/javascript">
	if ($('#filltext').length) {
		if (!$('#filltext').val()) {
			$('#filltext').val('<p></p>');
		}
		var i, t = tinyMCE.editors;
		for (i in t){
		    if (t.hasOwnProperty(i)){
		        t[i].remove();
		    }
		}
		tinyMCE.init({
			// General options
			mode : "exact",
			elements: 'filltext',
			theme : "advanced",
			//content_css : "resources/css/glexa.css",
			convert_urls : 0,
			theme_advanced_buttons1 : "glexaquizfill,|,glexaquizselect,|,|,ontsizeselect,bold,italic,underline,forecolor,|,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,|,charmap,table,|,undo,redo,link,unlink,|,cleanup,iespell,fullscreen,code",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
			plugins : "safari,table,iespell,inlinepopups,fullscreen,noneditable,glexaquizfill,tabfocus,contextmenu",
			forced_root_block : '',
			force_p_newlines : false,
			force_br_newlines : true,
			accessibility_focus : false,
		    preformatted : true,
		    remove_linebreaks : true			
		});
	}
</script>
<script type="text/javascript">window.validate=fill.validate</script>
<?php } ?>