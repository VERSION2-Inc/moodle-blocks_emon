<div class="title04_box">
<h3 class="title_04 title_green_03"><?php etpl('csv_upload') ?></h3>
<div class="title04_box_in">
<div class="list_body">

	<a href="page.php?cmid=<?php $this->p('cmid') ?>"><img src="resources/images/icon_022.gif" /> <?php etpl('back_to_edit'); ?></a>
	
	<div class="right">
		<?php echo $USER->username . ' ' . $USER->firstname . ' ' . $USER->lastname ?><?php etpl('person') ?>
	</div>
	<div class="form_box">
		<?php if ($this->g('is_finished')) { ?>
			<p>
				<?php etpl('csv_finished') ?>
			</p>
			<fieldset>
				<dl>
					<dt><?php etpl('csv_success') ?></dt>
					<dd><b><?php $this->p('success') ?></dd>
				</dl>
				<dl>
					<dt><?php etpl('csv_errors') ?></dt>
					<dd><?php foreach ($this->a('errors') as $e) { echo $e . '<br />'; } if (!$this->a('errors')) { echo '-'; } ?></dt>
				</dl>
			</filedset>
			<br />
			<a href="page.php?cmid=<?php $this->p('cmid') ?>"><img src="resources/images/icon_022.gif" /> <?php etpl('back_to_edit'); ?></a>
		<?php } else { ?>
			<form action="csv.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="cmid" value="<?php $this->p('cmid') ?>" />
				<fieldset>
					<legend><?php echo etpl('csv_upload') ?></legend>
					<dl>
						<dt><?php etpl('question_bank_category') ?></dt>
						<dd>
							<select name="category">
								<?php foreach ($this->g('categories') as $category => $c) { ?>
									<optgroup label="<?php echo $category ?>">
									<?php foreach ($c as $categoryId => $cc) { ?>
										<option value="<?php echo $categoryId ?>,<?php echo $cc[1] ?>" <?php if ($category == $categoryId) { ?>selected="selected"<?php } ?>><?php echo $cc[0] ?></option>
									<?php } ?>
									</optgroup>
								<?php } ?>
							</select>
						</dd>
					</dl>
					<dl>
						<dt><?php etpl('csv_file') ?></dt>
						<dd>
							<input type="file" name="csv" />						
						</dd>
					</dl>
					<dl>
						<dt><?php etpl('csv_file_explain') ?></dt>
						<dd>
							<?php etpl('csv_file_explain_body') ?>						
						</dd>
					</dl>
				</fieldset>
				<div id="submit_button">
					<input type="submit" value="<?php etpl('csv_upload') ?>" class="submit" />
				</div>
			</form>
		<?php } ?>
	</div>
</div>
</div>
</div>
</body>
</html>