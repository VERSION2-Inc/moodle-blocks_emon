<?php
// library
require_once($CFG->dirroot. '/config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/quiz/mod_form.php');
require_once($CFG->dirroot.'/mod/quiz/lib.php');

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

$emonResult = false;

// emon parameters
$cmid = optional_param('cmid', 0, PARAM_INT);
$course = optional_param('courseid', 0, PARAM_INT);
$section = optional_param('section', 0, PARAM_INT);
$name = optional_param('name', '', PARAM_RAW);
$intro = optional_param('intro', '', PARAM_RAW);
$visible = optional_param('visible', 0, PARAM_INT);
$openDate = optional_param('open_date', '', PARAM_RAW);
$openTime = optional_param('open_time', '', PARAM_RAW);
$closeDate = optional_param('close_date', '', PARAM_RAW);
$closeTime = optional_param('close_time', '', PARAM_RAW);
$attempts = optional_param('attempts', '0', PARAM_RAW);
$timelimit = optional_param('timelimit', 0, PARAM_INT) * 60;
$timelimitenable = optional_param('timelimitenable', 0, PARAM_INT);
$attemptonlast = optional_param('attemptonlast', '0', PARAM_RAW);
$password = optional_param('password', '', PARAM_RAW);
$isCorrect = optional_param('is_correct', 0, PARAM_INT);
$isPoint = optional_param('is_point', 0, PARAM_INT);
$isMoodleReview = optional_param('is_moodle_review', 0, PARAM_INT);


if ($openDate) {
	$timeopen = strtotime($openDate . ' ' . $openTime);
} else {
	$timeopen = 0;
}
if ($closeDate) {
	$timeclose = strtotime($closeDate . ' ' . $closeTime);
} else {
	$timeclose = 0;
}

if (!$cmid) {
	$add = 'quiz';
} else {
	$update = 'quiz';
}

if ($timelimit > 0) {
	$timelimitenable = 1;
}

$cm = new stdClass();
if ($cmid > 0) {
	if (! $cm = $DB->get_record('course_modules', array('id' => $cmid))) {
		error("This course module doesn't exist");
	}
	// get quiz instance
	list($thispageurl, $contexts, $cmid, $cm, $quiz, $pagevars) = question_edit_setup('editq', true);
} else {
	$cm->course = $course;
}

// error check
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
	error("This course doesn't exist");
}

require_login();
$context = context_course::instance($course->id);

has_capability('moodle/course:manageactivities', $context);

if (! $module = $DB->get_record("modules", array("name" => "quiz"))) {
	error("This module type doesn't exist");
}

//$cw = get_course_section($section, $course->id);
$cw =  course_create_sections_if_missing($course->id, $section);

if (!course_allowed_module($course, 'quiz')) {
	error("This module has been disabled for this particular course");
}

// create quiz (emon parameters)
//$quiz = new stdClass();
$quiz->course = $course->id;
$quiz->coursemodule = $cmid;
$quiz->section = $section;
$quiz->name = $name;
$quiz->intro = $intro;
$quiz->timeopen = $timeopen;
$quiz->timeclose = $timeclose;
$quiz->attempts = $attempts;
$quiz->timelimit = $timelimit;
$quiz->timelimitenable = $timelimitenable ? 1 : 0;
$quiz->attemptonlast = $attemptonlast;
$quiz->quizpassword = $password;

$quiz->specificfeedbackopen = 1;
$quiz->specificfeedbackimmediately = 1;
$quiz->specificfeedbackduring = null;
$quiz->specificfeedbackclosed = 1;

$quiz->generalfeedbackopen = 1;
$quiz->generalfeedbackimmediately = 1;
$quiz->generalfeedbackduring = null;
$quiz->generalfeedbackclosed = 1;

$quiz->overallfeedbackopen = 1;
$quiz->overallfeedbackimmediately = 1;
$quiz->overallfeedbackduring = null;
$quiz->overallfeedbackclosed = 1;

$quiz->attemptopen = 1;
$quiz->attemptimmediately = 1;
$quiz->attemptduring = 1;
$quiz->attemptclosed = 1;

if (!$isMoodleReview) {
	$quiz->rightansweropen = $isCorrect ? 1 : null;
	$quiz->rightanswerimmediately = $isCorrect ? 1 : null;
	$quiz->rightanswerduring = null;
	$quiz->rightanswerclosed = $isCorrect ? 1 : null;

	$quiz->marksopen = $isPoint ? 1 : null;
	$quiz->marksimmediately = $isPoint ? 1 : null;
	$quiz->marksduring = null;
	$quiz->marksclosed = $isPoint ? 1 : null;

	$quiz->correctnessopen = $isPoint ? 1 : null;
	$quiz->correctnessimmediately = $isPoint ? 1 : null;
	$quiz->correctnessduring = null;
	$quiz->correctnessclosed = $isPoint ? 1 : null;
}

