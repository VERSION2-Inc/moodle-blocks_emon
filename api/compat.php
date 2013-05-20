<?php

/**
 * コース内のセクション情報を取得する。
 *
 * @param
 */
function getSectionInfo($courseId)
{
	global $CFG,$DB;

	$rs = $DB->get_records('course_sections', array('course'=>$courseId));
	$sections = array();
	
	foreach ($rs as $table) {
		array_push($sections, $table->name);
	}
	return $sections;
}

/**
 * Creates missing course section(s) and rebuilds course cache
 *
 * @param int|stdClass $courseorid course id or course object
 * @param int|array $sections list of relative section numbers to create
 * @return bool if there were any sections created
 */
function course_create_sections_if_missing($courseorid, $sections)
{
    // @see /course/lib.php of Moodle 2.4
    global $DB;
    if (!is_array($sections)) {
        $sections = array($sections);
    }
    $existing = array_keys(getSectionInfo($courseorid));
    
    if (is_object($courseorid)) {
        $courseorid = $courseorid->id;
    }
    $coursechanged = false;
    foreach ($sections as $sectionnum) {
        if (!in_array($sectionnum, $existing)) {
            $cw = new stdClass();
            $cw->course   = $courseorid;
            $cw->section  = $sectionnum;
            $cw->summary  = '';
            $cw->summaryformat = FORMAT_HTML;
            $cw->sequence = '';
            $id = $DB->insert_record("course_sections", $cw);
            $coursechanged = true;
        }
    }
    if ($coursechanged) {
        rebuild_course_cache($courseorid, true);
    }
    
    return $coursechanged;
}

/**
 * Adds an existing module to the section
 *
 * Updates both tables {course_sections} and {course_modules}
 *
 * @param int|stdClass $courseorid course id or course object
 * @param int $cmid id of the module already existing in course_modules table
 * @param int $sectionnum relative number of the section (field course_sections.section)
 *     If section does not exist it will be created
 * @param int|stdClass $beforemod id or object with field id corresponding to the module
 *     before which the module needs to be included. Null for inserting in the
 *     end of the section
 * @return int The course_sections ID where the module is inserted
 */
function course_add_cm_to_section($courseorid, $cmid, $sectionnum, $beforemod = null)
{
    // @see /course/lib.php
    global $DB, $COURSE;
    if (is_object($beforemod)) {
        $beforemod = $beforemod->id;
    }
    if (is_object($courseorid)) {
        $courseid = $courseorid->id;
    } else {
        $courseid = $courseorid;
    }
    course_create_sections_if_missing($courseorid, $sectionnum);
    // Do not try to use modinfo here, there is no guarantee it is valid!
    $section = $DB->get_record('course_sections', array('course'=>$courseid, 'section'=>$sectionnum), '*', MUST_EXIST);
    $modarray = explode(",", trim($section->sequence));
    if (empty($section->sequence)) {
        $newsequence = "$cmid";
    } else if ($beforemod && ($key = array_keys($modarray, $beforemod))) {
        $insertarray = array($cmid, $beforemod);
        array_splice($modarray, $key[0], 1, $insertarray);
        $newsequence = implode(",", $modarray);
    } else {
        $newsequence = "$section->sequence,$cmid";
    }
    $DB->set_field("course_sections", "sequence", $newsequence, array("id" => $section->id));
    $DB->set_field('course_modules', 'section', $section->id, array('id' => $cmid));
    if (is_object($courseorid)) {
        rebuild_course_cache($courseorid->id, true);
    } else {
        rebuild_course_cache($courseorid, true);
    }
    
    return $section->id;     // Return course_sections ID that was used.
    
}
