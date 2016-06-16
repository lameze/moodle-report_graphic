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
 * Module to navigation between users in a course.
 *
 * @package    report_graphic
 * @copyright  2016 Simey Lameze
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {

    /**
     * CourseNavigation
     *
     * @param {String} The selector of the courses element.
     * @param {String} The base url for the page (no params).
     * @param {Number} The course id
     */
    var CourseNavigation = function(courseSelector, baseUrl, courseId) {
        this._baseUrl = baseUrl;
        this._courseId = courseId;

        $(courseSelector).on('change', this._courseChanged.bind(this));
    };

    /**
     * The course was changed in the select list.
     *
     * @method _userChanged
     * @param {Event} e
     */
    CourseNavigation.prototype._courseChanged = function(e) {
        if (this._ignoreFirstUser) {
            this._ignoreFirstUser = false;
            return;
        }

        var newCourseId = $(e.target).val();
        var queryStr = '?id=' + newCourseId;
        document.location = this._baseUrl + queryStr;
    };

    /** @type {Number} The id of the course. */
    CourseNavigation.prototype._courseId = null;
    /** @type {String} Plugin base url. */
    CourseNavigation.prototype._baseUrl = null;


    return /** @alias module:report_competency/user_course_navigation */ CourseNavigation;

});
