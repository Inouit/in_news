<?php
namespace Inouit\InNews\Domain\Model;

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
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $children;

	/**
	  * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Inouit\InNews\Domain\Model\Category>
	 */
	protected $parent;

	/**
	 * @var integer
	 */
	protected $listPage;

	/**
	 * @var integer
	 */
	protected $frontendHidden;



	/**
	 * Getter for title
	 *
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Setter for title
	 *
	 * @param mixed $title Value to set
	 * @return self
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Getter for children
	 *
	 * @return mixed
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Setter for children
	 *
	 * @param mixed $children Value to set
	 * @return self
	 */
	public function setChildren($children)
	{
		$this->children = $children;
	}

	/**
	 * Add a Category
	 *
	 * @param \Inouit\InNews\Domain\Model\Category $parent
	 * @return void
	 */
	public function addParent(\Inouit\InNews\Domain\Model\Category $parent) {
		$this->parent->attach($parent);
	}

	/**
	 * Remove a Category
	 *
	 * @param \Inouit\InNews\Domain\Model\Category $parentToRemove The Grades to be removed
	 * @return void
	 */
	public function removeParent(\Inouit\InNews\Domain\Model\Category $parentToRemove) {
		$this->parent->detach($parentToRemove);
	}

	/**
	* Returns the Categories
	*
	* @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding \Inouit\InNews\Domain\Model\Category objects
	*/
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Sets the Categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $parent One or more \Inouit\InNews\Domain\Model\Category objects
	 * @return void
	 */
	public function setParent(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $parent) {
		$this->parent = $parent;
	}

	/**
	 * Getter for listPage
	 *
	 * @return mixed
	 */
	public function getListPage()
	{
		return $this->listPage;
	}

	/**
	 * Setter for listPage
	 *
	 * @param mixed $listPage Value to set
	 * @return self
	 */
	public function setListPage($listPage)
	{
		$this->listPage = $listPage;
	}

	/**
	 * Getter for frontendHidden
	 *
	 * @return mixed
	 */
	public function getFrontendHidden()
	{
		return $this->frontendHidden;
	}

	/**
	 * Setter for frontendHidden
	 *
	 * @param mixed $frontendHidden Value to set
	 * @return self
	 */
	public function setFrontendHidden($frontendHidden)
	{
		$this->frontendHidden = $frontendHidden;
	}

	/**
	 * Getter for children
	 *
	 * @return mixed
	 */
	public function getShowThisCat()
	{
		if ($this->getParent() && $this->getParent()->count() > 0) {
			$checkFrontendHidden = false;
			foreach ($this->getParent()->toArray() as $parent) {
				$checkFrontendHidden = ($checkFrontendHidden || $parent->getShowThisCat());
			}
			return $checkFrontendHidden;
		}
		return !$this->frontendHidden;
	}
}
?>