<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Zoom
 * @package Astroanu\C3jsPHP
 *
 * Experimental
 */
class Zoom implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Enable zooming
     *
     * @param bool $enabled
     *
     * @link http://c3js.org/reference.html#zoom-enabled
     */
    public function setEnabled($enabled = false)
    {
        $this->data['enabled'] = $enabled;
    }

    /**
     * Enable to rescale after zooming
     *
     * @param bool $rescale
     *
     * @link http://c3js.org/reference.html#zoom-rescale
     */
    public function setRescale($rescale = false)
    {
        $this->data['rescale'] = $rescale;
    }

    /**
     * Change zoom extent
     *
     * @param array $extent
     *
     * @link http://c3js.org/reference.html#zoom-extent
     */
    public function setExtent($extent = array(1, 10))
    {
        $this->data['extent'] = $extent;
    }

    /**
     * Set callback that is called when the chart is zooming
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#zoom-onzoom
     */
    public function setOnZoom(Callback $callback)
    {
        $this->data['onzoom'] = $callback;
    }

    /**
     * Set callback that is called when zooming starts
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#zoom-onzoomstart
     */
    public function setOnZoomStart(Callback $callback)
    {
        $this->data['onzoomstart'] = $callback;
    }

    /**
     * Set callback that is called when zooming ends
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#zoom-onzoomend
     */
    public function setOnZoomEnd(Callback $callback)
    {
        $this->data['onzoomend'] = $callback;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }
}
