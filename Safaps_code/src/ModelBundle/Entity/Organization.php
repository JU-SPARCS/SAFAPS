<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organization
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\OrganizationRepository")
 */
class Organization
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255)
     */
    private $apiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_mail", type="string", length=255)
     */
    private $invoiceMail;

    /**
     * @ORM\OneToMany(targetEntity="ModelBundle\Entity\Manager", mappedBy="organization", cascade={"all"})
     **/
    private $managers;

    /**
     * @ORM\OneToMany(targetEntity="ModelBundle\Entity\Invoice", mappedBy="organization", cascade={"all"})
     **/
    private $invoices;


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
     * @return Organization
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
     * Set apiKey
     *
     * @param string $apiKey
     * @return Organization
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set invoiceMail
     *
     * @param string $invoiceMail
     * @return Organization
     */
    public function setInvoiceMail($invoiceMail)
    {
        $this->invoiceMail = $invoiceMail;

        return $this;
    }

    /**
     * Get invoiceMail
     *
     * @return string 
     */
    public function getInvoiceMail()
    {
        return $this->invoiceMail;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->managers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add managers
     *
     * @param \ModelBundle\Entity\Manager $managers
     * @return Organization
     */
    public function addManager(\ModelBundle\Entity\Manager $managers)
    {
        $this->managers[] = $managers;

        return $this;
    }

    /**
     * Remove managers
     *
     * @param \ModelBundle\Entity\Manager $managers
     */
    public function removeManager(\ModelBundle\Entity\Manager $managers)
    {
        $this->managers->removeElement($managers);
    }

    /**
     * Get managers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * Add invoices
     *
     * @param \ModelBundle\Entity\Invoice $invoices
     * @return Organization
     */
    public function addInvoice(\ModelBundle\Entity\Invoice $invoices)
    {
        $this->invoices[] = $invoices;

        return $this;
    }

    /**
     * Remove invoices
     *
     * @param \ModelBundle\Entity\Invoice $invoices
     */
    public function removeInvoice(\ModelBundle\Entity\Invoice $invoices)
    {
        $this->invoices->removeElement($invoices);
    }

    /**
     * Get invoices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoices()
    {
        return $this->invoices;
    }
}
