<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicantScenarios
 *
 * @ORM\Table(name="applicant_scenarios")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApplicantScenariosRepository")
 */
class ApplicantScenarios
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ApplicantStatus", inversedBy="scenarios")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="sampleScenario", type="text")
     */
    private $sampleScenario;


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
     * Set sampleScenario
     *
     * @param string $sampleScenario
     *
     * @return ApplicantScenarios
     */
    public function setSampleScenario($sampleScenario)
    {
        $this->sampleScenario = $sampleScenario;

        return $this;
    }

    /**
     * Get sampleScenario
     *
     * @return string
     */
    public function getSampleScenario()
    {
        return $this->sampleScenario;
    }

    /**
     * @param mixed $status
     * @return ApplicantScenarios
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}

