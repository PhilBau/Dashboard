<?php
/**
 * Copyright Zikula Foundation 2012
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * Dashboard_Entity_Widget
 *
 * @ORM\Table(name="dashboard_widget")
 * @ORM\Entity(repositoryClass="Dashboard_Entity_Repository_Widget")
 */
class Dashboard_Entity_Widget
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @var string $module
     *
     * @ORM\Column(name="module", type="string", length=50)
     */
    private $module;

    /**
     * @var string $class
     *
     * @ORM\Column(name="class", type="string", length=120)
     */
    private $class;

     /**
      * @var string $parameters
      *
      * @ORM\Column(name="parameters", type="string", length=500)
      */
     private $parameters;


    /**
     * @var string $def_widget
     *
     * @ORM\Column(name="def_widget", type="integer")
    */
    private $def_widget;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Dashboard_Entity_Widget
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set module
     *
     * @param string $module
     *
     * @return Dashboard_Entity_Widget
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string 
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return Dashboard_Entity_Widget
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     *
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }
	
    /**
     * Set parameters
     *
     * @param string $parameters
     *
     * @return Dashboard_Entity_Widget
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     *
     * Get parameters
     *
     * @return string
     */
    public function getParameters()
    {			
        return $this->parameters;
    }


    /**
     * Set def_widget
     *
     * @param integer $def_widget
     *
     * @return Dashboard_Entity_Widget
     */
     public function setDefWidget($def_widget)
     {
	$this->def_widget = $def_widget;

        return $this;
     }

     /**
      *
      * Get def_widget
      *
      * @return integer
      */
     public function getDefWidget()
     {
	return $this->def_widget;
     }

}
