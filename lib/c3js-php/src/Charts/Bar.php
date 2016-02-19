<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Bar
 * @package Astroanu\C3jsPHP\Charts
 */
class Bar implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Change the width of bar chart
     *
     * @param int $width
     *
     * @link http://c3js.org/reference.html#bar-width
     */
    public function setWidth($width)
    {
        $this->data['width'] = $width;
    }

    /**
     * Change the width of bar chart by ratio
     *
     * @param float $ratio
     *
     * @link http://c3js.org/reference.html#bar-width-ratio
     */
    public function setWidthRatio($ratio)
    {
        $this->data['ratio'] = $ratio;
    }

    /**
     * Set if min or max value will be 0 on bar chart
     *
     * @param bool $zerobased
     *
     * @link http://c3js.org/reference.html#bar-zerobased
     */
    public function setZerobased($zerobased = true)
    {
        $this->data['zerobased'] = $zerobased;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }
}
