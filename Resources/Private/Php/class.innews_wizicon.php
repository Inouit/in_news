<?php
/**
 * Add in_news extension to the wizard in page module
 *
 * @package TYPO3
 * @subpackage tx_innews
 */
class innews_pi1_wizicon {

  const KEY = 'in_news';

  /**
   * Processing the wizard items array
   *
   * @param array $wizardItems The wizard items
   * @return array array with wizard items
   */
  public function proc($wizardItems) {
    $wizardItems['plugins_tx_' . self::KEY.'_pi1'] = array(
      'icon'      => t3lib_extMgm::extRelPath(self::KEY) . 'Resources/Public/Icons/ce_wiz.png',
      'title'     => $GLOBALS['LANG']->sL('LLL:EXT:in_news/Resources/Private/Language/locallang_be.xlf:plugin.name'),
      'description' => $GLOBALS['LANG']->sL('LLL:EXT:in_news/Resources/Private/Language/locallang_be.xlf:plugin.description'),
      'params'    => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=innews_pi1'
    );

    return $wizardItems;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_news/Resources/Private/Php/class.innews_wizicon.php']) {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/in_news/Resources/Private/Php/class.innews_wizicon.php']);
}