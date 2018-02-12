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
     * @ORM\JoinColumn(name="answersIds", referencedColumnName="id")
     * @ORM\Column(type="array")
     */
    private $answers;

    public function __construct(string $text) {
        $this->answers = array();
        $this->text = $text;
    }
    public function  addAnswer(Answer $answer):void {
        array_push($this->answers, $answer->getId());
        $answer->setQuestion($this);
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }

}
