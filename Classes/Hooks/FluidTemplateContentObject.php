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
      if($settings['newsDoktype'] && $this->cObj->data['doktype']) {
        $news = new \Inouit\InNews\Domain\Model\News();
        $news->__populate($this->cObj->data);
        $this->view->assign('news', $news);
      }
    }
  }

}