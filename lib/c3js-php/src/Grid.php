<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Grid
 * @package Astroanu\C3jsPHP
 */
class Grid implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Show grids along x axis
     *
     * @param boolean $visibility
     *
     * @return Grid
     *
     * @link http://c3js.org/reference.html#grid-x-show
     */
    public function setXVisibility($visibility = false)
    {
        $this->ensureX();
        $this->data['x']['show'] = $visibility;
    }

    /**
     * Set additional grid lines along x axis
     *
     * @param array $lines
     *
     * @return Grid
     *
     * @link http://c3js.org/reference.html#grid-x-lines
     */
    public function setXLines($lines)
    {
        $this->ensureX();
        $this->data['x']['lines'] = $lines;
    }

    /**
     * Show grids along y axis
     *
     * @param boolean $visibility
     *
     * @return Grid
     *
     * @link http://c3js.org/reference.html#grid-y-show
     */
    public function setYVisibility($visibility = false)
    {
        $this->ensureY();
        $this->data['y']['show'] = $visibility;
    }

    /**
     * Set additional grid lines along y axis
     *
     * @param array $lines
     *
     * @return Grid
     *
     * @link http://c3js.org/reference.html#grid-y-lines
     */
    public function setYLines($lines)
    {
        $this->ensureY();

        $this->data['y']['lines'] = $lines;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureX()
    {
        if (!isset($this->data['x'])) {
            $this->data['x'] = [];
        }
    }

    private function ensureY()
    {
        if (!isset($this->data['y'])) {
            $this->data['y'] = [];
        }
    }
}
