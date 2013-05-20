<div class="title04_box">
	<div class="title04_box_in">
		<h3 class="title_04 title_green_03">
			<?php $this->p('module', 'name') ?>
			<?php etpl('how_to_access') ?>
		</h3>

		<div class="list_body">
			<a href="page.php?cmid=<?php $this->p('module', 'cmid') ?>"><img
				src="resources/images/icon_038.gif" /> <?php etpl('back_to_edit') ?>
			</a>
			<div class="form_box">
				<fieldset>
					<legend>
						<?php etpl('how_to_direct_attempt') ?>
					</legend>
					<p>
						<?php etpl('detail_direct_attempt') ?>
					</p>
					<dl>
						<dt>
							<?php etpl('attempt_url') ?>
						</dt>
						<dd>
							<b><a
								href="<?php echo $CFG->wwwroot ?>/mod/quiz/view.php?id=<?php $this->p('module', 'cmid') ?>"
								target="_moodle"><?php echo $CFG->wwwroot ?>/mod/quiz/view.php?id=<?php $this->p('module', 'cmid') ?>
							</a>
							</b>
						</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>
						<?php etpl('how_to_access') ?>
					</legend>
					<p>
						<?php etpl('detail_access') ?>
					</p>
					<dl>
						<dt>
							<?php etpl('course_name') ?>
						</dt>
						<dd>
							<b><a
								href="<?php echo $CFG->wwwroot ?>/course/view.php?id=<?php $this->p('module', 'course') ?>"
								target="_moodle"><?php $this->p('coursename') ?>
							</a>
							</b>
						</dd>
					</dl>
					<dl>
						<dt>
							<?php etpl('section') ?>
						</dt>
						<dd>
							<b><a
								href="<?php echo $CFG->wwwroot ?>/course/view.php?id=<?php $this->p('module', 'course') ?>&section=<?php $this->p('section_name') ?>"
								target="_moodle"><?php $this->p('section_name') ?>
							</a>
							</b>
						</dd>
					</dl>
				</fieldset>
				<fieldset>
					<legend>
						<?php etpl('how_to_modify') ?>
					</legend>
					<p>
						<?php etpl('detail_modify') ?>
					</p>
					<dl>
						<dt>
							<?php etpl('update') ?>
						</dt>
						<dd>
							<b><a
								href="<?php echo $CFG->wwwroot ?>/course/mod.php?update=<?php $this->p('module', 'cmid') ?>&sesskey=<?php $this->p('sesskey') ?>&sr=1"
								target="_moodle"><?php echo $CFG->wwwroot ?>/course/mod.php?update=<?php $this->p('module', 'cmid') ?>
							</a>
							</b>
						</dd>
					</dl>
					<dl>
						<dt>
							<?php etpl('delete') ?>
						</dt>
						<dd>
							<b><a
								href="<?php echo $CFG->wwwroot ?>/course/mod.php?delete=<?php $this->p('module', 'cmid') ?>&sesskey=<?php $this->p('sesskey') ?>&sr=1"
								target="_moodle"><?php echo $CFG->wwwroot ?>/course/mod.php?delete=<?php $this->p('module', 'cmid') ?>
							</a>
							</b>
						</dd>
					</dl>
				</fieldset>
			</div>
		</div>
	</div>
</div>

</body>
</html>
