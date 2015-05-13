<?php
$EmonTpl = new EmonTpl();

// get_string overwrap function
function etpl($string)
{
	echo get_string($string, 'block_emon');
}
function required()
{
	echo '<span class="error"> *</span>';
}

class EmonTpl {
	var $params;
	function load($filename, $params = array()) {
		global $CFG, $USER, $SESSION, $COURSE;
		$this->p = $params;
		$dir = realpath(dirname( __FILE__));
		require($dir.'/../view/tpl/'.$filename . '.tpl.php');
	}

	//TODO:gとpのマージ
	//TODO:エラー制御演算子はなくするべきである。

	// echo variable from parameter
	function p($key, $key2 = null) {
		$value = '';
		if ($key2) {
			$value = @$this->p[$key][$key2];
		} else {
			$value = @$this->p[$key];
		}
		echo $value;
	}

	// get variable from parameter
	function g($key, $key2 = null) {
		$value = '';
		if ($key2) {
			$value = @$this->p[$key][$key2];
		} else {
			$value = @$this->p[$key];
		}
		return $value;
	}
	// get variable from parameter
	function a($key, $key2 = null) {
		$value = '';
		if ($key2) {
			$value = @$this->p[$key][$key2];
		} else {
			$value = @$this->p[$key];
		}
		if (is_array($value)) {
			return $value;
		} else {
			return array();
		}
	}

	function escapejs($str)
	{
		return strtr($str, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
	}

	/**
	 * セクション一覧の取得
	 *
	 * @param	int $courseId
	 * @return	array
	 */
	function getMoodleCourseSections($courseId)
	{
		global $CFG, $DB;

		// library
		include_once($CFG->dirroot . '/course/lib.php');

		// error check
		if (empty($courseId)) {
			error('Must specify course id, short name or idnumber');
		}
		if (!($course = $DB->get_record('course', array('id' => $courseId), '*', MUST_EXIST)) ) {
			error('Invalid course id');
		}
		context_helper::preload_course($course->id);
		if (!$context = context_course::instance($course->id)) {
			error('nocontext');
		}
		require_login($course);
			
		// capability check
		if (!has_capability('moodle/course:manageactivities', $context)) {
			error('You do not have capability of this course');
		}

		// get course sections
		$sections = get_fast_modinfo($course->id)->get_section_info_all();
		
		$jsonSections = array();

		//セクション情報の詰め込み
		foreach ($sections as $number => $section) {
			if(isset($section->name)){
				//セクション名がセットされていれば
				$jsonSections[$number] = $section->name;
			}else{
				//セクション名をセットしていない場合は、セクション番号を詰める
				$jsonSections[$number] = $section->section;
			}
		}
		ksort($jsonSections);
		unset($jsonSections[0]);
		
		//error_log(print_r($jsonSections,true));
			
		return $jsonSections;
	}

}
