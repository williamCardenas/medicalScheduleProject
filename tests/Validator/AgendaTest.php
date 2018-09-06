<?php

namespace App\Tests\Validation;

use App\Entity\Agenda;
use App\Form\AgendaType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\PreloadedExtension;

class AgendaTest extends TypeTestCase
{
    protected $agenda;
    protected $validator;
    /**
    * {@inheritDoc}
    */
    // protected function setUp()
    // {
    //     self::bootKernel();
        
    //     $this->agenda = new Agenda();

    //     parent::setUp();
    // }

    // public function testLoginRequest()
    // {
    //     $client = static::createClient();

    //     $client->request('GET', '/login');

    //     $this->assertEquals(200, $client->getResponse()->getStatusCode());
    // }

    private $objectManager;

    protected function setUp()
    {
        // mock any dependencies
        $this->objectManager = $this->createMock(ObjectManager::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        // create a type instance with the mocked dependencies
        $type = new AgendaType($this->objectManager);

        return array(
            // register the type instances with the PreloadedExtension
            new PreloadedExtension(array($type), array()),
        );
    }

    public function testValidacaoSemInformacoes()
    {
        $formData = array(
            'test' => 'test',
            'test2' => 'test2',
        );

        $objectToCompare = new Agenda();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(AgendaType::class, $objectToCompare);
        $this->assertTrue($form->isSynchronized());
    }

}