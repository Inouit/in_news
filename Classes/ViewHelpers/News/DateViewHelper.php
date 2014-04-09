<?php
namespace Inouit\InNews\ViewHelpers\News;

class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper  {

    /**
     * @var \TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper
     * @inject
     */
    protected $formatDate;


    /**
     * Render the Date ViewHelper
     * @param  Inouit\InNews\Domain\Model\News $news targetted news
     * @return string HTML render
     */
    public function render(Inouit\InNews\Domain\Model\News $news) {
        if ($news === NULL) {
            $news = $this->renderChildren();
        }

        $llKey = 'LLL:EXT:in_news/Resources/Private/Language/locallang.xlf:';
        $displayDate = $news->getDisplayDate();
        $from = $news->getFrom();
        $to = $news->getTo();

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

        return $content;
    }
}