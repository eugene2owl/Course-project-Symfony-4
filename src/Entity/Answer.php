<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
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
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTrue;

    /**
     * @return mixed
     */
    public function getisTrue()
    {
        return $this->isTrue;
    }

    /**
     * @param mixed $isTrue
     */
    public function setIsTrue($isTrue): void
    {
        $this->isTrue = $isTrue;
    }

    public function __construct(string $answer, int $isTrue)
    {
        $this->resultList = new ArrayCollection();
        $this->text = $answer;
        $this->isTrue = $isTrue;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answerList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $question;

    public function MYgetQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question):void
    {
        $this->question = $question;
    }

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="answer")
     * @ORM\JoinColumn(name="list_of_results", referencedColumnName="is_true")
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
