Events Graphic Report
=====================

The Events Graphic Report is a Moodle plugin to add a nice chart reports to monitor events happening on your moodle.
It retrieve the log data and generate charts using google charts API.

Features
--------

- Course activity - Display a pie chart with the quantity of events grouping by course.

- User activity (Events by user) - displays a pie chart of the percentage and quantity of events triggered events by user in a given course.

- Most triggered events - displays a bar chart with the quantity of events, grouping by event name.

- Events by month - displays a line chart with  the quantity of events that each user triggered monthly in the current year.

- Filter by course - you can select a specific course to browse the event graphic reports.


Instalation
-----------
- Extract the content into your {Moodle root directory}/report/graphic.
- Go to Notifications page.
- Install the plugin and that's it.

Requirements
------------
- Moodle 2.7 onwards.
- Internet connection - Unfortunately Google terms of service doesn't allow download of the Google Charts API for offline use. In order to generate graphics you must have access to https://www.google.com/jsapi at least. For more information see: 
(https://developers.google.com/chart/interactive/faq)

TODO
----
This plugin is in ALPHA state of development, so there are more improvements to come:

- More filters - add more filtering option such: year, period and type of chart.
- User level - more charts for a given user such activity, events and modules.
- Performance - Make the existing code more efficient and fast. 

License
-------

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
