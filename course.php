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

/**
 * Display the course related events charts.
 *
 * @package    report_graphic
 * @copyright  2015 Simey Lameze <simey@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
//require_once($CFG->dirroot . '/course/lib.php');
//require_once($CFG->dirroot.'/grade/report/grader/lib.php');
//require_once($CFG->dirroot.'/grade/lib.php');
//require_once($CFG->libdir.'/gradelib.php');
require_login();



$courseid = required_param('id', 'int');
if (!$course = get_course($courseid)) {
    print_error('nocourseid');
}
$context = context_course::instance($courseid);
require_capability('report/graphic:view', $context);
admin_externalpage_setup('report_graphic');
$actionurl = new moodle_url('/report/graphic/course.php');
$PAGE->set_context($context);
$PAGE->set_url('/report/graphic/course.php', array('courseid' => $courseid));
$PAGE->set_title(get_string('pluginname', 'report_graphic'));
$PAGE->set_heading(get_string('pluginname', 'report_graphic'));
$PAGE->set_pagelayout('report');
echo $OUTPUT->header();
$page = 0;
//$gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'grader', 'courseid'=>$courseid, 'page'=>$page));
//$report = new grade_report_grader($courseid, $gpr, $context);
//$report->load_users();
//$report->load_final_grades();
//print_object($report->grades);
$renderable = new report_graphic_renderable($course);
$renderer = $PAGE->get_renderer('report_graphic');
echo $renderer->render($renderable);
echo $renderer->report_generate_charts();

echo $OUTPUT->footer();
