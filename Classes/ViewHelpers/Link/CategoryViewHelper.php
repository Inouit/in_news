<?php
// namespace Inouit\InNews\Classes\ViewHelpers\Link;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */
/**
 * A view helper for creating links to TYPO3 pages.
 *
 * = Examples =
 *
 * <code title="link to the current page">
 * <f:link.page>page link</f:link.page>
 * </code>
 * <output>
 * <a href="index.php?id=123">page link</f:link.action>
 * (depending on the current page and your TS configuration)
 * </output>
 */

class Tx_InNews_ViewHelpers_Link_CategoryViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
    * Arguments initialization
    *
    * @return void
    */
    public function initializeArguments() {
     $this->registerUniversalTagAttributes();
     $this->registerTagAttribute('category', '\Inouit\InNews\Classes\Model\Category', 'Category to link', true);
    }

    /**
     * render
     *
     * @param integer|null $pageUid target page. See TypoLink destination
     * @param array $additionalParams query parameters to be attached to the resulting URI
     * @param integer $pageType type of the target page. See typolink.parameter
     * @param boolean $noCache set this to disable caching for the target page. You should not need this.
     * @param boolean $noCacheHash set this to supress the cHash query parameter created by TypoLink. You should not need this.
     * @param string $section the anchor to be added to the URI
     * @param boolean $linkAccessRestrictedPages If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
     * @param boolean $absolute If set, the URI of the rendered link is absolute
     * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
     * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
     * @return string Rendered page URI
     * @throws \InvalidArgumentException
     */
    public function render($pageUid = null, array $additionalParams = array(), $pageType = 0, $noCache = false, $noCacheHash = false, $section = '', $linkAccessRestrictedPages = false, $absolute = false, $addQueryString = false, array $argumentsToBeExcludedFromQueryString = array())
    {
        $settings = $this->templateVariableContainer->get('settings');

        // $pageUid override
        $newPageUid = 0;
        if(isset($this->arguments['category']) && $this->arguments['category'] !== null){
            if(!is_object($this->arguments['category'])){
                throw new \InvalidArgumentException('The argument "category" should be a "\Inouit\InNews\Classes\Model\Category"');
            } else {
                if(is_int($this->arguments['category']->getListPage())) {
                    $newPageUid = $this->arguments['category']->getListPage();
                }

                $additionalParams['tx_innews_pi1']['category'] = $this->arguments['category']->getUid();
            }
        }

        if($newPageUid) {
            $pageUid = $newPageUid;
        }elseif($settings['listPage']) {
            $pageUid = $settings['listPage'];
        }elseif(!$pageUid) {
            $pageUid = $GLOBALS['TSFE']->id;
        }

        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uri = $uriBuilder->reset()->setTargetPageUid($pageUid)
                    ->setTargetPageType($pageType)
                    ->setNoCache($noCache)
                    ->setUseCacheHash(!$noCacheHash)
                    ->setSection($section)
                    ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
                    ->setArguments($additionalParams)
                    ->setCreateAbsoluteUri($absolute)
                    ->setAddQueryString($addQueryString)
                    ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
                    ->build();

        $this->tag->addAttribute('href', $uri);
        $this->tag->removeAttribute('category');
        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }
}

?>