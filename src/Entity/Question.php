<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="question")
 * @ORM\Entity()
 *
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     * @ORM\JoinColumn(name="list_of_answers", referencedColumnName="id")
     */
    private $answerList;

    public function __construct(string $text)
    {
        $this->answerList = new ArrayCollection();
        $this->resultList = new ArrayCollection();
        $this->text = $text;
    }

    /**
     * @param mixed $answer
     */
    public function  setAnswerList(Answer $answer): void
    {
        $this->answerList->add($answer);
        $answer->setQuestion($this);
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswerList()
    {
        return $this->answerList;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="questionList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $quiz;

    public function MYgetQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="question")
     * @ORM\JoinColumn(name="list_of_results", referencedColumnName="id")
     *
     */
    private $resultList;

    /**
     * @return Collection|Result[]
     */
    public function MYgetResultList()
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

    public function __toString(): string
    {
        return $this->text;
    }
}
