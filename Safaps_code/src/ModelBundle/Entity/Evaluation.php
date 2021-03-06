<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evaluation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\EvaluationRepository")
 */
class Evaluation
{
    // Constructor
    // Sets the dateCreated to be now. 
    public function __construct(){
        $this->setDateCreated(new \DateTime());
	$this->setStatus('create');
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\Url()
     * @ORM\Column(name="response_URL", type="string", length=1024)
     */
    private $responseURL;

    /**
     * @ORM\ManyToOne(targetEntity="ModelBundle\Entity\Manager")
     */
    private $manager;

    /**
     * @ORM\OneToOne(targetEntity="ModelBundle\Entity\Result", cascade={"persist"})
     */
    private $result;

    /**
     * @ORM\Column(name="dateCreated", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @ORM\Column(name="dateCompleted", type="datetime", nullable=true)
     */
    private $dateCompleted;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum('create', 'pending', 'ongoing', 'done', 'cancelled')")
     */
    private $status;

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
     * Set responseURL
     *
     * @param string $responseURL
     * @return Evaluation
     */
    public function setResponseURL($responseURL)
    {
        $this->responseURL = $responseURL;

        return $this;
    }

    /**
     * Get responseURL
     *
     * @return string 
     */
    public function getResponseURL()
    {
        return $this->responseURL;
    }

    /**
     * Set manager
     *
     * @param \ModelBundle\Entity\Manager $manager
     * @return Evaluation
     */
    public function setManager(\ModelBundle\Entity\Manager $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \ModelBundle\Entity\Manager 
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set result
     *
     * @param \ModelBundle\Entity\Result $result
     * @return Evaluation
     */
    public function setResult(\ModelBundle\Entity\Result $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return \ModelBundle\Entity\Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Evaluation
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateCompleted
     *
     * @param \DateTime $dateCompleted
     * @return Evaluation
     */
    public function setDateCompleted($dateCompleted)
    {
        $this->dateCompleted = $dateCompleted;

        return $this;
    }

    /**
     * Get dateCompleted
     *
     * @return \DateTime 
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Evaluation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
