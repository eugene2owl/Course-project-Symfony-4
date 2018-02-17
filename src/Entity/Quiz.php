<?php

namespace App\Entity;

use App\Entity\Question;
use App\Entity\Result;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Flex\Response;

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
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quiz")
     * @ORM\JoinColumn(name="list_of_questions", referencedColumnName="id")
     *
     */
    private $questionList;

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="quiz")
     * @ORM\JoinColumn(name="list_of_results", referencedColumnName="id")
     *
     */
    private $resultList;

    public function __construct()
    {
        $this->questionList = new ArrayCollection();
        $this->resultList = new ArrayCollection();
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestionList()
    {
        return $this->questionList;
    }

    /**
     * @param mixed $question
     */
    public function setQuestionList(Question $question): void
    {
        $this->questionList->add($question);
    }

    /**
     * @return Collection|Result[]
     */
    public function getResultList()
    {
        return $this->resultList;
    }

    /**
     * @param mixed $result
     */
    public function setResultList(Result $result): void
    {
        $this->resultList->add($result);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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
