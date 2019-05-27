<?php

namespace CareerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity(repositoryClass="CareerBundle\Repository\ApplicationRepository")
 */
class Application
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
     * @ORM\Column(name="pto", type="text", nullable=true)
     */
    private $pto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var string
     *
     * @ORM\Column(name="forumname", type="string", length=255, nullable=true)
     */
    private $forumname;

    /**
     * @var string
     *
     * @ORM\Column(name="steamname", type="string", length=255, nullable=true)
     */
    private $steamname;

    /**
     * @var string
     *
     * @ORM\Column(name="steamid", type="string", length=255, nullable=true)
     */
    private $steamid;

    /**
     * @var string
     *
     * @ORM\Column(name="rpname", type="string", length=255, nullable=true)
     */
    private $rpname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string", length=255, nullable=true)
     */
    private $timezone;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="string", length=255, nullable=true)
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="microphone", type="string", length=255, nullable=true)
     */
    private $microphone;

    /**
     * @var string
     *
     * @ORM\Column(name="reasonApply", type="text", nullable=true)
     */
    private $reasonApply;

    /**
     * @var string
     *
     * @ORM\Column(name="reasonChoose", type="text", nullable=true)
     */
    private $reasonChoose;

    /**
     * @var string
     *
     * @ORM\Column(name="experience", type="text", nullable=true)
     */
    private $experience;

    /**
     * @var string
     *
     * @ORM\Column(name="skills", type="text", nullable=true)
     */
    private $skills;

    /**
     * @var string
     *
     * @ORM\Column(name="concerns", type="text", nullable=true)
     */
    private $concerns;

    /**
     * @var string
     *
     * @ORM\Column(name="information", type="text", nullable=true)
     */
    private $information;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="step", type="integer", nullable=true)
     */
    private $step;

    /**
     * @var string
     *
     * @ORM\Column(name="completed", type="string", length=255, nullable=true)
     */
    private $completed;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;


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
     * Set pto
     *
     * @param string $pto
     *
     * @return Application
     */
    public function setPto($pto)
    {
        $this->pto = $pto;

        return $this;
    }

    /**
     * Get pto
     *
     * @return string
     */
    public function getPto()
    {
        return $this->pto;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Application
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set forumname
     *
     * @param string $forumname
     *
     * @return Application
     */
    public function setForumname($forumname)
    {
        $this->forumname = $forumname;

        return $this;
    }

    /**
     * Get forumname
     *
     * @return string
     */
    public function getForumname()
    {
        return $this->forumname;
    }

    /**
     * Set steamname
     *
     * @param string $steamname
     *
     * @return Application
     */
    public function setSteamname($steamname)
    {
        $this->steamname = $steamname;

        return $this;
    }

    /**
     * Get steamname
     *
     * @return string
     */
    public function getSteamname()
    {
        return $this->steamname;
    }

    /**
     * Set steamid
     *
     * @param string $steamid
     *
     * @return Application
     */
    public function setSteamid($steamid)
    {
        $this->steamid = $steamid;

        return $this;
    }

    /**
     * Get steamid
     *
     * @return string
     */
    public function getSteamid()
    {
        return $this->steamid;
    }

    /**
     * Set rpname
     *
     * @param string $rpname
     *
     * @return Application
     */
    public function setRpname($rpname)
    {
        $this->rpname = $rpname;

        return $this;
    }

    /**
     * Get rpname
     *
     * @return string
     */
    public function getRpname()
    {
        return $this->rpname;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Application
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     *
     * @return Application
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
     * Set dob
     *
     * @param string $dob
     *
     * @return Application
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set microphone
     *
     * @param string $microphone
     *
     * @return Application
     */
    public function setMicrophone($microphone)
    {
        $this->microphone = $microphone;

        return $this;
    }

    /**
     * Get microphone
     *
     * @return string
     */
    public function getMicrophone()
    {
        return $this->microphone;
    }

    /**
     * Set reasonApply
     *
     * @param string $reasonApply
     *
     * @return Application
     */
    public function setReasonApply($reasonApply)
    {
        $this->reasonApply = $reasonApply;

        return $this;
    }

    /**
     * Get reasonApply
     *
     * @return string
     */
    public function getReasonApply()
    {
        return $this->reasonApply;
    }

    /**
     * Set reasonChoose
     *
     * @param string $reasonChoose
     *
     * @return Application
     */
    public function setReasonChoose($reasonChoose)
    {
        $this->reasonChoose = $reasonChoose;

        return $this;
    }

    /**
     * Get reasonChoose
     *
     * @return string
     */
    public function getReasonChoose()
    {
        return $this->reasonChoose;
    }

    /**
     * Set experience
     *
     * @param string $experience
     *
     * @return Application
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set skills
     *
     * @param string $skills
     *
     * @return Application
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * Get skills
     *
     * @return string
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set concerns
     *
     * @param string $concerns
     *
     * @return Application
     */
    public function setConcerns($concerns)
    {
        $this->concerns = $concerns;

        return $this;
    }

    /**
     * Get concerns
     *
     * @return string
     */
    public function getConcerns()
    {
        return $this->concerns;
    }

    /**
     * Set information
     *
     * @param string $information
     *
     * @return Application
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Application
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return Application
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
     * Set completed
     *
     * @param string $completed
     *
     * @return Application
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return string
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Application
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $email
     * @return Application
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}

