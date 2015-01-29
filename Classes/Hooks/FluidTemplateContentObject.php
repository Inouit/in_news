<?php
namespace Inouit\InNews\Hooks;

class FluidTemplateContentObject extends \TYPO3\CMS\Frontend\ContentObject\FluidTemplateContentObject {

  /**
   * Assign content object renderer data and current to view
   *
   * @param array $conf Configuration
   * @return void
   */
  protected function assignContentObjectDataAndCurrent(array $conf) {
    parent::assignContentObjectDataAndCurrent($conf);
    //get news doktype
    $extConf = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_news'];
    if($extConf !== null) {
      $settings = unserialize($extConf);
      if($settings['newsDoktype'] && $this->cObj->data['doktype']==$settings['newsDoktype']) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $newsRepository = $objectManager->get('\Inouit\InNews\Domain\Repository\NewsRepository');
        $news = $newsRepository->findByUid($this->cObj->data['uid']);

        $this->view->assign('news', $news);
      }
    }
  }

}