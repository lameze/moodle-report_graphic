<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Tooltip
 * @package Astroanu\C3jsPHP
 */
class Tooltip implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Show or hide tooltip
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#tooltip-show
     */
    public function setVisibility($visibility = true)
    {
        $this->data['show'] = $visibility;
    }

    /**
     * Set if tooltip is grouped or not for the data points
     *
     * @param bool $grouped
     *
     * @link http://c3js.org/reference.html#tooltip-grouped
     */
    public function setGrouped($grouped = true)
    {
        $this->data['grouped'] = $grouped;
    }

    /**
     * Set format for the title of tooltip
     *
     * @param string $title
     *
     * @link http://c3js.org/reference.html#tooltip-format-title
     */
    public function setFormatTitle($title)
    {
        $this->ensureFormat();
        $this->data['format']['title'] = $title;
    }

    /**
     * Set format for the name of each data in tooltip
     *
     * @param string $name
     *
     * @link http://c3js.org/reference.html#tooltip-format-name
     */
    public function setFormatName($name)
    {
        $this->ensureFormat();
        $this->data['format']['name'] = $name;
    }

    /**
     * Set format for the value of each data in tooltip
     *
     * @param string $value
     *
     * @link http://c3js.org/reference.html#tooltip-format-value
     */
    public function setFormatValue($value)
    {
        $this->ensureFormat();
        $this->data['format']['value'] = $value;
    }

    /**
     * Set custom position for the tooltip
     *
     * @param string $position
     *
     * @link http://c3js.org/reference.html#tooltip-position
     */
    public function setPosition($position)
    {
        $this->data['position'] = $position;
    }

    /**
     * Set custom HTML for the tooltip
     *
     * @param string $contents
     *
     * @link http://c3js.org/reference.html#tooltip-contents
     */
    public function setContents($contents)
    {
        $this->data['contents'] = $contents;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureFormat()
    {
        if (!isset($this->data['format'])) {
            $this->data['format'] = [];
        }
    }
}
