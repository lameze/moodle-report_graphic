<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Pie
 * @package Astroanu\C3jsPHP\Charts
 */
class Pie implements \JsonSerializable
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Show or hide label on each pie piece
     *
     * @param $visibility
     *
     * @link http://c3js.org/reference.html#pie-label-show
     */
    public function setLabelVisibility($visibility = true)
    {
        $this->ensureLabel();
        $this->data['label']['show'] = $visibility;
    }

    /**
     * Set formatter for the label on each pie piece
     *
     * @param string $format
     *
     * @link http://c3js.org/reference.html#pie-label-format
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
     * @link http://c3js.org/reference.html#pie-label-threshold
     */
    public function setLabeltThreshold($threshold = 0.05)
    {
        $this->ensureLabel();
        $this->data['label']['threshold'] = $threshold;
    }

    /**
     * Enable or disable expanding pie pieces
     *
     * @param bool $expand
     *
     * @link http://c3js.org/reference.html#pie-expand
     */
    public function setExpand($expand = true)
    {
        $this->data['expand'] = $expand;
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
