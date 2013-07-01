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
	 * Find Recursive list
	 *
	 * @param array $parent list of parent id
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function findAllRecursivly($parent = 0) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		$results = $query->matching(
			$query->logicalAnd(
				$query->equals('parent', (int)$parent)
			))->execute();

		if($results && count($results)){
			$cats = array();
			foreach($results as $k=>$v) {
				$cats[$v->getUid()] = $v;
				$cats[$v->getUid()]->setChildren(self::findAllRecursivly($v->getUid()));

			}
		}

		return $cats;
	}
}
?>