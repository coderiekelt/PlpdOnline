<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicantStatus
 *
 * @ORM\Table(name="applicant_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApplicantStatusRepository")
 */
class ApplicantStatus
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="applicantstatus")
     * @ORM\JoinColumn(name="employee", referencedColumnName="id")
     */
    private $employee;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ApplicantScenarios", inversedBy="applicantstatus")
     * @ORM\JoinColumn(name="scenarios", referencedColumnName="id", nullable=true)
     */
    private $scenarios;

    /**
     * @var int
     *
     * @ORM\Column(name="step", type="integer")
     */
    private $step = 1;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ApplicantPatrol", inversedBy="applicantstatus")
     * @ORM\JoinColumn(name="patrol", referencedColumnName="id", nullable=true)
     */
    private $patrol;

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
     * Set step
     *
     * @param integer $step
     *
     * @return ApplicantStatus
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param mixed $employee
     * @return ApplicantStatus
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param mixed $scenarios
     * @return ApplicantStatus
     */
    public function setScenarios($scenarios)
    {
        $this->scenarios = $scenarios;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScenarios()
    {
        return $this->scenarios;
    }

    /**
     * @param mixed $patrol
     * @return ApplicantStatus
     */
    public function setPatrol($patrol)
    {
        $this->patrol = $patrol;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPatrol()
    {
        return $this->patrol;
    }
}

