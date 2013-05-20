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
require_once(dirname(__FILE__) . '/../lib/Views.php');

error_reporting(E_ALL);

$params = null;
$params['user'] = $USER;
$cmid = optional_param('cmid', 0, PARAM_INT);
require_login();

error_log("complete!");

// クイズデータの取得
$params['module'] = $moodles->getMoodleQuiz($cmid);

// セクション名
$sections = $moodles->getMoodleCourseSections($params['module']['course']);
$sectionKeys = array_keys($sections, $params['module']['section']);
$params['section_name'] = $sectionKeys[0];

// コースデータの取得
$courses = $moodles->getMoodleCourses();
$params['coursename'] = $courses[$params['module']['course']];

// セッションキー
$params['sesskey'] = $params['user']->sesskey;

// page print
$EmonTpl->load('header', $params);
$EmonTpl->load('complete', $params);
