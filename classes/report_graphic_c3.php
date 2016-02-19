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
 * Graphic report
 *
 * @package    report_graphic
 * @copyright  2014 onwards Simey Lameze <lameze@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/report/graphic/lib/gcharts.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Chart.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Data.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Axis.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Grid.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Charts/Pie.php');
require_once($CFG->dirroot . '/report/graphic/lib/c3js-php/src/Charts/Bar.php');
/**
 * Graphic report class.
 *
 * Retrieve log data, organize in the required format and send to google charts API.
 *
 * @package    report_graphic
 * @copyright  2015 onwards Simey Lameze <lameze@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_graphic extends Gcharts {

    /**
     * @var int|null the course id.
     */
    protected $courseid;
    /**
     * @var int the current year.
     */
    protected $year;
    /**
     * @var \core\log\sql_SELECT_reader instance.
     */
    protected $logreader;
    /**
     * @var  string Log reader table name.
     */
    protected $logtable;

    /**
     * @var string $period period of time.
     */
    protected $period;

    protected $logtablealias;

    /**
     * Graphic report constructor.
     *
     * Retrieve events log data to be used by other methods.
     *
     * @param int|null $courseid course id.
     */
    public function __construct($courseid = null, $period = '1d') {
        ini_set('memory_limit', '2048M');
        $this->courseid = $courseid;
        $this->period = $period;
        $this->year = 2015;

        // Get the log manager.
        $logreader = get_log_manager()->get_readers();
        $logreader = reset($logreader);
        $this->logreader = $logreader;

        // Set the log table.
        $this->logtable = $logreader->get_internal_log_table_name();
        $this->logtablealias = "l";
    }

