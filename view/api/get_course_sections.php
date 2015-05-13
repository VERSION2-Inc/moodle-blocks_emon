<?php
	// library
    require_once(dirname(__FILE__) . "/../../../../config.php");
	include_once($CFG->dirroot . '/course/lib.php');
	
	// parameters
	$courseid = required_param('courseid', PARAM_INT);
	
	// error check
	if (empty($courseid)) {
		error('Must specify course id, short name or idnumber');
	}
    if (! ($course = $DB->get_record('course', array('id' => $courseid))) ) {
        error('Invalid course id');
    }
    context_helper::preload_course($course->id);
    if (!$context = context_course::instance($course->id)) {
        error('nocontext');
    }
    require_login($course);
    
	// capability check
	if (!has_capability('moodle/course:manageactivities', $context)) {
		error('You do not have capability of this course');
	}
    
	// get course sections
    $sections = get_fast_modinfo($course)->get_section_info_all();
    
    foreach ($sections as $number => $section) {
    	$json['sections'][$section->section] = $section->id;
    }
    ksort($json['sections']);
    unset($json['sections'][0]);
	return $json;
?>