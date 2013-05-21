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
	$params['page_numbers'] = optional_param_array('page_numbers', array(), PARAM_INT);
	
	// 既存のページ情報を取得
	$pages = $moodles->getMoodleQuestions($params['cmid']);
	
	// ページの入れ替え
	$newPages = array();
	foreach ($params['page_numbers'] as $pageNumber) {
		$newPages[] = $pages[$pageNumber];
	}
	$moodles->setPageOrders($params['cmid'], $newPages);
	
	exit;
