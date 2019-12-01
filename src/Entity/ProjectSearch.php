<?php


namespace App\Entity;


class ProjectSearch
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ProjectSearch
     */
    public function setName(?string $name): ProjectSearch
    {
        $this->name = $name;
        return $this;
    }


}