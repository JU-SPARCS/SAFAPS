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
     * @ORM\OneToOne(targetEntity="ModelBundle\Entity\Organization", cascade={"persist"})
     */
    private $result;

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
     * @param \ModelBundle\Entity\Organization $result
     * @return Evaluation
     */
    public function setResult(\ModelBundle\Entity\Organization $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return \ModelBundle\Entity\Organization 
     */
    public function getResult()
    {
        return $this->result;
    }
}
