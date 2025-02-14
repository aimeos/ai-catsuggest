<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2025
 */


namespace Aimeos\MShop\Supplier\Manager\Decorator;


class SearchTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $stub;


	protected function setUp() : void
	{
		$this->context = \TestHelper::context();

		$this->stub = $this->getMockBuilder( '\\Aimeos\\MShop\\Supplier\\Manager\\Standard' )
			->setConstructorArgs( [$this->context] )
			->onlyMethods( ['getSearchAttributes'] )
			->getMock();

		$this->object = new \Aimeos\MShop\Supplier\Manager\Decorator\Search( $this->stub, $this->context );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->stub, $this->context );
	}


	public function testGetSearchAttributes()
	{
		$this->stub->expects( $this->once() )->method( 'getSearchAttributes' )->willReturn( [] );

		$result = $this->object->getSearchAttributes( false );

		$this->assertArrayHasKey( 'supplier:relevance', $result );
		$this->assertArrayHasKey( 'sort:supplier:relevance', $result );
	}


	public function testSearch()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'supplier' );
		$object = new \Aimeos\MShop\Supplier\Manager\Decorator\Search( $manager, $this->context );

		$filter = $object->filter()->order( '-sort:supplier:relevance("suppli")' );
		$filter->add( $filter->is( 'supplier:relevance("suppli")', '>', 0 ) );

		$result = $object->search( $filter, ['text'] );

		$this->assertEquals( 3, count( $result ) );
	}
}
