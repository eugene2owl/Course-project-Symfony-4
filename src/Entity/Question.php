<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(type="string", length=60)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answers;

    public function __construct(string $text) {
        $this->answers = new ArrayCollection();
        $this->text = $text;
    }
    public function  addAnswer(Answer $answer):void {
        $this->answers[] = $answer;
        $answer->setQuestion($this);
    }
}
