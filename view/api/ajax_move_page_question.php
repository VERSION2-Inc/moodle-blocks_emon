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
	$params['target_page_number'] = optional_param('target_page_number', 1, PARAM_INT);
	$params['questionid'] = optional_param('questionid', 0, PARAM_INT);

	// 現在のページの問題順を取得
	$currentQuestions = $moodles->getMoodleQuestionsFromPageNumber($params['cmid'], $params['page_number']);
	
	// 現在のページを再配置
	$newCurrentPageQuesitonIds = array();
	foreach ($currentQuestions as $question) {
		if ($question->id != $params['questionid']) {
    		$newCurrentPageQuesitonIds[] = $question->id;
		}
	}
	
	// 新しいページの問題に異動する問題を追加
	$targetQuestions = $moodles->getMoodleQuestionsFromPageNumber($params['cmid'], $params['target_page_number']);
	// 新しいページを再配置
	$newTargetQuestionIds = array();
	foreach ($targetQuestions as $question) {
		$newTargetQuestionIds[] = $question->id;
	}
	$newTargetQuestionIds[] = $params['questionid'] ;
	
	// 指定したページの配列を書き換える
	$pages = $moodles->getMoodleQuestions($params['cmid']);
	$pages[$params['page_number']]['questions'] = $newCurrentPageQuesitonIds;
	$pages[$params['target_page_number']]['questions'] = $newTargetQuestionIds;
	
	// ページ情報を保存
	$moodles->setPageOrders($params['cmid'], $pages);
	
	exit;
