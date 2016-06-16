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

/**
 * Graphic report class.
 *
 * Retrieve log data, build the JSON object and return it to Chart.js rendering.
 *
 * @package    report_graphic
 * @copyright  2015 onwards Simey Lameze <lameze@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report_graphic {

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
     * @var array array of colors and hover colors for effects.
     */
    protected $colors = ["#FF6384", "#36A2EB", "#FFCE56", "#0d737f", "#6eeb83", "#e4ff1a", "#e8aa14", "#ff5714", "#bce784",
        "#5dd39e", "#348aa7", "#525174", "#513b56", '#001641', '#001D71', '#3CBEA3', '#2EFF9C', '#97FF4A'];
    protected $hovercolors = ["#ffd0da", "#9ad0f5", "#ffe6aa", "#8df3ff", "#18CFE5", "#e4ff1a", "#e8aa14", "#ff5714", "#bce784",
        "#5dd39e", "#348aa7", "#525174", "#513b56", '#394F7F', '#576DAF', '#9CBFB8', '#82BFA2', '#97FF4A'];

    /**
     * Graphic report constructor.
     *
     * Retrieve events log data to be used by other methods.
     *
     * @param int|null $courseid course id.
     */
    public function __construct($courseid = null) {
        ini_set('memory_limit', '2048M');
        $this->courseid = $courseid;
        $this->year = date('Y');

        // Get the log manager.
        $logreader = get_log_manager()->get_readers();
        $logreader = reset($logreader);
        $this->logreader = $logreader;

        // Set the log table.
        $this->logtable = $logreader->get_internal_log_table_name();
    }

    /**
     * Get users that most triggered events by course id.
     *
     * @return stdClass JSON object of the chart.
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
        
        foreach ($result as $userid => $userdata) {
            $username = $userdata->firstname . ' ' . $userdata->lastname;
            $usernames[$userid] = $username;
            $useractivity[$userid] = (int)$userdata->quant;
        }

        $chart = new stdClass();
        $chart->type = 'doughnut';
        $chart->data = new stdClass();
        $chart->data->labels = array_values($usernames);
        $chart->data->datasets = array();
        $chart->data->datasets[0]['data'] = array_values($useractivity);
        $chart->data->datasets[0]['backgroundColor'] = array_values($this->colors);
        $chart->data->datasets[0]['hoverBackgroundColor'] = array_values($this->hovercolors);

        return $chart;
    }

    /**
     * Get most triggered events by course id.
     *
     * @return stdClass JSON object of the chart.
     */
    public function get_most_triggered_events() {
        global $DB;

        $sql = "SELECT l.eventname, COUNT(*) as quant
                  FROM {" . $this->logtable . "} l
                 WHERE l.courseid = ".$this->courseid."
                 GROUP BY l.eventname
                 ORDER BY quant DESC";
        $result = $DB->get_records_sql($sql);

        $events = array();
        $i = 0;
        foreach ($result as $eventdata) {
            $event = $eventdata->eventname;
            $events[$event::get_name()] = (int)$eventdata->quant;
        }
        $chart = new stdClass();
        $chart->type = 'horizontalBar';
        $chart->data = new stdClass();
        $chart->data->datasets = array();
        $chart->data->datasets[$i]['label'] = 'Most triggered events';
        $chart->data->datasets[$i]['data'] = array_values($events);
        $chart->data->datasets[$i]['backgroundColor'] = $this->colors[1];
        $chart->data->datasets[$i]['hoverBackgroundColor'] = $this->hovercolors[1];
        $chart->data->labels = array_keys($events);
        $chart->options = new stdClass();
        $chart->options->gridLines['display'] = 'false';

        return $chart;
    }

    /**
     * Get monthly activity (events by month x users).
     *
     * @return stdClass JSON object of the chart.
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

            // Create a empty array that will be filled after the results of this query.
            //$montharr[$monthabbrev][0] = $monthabbrev;
        }
        $sql .= "FROM {user} u
                ORDER BY u.id";
        $result = $DB->get_records_sql($sql);
        $usersarr = array();
        foreach ($result as $userid => $data) {

            // Faster than use fullname function.
            if (empty($usersarr[$userid])) {
                $usersarr[$userid] = $data->firstname . ' ' . $data->lastname;
            }

            // Fill the array with the quantity of triggered events in the month, by user id.
            $montharr[$userid]['Jan'] = (int)$data->january;
            $montharr[$userid]['Feb'] = (int)$data->february;
            $montharr[$userid]['Mar'] = (int)$data->march;
            $montharr[$userid]['Apr'] = (int)$data->april;
            $montharr[$userid]['May'] = (int)$data->may;
            $montharr[$userid]['Jun'] = (int)$data->june;
            $montharr[$userid]['Jul'] = (int)$data->july;
            $montharr[$userid]['Aug'] = (int)$data->august;
            $montharr[$userid]['Sep'] = (int)$data->september;
            $montharr[$userid]['Oct'] = (int)$data->october;
            $montharr[$userid]['Nov'] = (int)$data->november;
            $montharr[$userid]['Dec'] = (int)$data->december;
        }

        $chart = new stdClass();
        $chart->type = 'line';
        $chart->data = new stdClass();
        $chart->data->labels = array_keys($montharr[1]);
        $chart->data->datasets = array();
        $i = 0;
        foreach ($montharr as $userid => $monthlydata) {
            $chart->data->datasets[$i]['label'] = $usersarr[$userid];
            $chart->data->datasets[$i]['data'] = array_values($monthlydata);
            $chart->data->datasets[$i]['backgroundColor'] = $this->colors[$i];
            $chart->data->datasets[$i]['hoverBackgroundColor'] = $this->hovercolors[$i];
            $chart->data->datasets[$i]['fill'] =  false;
            $chart->data->datasets[$i]['lineTension'] =  0.3;
            $chart->data->datasets[$i]['borderColor'] =  $this->colors[$i];
            $chart->data->datasets[$i]['borderCapStyle'] =  'butt';
            $chart->data->datasets[$i]['borderDash'] =  [];
            $chart->data->datasets[$i]['borderDashOffset'] =  0.0;
            $chart->data->datasets[$i]['borderJoinStyle'] =  'miter';
            $chart->data->datasets[$i]['pointBorderColor'] =  $this->colors[$i];
            $chart->data->datasets[$i]['pointBackgroundColor'] = $this->hovercolors[$i];
            $chart->data->datasets[$i]['pointBorderWidth'] =  5;
            $chart->data->datasets[$i]['pointHoverRadius'] =  5;
            $chart->data->datasets[$i]['pointHoverBackgroundColor'] =  $this->hovercolors[$i];
            $chart->data->datasets[$i]['pointHoverBorderColor'] =  $this->colors[$i];
            $chart->data->datasets[$i]['pointHoverBorderWidth'] =  1;
            $chart->data->datasets[$i]['pointRadius'] =  1;
            $chart->data->datasets[$i]['pointHitRadius'] =  10;
            $i++;
        }

        return $chart;
    }

    /**
     * Create a chart of events triggered by courses.
     *
     * @return stdClass JSON object of the chart.
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

        $courseactivity = array();
        foreach ($result as $courseid => $coursedata) {
            $courseactivity[$coursedata->shortname] = $coursedata->quant;
        }

        $chart = new stdClass();
        $chart->type = 'pie';
        $chart->data = new stdClass();
        $chart->data->labels = array_keys($courseactivity);
        $chart->data->datasets = array();
        $chart->data->datasets[0]['data'] = array_values($courseactivity);
        $chart->data->datasets[0]['backgroundColor'] = array_values($this->colors);
        $chart->data->datasets[0]['hoverBackgroundColor'] = array_values($this->hovercolors);

        return $chart;
    }

    /**
     * Generate chart of the course module events grouping by CRUD.
     *
     * @return stdClass JSON object of the chart.
     */
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
        $i = 0;
        $cmactivity = array('Create', 'Read', 'Update','Delete');
        $chart = new stdClass();
        $chart->type = 'bar';
        $chart->data = new stdClass();
        $chart->data->datasets = array();
        $totals = array();
        foreach ($result as $cmid => $values) {
            if (!empty($cmid)) {
                $coursemodule = get_coursemodule_from_id('',$cmid, $this->courseid);

                if (!empty($coursemodule)) {
                    $cmtitles[] = $coursemodule->name .'('.$coursemodule->modname.')';
                    $cmactivitydata[$i] = array((int)$values->quant_c, (int)$values->quant_r,(int)$values->quant_u, (int)$values->quant_d);
                    $totals['Create'][$i] = (int)$values->quant_c;
                    $totals['Read'][$i] = (int)$values->quant_r;
                    $totals['Update'][$i] = (int)$values->quant_u;
                    $totals['Delete'][$i] = (int)$values->quant_d;
                    $i++;
                }
            }
        }

        $i = 0;
        $colors = array('rgba(151,187,205,0.5)', '#70C1B3', '#FFE066', '#247BA0', '#F25F5C');
        $chart->data->datasets[$i]['type'] = 'line';
        $chart->data->datasets[$i]['data'] = array_values($totals['Read']);
        $chart->data->datasets[$i]['label'] = 'Average';
        $chart->data->datasets[$i]['backgroundColor'] = $colors[$i];;
        $chart->data->datasets[$i]['borderColor'] = 'black';
        $chart->data->datasets[$i]['borderWidth'] = '2';
        $i++;
        foreach ($cmactivity as $activity) {
            $chart->data->datasets[$i]['data'] = array_values($totals[$activity]);
            $chart->data->datasets[$i]['type'] = 'bar';
            $chart->data->datasets[$i]['label'] = $activity;
            $chart->data->datasets[$i]['backgroundColor'] = $colors[$i];
            $chart->data->datasets[$i]['hoverBackgroundColor'] = $colors[$i];
            $i++;
        }

        $chart->data->labels = array_values($cmtitles);
        $chart->options = new stdClass();
        $chart->options->tooltips['mode'] = 'label';
        $chart->options->scales['xAxes'][0]['stacked'] = 'true';
        $chart->options->scales['yAxes'][0]['stacked'] = 'true';
        return $chart;
    }
}
