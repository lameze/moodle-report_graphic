<?php
/*
 * Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
 */

namespace Astroanu\C3jsPHP;

/**
 * Class Callback
 * @package Astroanu\C3jsPHP
 */
class Callback implements \JsonSerializable
{
    /**
     * @var string
     */
    private $script;

    /**
     * @param string $script
     */
    public function __construct($script)
    {
        $this->script = $script;
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param string $script
     */
    public function setScript($script)
    {
        $this->script = $script;
    }

    /**
     * @return string
     */
    public function JsonSerialize()
    {
        return $this->script;
    }
}
