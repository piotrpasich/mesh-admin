<?php

namespace App\Controller\Admin;

use App\Entity\Network;
use App\Entity\Sensor;
use App\Repository\NetworkRepository;
use App\Traits\NetworkParameterTrait;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;

class SensorCrudController extends AbstractCrudController
{
    use NetworkParameterTrait;

    public function __construct(protected NetworkRepository $networkRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Sensor::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {

        $result = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $result->andWhere('entity.network = :network');
        $result->setParameter('network', $this->getNetworkId());

        return $result;
    }

    public function createEntity(string $entityFqcn)
    {
        $product = new Sensor();
        $product->setNetwork($this->getNetwork());

        return $product;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
