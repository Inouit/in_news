<?php
namespace Inouit\InNews\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Grégory Copin <gcopin@inouit.com>, Inouit
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package in_news
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */

class NewsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var \Inouit\InNews\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository;

    /**
     * newsRepository
     *
     * @var \Inouit\InNews\Domain\Repository\NewsRepository
     * @inject
     */
    protected $newsRepository;

    /**
     * listAction
     *
     * @return void
     */
    public function listAction()
    {
        if ($this->request !== null) {
            $args = $this->request->getArguments();
            $categoryUid = $args['category'];

            $news = $this->newsRepository->findAllBySettings($this->settings);

            // if ($categoryUid !==null && !empty($categoryUid)) {
            //     $category = $this->categoryRepository->findOneByUid($categoryUid);
            //     $news = $this->newsRepository->findByCategory($category);

            //     $this->view->assign('requestedCategory', $category);
            // } else {
            //     $news = $this->newsRepository->findAll();
            // }
        }
        $this->view->assign('news', $news);
    }

    /**
     * listFutureEventAction
     *
     * @return void
     */
    public function listFutureEventAction()
    {
        if ($this->request !== null) {
            $args = $this->request->getArguments();
            $categoryUid = $args['category'];


            if ($categoryUid !==null && !empty($categoryUid)) {
                $category = $this->categoryRepository->findOneByUid($categoryUid);
                $news = $this->newsRepository->findByCategory($category);

                $this->view->assign('requestedCategory', $category);
            } else {
                $news = $this->newsRepository->findFutureEvents();
            }
        }
        $this->view->assign('news', $news);
    }

    /**
     * @var \Tx_Extbase_Configuration_ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     * Function is used to override the merge of settings via TS & flexforms
     *
     * @param \Tx_Extbase_Configuration_ConfigurationManagerInterface An instance of the Configuration Manager
     * @return void
     */
    public function injectConfigurationManager(\Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
        $this->configurationManager = $configurationManager;

        $tsSettings = $this->configurationManager->getConfiguration(
            \Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'innews',
            'innews_pi1'
        );
        $originalSettings = $this->configurationManager->getConfiguration(
            \Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );
        // start override
        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $overrideSettings = t3lib_div::trimExplode(',', $tsSettings['settings']['overrideFlexformSettingsIfEmpty'], TRUE);
            foreach($overrideSettings as $key) {
                // if flexform setting is empty and value is available in TS
                if ((!isset($originalSettings[$key]) || empty($originalSettings[$key]))
                        && isset($tsSettings['settings'][$key])){
                    $originalSettings[$key] = $tsSettings['settings'][$key];
                }
            }
        }
        $this->settings = $originalSettings;
    }

}
?>