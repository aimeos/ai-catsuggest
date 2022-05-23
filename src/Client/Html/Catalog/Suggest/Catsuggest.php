<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022
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
	public function data( \Aimeos\Base\View\Iface $view, array &$tags = [], string &$expire = null ) : \Aimeos\Base\View\Iface
	{
		$context = $this->context();
		$config = $context->config();
		$text = $view->param( 'f_search', '' );

		$domains = $config->get( 'client/html/catalog/suggest/domains', ['text'] );
		$size = $config->get( 'client/html/catalog/suggest/size', 24 );

		$catItems = \Aimeos\Controller\Frontend::create( $context, 'catalog' )->uses( $domains )
			->compare( '>', 'catalog:relevance("' . str_replace( '"', ' ', $text ) . '")', 0 )
			->sort( '-sort:catalog:relevance("' . str_replace( '"', ' ', $text ) . '")' )
			->slice( 0, (int) floor( $size / 2 ) )
			->search();


		$cntl = \Aimeos\Controller\Frontend::create( $context, 'product' )->uses( $domains )
			->text( $text ); // sort by relevance first

		if( $config->get( 'client/html/catalog/suggest/restrict', true ) == true )
		{
			$level = $config->get( 'client/html/catalog/lists/levels', \Aimeos\MW\Tree\Manager\Base::LEVEL_ONE );
			$catids = $view->param( 'f_catid', $config->get( 'client/html/catalog/lists/catid-default' ) );

			$cntl->category( $catids, 'default', $level )
				->allOf( $view->param( 'f_attrid', [] ) )
				->oneOf( $view->param( 'f_optid', [] ) )
				->oneOf( $view->param( 'f_oneid', [] ) );
		}

		$view->suggestItems = $cntl->slice( 0, $size - count( $catItems ) )->search();
		$view->suggestCatalogItems = $catItems;

		return parent::data( $view, $tags, $expire );
	}
}