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
	
	$cmid = required_param('cmid', PARAM_INT);
	$pageNumber = optional_param('page_number', 1, PARAM_INT);
	$params['cmid'] = $cmid;
	$params['page_number'] = $pageNumber;
	$params['is_attempts'] = $moodles->getMoodleIsAttempt($cmid);
	
	// 問題一覧の取得
	$pages = $moodles->getMoodleQuestions($cmid, $pageNumber);
	$questions = array();
	// 指定ページ内の問題データの取得
	if (isset($pages[$pageNumber]['questions'])) {
		$questionIds = $pages[$pageNumber]['questions'];
		if (is_array($questionIds)) {
			foreach ($questionIds as $id) {
				$questions[] = $moodles->getMoodleQuestion($id);
			} 
		}
	}
	
	// 問題のHTMLを生成
	$html = array();
	$methods = array(
		QUIZ_TYPE_CHOICE => 'getChoice',
		QUIZ_TYPE_MULTICHOICE => 'getMultichoice',
		QUIZ_TYPE_TEXT => 'getText',
		QUIZ_TYPE_FILL => 'getFill',
		QUIZ_TYPE_TRUEFALSE => 'getTruefalse',
		QUIZ_TYPE_MATCH => 'getMatch',
	);
	if (is_array($questions)) {
		// ファイル表示用コンテキスト
		$context = get_context_instance(CONTEXT_MODULE, $cmid);
		foreach ($questions as $number => &$q) {
			// ドラフト処理
    		$draftid = file_get_submitted_draft_itemid('questiontext');
        	$record = $DB->get_record('question_categories',
                array('id' => $q->category), 'contextid');
        	$context = context::instance_by_id($record->contextid);
        	$fileoptions = array('subdirs' => 1, 'maxfiles' => -1, 'maxbytes' => -1);
	        $q->questiontext = file_prepare_draft_area($draftid, $context->id,
	                'question', 'questiontext', empty($q->id) ? null : (int) $q->id,
	                $fileoptions, $q->questiontext);
	                                
			if ($methods[$q->qtype]) {
				$q->question_body = $views->$methods[$q->qtype]($q->id, $q->questiontext, $q);
			}
		}
	}
	$params['questions'] = $questions;
	
	// 問題タイプ
	$types = array(
		QUIZ_TYPE_CHOICE => get_string('choice', 'block_emon'),
		QUIZ_TYPE_MULTICHOICE => get_string('multichoice', 'block_emon'),
		QUIZ_TYPE_TEXT => get_string('input', 'block_emon'),
		QUIZ_TYPE_FILL => get_string('cloze', 'block_emon'),
		QUIZ_TYPE_TRUEFALSE => get_string('truefalse', 'block_emon'),
		QUIZ_TYPE_MATCH => get_string('matching', 'block_emon'),
	);
	$params['types'] = $types;
	$params['page_count'] = $params['page_number'];	

	$EmonTpl->load('ajax_get_page', $params);
