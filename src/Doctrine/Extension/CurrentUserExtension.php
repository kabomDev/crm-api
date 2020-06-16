<?php

namespace App\Doctrine\Extension;

use App\Entity\Invoice;
use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToItem(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        if ($resourceClass === Customer::class) {

            $rootAlias = $queryBuilder->getRootAliases()[0];
            //on fait une requete pour aller chercher le customer qui sont est lié au user connecté
            $queryBuilder->andWhere($rootAlias . '.user = :user')
                ->setParameter('user', $this->security->getUser());
        }

        //si on veut recuperer les factures
        if ($resourceClass === Invoice::class) {
            $alias = $queryNameGenerator->generateJoinAlias("customer");

            $rootAlias = $queryBuilder->getRootAliases()[0];

            $queryBuilder->join($rootAlias . '.customer', $alias)
                ->andWhere($alias . '.user = :user')
                ->setParameter('user', $this->security->getUser());
        }
    }

    public function applyToCollection(\Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        //$resourceClass = Entity Customer
        //$operationName = "GET"
        if ($resourceClass === Customer::class) {

            $rootAlias = $queryBuilder->getRootAliases()[0];
            //on fait une requete pour aller chercher les customers qui sont liés au user connecté
            $queryBuilder->andWhere($rootAlias . '.user = :user')
                ->setParameter('user', $this->security->getUser());
        }

        //si on veut recuperer les factures
        if ($resourceClass === Invoice::class) {
            $alias = $queryNameGenerator->generateJoinAlias("customer");

            $rootAlias = $queryBuilder->getRootAliases()[0];

            $queryBuilder->join($rootAlias . '.customer', $alias)
                ->andWhere($alias . '.user = :user')
                ->setParameter('user', $this->security->getUser());
        }
    }
}
