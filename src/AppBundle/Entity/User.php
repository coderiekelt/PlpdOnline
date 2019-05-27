<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="rank", type="string", length=255, nullable=true)
     */
    private $rank;

    /**
     * @var string
     *
     * @ORM\Column(name="divisions", type="string", length=255, nullable=true)
     */
    private $divisions;

    /**
     * @var string
     *
     * @ORM\Column(name="steamid", type="string", length=255, nullable=true)
     */
    private $steamid;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Infraction", mappedBy="employee")
     */
    private $infractions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commendation", mappedBy="employee")
     */
    private $commendations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="employee")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Inactivity", mappedBy="employee")
     */
    private $inactivities;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="employee")
     */
    private $notifications;

    /**
     * @var string
     *
     * @ORM\Column(name="forum", type="string", length=255, nullable=true)
     */
    private $forum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added", type="datetime")
     */
    private $added;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ApplicantStatus", inversedBy="employee")
     * @ORM\JoinColumn(name="applicantstatus", referencedColumnName="id", nullable=true)
     */
    private $applicantstatus;

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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
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
     * @param mixed $infractions
     * @return User
     */
    public function setInfractions($infractions)
    {
        $this->infractions = $infractions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfractions()
    {
        return $this->infractions;
    }

    /**
     * @param mixed $commendations
     * @return User
     */
    public function setCommendations($commendations)
    {
        $this->commendations = $commendations;
        return $this;
    }

    /**
     * @param mixed $notes
     * @return User
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return mixed
     */
    public function getCommendations()
    {
        return $this->commendations;
    }

    /**
     * @param mixed $rank
     * @return User
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $inactivities
     * @return User
     */
    public function setInactivities($inactivities)
    {
        $this->inactivities = $inactivities;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInactivities()
    {
        return $this->inactivities;
    }

    /**
     * @param mixed $divisions
     * @return User
     */
    public function setDivisions($divisions)
    {
        $this->divisions = $divisions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDivisions()
    {
        return $this->divisions;
    }

    /**
     * @param mixed $steamid
     * @return User
     */
    public function setSteamid($steamid)
    {
        $this->steamid = $steamid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSteamid()
    {
        return $this->steamid;
    }

    /**
     * @param mixed $notifications
     * @return User
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @return mixed
     */
    public function getUnreadnotifications()
    {
        $ur = 0;
        foreach ($this->notifications as $notification)
        {
            if ($notification->getSeen() == "no")
            {
                $ur++;
            }
        }

        return $ur;
    }

    /**
     * @param \DateTime $added
     * @return User
     */
    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    public function isPoliceTrainingOfficer()
    {
        if (strpos($this->divisions, "Police Training Officer") != false)
        {
            return true;
        } else {
            return false;
        }
    }

    public function isPTO()
    {
        return $this->isPoliceTrainingOfficer();
    }

    public function isApplicant()
    {
        if ($this->rank == "Applicant")
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $forum
     * @return User
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
        return $this;
    }

    /**
     * @return string
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * @param mixed $views
     * @return User
     */
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param mixed $applicantstatus
     * @return User
     */
    public function setApplicantstatus($applicantstatus)
    {
        $this->applicantstatus = $applicantstatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApplicantstatus()
    {
        return $this->applicantstatus;
    }
}

