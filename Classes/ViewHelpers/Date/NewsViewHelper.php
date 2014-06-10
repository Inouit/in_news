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
    public function render(\Inouit\InNews\Domain\Model\News $news = NULL, $dDate = 0, $dFrom = 0, $dTo = 0) {
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
                $content = $GLOBALS['TSFE']->sL($llKey.'date.the').$this->formatDate->render(($displayDate ? $displayDate : $from), $GLOBALS['TSFE']->sL($llKey.'dateFormat'));
            }else {
                if($from && $to) {
                    if($from->getTimeStamp() < time() && $to->getTimeStamp() > time() ){        // Until day
                        $content = $GLOBALS['TSFE']->sL($llKey.'date.toOnly').$this->formatDate->render($to, $GLOBALS['TSFE']->sL($llKey.'dateFormat'));
                    }else {                                                                     // Different days
                        $content = $GLOBALS['TSFE']->sL($llKey.'date.from').$this->formatDate->render($from, $GLOBALS['TSFE']->sL($llKey.'dateFormat')).$GLOBALS['TSFE']->sL($llKey.'date.to').$this->formatDate->render($to, $GLOBALS['TSFE']->sL($llKey.'dateFormat'));
                    }
                }else {
                    if($from) {                                                                 // From day
                        $content = $GLOBALS['TSFE']->sL($llKey.'date.fromOnly').$this->formatDate->render($from, $GLOBALS['TSFE']->sL($llKey.'dateFormat'));
                    }else {                                                                     // Until day
                        $content = $GLOBALS['TSFE']->sL($llKey.'date.toOnly').$this->formatDate->render($to, $GLOBALS['TSFE']->sL($llKey.'dateFormat'));
                    }
                }
            }
        }

        return $content;
    }
}