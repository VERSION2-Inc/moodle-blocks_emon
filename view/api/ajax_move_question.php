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
	
	$params['cmid'] = required_param('cmid', PARAM_INT);
	$params['page_number'] = optional_param('page_number', 1, PARAM_INT);
	$params['mode'] = optional_param('mode', '', PARAM_TEXT);
	$params['questionid'] = optional_param('questionid', 0, PARAM_INT);
	$params['question_ids'] = optional_param_array('question_ids', array(), PARAM_INT);

	//並び替え処理
	$number = 0;
	$questionIds = array();
	if (is_array($params['question_ids'])) {
		foreach ($params['question_ids'] as $id) {
			$questionIds[] = $id;
			if ($id == $params['questionid']) {
				$questionNumber = $number;
			}
			$number++;
		}
	}

	if($params['mode'] == 'd') {
		$arr = array($questionIds[$questionNumber], $questionIds[$questionNumber+1]);
		list($questionIds[$questionNumber+1], $questionIds[$questionNumber]) = $arr + array();
	} else if ($params['mode'] == 'u'){
		$arr = array($questionIds[$questionNumber], $questionIds[$questionNumber-1]);
		list($questionIds[$questionNumber-1], $questionIds[$questionNumber]) = $arr + array();
	}
	
	// 現在のページ一覧を取得
	$pages = $moodles->getMoodleQuestions($params['cmid']);
	
	// 指定したページの配列を書き換える
	$pages[$params['page_number']]['questions'] = $questionIds;
	
	// ページ順の書き換え
	$moodles->setPageOrders($params['cmid'], $pages);
	
	exit;
