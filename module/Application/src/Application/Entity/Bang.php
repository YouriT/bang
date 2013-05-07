<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Application\Model\BanfRepository")
 * @ORM\Table(name="Bangs")
 */
class Bang
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="bangs")
	 * @ORM\JoinColumn(name="idUserFrom", referencedColumnName="idUser", nullable=false)
	 */
	private $from;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="banged")
	 * @ORM\JoinColumn(name="idUserTo", referencedColumnName="idUser", nullable=false)
	 */
	private $to;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $dateBang;
	
	public function __construct()
	{
		$this->dateBang = new \DateTime();
		$this->from = new ArrayCollection();
		$this->to = new ArrayCollection();
	}
	
	public function getFrom(){
		return $this->from;
	}
	
	public function setFrom($from){
		$this->from = $from;
		return $this;
	}
	
	public function getTo(){
		return $this->to;
	}
	
	public function setTo($to){
		$this->to = $to;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getDateBang(){
		return $this->dateBang;
	}
	
	public function setDateBang(\DateTime $dateBang){
		$this->dateBang = $dateBang;
		return $this;
	}
}