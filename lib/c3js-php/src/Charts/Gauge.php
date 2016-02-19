<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Gauge
 * @package Astroanu\C3jsPHP\Charts
 */
class Gauge implements \JsonSerializable
{
    const TYPE_TIMESERIES = 'timeseries';
    const TYPE_CATEGORY = 'category';
    const TYPE_INDEXED = 'indexed';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Show or hide label on gauge
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#gauge-label-show
     */
    public function setLabelVisibility($visibility = true)
    {
       $this->ensureLabel();
        $this->data['label']['show'] = $visibility;
    }

    /**
     * Set formatter for the label on gauge
     *
     * @param string $format
     *
     * @link http://c3js.org/reference.html#gauge-label-format
     */
    public function setLabelFormat($format)
    {
        $this->ensureLabel();
        $this->data['label']['format'] = $format;
    }


    /**
     * Enable or disable expanding gauge
     *
     * @param bool $expand
     *
     * @link http://c3js.org/reference.html#gauge-expand
     */
    public function setExpand($expand = true)
    {
        $this->data['expand'] = $expand;
    }

    /**
     * Set min value of the gauge
     *
     * @param int $min
     *
     * @link http://c3js.org/reference.html#gauge-min
     */
    public function setMin($min = 0)
    {
        $this->data['min'] = $min;
    }

    /**
     * Set max value of the gauge
     *
     * @param int $max
     *
     * @link http://c3js.org/reference.html#gauge-max
     */
    public function setMax($max = 100)
    {
        $this->data['max'] = $max;
    }

    /**
     * Set units of the gauge
     *
     * @param string $units
     *
     * @link http://c3js.org/reference.html#gauge-units
     */
    public function setUnits($units)
    {
        $this->data['units'] = $units;
    }

    /**
     * Set width of gauge chart
     * @param int $width
     *
     * @link http://c3js.org/reference.html#gauge-width
     */
    public function setWidth($width)
    {
        $this->data['width'] = $width;
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
