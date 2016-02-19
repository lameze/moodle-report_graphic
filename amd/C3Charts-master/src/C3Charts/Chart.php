<?php
/* -------------------------------------------------------
 * C3Charts for PHP
 *
 * MIT License
 * -------------------------------------------------------
 */

namespace Alto\Libs\C3Charts;

/**
 * Class Chart
 *
 * @package Alto\C3Charts
 */
class Chart extends Entity{

    /** @var Column[] */
    protected $aColumns = [];

    /** @var Axis[] */
    protected $aAxes = [];

    protected $aGroups = [];

    protected $sChartType;

    protected $aChartOptions = [];

    /**
     * Chart constructor.
     *
     * @param array $aColumns
     */
    public function __construct($aColumns = []) {

        if (!empty($aColumns)) {
            foreach($aColumns as $sColumnsName => $xColumnData) {
                if ($xColumnData instanceof Column) {
                    $this->setColumn($xColumnData);
                } else {
                    $this->setColumn($sColumnsName, $xColumnData);
                }
            }
        }
        $this->setBindTo('#chart');
    }

    /**
     * @param string $sElement
     *
     * @return Chart
     */
    public function setBindTo($sElement) {

        $this->setProp('bind_to', $sElement);
        return $this;
    }

    /**
     * @return string
     */
    public function getBindTo() {

        return $this->getProp('bind_to');
    }

    /**
     * Clear all columns in chart
     */
    public function clearColumns() {

        $this->aColumns = [];
    }

    /**
     * @param string $sName
     * @param array $aData
     * @return Column|array
     */
    public function setColumn($sName, $aData = []) {

        if ((func_num_args() == 1) && ($sName instanceof Column)) {
            $oColumn = $sName;
            $sName = $oColumn->getName();
        } elseif ($aData instanceof Column) {
            $oColumn = $aData;
        } else {
            $oColumn = new Column($sName, $aData);
        }
        $this->aColumns[$sName] = $oColumn;

        return $oColumn;
    }

    /**
     * @return Column[]
     */
    public function getColumns() {

        return $this->aColumns;
    }

    /**
     * @param string|int $xName
     *
     * @return Column|null
     */
    public function getColumn($xName) {

        $aColumns = $this->getColumns();
        if (is_integer($xName)) {
            $aColumnNames = array_keys($aColumns);
            if (isset($aColumnNames[$xName])) {
                return $aColumns[$aColumnNames[$xName]];
            }
        } elseif (isset($aColumns[$xName])) {
            return $aColumns[$xName];
        }
        return null;
    }

    /**
     * @param int $iIndex
     * @return null
     */
    public function getColumnByIndex($iIndex) {

        $aColumns = array_values($this->getColumns());
        if (isset($aColumns[$iIndex])) {
            return $aColumns[$iIndex];
        }
        return null;
    }

    /**
     * @param string $sType
     * @param string $sLabel
     *
     * @return Axis
     */
    public function setAxis($sType, $sLabel = null) {

        if (empty($this->aAxes[$sType])) {
            $this->aAxes[$sType] = new Axis();
        }
        if ($sLabel === false) {
            $this->aAxes[$sType]->setShow(false);
        } elseif (($sLabel === true) || ($sLabel === null)) {
            $this->aAxes[$sType]->setShow(true);
        } else {
            $this->aAxes[$sType]->setLabel($sLabel);
        }
        return $this->aAxes[$sType];
    }

    /**
     * @param string $sLabel
     *
     * @return Axis
     */
    public function setX($sLabel = null) {

        return $this->setAxis('x', $sLabel);
    }

    /**
     * @param array $aData
     *
     * @return Axis
     */
    public function setXData($aData) {

        return $this->setX()->setData($aData);
    }

    public function setXTimeSeries($aData) {

        return $this->setX()->setTimeSeries($aData);
    }

    /**
     * @param $aData
     * @return $this
     */
    public function setXCategories($aData) {

        return $this->setX()->setCategories($aData);
    }

    /**
     * @param null $sLabel
     * @return Axis
     */
    public function setY($sLabel = null) {

        return $this->setAxis('y', $sLabel);
    }

    /**
     * @param null $sLabel
     * @return Axis
     */
    public function setY1($sLabel = null) {

        return $this->setY($sLabel);
    }

    /**
     * @param null $sLabel
     * @return Axis
     */
    public function setY2($sLabel = null) {

        return $this->setAxis('y2', $sLabel);
    }

