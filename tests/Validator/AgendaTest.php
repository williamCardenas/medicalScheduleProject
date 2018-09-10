<?php

namespace App\Tests\Validation;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\Cliente;
use App\Entity\Clinica;
use App\Entity\Medico;
use App\Form\AgendaType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;

use CommissionsBundle\DTO\CatalogItemGroupDTO;
use CommissionsBundle\Entity\CatalogItem;
use CommissionsBundle\Entity\CatalogItemGroup;
use CommissionsBundle\Form\Type\CatalogItemGroupType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormExtensionInterface;

class AgendaTest extends  TypeTestCase
{
    protected $agenda;
    protected $validator;
    private $_em;

    protected function setUp()
    {
        
        $this->_em = $this->createMock(EntityManager::class);


        parent::setUp();
    }

    

    // protected function getExtensions()
    // {
    //     $type = new AgendaType($this->_em);

    //     return array(
    //         new CoreExtension(),
    //         new PreloadedExtension([
    //             $type,
    //         ], []),
    //     );
    // }

    public function testValidacaoSemInformacoes()
    {
        $user = new User();
        $cliente = new Cliente();
        $user->setCliente($cliente);
        

        $formData = array(
            'test' => 'test',
            'test2' => 'test2',
        );

        $objectToCompare = new Agenda();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(AgendaType::class, $objectToCompare, ['user'=>$user]);
        $this->assertTrue($form->isSynchronized());

    }

    protected function getExtensions()
    {
        $this->_em->method('getClassMetadata')
                          ->willReturn(new ClassMetadata(Clinica::class))
        ;
        $entityRepository = $this->createMock(EntityRepository::class);
        $entityRepository->method('createQueryBuilder')
                         ->willReturn(new QueryBuilder($this->_em))
        ;
        $this->_em->method('getRepository')->willReturn($entityRepository);
        $mockRegistry = $this->getMockBuilder(Registry::class)
                             ->disableOriginalConstructor()
                             ->setMethods(['getManagerForClass'])
                             ->getMock()
        ;
        $mockRegistry->method('getManagerForClass')
                     ->willReturn($this->_em)
        ;
        /** @var EntityType|\PHPUnit_Framework_MockObject_MockObject $mockEntityType */
        $mockEntityType = $this->getMockBuilder(EntityType::class)
                               ->setConstructorArgs([$mockRegistry])
                               ->setMethodsExcept(['configureOptions', 'getParent'])
                               ->getMock()
        ;
        $mockEntityType->method('getLoader')->willReturnCallback(function ($a, $b, $class) {
            return new class($class) implements EntityLoaderInterface
            {
                /**
                 * @var
                 */
                private $class;
                /**
                 *  constructor.
                 *
                 * @param $class
                 */
                public function __construct($class)
                {
                    $this->class = $class;
                }
                /**
                 * Returns an array of entities that are valid choices in the corresponding choice list.
                 *
                 * @return array The entities
                 */
                public function getEntities()
                {
                    switch ($this->class) {
                        case Clinica::class:
                            return [new Clinica()];
                            break;
                        case Medico::class:
                            return [new Medico()];
                            break;
                    }
                }
                /**
                 * Returns an array of entities matching the given identifiers.
                 *
                 * @param string $identifier The identifier field of the object. This method
                 *                           is not applicable for fields with multiple
                 *                           identifiers.
                 * @param array  $values     The values of the identifiers
                 *
                 * @return array The entities
                 */
                public function getEntitiesByIds($identifier, array $values)
                {
                    // TODO: Implement getEntitiesByIds() method.
                }
            };
        })
        ;
        $type = new AgendaType($this->_em);
        return [
            new class($mockEntityType) implements FormExtensionInterface
            {
                private $type;
                public function __construct($type)
                {
                    $this->type = $type;
                }
                public function getType($name)
                {
                    return $this->type;
                }
                public function hasType($name)
                {
                    return $name === EntityType::class;
                }
                public function getTypeExtensions($name)
                {
                    return [];
                }
                public function hasTypeExtensions($name)
                {
                }
                public function getTypeGuesser()
                {
                }
            },
            new PreloadedExtension([
                $type,
            ], []),
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->_em->close();
        $this->_em = null; // avoid memory leaks
    }
}