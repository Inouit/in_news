<?php
namespace Inouit\InNews\ViewHelpers;

class IfPageBrowserEnabledViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper  {
    /**
     * Render the Date ViewHelper
     * @param  array $results QueryResult
     * @param  integer $limit Typoscript limit per page
     * @return string HTML render
     */
    public function render(array $results, integer $limit) {
        if ($limit && $limit > 0 && count($results) > $limit) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}