<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Axis
 * @package Astroanu\C3jsPHP
 */
class Axis implements \JsonSerializable
{
    const TYPE_TIMESERIES = 'timeseries';
    const TYPE_CATEGORY = 'category';
    const TYPE_INDEXED = 'indexed';

    const POSITION_H_INNER_RIGHT = 'inner-right';
    const POSITION_H_INNER_CENTER = 'inner-center';
    const POSITION_H_INNER_LEFT = 'inner-left';
    const POSITION_H_OUTER_RIGHT = 'outer-right';
    const POSITION_H_OUTER_CENTER = 'outer-center';
    const POSITION_H_OUTER_LEFT = 'outer-left';

    const POSITION_V_INNER_TOP = 'inner-top';
    const POSITION_V_INNER_MIDDLE = 'inner-middle';
    const POSITION_V_INNER_BOTTOM = 'inner-bottom';
    const POSITION_V_OUTER_TOP = 'outer-top';
    const POSITION_V_OUTER_MIDDLE = 'outer-middle';
    const POSITION_V_OUTER_BOTTOM = 'outer-bottom';

    /**
     * @var array
     */
    private $data = [];

    /**
     * Switch x and y axis position
     *
     * @param bool $rotated
     *
     * @link http://c3js.org/reference.html#axis-rotated
     */
    public function setRotated($rotated = false)
    {
        $this->data['rotated'] = $rotated;
    }

    /**
     * Show or hide x axis
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#axis-x-show
     */
    public function setXVisibility($visibility = false)
    {
        $this->ensureX();
        $this->data['x']['show'] = $visibility;
    }

    /**
     * Set type of x axis
     *
     * @param string $type
     *
     * @link http://c3js.org/reference.html#axis-x-type
     */
    public function setXType($type = self::TYPE_INDEXED)
    {
        $this->ensureX();
        $this->data['x']['type'] = $type;
    }

    /**
     * Set how to treat the timezone of x values
     *
     * @param bool $localtime
     *
     * @link http://c3js.org/reference.html#axis-x-localtime
     */
    public function setXLocaltime($localtime = true)
    {
        $this->ensureX();
        $this->data['x']['localtime'] = $localtime;
    }

    /**
     * Set category names on category axis
     *
     * @param array $categories
     *
     * @link http://c3js.org/reference.html#axis-x-categories
     */
    public function setXCategories($categories)
    {
        $this->ensureX();
        $this->data['x']['categories'] = $categories;
    }

