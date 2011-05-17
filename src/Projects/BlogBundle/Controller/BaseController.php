<?php

/**
 * Blog
 * 
 * @author    Kanat Gailimov <gailimov@gmail.com>
 * @copyright Copyright (c) 2011 Kanat Gailimov, (http://gailimov.info)
 */


namespace Projects\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Base controller
 */
class BaseController extends Controller
{
    /**
     * Entity manager
     * 
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Configuration
     * 
     * @var array
     */
    protected $config = array('titleSeparator' => '::');

    /**
     * URL
     * 
     * @var string
     */
    protected $url;

    /**
     * Title
     * 
     * @var string
     */
    protected $title;

    /**
     * Description
     * 
     * @var string
     */
    protected $description;

    /**
     * Comments moderation status
     * 
     * @var integer
     */
    protected $commentsModerated;

    /**
     * Pages
     * 
     * @var array
     */
    protected $pages = array();

    /**
     * Categories
     * 
     * @var array
     */
    protected $categories = array();

    /**
     * Links
     * 
     * @var array
     */
    protected $links = array();

    /**
     * Initialize
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    protected function init()
    {
        $em                      = $this->getEm();
        $config                  = $em->getRepository('ProjectsBlogBundle:Config')->get();
        $this->url               = $config->getUrl();
        $this->title             = $config->getTitle();
        $this->description       = $config->getDescription();
        $this->commentsModerated = $config->getCommentsModerated();
        $this->pages             = $em->getRepository('ProjectsBlogBundle:Post')->getAllPages();
        $this->categories        = $em->getRepository('ProjectsBlogBundle:Category')->getAll();
        $this->links             = $em->getRepository('ProjectsBlogBundle:Link')->findAll();

        return $em;
    }

    /**
     * Get entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEm()
    {
        if ($this->em == null) {
            $this->em = $this->get('doctrine.orm.entity_manager');
        }
        return $this->em;
    }

    /**
     * Create paginator
     * 
     * @param  \Doctrine\ORM\Query       $query Query
     * @param  integer                   $num   Num
     * @return \Zend\Paginator\Paginator
     */
    protected function createPaginator(\Doctrine\ORM\Query $query, $num = null)
    {
        $adapter = $this->get('knplabs_paginator.adapter');
        $adapter->setQuery($query, $num);
        $adapter->setDistinct(true);

        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->get('request')->query->get('page', 1));
        // Number of posts
        $paginator->setItemCountPerPage(1);
        // Number of paginator links
        $paginator->setPageRange(5);

        return $paginator;
    }
}