//    protected sql_calc_period() {
//        $l = $this->logtablealias;
//        $timecreated = "$l.timecreated";
////        switch ($this->period) {
////            case '1d':
////                $sql = "$timecreated ";
////            case '1w':
////            case '2w':
////            case '1m':
////            case '3m':
////            case '6m':
////            case '1y':
////            default:
////
////        }
//    }

    /**
     * Get users that most triggered events by course id.
     *
     * @return string google charts data.
     */
    public function get_most_active_users() {
        global $DB;

        $sql = "SELECT l.relateduserid, u.firstname, u.lastname, COUNT(*) as quant
                  FROM {" . $this->logtable . "} l
            INNER JOIN {user} u ON u.id = l.relateduserid
                 WHERE l.courseid = " . $this->courseid . "
              GROUP BY l.relateduserid, u.firstname, u.lastname
              ORDER BY quant DESC";
        $result = $DB->get_records_sql($sql);

        $chart = new Astroanu\C3jsPHP\Chart();
        $data = new Astroanu\C3jsPHP\Data();
        foreach ($result as $userdata) {
            $username = $userdata->firstname . ' ' . $userdata->lastname;
            $useractivity[] = [$username, (int)$userdata->quant];
        }

        $data->setType(Astroanu\C3jsPHP\Data::TYPE_PIE);
        $data->setColumns(array_values($useractivity));
        $chart->bindTo('#chart');
        $chart->setData($data);

        return $chart;


    }
    /**
     * Get most triggered events by course id.
     *
     * @return string google charts data.
     */
    public function get_most_triggered_events() {
        global $DB;

        $sql = "SELECT l.eventname, COUNT(*) as quant
                  FROM {" . $this->logtable . "} l
                 WHERE l.courseid = ".$this->courseid."
                 GROUP BY l.eventname
                 ORDER BY quant DESC";
        $result = $DB->get_records_sql($sql);


        $i = 1;
        foreach ($result as $eventdata) {
            $event = $eventdata->eventname;
            $events[$i] = array($event::get_name(), (int)$eventdata->quant);
            $i++;
        }
        $chart = new Astroanu\C3jsPHP\Chart();
        $data = new Astroanu\C3jsPHP\Data();
        $bar = new Astroanu\C3jsPHP\Charts\Bar();
        $data->setType(Astroanu\C3jsPHP\Data::TYPE_BAR);
        $bar->setWidthRatio('0:1');
        $data->setColumns(array_values(array_slice($events,0,10)));
        $chart->bindTo('#chart_most_triggered');
        $chart->setData($data);
        $chart->setBar($bar);
        return $chart;
    }

    /**
     * Get monthly activity (events by month x users).
     *
     * @return string the google charts data.
     */
    public function get_monthly_user_activity() {
        global $DB;

        $courseid = $this->courseid;
        $months = cal_info(0);
        $year = $this->year;
        $montharr = array();

        // Build the query to get how many events each user has triggered grouping by month.
        // This piece of code has few hacks to deal with cross-db issues but certainly can be improved.
        // Also create required arrays of months and etc.
        $sql = "SELECT u.id, u.firstname, u.lastname, ";
        for ($m = 1; $m <= count($months['abbrevmonths']); $m++) {

            // Get and format month name and number.
            $monthname = $months['months'][$m];
            $monthabbrev = $months['abbrevmonths'][$m];
            $month = sprintf("%02d", $m);

            // Get the first and the last day of the month.
            $ymdfrom = "$year-$month-01";
            $ymdto = date('Y-m-t', strtotime($ymdfrom));

            // Convert to timestamp.
            $date = new DateTime($ymdfrom);
            $datefrom = $date->getTimestamp();
            $date = new DateTime($ymdto);
            $dateto = $date->getTimestamp();

            // Get the quantity of triggered events for each month.
            $sql .= "(SELECT COUNT(*) AS quant
                        FROM {" . $this->logtable . "} l
                       WHERE l.courseid = $courseid
                         AND timecreated >= $datefrom
                         AND timecreated < $dateto
                         AND u.id = l.userid
                     ) AS $monthname";

            // Add comma after the month name.
            $sql .= ($m < 12 ? ',' : ' ');
        }
        $sql .= "FROM {user} u
                ORDER BY u.id";
        $result = $DB->get_records_sql($sql);

        foreach ($result as $userid => $data) {
            // Organize the data in the required format of the chart.
            $montharr[$userid][0] = $data->firstname . ' ' . $data->lastname;
            $montharr[$userid][1] = (int)$data->january;
            $montharr[$userid][2] = (int)$data->february;
            $montharr[$userid][3] = (int)$data->march;
            $montharr[$userid][4] = (int)$data->april;
            $montharr[$userid][5] = (int)$data->may;
            $montharr[$userid][6] = (int)$data->june;
            $montharr[$userid][7] = (int)$data->july;
            $montharr[$userid][8] = (int)$data->august;
            $montharr[$userid][9] = (int)$data->september;
            $montharr[$userid][10] = (int)$data->october;
            $montharr[$userid][11] = (int)$data->november;
            $montharr[$userid][12] = (int)$data->december;
        }

        $chart = new Astroanu\C3jsPHP\Chart();
        $data = new Astroanu\C3jsPHP\Data();
        $axis = new Astroanu\C3jsPHP\Axis();
        $data->setColumns(array_values($montharr));
        $axis->setXType('category');
        $axis->setXCategories(array_values($months['abbrevmonths']));
        $chart->bindTo('#chart_events_monthly');
        $chart->setData($data);
        $chart->setAxis($axis);
        return $chart;
    }

    /**
     * Create a chart of events triggered by courses.
     *
     * @return string the google charts data.
     */
    public function get_courses_activity() {
        global $DB;

        $sql = "SELECT l.courseid, c.shortname, COUNT(*) AS quant
                  FROM {" . $this->logtable . "} l
            INNER JOIN mdl_course c ON c.id = l.courseid
                 WHERE l.courseid = c.id
              GROUP BY l.courseid, c.shortname
              ORDER BY l.courseid";
        $result = $DB->get_records_sql($sql);

        // Format the data to google charts.
        $i = 1;
        $courseactivity[0] = array(get_string('course'), get_string('percentage', 'report_graphic'));
        foreach ($result as $courseid => $coursedata) {
            $courseactivity[$i] = array($coursedata->shortname, (int)$coursedata->quant);
            $i++;
        }

        $this->load(array('graphic_type' => 'PieChart'));
        $this->set_options(array('title' => get_string('coursesactivity', 'report_graphic')));

        return $this->generate($courseactivity);
    }

    public function get_events_course_module() {
        global $DB;

        $sql = "SELECT DISTINCT l.contextinstanceid,
                (SELECT COUNT(*) FROM mdl_logstore_standard_log lc WHERE lc.crud = 'c' and lc.courseid = l.courseid and lc.contextinstanceid = l.contextinstanceid) AS quant_c,
                (SELECT COUNT(*) FROM mdl_logstore_standard_log lr WHERE lr.crud = 'r' and lr.courseid = l.courseid and lr.contextinstanceid = l.contextinstanceid) AS quant_r,
                (SELECT COUNT(*) FROM mdl_logstore_standard_log lu WHERE lu.crud = 'u' and lu.courseid = l.courseid and lu.contextinstanceid = l.contextinstanceid) AS quant_u,
                (SELECT COUNT(*) FROM mdl_logstore_standard_log ld WHERE ld.crud = 'd' and ld.courseid = l.courseid and ld.contextinstanceid = l.contextinstanceid) AS quant_d,
                COUNT(*) AS total
                FROM {" . $this->logtable . "} l
                INNER JOIN mdl_course c ON c.id = l.courseid
                WHERE l.courseid = :courseid AND l.contextinstanceid IS NOT NULL
                GROUP BY l.contextinstanceid, quant_c,quant_r,quant_u,quant_d
                ORDER BY total DESC";
        $result = $DB->get_records_sql($sql, array('courseid' => $this->courseid));

        // Format the data to google charts.
        $i = 1;
        //$cmactivity[0] = array('Module', 'Create', 'Read', 'Update','Delete');
        foreach ($result as $cmid => $values) {
            if (!empty($cmid)) {
                $coursemodule = get_coursemodule_from_id('',$cmid, $this->courseid);

                if (!empty($coursemodule)) {
                    $title = $coursemodule->name .'('.$coursemodule->modname.')';
                    $cmactivity[$i] = array($title, (int)$values->quant_c, (int)$values->quant_r,(int)$values->quant_u, (int)$values->quant_d);
                    $i++;
                }
            }
        }

        $chart = new Astroanu\C3jsPHP\Chart();
        $data = new Astroanu\C3jsPHP\Data();
        //$axis = new Astroanu\C3jsPHP\Axis();
//        $grid = new Astroanu\C3jsPHP\Grid();
//        $grid->setYLines(['value' => 0]);

        $data->setColumns(array_values($cmactivity));
        $data->setType(Astroanu\C3jsPHP\Data::TYPE_BAR);
        $data->setGroups(array_values(array(array(1, 2, 3,4))));
        //$axis->setXType('category');
        //$axis->setXCategories(array_values($months['abbrevmonths']));
        $chart->bindTo('#chart_events_cm');
        $chart->setData($data);
//$chart->setGrid($grid);
        //$chart->setAxis($axis);
        //echo $chart->render();
//        $chart = "var chart = c3.generate({
//    data: {
//            columns: [
//                ['data1', -30, 200, 200, 400, -150, 250],
//                ['data2', 130, 100, -100, 200, -150, 50],
//                ['data3', -230, 200, 200, -300, 250, 250]
//            ],
//        type: 'bar',
//        groups: [
//                ['data1', 'data2']
//            ]
//    },
//    grid: {
//            y: {
//                lines: [{value:0}]
//        }
//        }
//});";
        return $chart;


//        var chart = c3.generate({
//    data: {
//            columns: [
//                ['data1', -30, 200, 200, 400, -150, 250],
//                ['data2', 130, 100, -100, 200, -150, 50],
//                ['data3', -230, 200, 200, -300, 250, 250]
//            ],
//        type: 'bar',
//        groups: [
//                ['data1', 'data2']
//            ]
//    },
    }
}
