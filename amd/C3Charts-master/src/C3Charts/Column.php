<?php
/* -------------------------------------------------------
 * C3Charts for PHP
 *
 * MIT License
 * -------------------------------------------------------
 */

namespace Alto\Libs\C3Charts;

/**
 * Class Column
 *
 * @package Alto\C3Charts
 */
class Column extends Entity {

    protected $sName;
    protected $aData = [];
    protected $aXData = [];
    protected $aRegions = [];
    protected $bY2 = false;

    public function __construct($sName, $aData = []) {

        $this->setName($sName);
        if ($aData) {
            $this->setData($aData);
        }
    }

    /**
     * @param string $sName
     *
     * @return Column
     */
    public function setName($sName) {

        $this->sName = $sName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {

        return $this->sName;
    }

    /**
     *
     */
    protected function _alignXData() {

        if (($iXDataLen = sizeof($this->aXData)) && ($iDataLen = sizeof($this->aData))) {
            for ($i = $iXDataLen; $i < $iDataLen; $i++) {
                $this->aXData[] = null;
            }
        }
    }

    /**
     * @param $xItem
     */
    protected function _appendItem($xItem) {

        if (is_array($xItem)) {
            $this->aData[] = reset($xItem);
            $this->aXData[] = key($xItem);
        } else {
            $this->aData[] = $xItem;
        }
    }

    /**
     * @param bool $bXData
     *
     * @return Column
     */
    public function clearData($bXData = true) {

        $this->aData = [];
        if ($bXData) {
            $this->clearXData();
        }
        return $this;
    }

    /**
     * @return Column
     */
    public function clearXData() {

        $this->aXData = [];
        return $this;
    }

    /**
     * @param $aData
     *
     * @return Column
     */
    public function setData($aData) {

        if (is_scalar($aData)) {
            $aData = [$aData];
        } else {
            $aData = (array)$aData;
        }
        if ($this->aXData) {
            $this->_alignXData();
        }
        foreach($aData as $xItem) {
            $this->_appendItem($xItem);
        }
        return $this;
    }

    /**
     * @param $xItem
     *
     * @return Column
     */
    public function addRow($xItem) {

        if ($this->aXData) {
            $this->_alignXData();
        }
        $this->_appendItem($xItem);
        return $this;
    }

    /**
     * @param int $iOffset
     * @param int $iLength
     *
     * @return array
     */
    public function getRows($iOffset = 0, $iLength = 1) {

        $aValues = array_slice($this->aData, $iOffset, $iLength);
        if ($this->aXData) {
            $aKeys = array_slice($this->aXData, $iOffset, $iLength);
            $aData = [];
            foreach($aValues as $iIndex => $iValue) {
                if (isset($aKeys[$iIndex])) {
                    $aData[$aKeys[$iIndex]] = $iValue;
                } else {
                    $aData[] = $iValue;
                }
            }
            return $aData;
        } else {
            return array_slice($this->aData, $iOffset, $iLength);
        }
    }

    /**
     * @param int $iOffset
     * @param int $iLength
     *
     * @return array
     */
    public function slice($iOffset = 0, $iLength = 1) {

        $oNewColumn = clone $this;
        $oNewColumn->clearData();
        $oNewColumn->setData($this->getRows($iOffset, $iLength));

        return $oNewColumn;
    }

    /**
     * @param $sChartType
     *
     * @return Column
     */
    public function setType($sChartType) {

        return $this->setProp('type', (string)$sChartType);
    }

    /**
     * @return mixed
     */
    public function getType() {

        return $this->getProp('type');
    }

    /**
     * @param $aData
     *
     * @return Column
     */
    public function setXData($aData) {

        if (is_scalar($aData)) {
            $aData = [$aData];
        } else {
            $aData = (array)$aData;
        }
        $this->aXData = $aData;
        return $this;
    }

    /**
     * @return array
     */
    public function getXData() {

        return $this->aXData;
    }

    /**
     * @param int      $iStart
     * @param int|null $iEnd
     */
    public function setRegion($iStart, $iEnd = null) {

        $oRegion = new Entity();
        if (!is_null($iStart)) {
            $oRegion->setProp('start', $iStart);
        }
        if (!is_null($iEnd)) {
            $oRegion->setProp('end', $iEnd);
        }
        $oRegion->setProp('style', 'dashed');
        $this->aRegions[] = ['index' => false, 'data' => $oRegion];
    }

    /**
     * @param int      $iStart
     * @param int|null $iEnd
     */
    public function setRegionIndex($iStart, $iEnd = null) {

        $oRegion = new Entity();
        if (!is_null($iStart)) {
            $oRegion->setProp('start', $iStart);
        }
        if (!is_null($iEnd)) {
            $oRegion->setProp('end', $iEnd);
        }
        $oRegion->setProp('style', 'dashed');
        $this->aRegions[] = ['index' => true, 'data' => $oRegion];
    }

    /**
     * @return array
     */
    public function getRegions() {

        return $this->aRegions;
    }

    /**
     * @return array
     */
    public function getRegionsData() {

        $aResult = [];
        $aXData = $this->getXData();
        /** @var Entity[] $aRegion */
        foreach($this->getRegions() as $aRegion) {
            if ($aRegion['index']) {
                $aRegionData = [];
                $iStart = $aRegion['data']->getProp('start');
                if (!is_null($iStart) && isset($aXData[$iStart])) {
                    $aRegionData['start'] = $aXData[$iStart];
                }
                $iEnd = $aRegion['data']->getProp('end');
                if (!is_null($iEnd) && isset($aXData[$iEnd])) {
                    $aRegionData['end'] = $aXData[$iEnd];
                }
                if ($aRegionData) {
                    if ($sStyle = $aRegion['data']->getProp('end')) {
                        $aRegionData['style'] = $sStyle;
                    }
                    $aResult[] = $aRegionData;
                }
            } else {
                $aResult[] = $aRegion['data']->asArray();
            }
        }
        return $aResult;
    }

    /**
     * @param bool $bSet
     *
     * @return Column
     */
    public function setY2($bSet = true) {

        $this->bY2 = (bool)$bSet;

        return $this;
    }

    /**
     * @return bool
     */
    public function isY2() {

        return $this->bY2;
    }

    /**
     * @return array
     */
    public function asArray() {

        $aData = $this->aData;
        array_unshift($aData, $this->sName);

        return $aData;
    }

}

// EOF