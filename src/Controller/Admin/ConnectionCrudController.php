<?php

namespace App\Controller\Admin;

use App\Entity\Connection;
use App\Entity\Network;
use App\Repository\NetworkRepository;
use App\Repository\SensorRepository;
use App\Traits\NetworkParameterTrait;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class ConnectionCrudController extends AbstractCrudController
{
    use NetworkParameterTrait;

    public function __construct(
        protected NetworkRepository $networkRepository,
        protected SensorRepository $sensorRepository
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Connection::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $result = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

//        $result->andWhere('entity.network = :network');
//        $result->setParameter('network', $this->getNetworkId());

        return $result;
    }

    public function configureFields(string $pageName): iterable
    {
        $sensors = $this->getNetwork()->getSensors();

        $keys = array_map(function ($sensor) {
            return $sensor->getName();
        }, $sensors->toArray());
        $values = array_map(function ($sensor) {
            return $sensor->getId();
        }, $sensors->toArray());

        $sensorsArray = array_combine($keys, $values);

        return [
            Field::new('name'),
            Field::new('action'),
            ChoiceField::new('sensorAId')
                ->setChoices([$sensorsArray])
                ->hideOnIndex(),
            ChoiceField::new('sensorBId')
                ->setChoices([$sensorsArray])
                ->hideOnIndex(),
        ];
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