    /**
     * @return $this
     */
    public function setGroup() {

        $aGroup = [];
        foreach(func_get_args() as $sArg) {
            $aGroup[] = (string)$sArg;
        }
        $this->aGroups[] = $aGroup;
        return $this;
    }

    /**
     * @return array
     */
    public function getGroups() {

        return $this->aGroups;
    }

    /**
     * @param string $sChartType
     * @param array $aOptions
     * @return $this
     */
    public function setChartType($sChartType, $aOptions = []) {

        $this->sChartType = $sChartType;
        $this->aChartOptions[$sChartType] = $aOptions;
        return $this;
    }

    /**
     * @return string
     */
    public function getChartType() {

        return $this->sChartType;
    }

    /**
     * @param null|string $sChartType
     * @return array|null
     */
    public function getChartOptions($sChartType = null) {

        if (!$sChartType) {
            return $this->aChartOptions;
        } elseif(isset($this->aChartOptions[$sChartType])) {
            return $this->aChartOptions[$sChartType];
        }
        return null;
    }

    /**
     * @param array $aRow
     */
    public function addRow($aRow) {

        foreach($aRow as $sName => $xItem) {
            $oColumn = $this->getColumn($sName);
            if ($oColumn) {
                $oColumn->addRow($xItem);
            }
        }
    }

    public function slice($iOffset = 0, $iLength = 1) {

        $oNewChart = clone $this;
        $oNewChart->clearColumns();
        foreach($this->getColumns() as $sName => $oColumn) {
            $oNewChart->setColumn($sName, $oColumn->slice($iOffset, $iLength));
        }
        return $oNewChart;
    }

    /**
     * @return array
     */
    public function asArray() {

        $aResult = ['bindto' => $this->getBindTo()];

        if (!empty($this->aAxes['x']) && $this->aAxes['x']->getType() == 'timeseries') {
            $aResult['data']['x'] = 'x';
            $aResult['data']['columns'][] = array_merge(['x'], $this->aAxes['x']->getData());
        }
        $aXs = [];
        $bXs = false;
        $i = 0;
        foreach ($this->aColumns as $oColumn) {
            $aXData = $oColumn->getXData();
            $sX = ('x' . ++$i);
            $aXs['data']['xs'][$oColumn->getName()] = $sX;
            if ($aXData) {
                $aXs['data']['columns'][] = array_merge([$sX], $aXData);
                $bXs = true;
            } else {
                $aXs['data']['columns'][] = [];
            }
        }
        if ($bXs) {
            $aResult = $aXs;
        }

        $aResult['data']['type'] = [];
        foreach ($this->aColumns as $oColumn) {
            $aResult['data']['columns'][] = $oColumn->asArray();
            if ($oColumn->isY2()) {
                $aResult['data']['axes'][$oColumn->getName()] = 'y2';
                $this->setY2();
            }
            if ($sType = $oColumn->getType()) {
                $aResult['data']['types'][$oColumn->getName()] = $sType;
            }
            if ($oColumn->getRegions()) {
                $aResult['data']['regions'][$oColumn->getName()] = $oColumn->getRegionsData();
            }
        }
        foreach($this->getGroups() as $aGroup) {
            foreach($aGroup as $iIndex => $sColumn) {
                if (!isset($this->aColumns[$sColumn])) {
                    unset($aGroup[$iIndex]);
                }
            }
            if ($aGroup) {
                $aResult['data']['groups'][] = $aGroup;
            }
        }
        if ($sChartType = $this->getChartType()) {
            $aResult['data']['type'] = $sChartType;
            if ($aOptions = $this->getChartOptions($sChartType)) {
                $aResult[$sChartType] = $aOptions;
            }
        } else {
            unset($aResult['data']['type']);
        }
        foreach($this->aAxes as $sType => $oAxis) {
            $aAxis = $oAxis->asArray();
            if ($aAxis) {
                $aResult['axis'][$sType] = $aAxis;
            }
        }
        return $aResult;
    }

    /**
     * @return string
     */
    public function getC3($sBindTo = null) {

        if (!empty($sBindTo)) {
            $aData = $this->asArray();
            $aData['bindto'] = (string)$sBindTo;
            return json_encode($aData);
        }
        return json_encode($this);
    }

    public function __toString() {

        return $this->getC3();
    }

}

// EOF