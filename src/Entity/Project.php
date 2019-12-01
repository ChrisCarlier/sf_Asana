<?php


namespace App\Entity;


class Project
{

    private $name;
    private $gid;
    private $tasks;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->tasks = [];
    }

    /**
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param mixed $tasks
     */
    public function setTasks($tasks): void
    {
        $this->tasks = $tasks;
    }

    public function addTask(string $task)
    {
        array_push($this->tasks,$task);
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

    /**
     * @return string
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param mixed $gid
     */
    public function setGid($gid): void
    {
        $this->gid = $gid;
    }

}