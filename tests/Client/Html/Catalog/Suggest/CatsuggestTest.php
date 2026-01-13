<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2026
 */


namespace Aimeos\Client\Html\Catalog\Suggest;


class CatsuggestTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $context;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelper::view();
		$this->context = \TestHelper::context();

		$this->object = new \Aimeos\Client\Html\Catalog\Suggest\Catsuggest( $this->context );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->context, $this->view );
	}


	public function testHeader()
	{
		$output = $this->object->header();
		$this->assertNotNull( $output );
	}


	public function testBody()
	{
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, array( 'f_search' => 'Cafe' ) );
		$this->view->addHelper( 'param', $helper );

		$output = $this->object->body();
		$suggestItems = $this->view->suggestItems;

		$this->assertMatchesRegularExpression( '#\{"label":"Cafe.*","html":".*Cafe.*"\}#smU', $output );
		$this->assertNotEquals( [], $suggestItems );

		foreach( $suggestItems as $item ) {
			$this->assertInstanceOf( \Aimeos\MShop\Product\Item\Iface::class, $item );
		}
	}


	public function testBodyCategories()
	{
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, array( 'f_search' => 'Kaffee' ) );
		$this->view->addHelper( 'param', $helper );

		$output = $this->object->body();
		$suggestItems = $this->view->suggestCatalogItems;

		$this->assertMatchesRegularExpression( '#\{"label":"Kaffee.*","html":".*Kaffee.*"\}#smU', $output );
		$this->assertNotEquals( [], $suggestItems );

		foreach( $suggestItems as $item ) {
			$this->assertInstanceOf( \Aimeos\MShop\Catalog\Item\Iface::class, $item );
		}
	}


	public function testBodySuppliers()
	{
		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, array( 'f_search' => 'Suppli' ) );
		$this->view->addHelper( 'param', $helper );

		$output = $this->object->body();
		$suggestItems = $this->view->suggestSupplierItems;

		$this->assertMatchesRegularExpression( '#\{"label":"Test supplier","html":".*Test supplier.*"\}#smU', $output );
		$this->assertNotEquals( [], $suggestItems );

		foreach( $suggestItems as $item ) {
			$this->assertInstanceOf( \Aimeos\MShop\Supplier\Item\Iface::class, $item );
		}
	}


	public function testBodyUseCodes()
	{
		$this->context->config()->set( 'client/html/catalog/suggest/usecode', true );

		$helper = new \Aimeos\Base\View\Helper\Param\Standard( $this->view, array( 'f_search' => 'CNC' ) );
		$this->view->addHelper( 'param', $helper );

		$output = $this->object->body();
		$suggestItems = $this->view->suggestItems;

		$this->assertMatchesRegularExpression( '#\{"label":"Cafe.*","html":".*Cafe.*"\}#smU', $output );
		$this->assertNotEquals( [], $suggestItems );

		foreach( $suggestItems as $item ) {
			$this->assertInstanceOf( \Aimeos\MShop\Product\Item\Iface::class, $item );
		}
	}
}
