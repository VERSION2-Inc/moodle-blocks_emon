<?php
	require_once(dirname(__FILE__) . '/../lib/EmonTpl.php');
	require_once(dirname(__FILE__) . '/../lib/Moodles.php');
	require_once(dirname(__FILE__) . '/../lib/Views.php');
	require_once(dirname(__FILE__) . '/../lib/Converters.php');
	
	// required library
    require_once(dirname(__FILE__) . "/../../../config.php");
    
	// login check
	if (!isloggedin()) {
		error('You must be login.');
		exit;
	}
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	// parameters
	$params['cmid'] = required_param('cmid', PARAM_INT);
	$params['page_number'] = required_param('page_number', PARAM_INT);
	$params['category'] = required_param('category', PARAM_TEXT);
	$params['course'] = optional_param('course', 0, PARAM_INT);
	$params['qtype'] = optional_param('qtype', '', PARAM_TEXT);
	$params['question_number'] = optional_param('question_number', 0, PARAM_INT);
	$params['create'] = optional_param('create', 0, PARAM_INT);
	$params['modify'] = optional_param('modify', 0, PARAM_INT);
	$params['questionid'] = optional_param('questionid', 0, PARAM_INT);
	$params['name'] = optional_param('name', '', PARAM_CLEAN);
	$params['questiontext'] = optional_param('questiontext', '', PARAM_RAW);
	$params['question_files'] = optional_param('question_files', '', PARAM_RAW);
	$params['generalfeedback'] = optional_param('generalfeedback', '', PARAM_RAW);
	$params['defaultgrade'] = optional_param('defaultgrade', 0, PARAM_INT);
	$params['copy'] = optional_param('copy', '', PARAM_TEXT);
	$params['itemid'] = optional_param('itemid', 0, PARAM_INT);
	// not recommended code (TODO)
	$params['option'] = $_REQUEST['option'];
	
	// action
    if ($params['copy'] != '') {
		$params['questionid'] = null;
	}

	// ファイル
	$questionFile = '';
	if ($params['question_files']) {
		$files = explode("\n", $params['question_files']);
		$context = context_user::instance($USER->id);
		foreach ($files as $f) {
			if (strlen(trim($f))) {
				//$moodleFilename = rtrim($CFG->wwwroot, '/') . '/file.php/' . $params['course'] . '/emon-' . $params['cmid'] . '/' . $f;
				$moodleFilename = rtrim($CFG->wwwroot, '/') . '/draftfile.php/' . $context->id . '/user/draft/' . $params['itemid'] . '/' . $f;
				// 拡張子が画像の場合はIMGタグ
				$pathInfo = pathinfo($f);
				$pathInfo['extension'] = strtolower($pathInfo['extension']);
				if ($pathInfo['extension'] == 'jpg' || $pathInfo['extension'] == 'jpeg' || $pathInfo['extension'] == 'gif' || $pathInfo['extension'] == 'png') {
					$questionFile .= '<img src="' . $moodleFilename . '" alt="" /><br />';
				} else {
	    			$questionFile .= '<a href="' . $moodleFilename . '" target="_blank">' . $f . '</a><br />';
				}
			}
		}
		$params['questiontext'] = $questionFile . $params['questiontext'];
	}

	$moodles->setQuestion($params);
	
	// ページ表示
	echo 'ok';