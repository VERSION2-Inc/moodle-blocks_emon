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
$params['action'] = optional_param('action', '', PARAM_TEXT);
$params['page_number'] = optional_param('page_number', 1, PARAM_INT);
$params['cmid'] = optional_param('cmid', 0, PARAM_INT);
$params['section'] = optional_param('section', 0, PARAM_INT);
$params['user'] = $USER;
require_login();

// load parameters
// get page numbers
$params['pages'] = $moodles->getMoodleQuestions($params['cmid']);
$params['page_total'] = count($params['pages']);
$params['is_attempts'] = $moodles->getMoodleIsAttempt($params['cmid']);
$params['questions'] = $moodles->getMoodleQuestionsFromPageNumber($params['cmid'], $params['page_number']);
$params['quiz'] = $moodles->getMoodleQuiz($params['cmid']);
$params['itemid'] = file_get_unused_draft_itemid();

// page print
$EmonTpl->load('header', $params);
$EmonTpl->load('page', $params);
