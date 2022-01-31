<?php

namespace App\Controller\Admin;

use App\Entity\Network;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

class NetworkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Network::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new Network();
        $product->setOwner($this->getUser());

        return $product;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $result = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $result->andWhere('entity.owner = :user');
        $result->setParameter('user', $this->getUser());

        return $result;
    }
}
