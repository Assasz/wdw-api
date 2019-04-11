<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Lecture
 *
 * @ORM\Table(name="lecture")
 * @ORM\Entity(repositoryClass="App\Repository\LectureRepository")
 *
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "access_control"="is_granted('ROLE_USER')",
 *             "swagger_context"={
 *                  "responses"={
 *                      "200"={
 *                          "description"="Retrieves the collection of Lecture resources.",
 *                          "examples"={
 *                              "application/json"={
 *                                  {
 *                                      "idLecture": 0,
 *                                      "name": "string",
 *                                      "ects": 0,
 *                                      "lecturer": "string",
 *                                      "auditorium": "string",
 *                                      "weekday": "string",
 *                                      "week": "string",
 *                                      "hour": "12:00",
 *                                      "slots": 0,
 *                                      "slotsOccupied": 0,
 *                                      "users": {
 *                                          0:"string"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "access_control"="is_granted('ROLE_USER')",
 *             "swagger_context"={
 *                  "responses"={
 *                      "200"={
 *                          "description"="Retrieves a Lecture resource.",
 *                          "examples"={
 *                              "application/json"={
 *                                  "idLecture": 0,
 *                                  "name": "string",
 *                                  "ects": 0,
 *                                  "lecturer": "string",
 *                                  "auditorium": "string",
 *                                  "weekday": "string",
 *                                  "week": "string",
 *                                  "hour": "12:00",
 *                                  "slots": 0,
 *                                  "slotsOccupied": 0,
 *                                  "users": {
 *                                      0:"string"
 *                                  }
 *                              }
 *                          }
 *                      },
 *                      "404"={
 *                          "description"="Resource not found"
 *                      }
 *                  }
 *              }
 *         }
 *     }
 * )
 */
class Lecture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_lecture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="ects", type="integer", nullable=false)
     */
    private $ects;

    /**
     * @var string
     *
     * @ORM\Column(name="lecturer", type="string", length=255, nullable=false)
     */
    private $lecturer;

    /**
     * @var string
     *
     * @ORM\Column(name="auditorium", type="string", length=255, nullable=false)
     */
    private $auditorium;

    /**
     * @var string
     *
     * @ORM\Column(name="weekday", type="string", length=255, nullable=false)
     */
    private $weekday;

    /**
     * @var string
     *
     * @ORM\Column(name="week", type="string", length=0, nullable=false)
     */
    private $week;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="time", nullable=false)
     */
    private $hour;

    /**
     * @var int
     *
     * @ORM\Column(name="slots", type="integer", nullable=false)
     */
    private $slots;

    /**
     * @var int
     *
     * @ORM\Column(name="slots_occupied", type="integer", nullable=false)
     */
    private $slotsOccupied = '0';

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="lectures")
     */
    private $users;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Enrollment", mappedBy="lectures")
     */
    private $enrollments;

    /**
     * Lecture constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getEcts(): ?int
    {
        return $this->ects;
    }

    /**
     * @return null|string
     */
    public function getLecturer(): ?string
    {
        return $this->lecturer;
    }

    /**
     * @return null|string
     */
    public function getAuditorium(): ?string
    {
        return $this->auditorium;
    }

    /**
     * @return null|string
     */
    public function getWeekday(): ?string
    {
        return $this->weekday;
    }

    /**
     * @return null|string
     */
    public function getWeek(): ?string
    {
        return $this->week;
    }

    /**
     * @return null|string
     */
    public function getHour(): ?string
    {
        return $this->hour->format('H:i');
    }

    /**
     * @return int|null
     */
    public function getSlots(): ?int
    {
        return $this->slots;
    }

    /**
     * @return int|null
     */
    public function getSlotsOccupied(): ?int
    {
        return $this->slotsOccupied;
    }

    /**
     * @param int $slotsOccupied
     */
    public function setSlotsOccupied(int $slotsOccupied): void
    {
        $this->slotsOccupied = $slotsOccupied;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Lecture
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addLecture($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return Lecture
     */
    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeLecture($this);
        }

        return $this;
    }

    /**
     * @return Collection|Enrollment[]
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }
}
