<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once("../../../config.php");
require_once(dirname(__FILE__) . '/../lib/EmonTpl.php');
require_once(dirname(__FILE__) . '/../lib/Moodles.php');

error_reporting(E_ALL);

$params = array();
$params['cmid'] = optional_param('cmid',0,PARAM_INT);
$params['action'] = optional_param('action', '', PARAM_TEXT);
$params['course_id'] = optional_param('course', 0, PARAM_INT);
$params['user'] = $USER;

$site = get_site();
$systemcontext = context_system::instance();
require_login();

// save quiz
if ($params['action'] == 'save') {
	// action file include
	if ($params['cmid']) {
		$quiz = $moodles->getMoodleQuiz($params['cmid']);
	} else {
		$quiz = new StdClass();
	}
	require_once dirname(__FILE__).'/api/set_quiz.php';
	header('Location: page.php?cmid='.$cm->id);
}

// data setup
// 既存データの読み込み
if ($params['cmid']) {
	$quiz = $moodles->getMoodleQuiz($params['cmid']);
	if (is_array($quiz)) {
		if ($quiz['timeopen']) {
			$quiz['open_date'] = date('Y-m-d', $quiz['timeopen']);
			$quiz['open_time'] = date('H:i:s', $quiz['timeopen']);
		}
		if ($quiz['timeclose']) {
			$quiz['close_date'] = date('Y-m-d', $quiz['timeclose']);
			$quiz['close_time'] = date('H:i:s', $quiz['timeclose']);
		}

		if (array_key_exists('reviewrightanswer',$quiz)) {
			// 解答を表示する
			if ($quiz['reviewrightanswer'] == 0x11110) {
				$quiz['is_correct'] = 1;
			} else if ($quiz['reviewrightanswer'] == 0x0000){
				$quiz['is_correct'] = 0;
			} else {
				$quiz['is_moodle_review'] = true;
			}
		}

		if (array_key_exists('reviewmarks',$quiz) && array_key_exists('reviewcorrectness',$quiz)) {
			// 得点と正誤を表示する
			if ($quiz['reviewmarks'] == 0x11110 && $quiz['reviewcorrectness'] == 0x11110) {
				$quiz['is_point'] = 1;
			} else if ($quiz['reviewmarks'] == 0x00000 && $quiz['reviewcorrectness'] == 0x00000) {
				$quiz['is_point'] = 0;
			} else {
				$quiz['is_moodle_review'] = true;
			}
		}

		foreach ($quiz as $k => $v) {
			$params[$k] = $v;
		}
	}
} else {
	// デフォルト
	$quiz = array();
	
	$params['visible'] = 1;
	$params['attemptonlast'] = 1;
	$params['is_correct'] = 1;
	$params['is_point'] = 1;
}

// get courses
$params['courses'] = $moodles->getMoodleCourses();

if (array_key_exists('course',$quiz)) {
	$params['coursename'] = $params['courses'][$quiz['course']];
}

// page print
$EmonTpl->load('header', $params);
$EmonTpl->load('form', $params);