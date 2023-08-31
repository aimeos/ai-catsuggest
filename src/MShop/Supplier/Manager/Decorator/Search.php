<?php

namespace Aimeos\MShop\Supplier\Manager\Decorator;


/**
 * Full text search config for supplier label
 */
class Search extends \Aimeos\MShop\Common\Manager\Decorator\Base
{
	private array $attr = [
		'supplier:relevance' => array(
			'code' => 'supplier:relevance()',
			'internalcode' => 'MATCH( msup."label" ) AGAINST( $1 IN BOOLEAN MODE )',
			'label' => 'Category texts, parameter(<search term>)',
			'type' => 'float',
			'public' => false,
		),
		'sort:supplier:relevance' => array(
			'code' => 'sort:supplier:relevance()',
			'internalcode' => 'MATCH( msup."label" ) AGAINST( $1 IN BOOLEAN MODE )',
			'label' => 'Category text sorting, parameter(<search term>)',
			'type' => 'float',
			'public' => false,
		),
	];


	public function __construct( \Aimeos\MShop\Common\Manager\Iface $manager, \Aimeos\MShop\ContextIface $context )
	{
		parent::__construct( $manager, $context );

		$func = function( $source, array $params ) {

			if( isset( $params[0] ) )
			{
				$str = '';
				$regex = '/(\&|\||\!|\-|\+|\>|\<|\(|\)|\~|\*|\:|\"|\'|\@|\\| )+/';
				$search = trim( mb_strtolower( preg_replace( $regex, ' ', $params[0] ) ), "' \t\n\r\0\x0B" );

				foreach( explode( ' ', $search ) as $part )
				{
					if( strlen( $part ) > 2 ) {
						$str .= '+' . $part . '* ';
					}
				}

				$params[0] = '\'' . $str . '\'';
			}

			return $params;
		};

		$this->attr['sort:supplier:relevance']['function'] = $func;
		$this->attr['supplier:relevance']['function'] = $func;
	}


	public function getSearchAttributes( bool $sub = true ) : array
	{
		return parent::getSearchAttributes( $sub ) + $this->createAttributes( $this->attr );
	}
}
