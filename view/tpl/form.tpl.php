<script type="text/javascript">
function formcheck() {
	if (!$('input[name="name"]').val()) {
		alert('名称を入力してください。');
		return false;
	}
	return true;
}
function windowclose() {
	window.close();
}
</script>

<div class="title04_box">
	<h3 class="title_04 title_green_03">
		<?php etpl('title_emon_form') ?>
	</h3>
	<div class="title04_box_in">
		<div class="list_body">

			<?php if ($this->g('cmid')) { ?>
			<a href="page.php?cmid=<?php $this->p('cmid') ?>"><img
				src="resources/images/icon_022.gif" /> <?php etpl('back_to_edit'); ?>
			</a>
			<?php } else { ?>
			<a href="#" onclick="windowclose();return false;"><img
				src="resources/images/icon_038.gif" /> <?php etpl('back_to_edit'); ?>
			</a>
			<?php } ?>

			<div class="right">
				<?php echo $USER->username . ' ' . $USER->firstname . ' ' . $USER->lastname ?>
				<?php etpl('person') ?>
			</div>
			<div class="form_box">
				<form id="quiz_form" name="quiz_form" action="form.php"
					method="post" onsubmit="return formcheck();">
					<input type="hidden" name="action" value="save" /> <input
						type="hidden" name="cmid" value="<?php $this->p('cmid') ?>" />
					<fieldset>
						<legend>
							<?php echo etpl('where_is_moodle_saved') ?>
						</legend>
						<dl>
							<dt>
								<?php echo etpl('course') ?>
							</dt>
							<dd>
								<?php if ($this->g('coursename')) { ?>
								<b><?php $this->p('coursename') ?> </b>
								<?php } else { ?>
								<select name="courseid">
									<?php foreach($this->a('courses') as $courseId => $c) { ?>
									<option value="<?php echo $courseId ?>"
									<?php if ($courseId == $this->g('course_id')) { ?>
										selected="selected" <?php } ?>>
										<?php echo $c ?>
									</option>
									<?php } ?>
								</select>
								<?php } ?>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('topic') ?>
							</dt>
							<dd>
								<?php if ($this->g('section') && $this->g('cmid')) { ?>
								<h3>
									<?php echo $this->p('section') ?>
								</h3>
								<?php } else { ?>

								<?php 
								echo "<select name='section'>";
								//セクション情報を取得
								$sections = array();
//								$sections = $this->g('section_list');
								$sections = $this->getMoodleCourseSections($courseId);

								foreach($sections as $key => $section){
									echo "<option value='$key'>$section</option>";
								}
								echo "</select>";
								?>
								<?php } ?>
							</dd>
						</dl>
					</fieldset>

					<fieldset>
						<legend>
							<?php etpl('quiz_setting') ?>
						</legend>
						<dl>
							<dt>
								<?php etpl('quiz_name') . required() ?>
							</dt>
							<dd>
								<input type="text" name="name" value="<?php $this->p('name') ?>"
									style="width: 20em;" />
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('introduction') ?>
							</dt>
							<dd>
								<textarea name="intro" class="large">
									<?php $this->p('intro') ?>
								</textarea>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('view_status') ?>
							</dt>
							<dd>
								<input type="checkbox" name="visible" value="1"
								<?php if ($this->g('visible')) { ?> checked="checked"
								<?php } ?> class="label" />
								<?php etpl('invisible_to_check') ?>
								</label>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('timing') ?>
							</dt>
							<dd>
								<input type="text" id="open_date" name="open_date"
									value="<?php $this->p('open_date') ?>" style="width: 8em;"
									onfocus="openCal.write();" onclick="openCal.write();"
									onchange="openCal.getFormValue(); openCal.hide();" />
								<div id="opencal" style="float: left; display: none;"></div>
								<input type="text" id="open_time" name="open_time"
									value="<?php $this->p('open_time') ?>" style="width: 5em;"
									onfocus="openCal.hide();" maxlength="5" />
								<script type="text/javascript">var openCal = new JKL.Calendar('opencal','quiz_form','open_date');openCal.setStyle('frame_color', '#99cc00');</script>
								～ <input type="text" id="close_date" name="close_date"
									value="<?php $this->p('close_date') ?>" style="width: 8em;"
									onfocus="limitCal.write();" onclick="limitCal.write();"
									onchange="limitCal.getFormValue(); limitCal.hide();" />
								<div id="limitcal" style="float: left; display: none;"></div>
								<input type="text" id="close_time" name="close_time"
									value="<?php $this->p('close_time') ?>" style="width: 5em;"
									onfocus="limitCal.hide();" maxlength="5" />
								<script type="text/javascript">var limitCal = new JKL.Calendar('limitcal','quiz_form','close_date');limitCal.setStyle('frame_color', '#cc9933');</script>
								<br />
								<?php etpl('time_format') ?>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('quiz_limit') ?>
							</dt>
							<dd>
								<input type="text" name="attempts"
									value="<?php $this->p('attempts') ?>" style="width: 2em;" />
								<?php etpl('number_zero_unlimited') ?>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('time_limit') ?>
							</dt>
							<dd>
								<input type="text" name="timelimit"
									value="<?php echo ceil($this->g('timelimit') / 60) ?>"
									style="width: 2em;" />
								<?php etpl('minute_zero_unlimited') ?>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('last_answer') ?>
							</dt>
							<dd>
								<label><input type="checkbox" name="attemptonlast" value="1"
								<?php if ($this->g('attemptonlast')) { ?> checked="checked"
								<?php } ?> class="label" /> <?php etpl('view_last_answer') ?> </label>
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('password') ?>
							</dt>
							<dd>
								<input type="text" name="password"
									value="<?php $this->p('password') ?>" style="width: 10em;" />
							</dd>
						</dl>
						<dl>
							<dt>
								<?php etpl('review_option') ?>
							</dt>
							<dd>
								<?php if ($this->g('is_moodle_review')) { ?>
								<?php etpl('set_on_moodle') ?>
								<input type="hidden" name="is_moodle_review" value="1" />
								<?php } else { ?>
								<input type="checkbox" name="is_correct" value="1"
								<?php if ($this->g('is_correct')) { ?> checked="checked"
								<?php } ?> class="label" />
								<?php etpl('view_answer') ?>
								</label> <br /> <input type="checkbox" name="is_point" value="1"
								<?php if ($this->g('is_point')) { ?> checked="checked"
								<?php } ?> class="label" />
								<?php etpl('view_point') ?>
								</label>
								<?php } ?>
							</dd>
						</dl>
						<div class="center">
							<input type="submit" value="<?php etpl('save') ?>" class="submit" />
						</div>
					</fieldset>

				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
		if (!$('textarea[name="intro"]').val()) {
			$('textarea[name="intro"]').val('<p></p>');
		}
		tinyMCE.init({
			// General options
			mode : "exact",
			elements: 'intro',
			theme : "advanced",
			content_css : "resources/css/glexa.css",
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
			plugins : "table,iespell,inlinepopups,fullscreen,noneditable,tabfocus", //contextmenu,
			forced_root_block : '',
			force_br_newlines : true,
			force_p_newlines : false,
			accessibility_focus : false,
		    preformatted : true,
		    remove_linebreaks : true			
		});
	</script>
</body>
</html>
