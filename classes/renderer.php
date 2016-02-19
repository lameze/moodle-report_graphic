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
        //$this->page->requires->css('/report/graphic/lib/c3/c3.css');
    }

    /**
     * This function is used to generate and display course filter.
     *
     */
    public function report_selector_form() {
        $renderable = $this->renderable;
        $courses = $renderable->get_course_list();
        $selectedcourseid = empty($renderable->course) ? 0 : $renderable->course->id;
        //$users = $renderable->get_user_list();
        $period = $renderable->get_period_list();
        $selecteduserid = empty($renderable->user) ? 0 : $renderable->user->id;
        echo html_writer::start_tag('form', array('class' => 'logselecform', 'action' => 'course.php', 'method' => 'get'));
        echo html_writer::start_div();
        echo html_writer::label(get_string('selectacourse'), 'courseid', false);
        echo html_writer::select($courses, "id", $selectedcourseid, null, array('id' => 'courseid'));
        echo html_writer::label('Select a period', 'period', false);
        echo html_writer::select($period, "period", null, false);
        echo html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('generate', 'report_graphic')));
        echo html_writer::end_div();
        echo html_writer::end_tag('form');
    }
    /**
     * Display course related graphic reports.
     */
    public function report_generate_charts() {
        $renderable = $this->renderable;
        echo $renderable->get_gcharts_data();
        echo "<h5>Events by user</h5>";
        echo "<div id=\"chart\"></div>";
        echo "<br /><hr /><br />";
        echo "<h5>Most triggered events</h5>";
        echo "<div id=\"chart_most_triggered\"></div>";
        echo "<br /><hr /><br />";
        echo "<h5>Events by month</h5>";
        echo "<div id=\"chart_events_monthly\"></div>";
        echo "<br /><hr /><br />";
        echo "<div id=\"chart_events_cm\"></div>";
        echo html_writer::start_tag('script', array('type' => 'text/javascript'));
        $this->get_chart_json($renderable->mostactiveusers);
        $this->get_chart_json($renderable->mosttriggeredevents);
        $this->get_chart_json($renderable->activitybyperiod);
        $this->get_chart_json($renderable->eventsbycoursemodule);
        echo html_writer::end_tag('script');
        //echo $renderable->usersgrades;
    }

    /**
     * Display site related graphic reports.
     */
    public function report_course_activity_chart() {
        $this->renderable->get_courses_activity();
        echo $this->renderable->mostactivecourses;
    }

    public function get_chart_json($chartjson) {
        $this->page->requires->js_amd_inline("require([\"report_graphic/c3loader\"], function(c3) { ".$chartjson->render('chart', true)." });");
    }
}
