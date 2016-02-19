<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Point
 * @package Astroanu\C3jsPHP
 */
class Point implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Whether to show each point in line
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#point-show
     */
    public function setVisibility($visibility = true)
    {
        $this->data['show'] = $visibility;
    }

    /**
     * The radius size of each point
     *
     * @param float $r
     *
     * @link http://c3js.org/reference.html#point-r
     */
    public function setR($r = 2.5)
    {
        $this->data['r'] = $r;
    }

    /**
     * Whether to expand each point on focus
     *
     * @param bool $enabled
     *
     * @link http://c3js.org/reference.html#point-focus-expand-enabled
     */
    public function setFocusExpandEnabled($enabled = true)
    {
        $this->ensureFocusExpand();
        $this->data['focus']['expand']['enabled'] = $enabled;
    }

    /**
     * The radius size of each point on focus
     *
     * @param float $r
     *
     * @link http://c3js.org/reference.html#point-focus-expand-r
     */
    public function setFocusExpandR($r)
    {
        $this->ensureFocusExpand();
        $this->data['focus']['expand']['r'] = $r;
    }

    /**
     * The radius size of each point on selected
     *
     * @param $r
     *
     * @link http://c3js.org/reference.html#point-select-r
     */
    public function setSelectR($r)
    {
        if (!isset($this->data['select'])) {
            $this->data['select'] = [];
        }

        $this->data['select']['r'] = $r;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureFocusExpand()
    {
        if (!isset($this->data['focus'])) {
            $this->data['focus'] = [];
        }

        if (!isset($this->data['focus']['expand'])) {
            $this->data['focus']['expand'] = [];
        }
    }
}
