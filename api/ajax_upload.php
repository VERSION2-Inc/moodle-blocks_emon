<?php
	require_once(dirname(__FILE__) . '/../lib/EmonTpl.php');
	require_once(dirname(__FILE__) . '/../lib/Moodles.php');
	require_once(dirname(__FILE__) . '/../lib/Views.php');
	
	// required library
    require_once(dirname(__FILE__) . '/../../../config.php');
	require_once(dirname(__FILE__).'/../../../repository/upload/lib.php');
    
	// login check
	if (!isloggedin()) {
		error('You must be login.');
		exit;
	}
	
	$params['itemid'] = required_param('itemid', PARAM_INT);
	
	// ファイルパラメーター
	$_FILES['repo_upload_file'] = $_FILES['Filedata'];
	
	$repo = new repository_upload();
	
	$result = $repo->process_upload(
		$_FILES['repo_upload_file']['name'],
		FILE_AREA_MAX_BYTES_UNLIMITED,
		'*',
		'/',
		$params['itemid'],
        null,
        $USER->firstname . ' ' . $USER->lastname,
        false,
        FILE_AREA_MAX_BYTES_UNLIMITED
	);

	echo 'ok';