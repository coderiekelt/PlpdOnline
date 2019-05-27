<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Infraction
 *
 * @ORM\Table(name="infraction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InfractionRepository")
 */
class Infraction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="text")
     */
    private $reason;

    /**
     * @var string
     *
     * @ORM\Column(name="thread", type="string", length=255, nullable=true)
     */
    private $thread;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="supervisor", referencedColumnName="id")
     */
    private $supervisor;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="infractions")
     * @ORM\JoinColumn(name="employee", referencedColumnName="id")
     */
    private $employee;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return Infraction
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Infraction
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set supervisor
     *
     * @param string $supervisor
     *
     * @return Infraction
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return User
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set employee
     *
     * @param string $employee
     *
     * @return Infraction
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return User
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param string $thread
     * @return Infraction
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
        return $this;
    }

    /**
     * @param string $type
     * @return Infraction
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getThread()
    {
        return $this->thread;
    }
}

