<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Chart
 * @package Astroanu\C3jsPHP
 */
class Chart
{
    /**
     * @var array
     */
    private $options = [];
    /**
     * @var Data
     */
    private $data;
    /**
     * @var Callback
     */
    private $oninit;
    /**
     * @var Callback
     */
    private $onrendered;
    /**
     * @var Callback
     */
    private $onmouseover;
    /**
     * @var Callback
     */
    private $onmouseout;
    /**
     * @var Callback
     */
    private $onresize;
    /**
     * @var Callback
     */
    private $onresized;

    /**
     * The CSS selector or the element which the chart will be set to
     *
     * @param string $selector
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#bindto
     */
    public function bindTo($selector)
    {
        $this->options['bindto'] = $selector;
        return $this;
    }

    /**
     * The desired width of the chart element
     *
     * @param int $width
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#size-width
     */
    public function setSizeWidth($width)
    {
        $this->ensureSize();
        $this->options['size']['width'] = $width;
        return $this;
    }

    /**
     * The desired height of the chart element
     *
     * @param int $height
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#size-height
     */
    public function setSizeHeight($height)
    {
        if (!isset($this->data['size'])) {
            $this->data['size'] = [];
        }

        $this->options['size']['height'] = $height;
        return $this;
    }

    /**
     * Set the padding on the top of the chart
     *
     * @param int $padding
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#padding-top
     */
    public function setPaddingTop($padding)
    {
        $this->ensurePadding();
        $this->options['padding']['top'] = $padding;
        return $this;
    }

    /**
     * Set the padding on the right of the chart
     *
     * @param int $padding
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#padding-right
     */
    public function setPaddingRight($padding)
    {
        $this->ensurePadding();
        $this->options['padding']['right'] = $padding;
        return $this;
    }

    /**
     * Set the padding on the bottom of the chart
     *
     * @param int $padding
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#padding-bottom
     */
    public function setPaddingBottom($padding)
    {
        $this->ensurePadding();
        $this->options['padding']['bottom'] = $padding;
        return $this;
    }

    /**
     * Set the padding on the left of the chart
     *
     * @param int $padding
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#padding-left
     */
    public function setPaddingLeft($padding)
    {
        $this->ensurePadding();
        $this->options['padding']['left'] = $padding;
        return $this;
    }

    /**
     * Set custom color pattern
     *
     * @param string[] $pattern CSS hex colors
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#color-pattern
     */
    public function setColorPattern($pattern)
    {
        if (!isset($this->data['color'])) {
            $this->data['color'] = [];
        }

        $this->options['color']['pattern'] = $pattern;
        return $this;
    }

    /**
     * Indicate if the chart should have interactions
     * @param bool $interaction
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#interaction-enabled
     */
    public function setInteractionEnabled($interaction = true)
    {
        $this->options['interaction']['enabled'] = $interaction;
        return $this;
    }

    /**
     * Set duration of transition for chart animation
     *
     * @param int $duration Duration of transition in milliseconds
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#transition-duration
     */
    public function setTransitionDuration($duration = 350)
    {
        $this->options['transition']['duration'] = $duration;
        return $this;
    }

    /**
     * Set a callback to execute when the chart is initialized
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#oninit
     */
    public function setOnInit(Callback $callback)
    {
        $this->oninit = $callback;
        return $this;
    }

    /**
     * Set a callback which is executed when the chart is rendered
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#onrendered
     */
    public function setOnRendered(Callback $callback)
    {
        $this->onrendered = $callback;
        return $this;
    }

    /**
     * Set a callback to execute when mouse enters the chart
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#onmouseover
     */
    public function setOnMouseOver(Callback $callback)
    {
        $this->onmouseover = $callback;
        return $this;
    }

    /**
     * Set a callback to execute when mouse leaves the chart
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#onmouseout
     */
    public function setOnMouseOut(Callback $callback)
    {
        $this->onmouseout = $callback;
        return $this;
    }

    /**
     * Set a callback to execute when user resizes the screen
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#onresize
     */
    public function onResize(Callback $callback)
    {
        $this->onresize = $callback;
        return $this;
    }

    /**
     * Set a callback to execute when screen resize finished
     *
     * @param Callback $callback
     *
     * @return Chart
     *
     * @link http://c3js.org/reference.html#onresized
     */
    public function onResized(Callback $callback)
    {
        $this->onresized = $callback;
        return $this;
    }

    /**
     * Attach a Data object to the Chart
     *
     * @param Data $data
     *
     * @return Chart
     */
    public function setData(Data $data)
    {
        $this->options['data'] = $data;
        return $this;
    }

    /**
     * Attach a Axis object to the Chart
     *
     * @param Axis $axis
     *
     * @return Chart
     */
    public function setAxis(Axis $axis)
    {
        $this->options['axis'] = $axis;
        return $this;
    }

