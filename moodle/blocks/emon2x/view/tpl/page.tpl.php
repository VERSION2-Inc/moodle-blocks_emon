<script type="text/javascript">
function windowclose() {
	if (window.parent) {
		window.parent.location.reload();
	}
	if (window.opener) {
		window.opener.location.reload();
	}
	window.close();
}
</script>
<?php 
if ($this->g('page_number') > count($this->g('pages'))) {
	$pageCount = $this->g('page_number');
} else {
	$pageCount = count($this->g('pages'));
}
?>
<h1 class="quiz_title">
	<?php echo $this->g('quiz', 'name') . '(' . $this->g('page_number') . ' / ' . $pageCount . ')' ?>
</h1>
<hr />
<a href="form.php?cmid=<?php $this->p('cmid') ?>"><img
	src="resources/images/icon_022.gif" /> <?php etpl('update_quiz') ?> </a>
|
<a href="complete.php?cmid=<?php $this->p('cmid') ?>"><img
	src="resources/images/icon_011.gif" /> <?php etpl('how_to_quiz') ?> </a>
|
<a
	href="csv.php?cmid=<?php $this->p('cmid') ?>&itemid=<?php $this->p('itemid') ?>"><img
	src="resources/images/icon_062.gif" /> <?php etpl('csv_upload') ?> </a>
|
<a href="#" onclick="windowclose();return false;"><img
	src="resources/images/icon_038.gif" /> <?php etpl('close_window') ?> </a>
<hr />
<div class="right">
	<?php echo $USER->username . ' ' . $USER->firstname . ' ' . $USER->lastname ?>
	<?php etpl('person') ?>
</div>
<table id="question_wrapper" style="border-width: 0px;">
	<tr>
		<td style="border-width: 0px;">
			<div id="questions">Now Loading...</div>
		</td>
		<td style="border-width: 0px; width: 150px;">
			<div id="pages">Now Loading...</div>
		</td>
	</tr>
</table>

<script type="text/javascript">getMoodlePageForEdit(<?php $this->p('cmid'); ?>, <?php $this->p('page_number'); ?>);</script>

<div
	id="page_setting_layer"
	style="display: none; position: absolute; top: 0px; left: 0px;"></div>
<div
	id="create_layer"
	style="display: none; position: absolute; top: 0px; left: 0px;"></div>
<script type="text/javascript">window.cmid = <?php $this->p('cmid'); ?>; window.pageNumber = <?php $this->p('page_number'); ?>;</script>
</body>
</html>
