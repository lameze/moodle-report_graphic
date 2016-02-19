<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Donut
 * @package Astroanu\C3jsPHP\Charts
 */
class Donut implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Show or hide label on each donut piece
     *
     * @param $visibility
     *
     * @link http://c3js.org/reference.html#donut-label-show
     */
    public function setLabelVisibility($visibility = true)
    {
        $this->ensureLabel();
        $this->data['label']['show'] = $visibility;
    }

    /**
     * Set formatter for the label on each donut piece
     *
     * @param string $format
     *
     * @link http://c3js.org/reference.html#donut-label-format
     */
    public function setLabelFormat($format)
    {
        $this->ensureLabel();
        $this->data['label']['format'] = $format;
    }

    /**
     * Set threshold to show/hide labels
     *
     * @param float $threshold
     *
     * @link http://c3js.org/reference.html#donut-label-threshold
     */
    public function setLabelThreshold($threshold = 0.05)
    {
        $this->ensureLabel();
        $this->data['label']['threshold'] = $threshold;
    }

    /**
     * Enable or disable expanding donut pieces
     *
     * @param bool $expand
     *
     * @link http://c3js.org/reference.html#donut-expand
     */
    public function setExpand($expand = true)
    {
        $this->data['expand'] = $expand;
    }

    /**
     * Set width of donut chart
     *
     * @param int $width
     *
     * @link http://c3js.org/reference.html#donut-width
     */
    public function setWidth($width)
    {
        $this->data['width'] = $width;
    }

    /**
     * Set title of donut chart
     *
     * @param string $title
     *
     * @link http://c3js.org/reference.html#donut-title
     */
    public function setTitle($title = '')
    {
        $this->data['title'] = $title;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureLabel()
    {
        if (!isset($this->data['label'])) {
            $this->data['label'] = [];
        }
    }
}
