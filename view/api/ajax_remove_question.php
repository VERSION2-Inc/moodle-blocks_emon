<?php
	require_once(dirname(__FILE__) . '/../lib/EmonTpl.php');
	require_once(dirname(__FILE__) . '/../lib/Moodles.php');
	require_once(dirname(__FILE__) . '/../lib/Views.php');

	// required library
    require_once(dirname(__FILE__) . "/../../../config.php");

	// login check
	if (!isloggedin()) {
		error('You must be login.');
		exit;
	}

	// parameters
	$params['cmid'] = required_param('cmid', PARAM_INT);
	$params['page_number'] = required_param('page_number', PARAM_INT);
	$params['questionid'] = required_param('questionid', PARAM_INT);

	// 問題番号の取得
	$quiz = $moodles->getMoodleQuiz($params['cmid']);
	$questions = explode(',', $quiz['questions']);
	$questionNumber = array_search($params['questionid'], $questions);

	// 問題削除
	if ($questionNumber !== false) {
    	$moodles->removeMoodleQuestion($params['cmid'], $params['questionid'], $USER->sesskey);
	}
