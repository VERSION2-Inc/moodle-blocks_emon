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
	
	$params['cmid'] = required_param('cmid', PARAM_INT);
	$params['page_number'] = optional_param('page_number', 1, PARAM_INT);
	$params['qtype'] = optional_param('qtype', '', PARAM_CLEAN);
	$params['question_number'] = optional_param('question_number', 0, PARAM_INT);
	$params['create'] = optional_param('create', 0, PARAM_INT);
	$params['modify'] = optional_param('modify', 0, PARAM_INT);
	$params['questionid'] = optional_param('questionid', 0, PARAM_INT);

	// クイズ情報の取得
	$quiz = $moodles->getMoodleQuiz($params['cmid']);
	$params['course'] = $quiz['course'];
	$params['itemid'] = file_get_unused_draft_itemid();
	
	// 新規
	if ($params['create']) {
		// 新規作成
	} else {
    	// 修正
    	if ($params['questionid']) {
	    	$question = $moodles->getMoodleQuestion($params['questionid']);
    		
    		// ファイルをドラフトに設定
    		$draftid = file_get_submitted_draft_itemid('questiontext');
        	$record = $DB->get_record('question_categories',
                array('id' => $question->category), 'contextid');
        	$context = context::instance_by_id($record->contextid);
        	$fileoptions = array('subdirs' => 1, 'maxfiles' => -1, 'maxbytes' => -1);
	        $question->questiontext = file_prepare_draft_area($draftid, $context->id,
	                'question', 'questiontext', empty($question->id) ? null : (int) $question->id,
	                $fileoptions, $question->questiontext);
	        
    	} else {
    		$question = new StdClass;
    		$question->qtype = $params['qtype'];
    		$question->defaultmark = 1;
    	}
    	// 配点をフォーム値へ
    	$params['defaultgrade'] = $question->defaultmark;

    	// 存在チェック
    	if ($question->qtype) {
	    	// id
	    	$question->id = $params['questionid'];
	    	
			$methods = array(
				QUIZ_TYPE_CHOICE => 'convertChoiceAPIToForms',
				QUIZ_TYPE_MULTICHOICE => 'convertMultichoiceAPIToForms',
				QUIZ_TYPE_TEXT => 'convertTextAPIToForms',
				QUIZ_TYPE_FILL => 'convertFillAPIToForms',
				QUIZ_TYPE_TRUEFALSE => 'convertTruefalseAPIToForms',
				QUIZ_TYPE_MATCH => 'convertMatchAPIToForms',
			);

			if ($methods[$question->qtype]) {
				$arrayQuestion = array();
				setArray($question, $arrayQuestion);
	    		$question = $converters->$methods[$question->qtype]($arrayQuestion);
        	}
			
	    	foreach ($question as $k => $v) {
	    		$params[$k] = $v;
	    	}
	    	
	    	$params['categories'] = $moodles->getMoodleCategories($params['cmid']);
    	}
	}

	// セッション変数
	$params['session_name'] = session_name();
	$params['session_id'] = session_id();
	
	// ページ表示
	$EmonTpl->load('ajax_get_question_form', $params);
	exit;
	
	
	function setArray($src, &$dest) {
		if (is_object($src) || is_array($src)) {
			foreach ($src as $k => $v) {
				if (is_string($v)) {
					$dest[$k] = $v;
				} else {
					setArray($v, $dest[$k]);
				}
			}
		}
	}	
	