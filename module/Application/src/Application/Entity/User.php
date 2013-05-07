<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Application\Model\UserRepository")
 * @ORM\Table(name="Users")
 */
class User
{
	const GENDER_UNKNOWN = 0;
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	/**
	 * @ORM\Id @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $idUser;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $locked = false;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $role = 'user';
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $email;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $verified = false;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $nickname;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $fullName;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $gender;
	
	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	private $birthDate;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $fbKey;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $fbToken;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $lastFbUpdate;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $locale;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $registerDate;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $lastLogin;
	
	/**
	 * @ORM\OneToMany(targetEntity="Bang", mappedBy="from")
	 */
	private $bangs;
	
	/**
	 * @ORM\OneToMany(targetEntity="Bang", mappedBy="to")
	 */
	private $banged;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User")
	 * @ORM\JoinTable(name="Friends",
     *      joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="idUser")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend", referencedColumnName="idUser")}
     *      )
	 */
	private $friends;
	
	/**
	 * @ORM\OneToMany(targetEntity="Picture", mappedBy="user")
	 */
	private $pictures;
	
	/**
	 * @ORM\OneToMany(targetEntity="Message", mappedBy="to")
	 */
	private $messages;
	
	public function __construct()
	{
		$this->registerDate = new \DateTime();
		$this->lastFbUpdate = null;
		$this->lastLogin = null;
		$this->bangs = new ArrayCollection();
		$this->banged = new ArrayCollection();
		$this->pictures = new ArrayCollection();
		$this->messages = new ArrayCollection();
		$this->friends = new ArrayCollection();
	}
	
	public function getIdUser(){
		return $this->idUser;
	}
	
	public function setIdUser($idUser){
		$this->idUser = $idUser;
		return $this;
	}
	
	public function getLocked(){
		return $this->locked;
	}
	
	public function setLocked($locked){
		$this->locked = $locked;
		return $this;
	}
	
	public function getRole(){
		return $this->role;
	}
	
	public function setRole($role){
		$this->role = $role;
		return $this;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setEmail($email){
		$this->email = $email;
		return $this;
	}
	
	public function getVerified(){
		return $this->email;
	}
	
	public function setVerified($v){
		$this->verified = $v ? true : false;
		return $this;
	}
	
	public function getNickname(){
		return $this->nickname;
	}
	
	public function setNickname($nickname){
		$this->nickname = $nickname;
		return $this;
	}
	
	public function getFullName(){
		return $this->fullName;
	}
	
	public function setFullName($fullName){
		$this->fullName = $fullName;
		return $this;
	}
	
	public function getGender(){
		return $this->gender;
	}
	
	public function setGender($gender){
		$this->gender = $gender;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getBirthDate(){
		return $this->birthDate;
	}
	
	public function setBirthDate(\DateTime $birthDate){
		$this->birthDate = $birthDate;
		return $this;
	}
	
	public function getFbKey(){
		return $this->fbKey;
	}
	
	public function setFbKey($fbKey){
		$this->fbKey = $fbKey;
		return $this;
	}
	
	public function getFbToken(){
		return $this->fbToken;
	}
	
	public function setFbToken($fbToken){
		$this->fbToken = $fbToken;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getLastFbUpdate(){
		return $this->lastFbUpdate;
	}
	
	public function setLastFbUpdate(\DateTime $lastFbUpdate){
		$this->lastFbUpdate = $lastFbUpdate;
		return $this;
	}
	
	public function getLocale(){
		return $this->locale;
	}
	
	public function setLocale($locale){
		$this->locale = $locale;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getRegisterDate(){
		return $this->registerDate;
	}
	
	public function setRegisterDate(\DateTime $registerDate){
		$this->registerDate = $registerDate;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getLastLogin(){
		return $this->lastLogin;
	}
	
	public function setLastLogin(\DateTime $lastLogin){
		$this->lastLogin = $lastLogin;
		return $this;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getBangs(){
		return $this->bangs;
	}
	
	public function addBang(Bang $bangs){
		$this->bangs->add($bangs);
		return $this;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getBanged(){
		return $this->banged;
	}
	
	public function addBanged(Bang $banged){
		$this->banged->add($banged);
		return $this;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getFriends(){
		return $this->friends;
	}
	
	public function addFriend(User $u){
		$this->friends->add($u);
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getPictures(){
		return $this->pictures;
	}
	
	public function addPicture(Picture $pictures){
		$this->pictures->add($pictures);
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getMessages(){
		return $this->messages;
	}
	
	public function addMessage(Message $messages){
		$this->messages->add($messages);
		return $this;
	}
}