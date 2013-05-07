<?php

namespace Application\Model;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	public function friendsToFetch()
	{
		return $this->createQueryBuilder('u')
			->where('u.lastFbUpdate IS NULL')
			->andWhere("u.role != 'unregistered'")
			->getQuery()->getResult();
	}
}