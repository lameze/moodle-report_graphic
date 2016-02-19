<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP\Charts;

/**
 * Class Line
 * @package Astroanu\C3jsPHP\Charts
 */
class Line implements \JsonSerializable
{
    const STEP_TYPE_STEP = 'step';
    const STEP_TYPE_STEP_BEFORE = 'step-before';
    const STEP_TYPE_STEP_AFTER = 'step-after';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Set if null data point will be connected or not
     *
     * @param bool $connect
     *
     * @link http://c3js.org/reference.html#line-connectNull
     */
    public function setConnectNull($connect = false)
    {
        $this->data['connectNull'] = $connect;
    }

    /**
     * Change step type for step chart
     *
     * @param string $type
     *
     * @link http://c3js.org/reference.html#line-step_type
     */
    public function setStepType($type = self::STEP_TYPE_STEP)
    {
        if (!isset($this->data['step'])) {
            $this->data['step'] = [];
        }

        $this->data['step']['type'] = $type;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }
}
