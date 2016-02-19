<?php
/* -------------------------------------------------------
 * C3Charts for PHP. Demo site
 *
 * MIT License
 * -------------------------------------------------------
 */

include_once $sDataFile;

if (!empty($aItem['template'])) {
    $sTemplate = ROOT_DIR . '/' . $aItem['template'];
} else {
    $sTemplate = ROOT_DIR . '/_chart.tpl';
}

$sPhpCode = htmlspecialchars(file_get_contents($sDataFile));
$sHtml = file_get_contents($sTemplate);

$sHtmlCode = '';
if (preg_match_all('/\<\!-- \[\[BEGIN CHART HTML\]\]--\>(.*)\<\!-- \[\[END CHART HTML\]\]-->/sU', $sHtml, $aM)) {
    foreach($aM[1] as $sMatch) {
        $sHtmlCode .= $sMatch . "\n";
    }
}
$sHtmlCode = htmlspecialchars($sHtmlCode);

$sJsCode = '';
if (preg_match_all('/\<\!-- \[\[BEGIN CHART JS\]\]--\>(.*)\<\!-- \[\[END CHART JS\]\]-->/sU', $sHtml, $aM)) {
    foreach($aM[1] as $sMatch) {
        $sJsCode .= $sMatch . "\n";
    }
}
$sJsCode = htmlspecialchars($sJsCode);

include_once $sTemplate;

// EOF