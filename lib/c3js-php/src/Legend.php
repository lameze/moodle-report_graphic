<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Legend
 * @package Astroanu\C3jsPHP
 */
class Legend implements \JsonSerializable
{
    const POSITION_BOTTOM = 'bottom';
    const POSITION_RIGHT = 'right';
    const POSITION_INSET = 'inset';

    const INSET_TOP_LEFT = 'top-left';
    const INSET_TOP_RIGHT = 'top-right';
    const INSET_BOTTOM_LEFT = 'bottom-left';
    const INSET_BOTTOM_RIGHT = 'bottom-right';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Show or hide legend
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#legend-show
     */
    public function setVisibility($visibility = true)
    {
        $this->data['show'] = $visibility;
    }

    /**
     * Hide legend
     *
     * @param bool|string|array $hidden
     *
     * @link http://c3js.org/reference.html#legend-hide
     */
    public function setHidden($hidden = false)
    {
        $this->data['hide'] = $hidden;
    }

    /**
     * Change the position of legend
     *
     * @param POSITION_BOTTOM|POSITION_RIGHT|POSITION_INSET $position
     *
     * @link http://c3js.org/reference.html#legend-position
     */
    public function setPosition($position = self::POSITION_BOTTOM)
    {
        $this->data['position'] = $position;
    }

    /**
     * Change inset legend anchor
     *
     * @param INSET_TOP_LEFT|INSET_TOP_RIGHT|INSET_BOTTOM_LEFT|INSET_BOTTOM_RIGHT $anchor
     *
     * @link http://c3js.org/reference.html#legend-inset
     *
     * @see setInsetX()
     * @see setInsetY()
     * @see setInsetStep()
     */
    public function setInsetAnchor($anchor = self::INSET_TOP_LEFT)
    {
        $this->ensureInset();
        $this->data['inset']['anchor'] = $anchor;
    }

    /**
     * Change inset legend x
     *
     * @param int $x
     *
     * @link http://c3js.org/reference.html#legend-inset
     *
     * @see setInsetAnchor()
     * @see setInsetY()
     * @see setInsetStep()
     */
    public function setInsetX($x = 10)
    {
        $this->ensureInset();
        $this->data['inset']['x'] = $x;
    }

    /**
     * Change inset legend y
     *
     * @param int $y
     *
     * @link http://c3js.org/reference.html#legend-inset
     *
     * @see setInsetAnchor()
     * @see setInsetX()
     * @see setInsetStep()
     */
    public function setInsetY($y = 0)
    {
        $this->ensureInset();
        $this->data['inset']['y'] = $y;
    }

    /**
     * Change inset legend step
     *
     * @param int $step
     *
     * @link http://c3js.org/reference.html#legend-inset
     *
     * @see setInsetAnchor()
     * @see setInsetX()
     * @see setInsetY()
     */
    public function setInsetStep($step)
    {
        $this->ensureInset();
        $this->data['inset']['step'] = $step;
    }

    /**
     * Set click event handler to the legend item
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#legend-item-onclick
     */
    public function setItemOnClick(Callback $callback)
    {
        $this->ensureItem();
        $this->data['item']['onclick'] = $callback;
    }

    /**
     * Set mouseover event handler to the legend item
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#legend-item-onmouseover
     */
    public function setItemOnMouseOver(Callback $callback)
    {
        $this->ensureItem();
        $this->data['item']['onmouseover'] = $callback;
    }

    /**
     * Set mouseout event handler to the legend item
     *
     * @param Callback $callback
     *
     * @link http://c3js.org/reference.html#legend-item-onmouseout
     */
    public function setItemOnMouseOut(Callback $callback)
    {
        $this->ensureItem();
        $this->data['item']['onmouseout'] = $callback;
    }

    /**
     * @return array
     */
    public function JsonSerialize()
    {
        return $this->data;
    }

    private function ensureInset()
    {
        if (!isset($this->data['inset'])) {
            $this->data['inset'] = [];
        }
    }

    private function ensureItem()
    {
        if (!isset($this->data['item'])) {
            $this->data['item'] = [];
        }
    }
}