if ($cmid > 0) {
	// 上書き
	//$mod->instance = quiz_update_instance($quiz, $quiz);
	set_coursemodule_visible($cmid, $visible);
	rebuild_course_cache($course->id);

	$json['mod'] = $mod->instance;
	$json['cmid'] = $cmid;

} else {
	// mod generator
	$modulename = 'quiz';
	$cm = new stdClass();
	$cm->course             = $course->id;
	$cm->module             = $DB->get_field('modules', 'id', array('name'=>$modulename));
	$cm->instance           = 0;
	$cm->section            = $section;
	$cm->idnumber           = null;
	$cm->added              = time();
	$cm->id					= $DB->insert_record('course_modules', $cm);
	course_add_cm_to_section($course->id, $cm->id, $section);

	// 新規
	// moodle parameters
	$optionflags = optional_param('optionflags', '1', PARAM_RAW);
	$penaltyscheme = optional_param('penaltyscheme', '1', PARAM_RAW);
	$adaptive = optional_param('adaptive', '0', PARAM_RAW);
	$grademethod = optional_param('grademethod', 1, PARAM_INT);
	$decimalpoints = optional_param('decimalpoints', 2, PARAM_INT);
	$questionsperpage = optional_param('questionsperpage', 0, PARAM_INT);
	$shufflequestions = optional_param('shufflequestions', 0, PARAM_INT);
	$shuffleanswers = optional_param('shuffleanswers', 1, PARAM_INT);
	$questions = optional_param('questions', '', PARAM_RAW);
	$sumgrades = optional_param('sumgrades', '0', PARAM_RAW);
	$grade = optional_param('grade', '100', PARAM_RAW);
	$subnet = optional_param('subnet', '', PARAM_RAW);
	$popup = optional_param('popup', '0', PARAM_RAW);
	$delay1 = optional_param('delay1', '0', PARAM_RAW);
	$delay2 = optional_param('delay2', '0', PARAM_RAW);
	$type = optional_param('type', '', PARAM_ALPHANUM);
	$groupmode = optional_param('groupmode', '0', PARAM_INT);

	$quiz->add = 'quiz';
	$quiz->modulename = 'quiz';
	$quiz->module = $cm->module;
	$quiz->optionflags = $optionflags;
	$quiz->penaltyscheme = $penaltyscheme;
	$quiz->adaptive = $adaptive;
	$quiz->grademethod = $grademethod;
	$quiz->decimalpoints = $decimalpoints;
	$quiz->questionsperpage = $questionsperpage;
	$quiz->shufflequestions = $shufflequestions;
	$quiz->shuffleanswers = $shuffleanswers;
	$quiz->sumgrades = $sumgrades;
	$quiz->grade = $grade;
	$quiz->subnet = $subnet;
	$quiz->popup = $popup;
	$quiz->delay1 = $delay1;
	$quiz->delay2 = $delay2;
	$quiz->type = $type;
	$quiz->groupmode = $groupmode;
	$quiz->visible = $visible;
	$quiz->cmidnumber = '';
	$quiz->gradecat = 1;
	$quiz->groupingid = 0;
	$quiz->groupmembersonly = 0;
	$quiz->boundary_repeats = 3;
	//$quiz->feedbacktext = array('', '', '', '', '');
	//$quiz->feedbackboundaries = array('', '', '', '', '');
	$quiz->feedbacktext = null;
	$quiz->feedbackboundaries = null;
	$quiz->questions = '';
	// Add for 2.x
	$quiz->preferredbehaviour = 'deferredfeedback';

	// create course module
	$quiz->coursemodule = $cm->id;
	$instance = quiz_add_instance($quiz);
	$DB->set_field('course_modules', 'instance', $instance, array('id'=>$quiz->coursemodule));
	$instance = $DB->get_record('quiz', array('id'=>$instance), '*', MUST_EXIST);
	$cm = get_coursemodule_from_id('quiz', $quiz->coursemodule, $instance->course, true, MUST_EXIST);
	context_module::instance($cm->id);
	rebuild_course_cache($course->id);

	add_to_log($course->id, "course", "add mod",
	"../../mod/$cm->modulename/view.php?id=$cm->id",
	"$cm->modulename $cm->instance");
	add_to_log($course->id, 'quiz', "add",
	"view.php?id=$cm->coursemodule",
	"$cm->instance", $cm->id);

}


//grade_regrade_final_grades($course->id);

// json
$emonResult = false;
?>