    /**
     * Attach a Grid object to the Chart
     *
     * @param Grid $grid
     *
     * @return Chart
     */
    public function setGrid(Grid $grid)
    {
        $this->options['grid'] = $grid;
        return $this;
    }

    /**
     * Attach a Region object to the Chart
     *
     * @param Region $region
     *
     * @return Chart
     *
     * @see setRegions()
     */
    public function addRegion(Region $region)
    {
        $this->ensureRegions();
        $this->options['regions'][] = $region;
        return $this;
    }

    /**
     * Set Chart Regions
     *
     * @param Region[] $regions
     *
     * @return Chart
     *
     * @see addRegion()
     */
    public function setRegions($regions) {
        $this->ensureRegions();
        $this->options['regions'] = $regions;
        return $this;
    }

    /**
     * Attach a Legend object to the Chart
     *
     * @param Legend $legend
     *
     * @return Chart
     */
    public function setLegend(Legend $legend)
    {
        $this->options['legend'] = $legend;
        return $this;
    }

    /**
     * Attach a Tooltip object to the Chart
     *
     * @param Tooltip $tooltip
     *
     * @return Chart
     */
    public function setTooltip(Tooltip $tooltip)
    {
        $this->options['tooltip'] = $tooltip;
        return $this;
    }

    /**
     * Attach a Subchart object to the Chart
     *
     * @param Subchart $subchart
     *
     * @return Chart
     */
    public function setSubChart(Subchart $subchart)
    {
        $this->options['subchart'] = $subchart;
        return $this;
    }

    /**
     * Attach a Zoom object to the Chart
     *
     * @param Zoom $zoom
     *
     * @return Chart
     */
    public function setZoom(Zoom $zoom)
    {
        $this->options['zoom'] = $zoom;
        return $this;
    }

    /**
     * Attach a Point object to the Chart
     *
     * @param Point $point
     *
     * @return Chart
     */
    public function setPoint(Point $point)
    {
        $this->options['point'] = $point;
        return $this;
    }

    /**
     * Attach a Line object to the Chart
     *
     * @param Charts\Line $line
     *
     * @return Chart
     */
    public function setLine(Charts\Line $line)
    {
        $this->options['line'] = $line;
        return $this;
    }

    /**
     * Attach a Area object to the Chart
     *
     * @param Charts\Area $area
     *
     * @return Chart
     */
    public function setArea(Charts\Area $area)
    {
        $this->options['area'] = $area;
        return $this;
    }

    /**
     * Attach a Bar object to the Chart
     *
     * @param Charts\Bar $bar
     *
     * @return Chart
     */
    public function setBar(Charts\Bar $bar)
    {
        $this->options['bar'] = $bar;
        return $this;
    }

    /**
     * Attach a Pie object to the Chart
     *
     * @param Charts\Pie $pie
     *
     * @return Chart
     */
    public function setPie(Charts\Pie $pie)
    {
        $this->options['pie'] = $pie;
        return $this;
    }

    /**
     * Attach a Donut object to the Chart
     *
     * @param Charts\Donut $donut
     *
     * @return Chart
     */
    public function setDonut(Charts\Donut $donut)
    {
        $this->options['donut'] = $donut;
        return $this;
    }

    /**
     * Attach a Gauge object to the Chart
     *
     * @param Charts\Gauge $gauge
     *
     * @return Chart
     */
    public function setGauge(Charts\Gauge $gauge)
    {
        $this->options['gauge'] = $gauge;
        return $this;
    }

    /**
     * Get the rendered JavaScript
     *
     * @param string $var (optional) Returning javascript variable name
     * @param bool $pretty (optional) Render prettyfied javascript
     *
     * @return string
     */
    public function getRendering($var = 'chart', $pretty = false)
    {
        $result = 'var ' . $var . ' = c3.generate(';

        if ($pretty) {
            $body = json_encode($this->options, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        } else {
            $body = json_encode($this->options, JSON_NUMERIC_CHECK);
        }

        $body = str_replace('"function', 'function', $body);
        $body = str_replace('}"', '}', $body);
        $body = str_replace('\/', '/', $body);
        $body = str_replace('\"', '"', $body);

        $result .= $body;

        $result .= ');';

        return $result;
    }

    /**
     * Renders the JavaScript directly onto the HTML document
     *
     * @param string $var (optional) Returning javascript variable name
     * @param bool $pretty (optional) Render prettyfied javascript
     */
    public function render($var = 'chart', $pretty = false)
    {
        return $this->getRendering($var, $pretty);
    }

    private function ensureSize()
    {
        if (!isset($this->data['size'])) {
            $this->data['size'] = [];
        }
    }

    private function ensurePadding()
    {
        if (!isset($this->data['padding'])) {
            $this->data['padding'] = [];
        }
    }

    private function ensureRegions()
    {
        if (!isset($this->data['regions'])) {
            $this->data['regions'] = [];
        }
    }
}
