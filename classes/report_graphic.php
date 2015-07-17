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
     * Graphic report constructor.
     *
     * Retrieve events log data to be used by other methods.
     *
     * @param int|null $courseid course id.
     */
    public function __construct($courseid = null) {
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

        // Graphic header, must be always the first element of the array.
        $events[0] = array(get_string('event', 'report_graphic'), get_string('quantity', 'report_graphic'));

        $i = 1;
        foreach ($result as $eventdata) {
            $event = $eventdata->eventname;
            $events[$i] = array($event::get_name(), (int)$eventdata->quant);
            $i++;
        }

        $this->load(array('graphic_type' => 'ColumnChart'));
        $this->set_options(array('title' => get_string('eventsmosttriggered', 'report_graphic')));

        return $this->generate($events);
    }

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

        // Graphic header, must be the first element of the array.
        $useractivity[0] = array(get_string('user'), get_string('percentage', 'report_graphic'));

        // Organize the data in the required format.
        $i = 1;
        foreach ($result as $userdata) {
            $username = $userdata->firstname . ' ' . $userdata->lastname;
            $useractivity[$i] = array($username, (int)$userdata->quant);
            $i++;
        }

        $this->load(array('graphic_type' => 'PieChart'));
        $this->set_options(array('title' => get_string('usersactivity', 'report_graphic')));

        return $this->generate($useractivity);
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

            // Create a empty array that will be filled after the results of this query.
            $montharr[$monthabbrev][0] = $monthabbrev;
        }
        $sql .= "FROM {user} u
                ORDER BY u.id";
        $result = $DB->get_records_sql($sql);

        $usersarr[0] = 'Month';
        foreach ($result as $userid => $data) {

            // Faster than use fullname function.
            if (empty($usersarr[$userid])) {
                $usersarr[$userid] = $data->firstname . ' ' . $data->lastname;
            }

            // Fill the array with the quantity of triggered events in the month, by user id.
            $montharr['Jan'][$userid] = (int)$data->january;
            $montharr['Feb'][$userid] = (int)$data->february;
            $montharr['Mar'][$userid] = (int)$data->march;
            $montharr['Apr'][$userid] = (int)$data->april;
            $montharr['May'][$userid] = (int)$data->may;
            $montharr['Jun'][$userid] = (int)$data->june;
            $montharr['Jul'][$userid] = (int)$data->july;
            $montharr['Aug'][$userid] = (int)$data->august;
            $montharr['Sep'][$userid] = (int)$data->september;
            $montharr['Oct'][$userid] = (int)$data->october;
            $montharr['Nov'][$userid] = (int)$data->november;
            $montharr['Dec'][$userid] = (int)$data->december;
        }

        // The header of the report, must be all users
        $final = array(0 => $usersarr);

        // Organize the data in the required format of the chart.
        for ($m = 1; $m <= count($months['abbrevmonths']); $m++) {
            $monthabbrev = $months['abbrevmonths'][$m];
            $final[$m] = $montharr[$monthabbrev];
        }

        $this->load(array('graphic_type' => 'linechart'));
        $this->set_options(array('title' => get_string('eventsbymonth', 'report_graphic', $year), 'curveType' => 'function'));

        return $this->generate($final);
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
}
