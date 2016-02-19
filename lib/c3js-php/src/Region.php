<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Region
 * @package Astroanu\C3jsPHP
 *
 * Show rectangles inside the chart
 *
 * @link http://c3js.org/reference.html#regions
 */
class Region implements \JsonSerializable
{
    const AXIS_X = 'x';
    const AXIS_Y = 'y';
    const AXIS_Y2 = 'y2';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param AXIS_X|AXIS_Y|AXIS_Y2 $axis
     */
    public function setAxis($axis)
    {
        $this->data['axis'] = $axis;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->data['x'] = $x;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->data['y'] = $y;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->data['class'] = $class;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }
}
