<?php
require_once(dirname(__FILE__) . '/../lib/EmonTpl.php');
require_once(dirname(__FILE__) . '/../lib/Moodles.php');
require_once(dirname(__FILE__) . '/../lib/Views.php');
require_once(dirname(__FILE__) . '/../lib/Converters.php');

// required library
require_once(dirname(__FILE__) . '/../../../config.php');

// login check
if (!isloggedin()) {
	error('You must be login.');
	exit;
}

// parameters
$params = array();
$params['cmid'] = required_param('cmid', PARAM_INT);

// file parameter
if (isset($_FILES['csv'])) {
	if ($_FILES['csv']['tmp_name']) {
		$csv = $moodles->parseCsvIncludeBreak(file_get_contents($_FILES['csv']['tmp_name']));
		error_reporting(0);

		$success = 0;
		$errors = array();
		foreach ($csv as $lineNumber => $line) {
			if (isset($line[0]) && isset($line[1]) && intval($line[1]) > 0 && (count($line) >= ($line[1] + 2))) {
				$params['page_number'] = 1;
				$params['question_number'] = 0;
				$params['category'] = required_param('category', PARAM_TEXT);
				$params['course'] = optional_param('course', 0, PARAM_INT);
				$params['qtype'] = QUIZ_TYPE_CHOICE;
				$params['questiontext'] = trim($line[0]);
				$params['defaultgrade'] = 1;
				$params['option']['shuffleanswers'] = 1;
				$params['option']['correct'] = intval($line[1]);
				foreach ($line as $number => $l) {
					if ($number > 1) {
						$params['option']['select'][$number - 2] = $l;
					}
				}
				$moodles->setQuestion($params);
				$success++;
			} else {
				$errors[] = $lineNumber + 1;
			}
		}
	}
	$params['is_finished'] = 1;
	$params['success'] = $success;
	$params['errors'] = $errors;
} else {
	$params['categories'] = $moodles->getMoodleCategories($params['cmid']);
}

// page print
$EmonTpl->load('header', $params);
$EmonTpl->load('csv', $params);
