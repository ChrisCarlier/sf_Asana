<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoleKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="Role")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Right", inversedBy="roles")
     */
    private $rigths;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rigths = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleKey(): ?string
    {
        return $this->RoleKey;
    }

    public function setRoleKey(string $RoleKey): self
    {
        $this->RoleKey = $RoleKey;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Right[]
     */
    public function getRigths(): Collection
    {
        return $this->rigths;
    }

    public function addRigth(Right $rigth): self
    {
        if (!$this->rigths->contains($rigth)) {
            $this->rigths[] = $rigth;
        }

        return $this;
    }

    public function removeRigth(Right $rigth): self
    {
        if ($this->rigths->contains($rigth)) {
            $this->rigths->removeElement($rigth);
        }

        return $this;
    }
}
