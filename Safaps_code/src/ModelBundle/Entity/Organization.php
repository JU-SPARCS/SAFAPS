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
}
