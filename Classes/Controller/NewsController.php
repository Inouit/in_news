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
     * listChronoAction
     *
     * @return void
     */
    public function listChronoAction()
    {
        $news = $this->insertCategoryFilter();
        $this->view->assign('news', $news);
    }

    /**
     * listAnteChronoAction
     *
     * @return void
     */
    public function listAnteChronoAction()
    {
        $news = $this->insertCategoryFilter(\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING);
        $this->view->assign('news', $news);
    }

    /**
     * Insert category filter in list view
     * @return ArrayCollection news list
     */
    protected function insertCategoryFilter($orderDirection = null) {
        if ($this->request !== null) {
            $args = $this->request->getArguments();
            $categoryUid = $args['category'];

            if ($categoryUid !==null && !empty($categoryUid)) {
                $category = $this->categoryRepository->findOneByUid($categoryUid);
                $this->view->assign('requestedCategory', $category);

                return $this->newsRepository->findByCategory($category, $orderDirection);

            }
        }

        return $this->newsRepository->findAll($orderDirection);
    }

}
?>