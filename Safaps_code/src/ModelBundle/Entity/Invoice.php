<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="period_start", type="date")
     */
    private $periodStart;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=16)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="ModelBundle\Entity\Organization")
     */
    private $organization;

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
}
