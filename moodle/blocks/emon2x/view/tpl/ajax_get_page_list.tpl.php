<h3><?php etpl('page_list') ?></h3>
<ul id="page_sort">
<?php
	$count = 0;
	foreach ($this->a('pages') as $p) {
		if (!isset($p['question_count'])) {
			$p['question_count'] = 0;
		}
?>
	<li id="page_numbers_<?php echo $p['page_number'] ?>" class="page pageblock <?php if (!$this->g('is_attempts')) { ?>handle<?php } ?>"<?php if ($this->g('page_number') == $p['page_number']) { ?> style="border-width:3px;"<?php } ?>>
		<a href="page.php?cmid=<?php $this->p('cmid') ?>&page_number=<?php echo $p['page_number'] ?>"><strong><?php echo $p['page_number'] ?></strong></a>
		<br />
		<?php echo intval($p['question_count']); etpl('question_number'); ?>
	</li>
<?php
		$count = intval($p['question_count']);
	}
?>
<?php
	if (!$this->g('pages')) {
		etpl('no_page');
	}
?>
</ul>
<?php
	if (!$this->g('is_attempts')) {
		if ($count > 0) {
?>
		<br />
		<a href="page.php?cmid=<?php $this->p('cmid') ?>&page_number=<?php echo ($this->g('page_number') + 1) ?>"><?php etpl('create_page') ?></a>
<?php
		}
	}
?>