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
 * Graphic report renderer.
 *
 * @package    report_graphic
 * @copyright  2015 Simey Lameze <simey@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Graphic report renderer class.
 *
 * @package    report_graphic
 * @copyright  2015 Simey Lameze <simey@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_graphic_renderer extends plugin_renderer_base {

    /** @var report_graphic_renderable instance of report graphic renderable. */
    protected $renderable;

    /**
     * Renderer constructor.
     *
     * @param report_graphic_renderable $renderable graphic report renderable instance.
     */
    protected function render_report_graphic(report_graphic_renderable $renderable) {
        $this->renderable = $renderable;
        $this->report_selector_form();
    }
    
    /**
     * This function is used to generate and display course filter.
     *
     */
    public function report_selector_form() {
        global $CFG;

        require_once($CFG->dirroot . '/report/graphic/filter_form.php');


        $renderable = $this->renderable;

        //$courses = $renderable->get_course_list();
        //$selectedcourseid = empty($renderable->course) ? 0 : $renderable->course->id;
        //$users = $renderable->get_user_list();
        //$period = $renderable->get_period_list();
        //$selecteduserid = empty($renderable->user) ? 0 : $renderable->user->id;
        echo html_writer::start_tag('form', array('class' => 'logselecform', 'action' => 'course.php', 'method' => 'get'));
        echo html_writer::start_div();
        $mform = new filter_form;
        $mform->display();
        echo html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('generate', 'report_graphic')));
        echo html_writer::end_div();
        echo html_writer::end_tag('form');
    }
    /**
     * Display course related graphic reports.
     */
    public function report_generate_charts() {
        $renderable = $this->renderable;
        $renderable->get_charts_data();


        $this->get_chart('Events by user', 'eventsbyuser', $renderable->mostactiveusers);
        $this->get_chart('Most triggered events', 'mosttriggered', $renderable->mosttriggeredevents);
        $this->get_chart('Events by course module', 'eventsbycm', $renderable->eventsbycoursemodule);
    }

    /**
     * Get the JSON from script tag and fill the canvas.
     *
     * @param string $title the chart title
     * @param string $id id of the script tag
     * @param mixed $chartdata the report_graphic data.
     */
    protected function get_chart($title, $id, $chartdata) {
        // Header.
        echo html_writer::start_tag('h4') . $title . html_writer::end_tag('h4');
        echo html_writer::empty_tag('hr');
        //echo html_writer::start_tag('div', array('style' => 'width: 70%'));
        echo '<div style="width: 70%">';
        $canvasid = $id . 'canvas';
        echo html_writer::start_tag('canvas', array('id' => $canvasid)) . html_writer::end_tag('canvas');
        //echo html_writer::end_tag('div');
        echo '</div>';
        // Script tag that contains the
        echo html_writer::start_tag('script', array('type' => 'data-chart', 'id' => $id));
        echo json_encode($chartdata);
        echo $this->get_chart_js($id);
        echo html_writer::end_tag('script');
        echo html_writer::empty_tag('br ');
    }
    
    /**
     * Display site related graphic reports.
     */
    public function report_course_activity_chart() {
        $this->renderable->get_courses_activity();
        echo $this->renderable->mostactivecourses;
    }

    /**
     * Call Chart.js wrapper.
     *
     * @param string $scriptid the script tag id.
     */
    public function get_chart_js($scriptid) {
        $this->page->requires->js_call_amd('report_graphic/chart', 'init', array($scriptid));
    }
}
