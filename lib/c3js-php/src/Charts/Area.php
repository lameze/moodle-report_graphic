<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Area
 * @package Astroanu\C3jsPHP\Charts
 */
class Area implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Set if min or max value will be 0 on area chart
     *
     * @param bool $zerobased
     *
     * @link http://c3js.org/reference.html#area-zerobased
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
