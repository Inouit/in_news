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
class News extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * @var integer
     */
    protected $uid;

    /**
     * @var string
     */
    protected $title;

	/**
	 * @var DateTime
	 */
    protected $crdate;

	/**
	 * @var DateTime
	 */
    protected $starttime;

	/**
	 * media
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
 	 * @lazy
	 */
	protected $media;

    /**
      * List of categories
      *
      * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
      */
     protected $categories;

    /**
     * @var boolean
     */
    protected $top;

	/**
	 * @var string
	 */
    protected $teaser;

  /**
   * @var DateTime
   */
    protected $displayDate;

	/**
	 * @var DateTime
	 */
    protected $from;

	/**
	 * @var DateTime
	 */
    protected $to;

    /**
     * @var string
     */
    protected $further;

    /**
     * Constructs this post
     */
    public function __construct() {
        $this->media = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Add a Category
     *
     * @param Tx_Inannuaire_Domain_Model_Grades $categories
     * @return void
     */
    public function addCategories(Tx_Inannuaire_Domain_Model_Grades $categories) {
        $this->categories->attach($categories);
    }

    /**
     * Remove a Category
     *
     * @param Tx_Inannuaire_Domain_Model_Grades $categoriesToRemove The Grades to be removed
     * @return void
     */
    public function removeCategories(Tx_Inannuaire_Domain_Model_Grades $categoriesToRemove) {
        $this->categories->detach($categoriesToRemove);
    }

    /**
    * Returns the Categories
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding Tx_Inannuaire_Domain_Model_Grades objects
    */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Sets the Categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories One or more Tx_Inannuaire_Domain_Model_Grades objects
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
        $this->categories = $categories;
    }

    /**
     * Add a Media
     *
     * @param Tx_Inannuaire_Domain_Model_Grades $media
     * @return void
     */
    public function addMedia(Tx_Inannuaire_Domain_Model_Grades $media) {
        $this->media->attach($media);
    }

    /**
     * Remove a Media
     *
     * @param Tx_Inannuaire_Domain_Model_Grades $mediaToRemove The Grades to be removed
     * @return void
     */
    public function removeMedia(Tx_Inannuaire_Domain_Model_Grades $mediaToRemove) {
        $this->media->detach($mediaToRemove);
    }

    /**
    * Returns the Media
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding Tx_Inannuaire_Domain_Model_Grades objects
    */
    public function getMedia() {
        return $this->media;
    }

    /**
    * Returns the Media
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding Tx_Inannuaire_Domain_Model_Grades objects
    */
    public function getFirstMedia() {
        return count($this->media) ? $this->media->current() : null;
    }

    /**
     * Sets the Media
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media One or more Tx_Inannuaire_Domain_Model_Grades objects
     * @return void
     */
    public function setMedia(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $media) {
        $this->media = $media;
    }


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
	 * Get creation date
	 *
	 * @return DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Set Creation Date
	 *
	 * @param DateTime $crdate crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get starttime
	 *
	 * @return DateTime
	 */
	public function getStarttime() {
		return ($this->starttime!=0 ? $this->starttime : $this->crdate);
	}

	/**
	 * Set starttime
	 *
	 * @param DateTime $starttime starttime
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	// /**
	//  * sets the media
	//  *
	//  * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $media
	//  *
	//  * @return void
	//  */
	// public function setMedia($media) {
	// 	$this->media = $media;
	// }

	// *
	//  * get the media
	//  *
	//  * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference

	// public function getMedia() {
	// 	if ($this->media instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
	// 		$this->media->_loadRealInstance();
	// 	}
	// 	return ($this->media ? $this->media->getOriginalResource() : null);
	// }

    /**
     * Getter for displayDate
     *
     * @return DateTime
     */
    public function getDisplayDate() {

        return $this->displayDate;
    }


    /**
     * Setter for displayDate
     *
     * @param  DateTime  displayDate
     * @return DateTime
     */
    public function setDisplayDate($displayDate) {
        $this->displayDate = $displayDate;
        return $this;
    }

    /**
     * Getter for top
     *
     * @return is
     */
    public function isTop() {
        return $this->top;
    }


    /**
     * Setter for top
     *
     * @param  is  top
     * @return is
     */
    public function setTop($top) {
        $this->top = $top;
        return $this;
    }



    /**
     * Getter for teaser
     *
     * @return mixed
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * Setter for teaser
     *
     * @param mixed $teaser Value to set
     * @return self
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

	/**
	 * Get from date
	 *
	 * @return DateTime
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * Set from date
	 *
	 * @param DateTime $from from
	 * @return void
	 */
	public function setFrom($from) {
		$this->from = $from;
	}

	/**
	 * Get to date
	 *
	 * @return DateTime
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * Set to Date
	 *
	 * @param DateTime $to to
	 * @return void
	 */
	public function setTo($to) {
		$this->to = $to;
	}

    /**
     * Getter for further
     *
     * @return string
     */
    public function getFurther() {
        return $this->further;
    }


    /**
     * Setter for further
     *
     * @param  string  further
     * @return string
     */
    public function setFurther($further) {
        $this->further = $further;
        return $this;
    }

}
?>