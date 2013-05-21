<?php if ($this->g('is_attempts')) { ?><span class="error"><?php echo etpl('already_attempt'); ?></span><?php } ?>

<?php if (count($this->g('questions_html')) > 3) { ?>
<hr />
	<div class="page_controller">
		<?php if (count($this->g('questions_html'))) { ?>
			<div style="float:left;">
				<a href="page.php?cmid=<?php echo ($this->g('cmid')) ?>&page_number=<?php echo ($this->g('page_number') + 1) ?>"><img src="resources/images/icon_065.gif" /> <?php etpl('next_page') ?></a>
			</div>
		<?php } ?>
		<?php if ($this->g('page_number') > 1) {  ?>
			<div style="float:right;">
				<a href="page.php?cmid=<?php echo ($this->g('cmid')) ?>&page_number=<?php echo ($this->g('page_number') - 1) ?>"><img src="resources/images/icon_064.gif" /> <?php etpl('prev_page') ?></a>
			</div>
		<?php } ?>
		<div style="clear:both;"></div>
	</div>
<hr />
<?php } ?>

<ul id="question_sort" style="margin-top:10px;">
	<?php $number = 1 ?>
	<?php $prevType = 0 ?>
	<?php foreach ($this->a('questions') as $question => $q) { ?>
		<li id="question_ids_<?php echo $q->id ?>" class="question">
			<table>
				<tr>
					<td style="width:50px;background-color:#efefef" id="td_question_<?php echo $q->id ?>" class="question_drag <?php if (!$this->g('is_attempts')) { ?> handle<?php } ?>">
						<strong>
							<?php echo $number ?>
						</strong>
						<br />
						<span class="question_type"><?php $this->p('types', $q->qtype) ?></span><br />
						<?php if (!$this->g('is_attempts')) { ?>
							<?php if ($number > 1) { ?>
								<a href="#" onclick="moveSort(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, <?php echo $q->id ?>, 'u'); return false;"><img src="resources/images/icon_044.gif" /> </a><br />
							<?php } ?>
							<?php if ($number < count($this->g('questions'))) { ?>
								<a href="#" onclick="moveSort(<?php $this->p('cmid') ?>, <?php $this->p('page_number') ?>, <?php echo $q->id ?>, 'd'); return false;"><img src="resources/images/icon_018.gif" /> </a><br />
							<?php } ?>
						<?php } ?>
					</td>
					<td class="td_question pointer" question_number="<?php echo ($number - 1) ?>" question_id="<?php echo $q->id ?>">
						<div id="body_<?php echo $q->id ?>">
							<?php echo $q->question_body ?>
						</div>
					</td>
				</tr>
			</table>
			<?php $number++; ?>
			<?php $prevType = $q->qtype; ?>
		</li>
	<?php } ?>
</ul>

<!-- 問題エディタ -->
<div id="question_menu" class="question_menu" style="display:none;position:absolute;top:0px;left:0px;">
	<?php if (!$this->g('is_attempts')) { ?><a href="#" onclick="openQuestionInsertForm(event);return false;"><img src="resources/images/icon_017.gif" /> <?php etpl('insert') ?></a> | <?php } ?>
	<a href="#" onclick="openQuestionForm(event);return false;"><img src="resources/images/icon_022.gif" /><?php etpl('modify') ?></a>
	<?php if (!$this->g('is_attempts')) { ?> | <a href="#" class="a_remove"><img src="resources/images/icon_021.gif" /> <?php etpl('delete') ?></a><?php } ?>
</div>

<?php if (!$this->g('is_attempts')) { ?>
<div id="question_form">
	<a href="#" class="a_insert" cmid="<?php $this->p('cmid') ?>" page_number="<?php $this->p('page_number') ?>"><img src="resources/images/icon_017.gif" /> <?php etpl('create_question') ?></a>
</div>
<?php } ?>

<?php if (count($this->g('questions')) || $this->g('page_number') > 1) { ?>
<hr />
	<div class="page_controller">
		<?php if (count($this->g('questions'))) { ?>
			<div style="float:left;">
				<a href="?cmid=<?php $this->p('cmid') ?>&page_number=<?php echo ($this->g('page_number') + 1) ?>"><img src="resources/images/icon_065.gif" /> <?php etpl('next_page') ?></a>
			</div>
		<?php } ?>
		<?php if ($this->g('page_number') > 1) { ?>
			<div style="float:right;">
				<a href="?cmid=<?php $this->p('cmid') ?>&page_number=<?php echo ($this->g('page_number') - 1) ?>"><img src="resources/images/icon_064.gif" /> <?php etpl('prev_page') ?></a>
			</div>
		<?php } ?>
		<div style="clear:both;"></div>

		<?php if ($this->g('page_total') > 1) { ?>
			<hr />
			<?php etpl('move_page') ?>
			<?php foreach ($this->g('pages') as $p) {?>
				<?php if ($this->g('page_number') == $p['page_number']) { ?>
					<b><?php echo $p['page_number'] ?></b>
				<?php } else { ?>
					<a href="{$smarty.server.SCRIPT_NAME}?action=plugin_moodle_quiz_page_form&cmid=<?php $this->p('cmid') ?>&page_number=<?php echo $p['page_number'] ?>"><?php echo $p['page_number'] ?></a>
				<?php } ?>
				<?php if (count($this->g('pages')) != $p['page_number']) { ?> | <?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
<hr />
<?php } ?>