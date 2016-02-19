<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

$sGroup = 'single';

include_once '../samples.php';

$sDemo = (empty($_GET['demo']) ? $_GET['demo'] : '');

if (empty($_GET['demo'])) {
    $sDemo = 'line';
} else {
    $sDemo = $_GET['demo'];
}

if (empty($aDemos[$sGroup]['items'][$sDemo])) {
    $sDemo = 'link';
}

$aItem = $aDemos[$sGroup]['items'][$sDemo];

$sMenuItem = $aItem['title'];

$sDataFile = ROOT_DIR . '/' . $aItem['data'];

if (is_file($sDataFile)) {
    include_once 'draw.php';
}

// EOF