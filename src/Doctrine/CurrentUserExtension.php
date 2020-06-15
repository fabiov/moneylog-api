<?php
namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Movement;
use App\Entity\Provision;
use App\Entity\Setting;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (null !== ($user = $this->security->getUser())) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            switch ($resourceClass) {
                case Account::class:
                case Category::class:
                case Provision::class:
                case Setting::class:
                    $queryBuilder->andWhere("$rootAlias.user = :current_user");
                    $queryBuilder->setParameter('current_user', $user->getId());
                    break;
                case Movement::class:
                    $queryBuilder->join("$rootAlias.account", 'a');
                    $queryBuilder->andWhere("a.user = :current_user");
                    $queryBuilder->setParameter('current_user', $user->getId());
                    break;
            }
        }
    }
}
