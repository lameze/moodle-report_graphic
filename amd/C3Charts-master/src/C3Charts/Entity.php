<?php
/* -------------------------------------------------------
 * C3Charts for PHP
 *
 * MIT License
 * -------------------------------------------------------
 */

namespace Alto\Libs\C3Charts;

/**
 * Class Entity
 *
 * @package Alto\C3Charts
 */
class Entity implements \JsonSerializable {

    protected $aProps = [];

    /**
     * @param string $sName
     * @param mixed  $xValue
     *
     * @return Entity
     */
    public function setProp($sName, $xValue) {

        $this->aProps[$sName] = $xValue;
        return $this;
    }

    /**
     * @param string $sName
     *
     * @return mixed
     */
    public function getProp($sName) {

        if (isset($this->aProps[$sName])) {
            return $this->aProps[$sName];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAllProps() {

        return $this->aProps;
    }

    /**
     * @return array
     */
    public function asArray() {

        $aResult = [];
        foreach($this->aProps as $sName => $xValue) {
            $aResult[$sName] = $xValue;
        }
        return $aResult;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {

        return $this->asArray();
    }

}

// EOF