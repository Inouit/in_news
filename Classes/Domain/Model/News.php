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
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
 	 * @lazy
	 */
	protected $media;

	/**
	 * @var string
	 */
    protected $category;

	/**
	 * @var string
	 */
    protected $teaser;
	
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
    protected $where;

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

	/**
	 * sets the media
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $media
	 *
	 * @return void
	 */
	public function setMedia($media) {
		$this->media = $media;
	}

	/**
	 * get the media
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getMedia() {
		if ($this->media instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->media->_loadRealInstance();
		}
		return ($this->media ? $this->media->getOriginalResource() : null);
	}

    /**
     * Getter for category
     *
     * @return \Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Setter for category
     *
     * @param \Category $category Value to set
     * @return self
     */
    public function setCategory(\Category $category)
    {
        $this->category = $category;
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
     * Getter for where
     *
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }
    
    /**
     * Setter for where
     *
     * @param mixed $where Value to set
     * @return self
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }
}
?>