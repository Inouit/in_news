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
use Inouit\InNews\Domain\Model\Category;

/**
 *
 *
 * @package in_news
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
use \TYPO3\CMS\Extbase\Persistence\QueryInterface;

class NewsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	const CATEGORY_UNION_OR = 'OR';
	const CATEGORY_UNION_AND = 'AND';
	const CATEGORY_UNION_ANY = 'ANY';

	 protected $defaultOrderings = array(
	 	'displayDate' => QueryInterface::ORDER_DESCENDING
	);

	/**
	 * Override default createQuery to match with the news doktype
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 * @api
	 */
	public function createQuery() {
		$query = parent::createQuery();

		//filter by doktype
		$extConf = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['in_news'];
		if($extConf !== null) {
			$settings = unserialize($extConf);
			if($settings['newsDoktype']) {
				$query->matching(
					$query->equals('doktype', $settings['newsDoktype'])
					);
			}
		}

		return $query;
	}

	/**
	 * Find all news
	 * @param  string $orderDirection ASC or DESC
	 * @return ArrayCollection
	 */
	public function findAll($orderDirection = null) {
		$query = $this->createQuery();

		if($orderDirection !== null) {
			$query->setOrderings(array('crdate' => $orderDirection));
		}

		return $query->execute();
	}

	/**
	 * Apply typoscript settings to a query
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $settings
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected function applySettings($query, $settings) {
		// MATCHING
		$matching = array();
		$matching = $this->dateFilter($matching, $query, $settings);
		$matching = $this->onlyTopFilter($matching, $query, $settings);
		$matching = $this->categoriesFilter($matching, $query, $settings);
		$matching = $this->excludePastEventsFilter($matching, $query, $settings);
		// -- Apply matching
		if(count($matching)){
			$query->matching($query->logicalAnd($matching));
		}

		// ORDERS BY
		$query = $this->setOrderBy($query, $settings);

		// LIMIT
		$query = $this->setLimit($query, $settings);

		return $query;
	}

	/**
	 * Filter news by start date and duration
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 */
	protected function dateFilter($matching, $query, $settings) {
		if($settings['dateRange'] && $settings['dateRange']['start'] && trim($settings['dateRange']['start']) != '') {
			$startdate = new \DateTime($settings['dateRange']['start']);
			$enddate = clone($startdate);
			$enddate->add(\DateInterval::createFromDateString($settings['dateRange']['duration']));

			$starttime = $startdate->getTimestamp();
			$endtime = $enddate->getTimestamp();

			array_push($matching,
				$query->logicalOr(
					$query->logicalAnd(
						$query->greaterThan('displayDate', 0),
						$query->logicalOr(
							$query->equals('displayDate', $starttime),
							$query->logicalAnd(
								$query->greaterThan('displayDate', $starttime),
								$query->lessThan('displayDate', $endtime)
							)
						)
					),
					$query->logicalAnd(
						$query->greaterThan('from', 0),
						$query->logicalOr(
							$query->equals('from', $starttime),
							$query->logicalAnd(
								$query->greaterThan('from', $starttime),
								$query->lessThanOrEqual('from', $endtime)
							)
						)
					),
					$query->logicalAnd(
						$query->greaterThan('to', 0),
						$query->logicalOr(
							$query->equals('to', $starttime),
							$query->logicalAnd(
								$query->greaterThan('to', $starttime),
								$query->lessThan('to', $endtime)
							)
						)
					),
					$query->logicalAnd(
						$query->greaterThan('from', 0),
						$query->greaterThan('to', 0),
						$query->lessThanOrEqual('from', $starttime),
						$query->greaterThanOrEqual('to', $endtime)
					)
				)
			);
		}

		return $matching;
	}

	/**
	 * Filter only the highlighted news
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 */
	protected function onlyTopFilter($matching, $query, $settings) {
		if($settings['onlyTop'] == 1) {
			array_push($matching, $query->equals('top', 1));
		}

		return $matching;
	}

	/**
	 * Filter only news related to targeted categories
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 */
	protected function categoriesFilter($matching, $query, $settings) {
		if($settings['targetedCategories']) {
			return (strpos($settings['targetedCategories'], '&') == FALSE ? $this->oneLevelFilter($matching, $query, $settings) : $this->multiLevelFilter($matching, $query, $settings));
		}else {
			return $matching;
		}
	}

	/**
	 * Filter categories with complex operation
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 *
	 * @example	targetedCategories can be separated by &
	 * @example	3,8&12 will result to a filter (3 OR 8) AND 12
	 */
	protected function multiLevelFilter($matching, $query, $settings) {
		$groups = explode('&', $settings['targetedCategories']);

		$matching2 = array();
		foreach($groups as $group) {
			if($cats = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $group)) {
				$matching3 = array();
				foreach($cats as $cat) {
					array_push($matching3, $query->contains('categories', $cat));
				}
				array_push($matching2, $query->logicalOr($matching3));
			}
		}
		array_push($matching, $query->logicalAnd($matching2));

		return $matching;
	}

	/**
	 * Filter categories with simple operation
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return array
	 *
	 * @example	targetedCategories can be separated by settings.targetedCategoriesUnion
	 * @example	3,8,12 will result to a filter (3 OR|AND|ANY 8 OR|AND|ANY 12)
	 */
	protected function oneLevelFilter($matching, $query, $settings) {
		if($cats = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $settings['targetedCategories'])) {
			$matching2 = array();

			foreach($cats as $cat) {
				array_push($matching2, $query->contains('categories', $cat));
			}
			switch (strtoupper($settings['targetedCategoriesUnion'])) {
				case self::CATEGORY_UNION_AND:
					array_push($matching, $query->logicalAnd($matching2));
					break;
				case self::CATEGORY_UNION_ANY:
					array_push($matching, $query->logicalNot($query->logicalOr($matching2)));
					break;
				case self::CATEGORY_UNION_OR:
				default:
					array_push($matching, $query->logicalOr($matching2));
					break;
			}
		}

		return $matching;
	}

	/**
	 * Filter only news which are not past's news
	 * @param  array $matching current constraints
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param  array $settings
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected function excludePastEventsFilter($matching, $query, $settings) {
		if($settings['excludePastEvents'] == 1) {
			$limit = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			array_push($matching, $query->logicalOr(
				$query->greaterThanOrEqual('to', $limit),
				$query->greaterThanOrEqual('displayDate', $limit)
			));
		}

		return $matching;
	}

	/**
	 * Set the order by depending on settings
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $settings
	 * @return  array
	 */
	protected function setOrderBy( $query, $settings) {
		$orders = array();
		// -- Highilighted first
		if($settings['topFirst'] == 1) {
			$orders['top'] = QueryInterface::ORDER_DESCENDING;
		}
		// -- Others orders
		$orders[$settings['orderBy']] = $settings['orderDirection'];
		// -- Default ordering
		$orders['from'] = $settings['orderDirection'];
		$orders['to'] = $settings['orderDirection'];

		return $query->setOrderings($orders);
	}

	/**
	 * Set the limit of the query depending on settings
	 * @param  \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $settings
	 * @return  \TYPO3\CMS\Extbase\Persistence\QueryInterface
	 */
	protected function setLimit($query, $settings) {
		if($settings['limit'] && intVal($settings['limit']) > 0) {
			$query->setLimit(intVal($settings['limit']));
		}

		return $query;
	}

	/**
	 * Find all news by settings
	 * @param  array $settings Typoscript settings
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findAllBySettings($settings) {
		$query = $this->createQuery();

		$query = $this->applySettings($query, $settings);

		return $query->execute();
	}
}
?>
