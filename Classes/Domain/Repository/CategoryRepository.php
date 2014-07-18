<?php
namespace Inouit\InNews\Domain\Repository;

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
 *
 *
 * @package in_news
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\QueryInterface Targeted categories
	 */
	protected $targetedCategories;

	/**
	 * Filter only news related to targeted categories
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 */
	protected function categoriesFilter($query, $settings) {
		if($settings['targetedCategories'] && $cats = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $settings['targetedCategories'])) {
			return $query->in('uid', $cats);
		}

		return null;
	}

	/**
	 * Get children with recursivity
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface  $query
	 * @param  integer $parent
	 * @param  array   $matching
	 * @return array
	 */
	protected function getChildrenRecursivly($query, $parent = 0, $matching = array()) {
		// MATCHING
		$queryMatching = $matching;
		array_push($queryMatching, $query->equals('parent', (int)$parent));
		// -- Apply matching
		$query->matching($query->logicalAnd($queryMatching));
		$results = $query->execute();

		$cats = array();
		if($results && count($results)){
			foreach($results as $k=>$v) {
				$cats[$v->getUid()] = $v;
				$cats[$v->getUid()]->setChildren(self::getChildrenRecursivly($query, $v->getUid(), $matching));

			}
		}

		return $cats;
	}

	/**
	 * Find Recursive list
	 *
	 * @param array $parent list of parent id
	 * @param array $settings Typoscript settings
	 * @return array
	 */
	public function findAllRecursivly($parent = 0, $settings) {
		$query = $this->createQuery();


		// MATCHING
		if( $catsMatching =  $this->categoriesFilter($query, $settings) ){
			$matching = array($catsMatching);
		}else {
			$matching = array();
		}
		
		$query->setOrderings(array('sorting' => "ASC"));

		return $this->getChildrenRecursivly($query, $parent, $matching);
	}
}
?>
