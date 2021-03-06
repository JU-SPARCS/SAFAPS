<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Invoice
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\InvoiceRepository")
 */
class Invoice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="period_start", type="date", nullable=false)
     */
    private $periodStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="period_end", type="date", nullable=false)
     */
    private $periodEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="totalCost", type="float", nullable=false)
     */
    private $totalCost;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=16, nullable=false)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="ModelBundle\Entity\Organization", inversedBy="invoices")
     */
    private $organization;


    public function __construct() 
    {
	$this->setCreationDate((new \DateTime())->format('Y-m-d H:i:s'));
    }

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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Invoice
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set periodStart
     *
     * @param \DateTime $periodStart
     * @return Invoice
     */
    public function setPeriodStart($periodStart)
    {
        $this->periodStart = $periodStart;

        return $this;
    }

    /**
     * Get periodStart
     *
     * @return \DateTime 
     */
    public function getPeriodStart()
    {
        return $this->periodStart;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return Invoice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Invoice
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set organization
     *
     * @param \ModelBundle\Entity\Organization $organization
     * @return Invoice
     */
    public function setOrganization(\ModelBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \ModelBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set periodEnd
     *
     * @param \DateTime $periodEnd
     * @return Invoice
     */
    public function setPeriodEnd($periodEnd)
    {
        $this->periodEnd = $periodEnd;

        return $this;
    }

    /**
     * Get periodEnd
     *
     * @return \DateTime 
     */
    public function getPeriodEnd()
    {
        return $this->periodEnd;
    }

    /**
     * Set totalCost
     *
     * @param string $totalCost
     * @return Invoice
     */
    public function setTotalCost($totalCost)
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    /**
     * Get totalCost
     *
     * @return string 
     */
    public function getTotalCost()
    {
        return $this->totalCost;
    }
}
