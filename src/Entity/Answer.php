<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="answer")
 * @ORM\Entity()
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

    public function __construct(string $answer, int $isTrue)
    {
        $this->text = $answer;
        $this->isTrue = $isTrue;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answerList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $question;

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question):void
    {
        $this->question = $question;
    }
}
