<?php
	// library
	include_once($CFG->dirroot . '/course/lib.php');
	
	// parameters
	$courseid = required_param('courseid', PARAM_INT);
	
	// error check
	if (empty($courseid)) {
		error('Must specify course id, short name or idnumber');
	}
    if (! ($course = get_record('course', 'id', $courseid)) ) {
        error('Invalid course id');
    }
    preload_course_contexts($course->id);
    if (!$context = get_context_instance(CONTEXT_COURSE, $course->id)) {
        error('nocontext');
    }
    require_login($course);
    
	// capability check
	if (!has_capability('moodle/course:manageactivities', $context)) {
		error('You do not have capability of this course');
	}
    
	// get course sections
    $sections = get_all_sections($course->id);
    
    foreach ($sections as $number => $section) {
    	$json['sections'][$number] = $section->id;
    }
	return $json;
?>