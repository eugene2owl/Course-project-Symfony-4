<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="resultList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $quiz;

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="resultList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="resultList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $question;

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Answer", inversedBy="resultList")
     * @ORM\JoinColumn(nullable=true)
     */
    private $answer;

    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }
}