    /**
     * Centerise ticks on category axis.
     *
     * @param bool $centered
     *
     * @link http://c3js.org/reference.html#axis-x-tick-centered
     */
    public function setXTickCentered($centered = false)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['centered'] = $centered;
    }

    /**
     * A function to format tick value
     *
     * @param string $format d3.format
     *
     * @link http://c3js.org/reference.html#axis-x-tick-format
     */
    public function setXTickFormat($format)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['format'] = $format;
    }

    /**
     * Setting for culling ticks
     *
     * @param bool $culling
     *
     * @link http://c3js.org/reference.html#axis-x-tick-culling
     */
    public function setXTickCulling($culling)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['culling'] = $culling;
    }


    /**
     * The number of tick texts will be adjusted to less than this value
     *
     * @param int $max
     *
     * @link http://c3js.org/reference.html#axis-x-tick-culling-max
     */
    public function setXTickCullingMax($max = 10)
    {
        $this->ensureXTick();

        if (!isset($this->data['x']['tick']['culling'])) {
            $this->data['x']['tick']['culling'] = [];
        }

        $this->data['x']['tick']['culling']['max'] = $max;
    }

    /**
     * The number of x axis ticks to show
     *
     * @param int $count
     *
     * @link http://c3js.org/reference.html#axis-x-tick-count
     */
    public function setXTickCount($count)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['count'] = $count;
    }

    /**
     * Fit x axis ticks
     *
     * @param bool $fit
     *
     * @link http://c3js.org/reference.html#axis-x-tick-fit
     */
    public function setXTickFit($fit = true)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['fit'] = $fit;
    }

    /**
     * Set the x values of ticks manually
     *
     * @param array $values
     *
     * @link http://c3js.org/reference.html#axis-x-tick-values
     */
    public function setXTickValues($values)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['values'] = $values;
    }

    /**
     * Rotate x axis tick text
     *
     * @param int $angle
     *
     * @link http://c3js.org/reference.html#axis-x-tick-rotate
     */
    public function setXTickRotate($angle = 0)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['rotate'] = $angle;
    }

    /**
     * Show x axis outer tick
     *
     * @param bool $outer
     *
     * @link http://c3js.org/reference.html#axis-x-tick-outer
     */
    public function setXTickOuter($outer = true)
    {
        $this->ensureXTick();
        $this->data['x']['tick']['outer'] = $outer;
    }

    /**
     * Set max value of x axis range
     *
     * @param int $max
     *
     * @link http://c3js.org/reference.html#axis-x-max
     */
    public function setXMax($max)
    {
        $this->ensureX();
        $this->data['x']['max'] = $max;
    }

    /**
     * Set min value of x axis range
     *
     * @param int $min
     *
     * @link http://c3js.org/reference.html#axis-x-min
     */
    public function setXMin($min)
    {
        $this->ensureX();
        $this->data['x']['min'] = $min;
    }

    /**
     * Set left padding for x axis
     *
     * @param int $left
     *
     * @link http://c3js.org/reference.html#axis-x-padding
     *
     * @see setXPaddingRight()
     */
    public function setXPaddingLeft($left)
    {
        $this->ensureXPadding();
        $this->data['x']['padding']['left'] = $left;
    }

    /**
     * Set right padding for x axis
     *
     * @param int $right
     *
     * @link http://c3js.org/reference.html#axis-x-padding
     *
     * @see setXPaddingLeft()
     */
    public function setXPaddingRight($right)
    {
        $this->ensureXPadding();
        $this->data['x']['padding']['right'] = $right;
    }

    /**
     * Set height of x axis
     *
     * @param int $height Height in pixel
     *
     * @link http://c3js.org/reference.html#axis-x-height
     */
    public function setXHeight($height)
    {
        $this->ensureX();
        $this->data['x']['height'] = $height;
    }

    /**
     * Set default extent for subchart and zoom
     *
     * @param array $extent
     *
     * @link http://c3js.org/reference.html#axis-x-extent
     */
    public function setXExtent($extent)
    {
        $this->ensureX();
        $this->data['x']['extent'] = $extent;
    }

    /**
     * Set label text on x axis
     *
     * @param string $text
     *
     * @link http://c3js.org/reference.html#axis-x-label
     *
     * @see setXLabelPosition()
     */
    public function setXLabelText($text)
    {
        $this->ensureXLabel();
        $this->data['x']['label']['text'] = $text;
    }

    /**
     * Set label text position on x axis
     *
     * @param POSITION_H_INNER_RIGHT|POSITION_H_INNER_CENTER|POSITION_H_INNER_LEFT|POSITION_H_OUTER_RIGHT|POSITION_H_OUTER_CENTER|POSITION_H_OUTER_LEFT|POSITION_V_INNER_TOP|POSITION_V_INNER_MIDDLE|POSITION_V_INNER_BOTTOM|POSITION_V_OUTER_TOP|POSITION_V_OUTER_MIDDLE|POSITION_V_OUTER_BOTTOM $const
     *
     * @link http://c3js.org/reference.html#axis-x-label
     *
     * @see setXLabelText()
     */
    public function setXLabelPosition($const)
    {
        $this->ensureXLabel();
        $this->data['x']['label']['position'] = $const;
    }

    /**
     * Show or hide y axis
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#axis-y-show
     */
    public function setYVisibility($visibility = true)
    {
        $this->ensureY();
        $this->data['y']['show'] = $visibility;
    }

    /**
     * Show y axis inside of the chart
     *
     * @param bool $inner
     *
     * @link http://c3js.org/reference.html#axis-y-inner
     */
    public function setYInner($inner = false)
    {
        $this->ensureY();
        $this->data['y']['inner'] = $inner;
    }

    /**
     * Set max value of y axis
     *
     * @param int $max
     *
     * @link http://c3js.org/reference.html#axis-y-max
     */
    public function setYMax($max)
    {
        $this->ensureY();
        $this->data['y']['max'] = $max;
    }

    /**
     * Set min value of y axis
     *
     * @param int $min
     *
     * @link http://c3js.org/reference.html#axis-y-min
     */
    public function setYMin($min)
    {
        $this->ensureY();
        $this->data['y']['min'] = $min;
    }

    /**
     * Change the direction of y axis
     *
     * @param bool $inverted
     *
     * @link http://c3js.org/reference.html#axis-y-inverted
     */
    public function setYInverted($inverted = false)
    {
        $this->ensureY();
        $this->data['y']['inverted'] = $inverted;
    }

    /**
     * Set center value of y axis
     *
     * @param int $center
     *
     * @link http://c3js.org/reference.html#axis-y-center
     */
    public function setYCenter($center)
    {
        $this->ensureY();
        $this->data['y']['center'] = $center;
    }

    /**
     * Set label on y axis
     *
     * @param string $text
     *
     * @link http://c3js.org/reference.html#axis-y-label
     *
     * @see setYLabelPosition()
     */
    public function setYLabelText($text)
    {
        $this->ensureYLabel();
        $this->data['y']['label']['text'] = $text;
    }

    /**
     * Set label position on y axis
     *
     * @param POSITION_H_INNER_RIGHT|POSITION_H_INNER_CENTER|POSITION_H_INNER_LEFT|POSITION_H_OUTER_RIGHT|POSITION_H_OUTER_CENTER|POSITION_H_OUTER_LEFT|POSITION_V_INNER_TOP|POSITION_V_INNER_MIDDLE|POSITION_V_INNER_BOTTOM|POSITION_V_OUTER_TOP|POSITION_V_OUTER_MIDDLE|POSITION_V_OUTER_BOTTOM $const
     *
     * @link http://c3js.org/reference.html#axis-y-label
     *
     * @see setYLabelText()
     */
    public function setYLabelPosition($const)
    {
        $this->ensureYLabel();
        $this->data['y']['label']['position'] = $const;
    }

    /**
     * Set formatter for y axis tick text
     *
     * @param string $format d3.format
     *
     * @link http://c3js.org/reference.html#axis-y-tick-format
     */
    public function setYTickFormat($format)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['format'] = $format;
    }

    /**
     * Show or hide outer tick
     *
     * @param $outer
     *
     * @link http://c3js.org/reference.html#axis-y-tick-outer
     */
    public function setYTickOuter($outer)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['outer'] = $outer;
    }

    /**
     * Set y axis tick values manually
     *
     * @param array $values
     *
     * @link http://c3js.org/reference.html#axis-y-tick-values
     */
    public function setYTickValues($values)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['values'] = $values;
    }

    /**
     * Set the number of y axis ticks
     *
     * @param int $count
     *
     * @link http://c3js.org/reference.html#axis-y-tick-count
     */
    public function setYTickCount($count)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['count'] = $count;
    }

    /**
     * Set top padding for y axis
     *
     * @param int $top
     *
     * http://c3js.org/reference.html#axis-y-padding
     *
     * @see setYPaddingBottom()
     */
    public function setYPaddingTop($top)
    {
        $this->ensureYPadding();
        $this->data['y']['padding']['top'] = $top;
    }

    /**
     * Set bottom padding for y axis
     *
     * @param int $bottom
     *
     * http://c3js.org/reference.html#axis-y-padding
     *
     * @see setYPaddingTop()
     */
    public function setYPaddingBottom($bottom)
    {
        $this->ensureYPadding();
        $this->data['y']['padding']['bottom'] = $bottom;
    }

    /**
     * Set default range of y axis
     *
     * @param array $range
     *
     * @link http://c3js.org/reference.html#axis-y-default
     */
    public function setYDefault($range)
    {
        $this->ensureY();
        $this->data['y']['default'] = $range;
    }

    /**
     * Show or hide y2 axis
     *
     * @param bool $visibility
     *
     * @link http://c3js.org/reference.html#axis-y2-show
     */
    public function setY2Visibility($visibility = false)
    {
        $this->ensureY2();
        $this->data['y2']['show'] = $visibility;
    }

    /**
     * Show y2 axis inside of the chart
     *
     * @param bool $inner
     *
     * @link http://c3js.org/reference.html#axis-y2-inner
     */
    public function setY2Inner($inner = false)
    {
        $this->ensureY2();
        $this->data['y2']['inner'] = $inner;
    }

    /**
     * Set max value of y2 axis
     *
     * @param int $max
     *
     * @link http://c3js.org/reference.html#axis-y2-max
     */
    public function setY2Max($max)
    {
        $this->ensureY2();
        $this->data['y2']['max'] = $max;
    }

    /**
     * Set min value of y2 axis
     *
     * @param int $min
     *
     * @link http://c3js.org/reference.html#axis-y2-min
     */
    public function setY2Min($min)
    {
        $this->ensureY2();
        $this->data['y2']['min'] = $min;
    }

    /**
     * Change the direction of y2 axis
     *
     * @param bool $inverted
     *
     * @link http://c3js.org/reference.html#axis-y2-inverted
     */
    public function setY2Inverted($inverted = false)
    {
        $this->ensureY2();
        $this->data['y2']['inverted'] = $inverted;
    }

    /**
     * Set center value of y2 axis
     *
     * @param int $center
     *
     * @link http://c3js.org/reference.html#axis-y2-center
     */
    public function setY2Center($center)
    {
        $this->ensureY2();
        $this->data['y2']['center'] = $center;
    }

    /**
     * Set label on y2 axis
     *
     * @param string $text
     *
     * @link http://c3js.org/reference.html#axis-y2-label
     *
     * @see setY2LabelPosition()
     */
    public function setY2LabelText($text)
    {
        $this->ensureY2Label();
        $this->data['y2']['label']['text'] = $text;
    }

    /**
     * Set label position on y2 axis
     *
     * @param POSITION_H_INNER_RIGHT|POSITION_H_INNER_CENTER|POSITION_H_INNER_LEFT|POSITION_H_OUTER_RIGHT|POSITION_H_OUTER_CENTER|POSITION_H_OUTER_LEFT|POSITION_V_INNER_TOP|POSITION_V_INNER_MIDDLE|POSITION_V_INNER_BOTTOM|POSITION_V_OUTER_TOP|POSITION_V_OUTER_MIDDLE|POSITION_V_OUTER_BOTTOM $const
     *
     * @link http://c3js.org/reference.html#axis-y2-label
     *
     * @see setY2LabelText()
     */
    public function setY2LabelPosition($const)
    {
        $this->ensureY2Label();
        $this->data['y2']['label']['position'] = $const;
    }

    /**
     * Set formatter for y2 axis tick text
     *
     * @param string $format d3.format
     *
     * @link http://c3js.org/reference.html#axis-y2-tick-format
     */
    public function setY2TickFormat($format)
    {
        $this->ensureY2Tick();
        $this->data['y2']['tick']['format'] = $format;
    }

    /**
     * Show or hide outer tick
     *
     * @param $outer
     *
     * @link http://c3js.org/reference.html#axis-y2-tick-outer
     */
    public function setY2TickOuter($outer)
    {
        $this->ensureY2Tick();
        $this->data['y2']['tick']['outer'] = $outer;
    }

    /**
     * Set y2 axis tick values manually
     *
     * @param array $values
     *
     * @link http://c3js.org/reference.html#axis-y2-tick-values
     */
    public function setY2TickValues($values)
    {
        $this->ensureY2Tick();
        $this->data['y2']['tick']['values'] = $values;
    }

    /**
     * Set the number of y2 axis ticks
     *
     * @param int $count
     *
     * @link http://c3js.org/reference.html#axis-y2-tick-count
     */
    public function setY2TickCount($count)
    {
        $this->ensureY2Tick();
        $this->data['y2']['tick']['count'] = $count;
    }

    /**
     * Set top padding for y2 axis
     *
     * @param int $top
     *
     * http://c3js.org/reference.html#axis-y2-padding
     *
     * @see setY2PaddingBottom()
     */
    public function setY2PaddingTop($top)
    {
        $this->ensureY2Padding();
        $this->data['y2']['padding']['top'] = $top;
    }

    /**
     * Set bottom padding for y2 axis
     *
     * @param int $bottom
     *
     * http://c3js.org/reference.html#axis-y2-padding
     *
     * @see setY2PaddingTop()
     */
    public function setY2PaddingBottom($bottom)
    {
        $this->ensureY2Padding();
        $this->data['y2']['padding']['bottom'] = $bottom;
    }

    /**
     * Set default range of y2 axis
     *
     * @param array $range
     *
     * @link http://c3js.org/reference.html#axis-y2-default
     */
    public function setY2Default($range)
    {
        $this->ensureY2();
        $this->data['y2']['default'] = $range;
    }

    /**
     * @param $fit
     *
     * @fixme Undocumented. Is this really working?
     */
    public function setYTickFit($fit)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['fit'] = $fit;
    }

    /**
     * @param $angle
     *
     * @fixme Undocumented. Is this really working?
     */
    public function setYTickRotate($angle)
    {
        $this->ensureYTick();
        $this->data['y']['tick']['rotate'] = $angle;
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

    private function ensureXLabel()
    {
        $this->ensureX();

        if (!isset($this->data['x']['label'])) {
            $this->data['x']['label'] = [];
        }
    }

    private function ensureXPadding()
    {
        $this->ensureX();

        if (!isset($this->data['x']['padding'])) {
            $this->data['x']['padding'] = [];
        }
    }

    private function ensureXTick()
    {
        $this->ensureX();

        if (!isset($this->data['x']['tick'])) {
            $this->data['x']['tick'] = [];
        }
    }

    private function ensureY()
    {
        if (!isset($this->data['y'])) {
            $this->data['y'] = [];
        }
    }

    private function ensureYLabel()
    {
        $this->ensureY();

        if (!isset($this->data['y']['label'])) {
            $this->data['y']['label'] = [];
        }
    }

    private function ensureYTick()
    {
        $this->ensureY();

        if (!isset($this->data['y']['tick'])) {
            $this->data['y']['tick'] = [];
        }
    }

    private function ensureYPadding()
    {
        $this->ensureY();

        if (!isset($this->data['y']['padding'])) {
            $this->data['y']['padding'] = [];
        }
    }

    private function ensureY2()
    {
        if (!isset($this->data['y2'])) {
            $this->data['y2'] = [];
        }
    }

    private function ensureY2Label()
    {
        $this->ensureY2();

        if (!isset($this->data['y2']['label'])) {
            $this->data['y2']['label'] = [];
        }
    }

    private function ensureY2Tick()
    {
        $this->ensureY2();

        if (!isset($this->data['y2']['tick'])) {
            $this->data['y2']['tick'] = [];
        }
    }

    private function ensureY2Padding()
    {
        $this->ensureY2();

        if (!isset($this->data['y2']['padding'])) {
            $this->data['y2']['padding'] = [];
        }
    }
}
