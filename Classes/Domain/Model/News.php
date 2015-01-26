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
   */
  protected $media;

  /**
    * List of categories
    *
    * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Inouit\InNews\Domain\Model\Category>
    * @lazy
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
     * Populate the object
     *
     * @param  array  $datas
     * @return void
     */
    public function __populate(array $datas = array()) {
      if(count($datas)){
        if($datas['tx_innews_news_top']) {
          $datas['top'] = $datas['tx_innews_news_top'];
          unset($datas['tx_innews_news_top']);
        }
        if($datas['tx_innews_news_teaser']) {
          $datas['teaser'] = $datas['tx_innews_news_teaser'];
          unset($datas['tx_innews_news_teaser']);
        }
        if($datas['tx_innews_event_further']) {
          $datas['further'] = $datas['tx_innews_event_further'];
          unset($datas['tx_innews_event_further']);
        }
        if($datas['tx_innews_news_display_date']) {
          $datas['displayDate'] = new \DateTime();
          $datas['displayDate']->setTimestamp($datas['tx_innews_news_display_date']);
          unset($datas['tx_innews_news_display_date']);
        }
        if($datas['tx_innews_event_from']) {
          $datas['from'] = new \DateTime();
          $datas['from']->setTimestamp($datas['tx_innews_event_from']);
          unset($datas['tx_innews_event_from']);
        }
        if($datas['tx_innews_event_to']) {
          $datas['to'] = new \DateTime();
          $datas['to']->setTimestamp($datas['tx_innews_event_to']);
          unset($datas['tx_innews_event_to']);
        }
        if($datas['categories']) {
          $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
          $datas['categories'] = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\LazyObjectStorage', $this, 'categories', $datas['categories']);
        }
        if($datas['media']) {
          $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
          $datas['media'] = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\LazyObjectStorage', $this, 'media', $datas['media']);
        }
        foreach($datas as $key=>$value){
          $this->_setProperty($key, $value);
        }
      }

      $this->__wakeup();
    }

    /**
     * Add a Category
     *
     * @param \Inouit\InNews\Domain\Model\Category $categories
     * @return void
     */
    public function addCategories(\Inouit\InNews\Domain\Model\Category $categories) {
        $this->categories->attach($categories);
    }

    /**
     * Remove a Category
     *
     * @param \Inouit\InNews\Domain\Model\Category $categoriesToRemove The Grades to be removed
     * @return void
     */
    public function removeCategories(\Inouit\InNews\Domain\Model\Category $categoriesToRemove) {
        $this->categories->detach($categoriesToRemove);
    }

    /**
    * Returns the Categories
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding \Inouit\InNews\Domain\Model\Category objects
    */
    public function getCategories() {
        if($this->categories && intVal($this->categories)){
          // $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
          // $newsRepository = $objectManager->get('\Inouit\InNews\Domain\Repository\CategoryRepository');
          // $this->categories = $newsRepository->findByNews($this);
         
        }
       if ($this->categories instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
          $this->categories->_loadRealInstance();
        }
        return $this->categories;
    }

    /**
     * Sets the Categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories One or more \Inouit\InNews\Domain\Model\Category objects
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
        $this->categories = $categories;
    }

    /**
     * Add a Media
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $media
     * @return void
     */
    public function addMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $media) {
        $this->media->attach($media);
    }

    /**
     * Remove a Media
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $mediaToRemove The Grades to be removed
     * @return void
     */
    public function removeMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $mediaToRemove) {
        $this->media->detach($mediaToRemove);
    }

    /**
    * Returns the Media
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding \TYPO3\CMS\Extbase\Domain\Model\FileReference objects
    */
    public function getMedia() {
        return $this->media;
    }

    /**
    * Returns the Media Not First
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding \TYPO3\CMS\Extbase\Domain\Model\FileReference objects
    */
    public function getNotFirstMedia() {
        $medias = null;
        if (count($this->media)){
            $cpt = 0;
            foreach ($this->media as $key => $media) {
                if ($cpt){
                    $medias[$key] = $media;
                }else{
                    $cpt = 1;
                }
            }
        }
        return $medias;
    }

    /**
    * Returns the Media
    *
    * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage A storage holding \TYPO3\CMS\Extbase\Domain\Model\FileReference objects
    */
    public function getFirstMedia() {
        return count($this->media) ? $this->media->current() : null;
    }

    /**
     * Sets the Media
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $media One or more \TYPO3\CMS\Extbase\Domain\Model\FileReference objects
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

    /**
     * get Number Show Category
     *
     * @return boolean
     */
    public function getNbrShowCategories() {
        $findShowCat = false;
        if ($this->getCategories() && $this->getCategories()->count()){
            foreach ($this->getCategories() as $categorie) {
                if ( $categorie->getShowThisCat() ){
                    $findShowCat = true;
                }
            }
        }
        return $findShowCat;
    }

}
?>