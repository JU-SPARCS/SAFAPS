<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
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
}
