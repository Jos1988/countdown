<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(max=255, maxMessage="Please use a shorter name.")
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
     * @ORM\Column(name="deadline", type="string", length=20, nullable=true)
     */
    private $deadline;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="items", cascade={"persist", "remove"})
     */
    private $project;

    /**
     * @var Collection
     */
    private $originalActions;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Action", mappedBy="item", cascade={"persist", "remove"})
     */
    private $actions;

    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->originalActions = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
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
    public function getName()
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
    public function getDescription()
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
    public function getOwner()
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
     * get Deadline
     *
     * @return string
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * set Deadline
     *
     * @param string $deadline
     *
     * @return Item
     */
    public function setDeadline(string $deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
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

    /**
     * get OriginalActions
     *
     * @return Collection
     */
    public function getOriginalActions()
    {
        return $this->originalActions;
    }

    /**
     * set OriginalActions
     *
     * @param Collection $originalActions
     *
     * @return Item
     */
    public function setOriginalActions(Collection $originalActions)
    {
        $this->originalActions = $originalActions;

        return $this;
    }

    /**
     * Add action to item.
     *
     * @param Action $action
     *
     * @return Item
     */
    public function addOriginalAction(Action $action)
    {
        if (!$this->originalActions->contains($action)) {
            $action->setItem($this);
            $this->originalActions->add($action);
        }

        return $this;
    }

    /**
     * Remove action from item.
     *
     * @param Action $action
     *
     * @return $this
     */
    public function removeOriginalAction(Action $action)
    {
        if ($this->originalActions->contains($action)) {
            $this->originalActions->remove($action);
        }

        return $this;
    }

    /**
     * get Actions
     *
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    /**
     * set Actions
     *
     * @param Collection $actions
     *
     * @return Item
     */
    public function setActions(Collection $actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Add action to item.
     *
     * @param Action $action
     *
     * @return Item
     */
    public function addAction(Action $action)
    {
        if (!$this->actions->contains($action)) {
            $action->setItem($this);
            $this->actions->add($action);
        }

        return $this;
    }

    /**
     * Remove action from item.
     *
     * @param Action $action
     *
     * @return $this
     */
    public function removeAction(Action $action)
    {
        if ($this->actions->contains($action)) {
            $this->actions->remove($action);
        }

        return $this;
    }
}
