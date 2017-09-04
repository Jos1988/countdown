<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App
 *
 * @ORM\Table(name="app")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AppRepository")
 */
class App
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
     * @ORM\Column(name="project_name", type="string", length=100, unique=true)
     */
    private $projectName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    const PROJECT_PHASE = [
        0 => 'concept',
        1 => 'prototype',
        2 => 'demo',
        3 => 'live',
    ];

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="economics", type="text", nullable=true)
     */
    private $economics;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true, unique=true)
     */
    private $address;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get ProjectName
     *
     * @return string
     */
    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    /**
     * set ProjectName
     *
     * @param string $projectName
     *
     * @return App
     */
    public function setProjectName(string $projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return App
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return App
     */
    public function setDescription(string $description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return App
     */
    public function setAddress(string $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return App
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * get Economics
     *
     * @return string
     */
    public function getEconomics(): ?string
    {
        return $this->economics;
    }

    /**
     * set Economics
     *
     * @param string $economics
     *
     * @return App
     */
    public function setEconomics(string $economics = null)
    {
        $this->economics = $economics;

        return $this;
    }

    /**
     * get Notes
     *
     * @return string
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * set Notes
     *
     * @param string $notes
     *
     * @return App
     */
    public function setNotes(string $notes = null)
    {
        $this->notes = $notes;

        return $this;
    }
}
