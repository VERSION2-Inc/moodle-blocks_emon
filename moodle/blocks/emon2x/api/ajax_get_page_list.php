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
	
	$cmid = required_param('cmid', PARAM_INT);
	$pageNumber = optional_param('page_number', 1, PARAM_INT);
	
	$params['cmid'] = $cmid;
	$params['page_number'] = $pageNumber;
	$params['pages'] = $moodles->getMoodleQuestions($cmid);
	$params['is_attemps'] = $moodles->getMoodleIsAttempt($cmid);
	
	// 新規ページ
	if (count($params['pages']) < $pageNumber) {
		$params['pages'][$pageNumber]['page_number'] = $pageNumber;
	}
	
	$EmonTpl->load('ajax_get_page_list', $params);
