<?php 

namespace App\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface{
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function addWhere(QueryBuilder $queryBuilder,string $resourceClass)
    {
        $user = $this->security->getUser();

        if($resourceClass === Category::class || $resourceClass === Article::class || $resourceClass === User::class){
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if($resourceClass === Category::class){
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }
            else if($resourceClass === User::class){
                $queryBuilder->andWhere("$rootAlias.user = :user");
            }
            else if($resourceClass === Article::class){
                $queryBuilder->join("$rootAlias.category", "c")
                            ->andWhere("c.user = :user");
            }

            $queryBuilder->setParameter("user", $user);
            
        }
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}