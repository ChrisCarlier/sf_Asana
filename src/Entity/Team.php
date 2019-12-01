<?php


namespace App\Entity;


class Team
{

    private $name;
    private $gid;

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param $gid
     */
    public function setGid($gid): void
    {
        $this->gid = $gid;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


}