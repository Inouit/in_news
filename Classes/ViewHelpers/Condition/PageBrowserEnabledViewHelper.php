<?php
namespace Inouit\InNews\ViewHelpers\Condition;

class PageBrowserEnabledViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper  {
    /**
     * Check if the pagebrowser need to be displayed
     * @param  mixed $results QueryResult
     * @param  integer $limit Typoscript limit per page
     * @return mixed
     */
    public function render($results, $limit) {
        if ($limit && $limit > 0 && count($results) > $limit) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}