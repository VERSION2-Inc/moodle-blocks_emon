<?php
$EmonTpl = new EmonTpl();

// get_string overwrap function
function etpl($string)
{
	echo get_string($string, 'block_emon2x');
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
	 * コース内のセクション情報を取得する。
	 *
	 * @param
	 */
	function getSectionInfo($courseId)
	{
		global $CFG,$DB;

		$sql = "SELECT * FROM `mdl_course_sections` WHERE course = $courseId";
		$records = $DB->get_records('course_sections', array('course'=>$courseId));

		$sections = array();
		foreach($records as $row){
			array_push($sections,$row->name);
		}

		return $sections;
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
		preload_course_contexts($course->id);
		if (!$context = get_context_instance(CONTEXT_COURSE, $course->id)) {
			error('nocontext');
		}
		require_login($course);
			
		// capability check
		if (!has_capability('moodle/course:manageactivities', $context)) {
			error('You do not have capability of this course');
		}
		
		//$sections = $this->getSectionInfo($course->id);
		$sections = $DB->get_records('course_sections', array('course'=>$course->id)
				, 'section');
		
		$jsonSections = array();
		//セクション情報の詰め込み
		foreach ($sections as $section) {
			$jsonSections[] = $section->name ? $section->name : $section->section;
		}
		return $jsonSections;
	}



}