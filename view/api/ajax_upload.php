<?php
	require_once(dirname(__FILE__) . '/../../lib/EmonTpl.php');
	require_once(dirname(__FILE__) . '/../../lib/Moodles.php');
	require_once(dirname(__FILE__) . '/../../lib/Views.php');
	
	// required library
    require_once(dirname(__FILE__) . '/../../../../config.php');
	require_once(dirname(__FILE__).'/../../../../repository/upload/lib.php');
    
	// login check
	if (!isloggedin()) {
		error('You must be login.');
		exit;
	}
	
	$params['itemid'] = required_param('itemid', PARAM_INT);
	
	// ファイルパラメーター
	$_FILES['repo_upload_file'] = $_FILES['Filedata'];
	
	$repos = repository::get_instances(array('type' => 'upload'));
    /* @var $repo repository_upload */
    $repo = reset($repos);
	
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

?>
<html>
<body style="margin:0; padding:0;">
<form action="<?php echo new moodle_url('/blocks/emon/view/api/ajax_upload.php'); ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="itemid" value="<?php echo $params['itemid']; ?>" />
<input type="file" name="Filedata" onchange="this.form.submit();" />
</form>
<form name="added">
<input type="hidden" name="filename" value="<?php echo s($_FILES['Filedata']['name']); ?>" />
</form>
<script>
//<![CDATA[
parent.setMediaFile(document.forms['added'].elements['filename'].value);
//]]>
</script>
</body>
</html>
