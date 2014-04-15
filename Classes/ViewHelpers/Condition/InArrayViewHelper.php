<?php
namespace Inouit\InNews\ViewHelpers\Condition;

class InArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper  {
    /**
     * Check if the needle is found in haystack
     * @param  mixed $needle
     * @param  string $haystack Typoscript limit per page
     * @return mixed
     */
    public function render($needle, $haystack = '') {
        if(is_string($haystack)) {
            $haystack = explode(',', $haystack);

            if(!is_array($haystack)) {
                return $this->renderElseChild();
            }
        }

        if (in_array($needle, $haystack)) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}