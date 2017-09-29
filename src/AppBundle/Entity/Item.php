<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="Item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
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
     * @ORM\Column(name="name", type="string", unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description",  type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", nullable=true)
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", length=20)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", length=20, nullable=true)
     */
    private $end;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="items", cascade={"persist", "remove"})
     */
    private $project;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Item
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Item
     */
    public function setDescription(string $description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     *
     * @return Item
     */
    public function setOwner(string $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * get Start
     *
     * @return string
     */
    public function getStart(): ?string
    {
        return $this->start;
    }

    /**
     * set Start
     *
     * @param string $start
     *
     * @return Item
     */
    public function setStart(string $start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * get End
     *
     * @return string
     */
    public function getEnd(): ?string
    {
        return $this->end;
    }

    /**
     * set End
     *
     * @param string $end
     *
     * @return Item
     */
    public function setEnd(string $end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return Item|null
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }
}
