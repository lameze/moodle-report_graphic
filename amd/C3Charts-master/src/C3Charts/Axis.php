<?php
/* -------------------------------------------------------
 * C3Charts for PHP
 *
 * MIT License
 * -------------------------------------------------------
 */

namespace Alto\Libs\C3Charts;

/**
 * Class Axis
 *
 * @package Alto\C3Charts
 */
class Axis extends Entity {

    protected $bShow = null;
    protected $sLabelText;
    protected $sLabelPosition;
    protected $oTick;
    protected $aData;

    /**
     * Axis constructor.
     */
    public function __construct() {

        $this->oTick = new Entity();
    }

    /**
     * @param string $sLabel
     *
     * @return Axis
     */
    public function setLabelText($sLabel) {

        $this->sLabelText = (string)$sLabel;
        return $this;
    }

    /**
     * @param string|array $xLabel
     *
     * @return Axis
     */
    public function setLabel($xLabel) {

        if (is_scalar($xLabel)) {
            return $this->setLabelText($xLabel);
        }
        $aData = (array)$xLabel;
        $this->setLabelText(reset($aData));
        $this->setLabelPosition(next($aData));

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabelText() {

        return $this->sLabelText;
    }

    /**
     * @param string $sPosition
     *
     * @return Axis
     */
    public function setLabelPosition($sPosition) {

        $this->sLabelPosition = (string)$sPosition;
        return $this;
    }

    /**
     * @param string $sPosition
     *
     * @return Axis
     */
    public function setPosition($sPosition) {

        return $this->setLabelPosition($sPosition);
    }

    /**
     * @return mixed
     */
    public function getLabelPosition() {

        return $this->sLabelPosition;
    }

    /**
     * @param bool $bShow
     *
     * @return Axis
     */
    public function setShow($bShow = true) {

        $this->bShow = (bool)$bShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getShow() {

        return (bool)$this->bShow;
    }

    /**
     * @param array $aData
     *
     * @return Axis
     */
    public function setData($aData) {

        if (is_scalar($aData)) {
            $this->aData = (array)$aData;
        } else {
            $this->aData = (array)$aData;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {

        return $this->aData;
    }

    /**
     * @param string $sType
     *
     * @return Axis
     */
    public function setType($sType) {

        return $this->setProp('type', $sType);
    }

    /**
     * @return mixed|string
     */
    public function getType() {

        $sType = $this->getProp('type');
        if (!$sType && $this->getData()) {
            return 'category';
        }
        return $sType;
    }

    /**
     * @param string $sFormat
     *
     * @return Axis
     */
    public function setFormat($sFormat) {

        $this->oTick->setProp('format', $sFormat);
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat() {

        $this->oTick->getProp('format');
    }

    /**
     * @param array $aData
     *
     * @return Axis
     */
    public function setTimeSeries($aData) {

        $this->setType('timeseries')->setData($aData)->setFormat('%Y-%m-%d');
        return $this;
    }

    /**
     * @param array $aData
     *
     * @return Axis
     */
    public function setCategories($aData) {

        $this->setType('category')->setData($aData);
        return $this;
    }

    /**
     * @return array
     */
    public function asArray() {

        $aResult = [];
        if (!is_null($this->bShow)) {
            $aResult['show'] = $this->bShow;
        }
        if ($this->sLabelText) {
            $aResult['label'] = ['text' => $this->sLabelText];
            if ($this->sLabelPosition) {
                $aResult['label'] = ['text' => $this->sLabelText, 'position' => $this->sLabelPosition];
            } else {
                $aResult['label'] = $this->sLabelText;
            }
        }
        if ($sType = $this->getType()) {
            $aResult['type'] = $sType;
        }
        if (($sType == 'category') && ($aData = $this->getData())) {
            $aResult['categories'] = $aData;
        }
        if ($aTick = $this->oTick->asArray()) {
            $aResult['tick'] = $aTick;
        }
        return $aResult;
    }

}

// EOF