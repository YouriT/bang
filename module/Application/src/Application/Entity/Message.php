<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Application\Model\MessageRepository")
 * @ORM\Table(name="Messages")
 */
class Message
{
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $idMessage;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $content;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="idUserFrom", referencedColumnName="idUser", nullable=true, onDelete="SET NULL")
	 */
	private $from;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="idUserTo", referencedColumnName="idUser", nullable=true, onDelete="SET NULL")
	 */
	private $to;
	
	public function getIdMessage(){
		return $this->idMessage;
	}
	
	public function setIdMessage($idMessage){
		$this->idMessage = $idMessage;
		return $this;
	}
	
	public function getContent(){
		return $this->content;
	}
	
	public function setContent($content){
		$this->content = $content;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getDate(){
		return $this->date;
	}
	
	public function setDate(\DateTime $date){
		$this->date = $date;
		return $this;
	}
	
	/**
	 * @return User
	 */
	public function getFrom(){
		return $this->from;
	}
	
	public function setFrom(User $from){
		$this->from = $from;
		return $this;
	}
	
	/**
	 * @return User
	 */
	public function getTo(){
		return $this->to;
	}
	
	public function setTo(User $to){
		$this->to = $to;
		return $this;
	}
}