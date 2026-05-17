<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2026
 * @package Client
 * @subpackage Html
 */

namespace Aimeos\Client\Html\Catalog\Suggest;


/**
 * Category suggestion implementation of catalog suggest section HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Catsuggest extends Standard
{
	public function data( \Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null ) : \Aimeos\Base\View\Iface
	{
		$context = $this->context();
		$config = $context->config();
		$text = $view->param( 'f_search', '' );

		$domains = $config->get( 'client/html/catalog/suggest/domains', ['text'] );
		$size = $config->get( 'client/html/catalog/suggest/size', 24 );

		$supItems = \Aimeos\Controller\Frontend::create( $context, 'supplier' )->uses( $domains )
			// @phpstan-ignore argument.type
			->compare( '>', 'supplier:relevance("' . str_replace( ['"', ','], ' ', $text ) . '")', 0 ) // @phpstan-ignore binaryOp.invalid
			// @phpstan-ignore argument.type
			->sort( '-sort:supplier:relevance("' . str_replace( ['"', ','], ' ', $text ) . '")' ) // @phpstan-ignore binaryOp.invalid
			->slice( 0, $size )
			->search();


		$catItems = \Aimeos\Controller\Frontend::create( $context, 'catalog' )->uses( $domains )
			// @phpstan-ignore argument.type
			->compare( '>', 'catalog:relevance("' . str_replace( ['"', ','], ' ', $text ) . '")', 0 ) // @phpstan-ignore binaryOp.invalid
			// @phpstan-ignore argument.type
			->sort( '-sort:catalog:relevance("' . str_replace( ['"', ','], ' ', $text ) . '")' ) // @phpstan-ignore binaryOp.invalid
			// @phpstan-ignore argument.type
			->slice( 0, $size - count( $supItems ) )
			->search();


		$cntl = \Aimeos\Controller\Frontend::create( $context, 'product' )->uses( $domains )
			->text( $text ); // sort by relevance first

		if( $config->get( 'client/html/catalog/suggest/restrict', true ) == true )
		{
			$level = $config->get( 'client/html/catalog/lists/levels', \Aimeos\MW\Tree\Manager\Base::LEVEL_ONE );
			// @phpstan-ignore argument.type
			$catids = $view->param( 'f_catid', $config->get( 'client/html/catalog/lists/catid-default' ) );

			$cntl->category( $catids, 'default', $level )
				->allOf( $view->param( 'f_attrid', [] ) )
				->oneOf( $view->param( 'f_optid', [] ) )
				->oneOf( $view->param( 'f_oneid', [] ) );

			$this->call( 'conditions', $cntl, $view );
		}

		// @phpstan-ignore argument.type, argument.type
		$view->suggestItems = $cntl->slice( 0, $size - count( $catItems ) - count( $supItems ) )->search();
		$view->suggestSupplierItems = $supItems;
		$view->suggestCatalogItems = $catItems;

		return $view;
	}
}
