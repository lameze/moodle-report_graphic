<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Data
 * @package Astroanu\C3jsPHP
 */
class Data implements \JsonSerializable
{
    const MIMETYPE_JSON = 'json';

    const TYPE_LINE = 'line';
    const TYPE_SPLINE = 'spline';
    const TYPE_STEP = 'step';
    const TYPE_AREA = 'area';
    const TYPE_AREA_SPLINE = 'area-spline';
    const TYPE_AREA_STEP = 'area-step';
    const TYPE_BAR = 'bar';
    const TYPE_SCATTER = 'scatter';
    const TYPE_PIE = 'pie';
    const TYPE_DONUT = 'donut';
    const TYPE_GAUGE = 'gauge';

    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Set chart data from a JSON or CSV file
     *
     * @param string $url
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-url
     *
     * @see setMimeType()
     */
    public function setUrl($url)
    {
        $this->data['url'] = $url;
        return $this;
    }

    /**
     * Set chart data from JSON
     *
     * @param array $data
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-json
     */
    public function setJson($data)
    {
        $this->data['json'] = $data;
        return $this;
    }

    /**
     * Set chart data as rows
     *
     * @param array $data
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-rows
     */
    public function setRows($data)
    {
        $this->data['rows'] = $data;
        return $this;
    }

    /**
     * Set chart data as columns
     *
     * @param array $data
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-columns
     */
    public function setColumns($data)
    {
        $this->data['columns'] = $data;
        return $this;
    }

    /**
     * Set data URL MIME type
     *
     * @param string $mime Data URL mime type
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-mimeType
     *
     * @see setUrl()
     */
    public function setMimeType($mime = self::MIMETYPE_JSON)
    {
        $this->data['mimeType'] = $mime;
        return $this;
    }

    /**
     * Set which JSON object keys correspond to which data
     *
     * @param array $fields
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-keys
     */
    public function setKeysValue($fields)
    {
        $this->ensureKeys();
        $this->data['keys']['value'] = $fields;
        return $this;
    }

    /**
     * Set keys for x axis when axis x is on category type
     *
     * @param string $field
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-keys
     */
    public function setKeysX($field)
    {
        $this->ensureKeys();
        $this->data['keys']['x'] = $field;
        return $this;
    }

    /**
     * Set key of x values in data
     *
     * @param string $x
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-x
     *
     * @see setXs()
     */
    public function setX($x)
    {
        $this->data['x'] = $x;
        return $this;
    }

    /**
     * Specify the keys of the x values for each data
     *
     * @param array $xs
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-xs
     *
     * @see setX()
     */
    public function setXs($xs)
    {
        $this->data['xs'] = $xs;
        return $this;
    }

    /**
     * Set a format to parse string specifed as x
     *
     * @param string $format
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-xFormat
     */
    public function setXFormat($format = '%Y-%m-%d')
    {
        $this->data['xFormat'] = $format;
        return $this;
    }

    /**
     * Set custom data name
     *
     * @param array $names
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-names
     */
    public function setNames($names)
    {
        $this->data['names'] = $names;
        return $this;
    }

    /**
     * Set custom data class
     *
     * @param array $classes
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-classes
     */
    public function setClasses($classes)
    {
        $this->data['classes'] = $classes;
        return $this;
    }

    /**
     * Set groups for the data for stacking
     *
     * @param array $groups
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-groups
     */
    public function setGroups($groups)
    {
        $this->data['groups'] = $groups;
        return $this;
    }

    /**
     * Set y axis the data related to. y and y2 can be used
     *
     * @param array $axes
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-axes
     */
    public function setAxes($axes)
    {
        $this->data['axes'] = $axes;
        return $this;
    }

    /**
     * Set chart type at once
     *
     * @param TYPE_LINE|TYPE_SPLINE|TYPE_STEP|TYPE_AREA|TYPE_AREA_SPLINE|TYPE_AREA_STEP|TYPE_BAR|TYPE_SCATTER|TYPE_PIE|TYPE_DONUT|TYPE_GAUGE $type
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-type
     *
     * @see setTypes()
     */
    public function setType($type = self::TYPE_LINE)
    {
        $this->data['type'] = $type;
        return $this;
    }

    /**
     * Set chart type for each data
     *
     * @param array $types
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-types
     *
     * @see setType()
     */
    public function setTypes($types)
    {
        $this->data['types'] = $types;
        return $this;
    }

