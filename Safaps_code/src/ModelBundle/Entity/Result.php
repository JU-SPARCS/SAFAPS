<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\ResultRepository")
 */
class Result
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="stress", type="smallint")
     */
    private $stress;

    /**
     * @var integer
     *
     * @ORM\Column(name="fatigue", type="smallint")
     */
    private $fatigue;


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
     * Set stress
     *
     * @param integer $stress
     * @return Result
     */
    public function setStress($stress)
    {
        $this->stress = $stress;

        return $this;
    }

    /**
     * Get stress
     *
     * @return integer 
     */
    public function getStress()
    {
        return $this->stress;
    }

    /**
     * Set fatigue
     *
     * @param integer $fatigue
     * @return Result
     */
    public function setFatigue($fatigue)
    {
        $this->fatigue = $fatigue;

        return $this;
    }

    /**
     * Get fatigue
     *
     * @return integer 
     */
    public function getFatigue()
    {
        return $this->fatigue;
    }
}
