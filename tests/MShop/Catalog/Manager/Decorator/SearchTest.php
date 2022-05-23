<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022
 */


namespace Aimeos\MShop\Catalog\Manager\Decorator;


class SearchTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $stub;


	protected function setUp() : void
	{
		$this->context = \TestHelper::context();

		$this->stub = $this->getMockBuilder( '\\Aimeos\\MShop\\Catalog\\Manager\\Standard' )
			->setConstructorArgs( [$this->context] )
			->setMethods( ['getSearchAttributes'] )
			->getMock();

		$this->object = new \Aimeos\MShop\Catalog\Manager\Decorator\Search( $this->stub, $this->context );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->stub, $this->context );
	}


    public function testGetSearchAttributes()
    {
        $this->stub->expects( $this->once() )->method( 'getSearchAttributes' )->will( $this->returnValue( [] ) );

        $result = $this->object->getSearchAttributes( false );

        $this->assertArrayHasKey( 'catalog:relevance', $result );
        $this->assertArrayHasKey( 'sort:catalog:relevance', $result );
    }


    public function testSearch()
	{
        $manager = \Aimeos\MShop\Catalog\Manager\Factory::create( $this->context );
        $object = new \Aimeos\MShop\Catalog\Manager\Decorator\Search( $manager, $this->context );

		$filter = $object->filter()->order( '-sort:catalog:relevance("Kaffee")' );
        $filter->add( $filter->is( 'catalog:relevance("Kaffee")', '>', 0 ) );

        $result = $object->search( $filter, ['text'] );

		$this->assertEquals( 1, count( $result ) );
	}
}