    /**
     * Show labels on each data points
     *
     * @param bool $labels
     *
     * @return Data
     *
     * @see http://c3js.org/reference.html#data-labels
     */
    public function showLabels($labels = false)
    {
        $this->data['labels'] = $labels;
        return $this;
    }

    /**
     * Set formatter function for data labels
     *
     * @param string $format
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-labels-format
     */
    public function setLabelsFormat($format)
    {
        if (!isset($this->data['labels'])) {
            $this->data['labels'] = [];
        }

        $this->data['labels']['format'] = $format;
        return $this;
    }

    /**
     * Define the order of the data
     *
     * @param ORDER_DESC|ORDER_ASC|string|null $order
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-order
     */
    public function setOrder($order = self::ORDER_DESC)
    {
        $this->data['order'] = $order;
        return $this;
    }

    /**
     * Define regions for each data
     *
     * @param array $regions
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-regions
     */
    public function setRegions($regions)
    {
        $this->data['regions'] = $regions;
        return $this;
    }

    /**
     * Set color converter function
     *
     * @param string $color
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-color
     */
    public function setColor($color)
    {
        $this->data['color'] = $color;
        return $this;
    }

    /**
     * Set color for each data
     *
     * @param array $colors
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-colors
     */
    public function setColors($colors)
    {
        $this->data['colors'] = $colors;
        return $this;

    }

    /**
     * Hide each data when the chart appears
     *
     * @param bool|array $hide
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-hide
     */
    public function hide($hide = false)
    {
        $this->data['hide'] = $hide;
        return $this;
    }

    /**
     * Set text displayed when empty data
     *
     * @param string $text
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-empty-label-text
     */
    public function setEmptyLabelText($text = '')
    {
        if (!isset($this->data['empty'])) {
            $this->data['empty'] = [];
        }

        if (!isset($this->data['empty']['label'])) {
            $this->data['empty']['label'] = [];
        }

        $this->data['empty']['label']['text'] = $text;
        return $this;
    }

    /**
     * Set data selection enabled
     *
     * @param bool $selection
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-selection-enabled
     */
    public function enableSelection($selection = false)
    {
        $this->ensureSelection();
        $this->data['selection']['enabled'] = $selection;
        return $this;
    }

    /**
     * Set grouped selection enabled
     *
     * @param bool $grouped
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-selection-grouped
     */
    public function enableGroupedSelection($grouped = false)
    {
        $this->ensureSelection();
        $this->data['selection']['grouped'] = $grouped;
        return $this;
    }

    /**
     * Set multiple data points selection enabled
     *
     * @param bool $multiple
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-selection-multiple
     */
    public function enableMultipleSelection($multiple = false)
    {
        $this->ensureSelection();
        $this->data['selection']['multiple'] = $multiple;
        return $this;
    }

    /**
     * Enable to select data points by dragging
     *
     * @param bool $draggable
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-selection-draggable
     */
    public function enableDraggableSeletion($draggable = false)
    {
        $this->ensureSelection();
        $this->data['selection']['draggable'] = $draggable;
        return $this;
    }

    /**
     * Set a callback for each data point to determine if it's selectable or not
     *
     * @param Callback $callback
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-selection-isselectable
     */
    public function setIsSelectable(Callback $callback)
    {
        $this->ensureSelection();
        $this->data['selection']['isselectable'] = $callback;
        return $this;
    }

    /**
     * Set a callback for click event on each data point
     *
     * @param Callback $callback
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-onclick
     */
    public function setOnClick(Callback $callback)
    {
        $this->data['onclick'] = $callback;
        return $this;
    }

    /**
     * Set a callback for mouseover event on each data point
     *
     * @param Callback $callback
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-onmouseover
     */
    public function setOnMouseOver(Callback $callback)
    {
        $this->data['onmouseover'] = $callback;
        return $this;
    }

    /**
     * Set a callback for mouseout event on each data point
     *
     * @param Callback $callback
     *
     * @return Data
     *
     * @link http://c3js.org/reference.html#data-onmouseout
     */
    public function setOnMouseOut(Callback $callback)
    {
        $this->data['onmousout'] = $callback;
        return $this;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureKeys()
    {
        if (!isset($this->data['keys'])) {
            $this->data['keys'] = [];
        }
    }

    private function ensureSelection()
    {
        if (!isset($this->data['selection'])) {
            $this->data['selection'] = [];
        }
    }
}
