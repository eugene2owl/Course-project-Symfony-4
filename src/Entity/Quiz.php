<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $quizname;

    /**
     * @ORM\Column(type="integer")
     */
    private $playersAmount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $firstNameLider;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $secondNameLider;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $thirdNameLider;

    /**
     * @return mixed
     */
    public function getQuizname()
    {
        return $this->quizname;
    }

    /**
     * @param mixed $quizname
     */
    public function setQuizname($quizname): void
    {
        $this->quizname = $quizname;
    }

    /**
     * @return mixed
     */
    public function getPlayersAmount()
    {
        return $this->playersAmount;
    }

    /**
     * @param mixed $playersAmount
     */
    public function setPlayersAmount($playersAmount): void
    {
        $this->playersAmount = $playersAmount;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getFirstNameLider()
    {
        return $this->firstNameLider;
    }

    /**
     * @param mixed $firstNameLider
     */
    public function setFirstNameLider($firstNameLider): void
    {
        $this->firstNameLider = $firstNameLider;
    }

    /**
     * @return mixed
     */
    public function getSecondNameLider()
    {
        return $this->secondNameLider;
    }

    /**
     * @param mixed $secondNameLider
     */
    public function setSecondNameLider($secondNameLider): void
    {
        $this->secondNameLider = $secondNameLider;
    }

    /**
     * @return mixed
     */
    public function getThirdNameLider()
    {
        return $this->thirdNameLider;
    }

    /**
     * @param mixed $thirdNameLider
     */
    public function setThirdNameLider($thirdNameLider): void
    {
        $this->thirdNameLider = $thirdNameLider;
    }


}
