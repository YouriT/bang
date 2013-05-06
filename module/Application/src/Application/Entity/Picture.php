<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Application\Model\PictureRepository")
 * @ORM\Table(name="Pictures")
 */
class Picture
{
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $idPicture;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Message")
	 * @ORM\JoinColumn(name="idMessage", referencedColumnName="idMessage", nullable=true)
	 */
	private $message;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="pictures")
	 * @ORM\JoinColumn(name="idUser", referencedColumnName="idUser", nullable=true)
	 */
	private $user;
	
	public function getIdPicture(){
		return $this->idPicture;
	}
	
	public function setIdPicture($idPicture){
		$this->idPicture = $idPicture;
		return $this;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
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
	
	public function getMessage(){
		return $this->message;
	}
	
	public function setMessage($message){
		$this->message = $message;
		return $this;
	}
	
	/**
	 * @return User
	 */
	public function getUser(){
		return $this->user;
	}
	
	public function setUser(User $user){
		$this->user = $user;
		return $this;
	}
}