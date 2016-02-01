<?php

namespace ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ModelBundle\Entity\EventRepository")
 */
class Event
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
     * @ORM\Column(name="timezone", type="string", length=64)
     */
    private $timezone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="date")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="date")
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="asm_environment", type="string", length=8)
     */
    private $asmEnvironment;

    /**
     * @var string
     *
     * @ORM\Column(name="control_technology", type="string", length=8)
     */
    private $controlTechnology;

    /**
     * @var string
     *
     * @ORM\Column(name="controller_status", type="string", length=8)
     */
    private $controllerStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="traffic", type="string", length=8)
     */
    private $traffic;

    /**
     * @var string
     *
     * @ORM\Column(name="equipment", type="string", length=8)
     */
    private $equipment;

    /**
     * @var string
     *
     * @ORM\Column(name="weather", type="string", length=8)
     */
    private $weather;

    /**
     * @ORM\ManyToOne(targetEntity="ModelBundle\Entity\Evaluation")
     */
    private $evaluation;

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
     * Set timezone
     *
     * @param string $timezone
     * @return Event
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Event
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set asmEnvironment
     *
     * @param string $asmEnvironment
     * @return Event
     */
    public function setAsmEnvironment($asmEnvironment)
    {
        $this->asmEnvironment = $asmEnvironment;

        return $this;
    }

    /**
     * Get asmEnvironment
     *
     * @return string 
     */
    public function getAsmEnvironment()
    {
        return $this->asmEnvironment;
    }

    /**
     * Set controlTechnology
     *
     * @param string $controlTechnology
     * @return Event
     */
    public function setControlTechnology($controlTechnology)
    {
        $this->controlTechnology = $controlTechnology;

        return $this;
    }

    /**
     * Get controlTechnology
     *
     * @return string 
     */
    public function getControlTechnology()
    {
        return $this->controlTechnology;
    }

    /**
     * Set controllerStatus
     *
     * @param string $controllerStatus
     * @return Event
     */
    public function setControllerStatus($controllerStatus)
    {
        $this->controllerStatus = $controllerStatus;

        return $this;
    }

    /**
     * Get controllerStatus
     *
     * @return string 
     */
    public function getControllerStatus()
    {
        return $this->controllerStatus;
    }

    /**
     * Set traffic
     *
     * @param string $traffic
     * @return Event
     */
    public function setTraffic($traffic)
    {
        $this->traffic = $traffic;

        return $this;
    }

    /**
     * Get traffic
     *
     * @return string 
     */
    public function getTraffic()
    {
        return $this->traffic;
    }

    /**
     * Set equipment
     *
     * @param string $equipment
     * @return Event
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return string 
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Set weather
     *
     * @param string $weather
     * @return Event
     */
    public function setWeather($weather)
    {
        $this->weather = $weather;

        return $this;
    }

    /**
     * Get weather
     *
     * @return string 
     */
    public function getWeather()
    {
        return $this->weather;
    }
}
