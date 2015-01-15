<?php
namespace Inouit\InNews\ViewHelpers\Date;

class NewsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper  {

    /**
     * @var \TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper
     * @inject
     */
    protected $formatDate;


    /**
     * Render the Date ViewHelper
     * @param  Inouit\InNews\Domain\Model\News $news targetted news
     * @param  mixed $news targetted news
     * @param  mixed $displayDate
     * @param  mixed $from
     * @param  mixed $to
     * @return string HTML render
     */
    public function render(\Inouit\InNews\Domain\Model\News $news = NULL, $dDate = 0, $dFrom = 0, $dTo = 0, $dateFormat = '') {
      if(!$dateFormat) {
        $dateFormat = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('dateFormat', 'in_news');
      }

      if ($news != NULL) {
        $displayDate = $news->getDisplayDate();
        $from = $news->getFrom();
        $to = $news->getTo();
      }else{
        if($dDate != 0){
          $displayDate = new \Datetime();
          $displayDate->setTimestamp($dDate);
        }
        if($dFrom != 0){
          $from = new \Datetime();
          $from->setTimestamp($dFrom);
        }
        if($dTo != 0){
          $to = new \Datetime();
          $to->setTimestamp($dTo);
        }
      }

      if($displayDate == 0 && $from == 0 && $to == 0){
        $content = $this->renderChildren();
      }else {
        $llKey = 'LLL:EXT:in_news/Resources/Private/Language/locallang.xlf:';

        $class='';
        $content = '';

            if($displayDate || ($from == $to && $from != 0)) {                                  // Same day
              $content = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('date.the',
                'in_news',
                array(
                  $this->formatDate->render(($displayDate ? $displayDate : $from), $dateFormat)
                  ));
            }else {
              if($from && $to) {
                    if($from->getTimeStamp() < time() && $to->getTimeStamp() > time() ){        // Until day
                      $content = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('date.toOnly',
                        'in_news',
                        array(
                          $this->formatDate->render($to, $dateFormat)
                          ));
                    }else {                                                                     // Different days
                      $content = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('date.fromTo', 
                        'in_news',
                        array(
                          $this->formatDate->render($from, $dateFormat),
                          $this->formatDate->render($to, $dateFormat)
                          )
                        );
                    }
                  }else {
                    if($from) {                                                                 // From day
                      $content = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('date.fromOnly',
                        'in_news',
                        array(
                          $this->formatDate->render($from, $dateFormat)
                          ));
                    }else {                                                                     // Until day
                      $content = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('date.toOnly',
                        'in_news',
                        array(
                          $this->formatDate->render($to, $dateFormat)
                          ));
                    }
                  }
                }
              }

              return $content;
            }
          }