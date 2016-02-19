<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Subchart
 * @package Astroanu\C3jsPHP
 *
 * Experimental
 */
class Subchart implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Show sub chart on the bottom of the chart
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#subchart-show
     */
    public function setVisibility($visibility = false)
    {
        $this->data['show'] = $visibility;
    }

    /**
     * Change the height of the subchart
     *
     * @param int $height
     *
     * @link http://c3js.org/reference.html#subchart-size-height
     */
    public function setSizeHeight($height)
    {
        if (!isset($this->data['size'])) {
            $this->data['size'] = [];
        }

        $this->data['size']['height'] = $height;
    }

    /**
     * Set callback for brush event
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#subchart-onbrush
     */
    public function setOnBrush(Callback $callback)
    {
        $this->data['onbrush'] = $callback;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }
}
