<?php

namespace Aimeos\MShop\Catalog\Manager\Decorator;


/**
 * Full text search config for catalog label
 */
class Search extends Base
{
	private $attr = [
		'catalog:relevance' => array(
			'code' => 'catalog:relevance()',
			'internalcode' => 'MATCH( mcat."label" ) AGAINST( $1 IN BOOLEAN MODE )',
			'label' => 'Category texts, parameter(<search term>)',
			'type' => 'float',
			'internaltype' => \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT,
			'public' => false,
		),
		'sort:catalog:relevance' => array(
			'code' => 'sort:catalog:relevance()',
			'internalcode' => 'MATCH( mcat."label" ) AGAINST( $1 IN BOOLEAN MODE )',
			'label' => 'Category text sorting, parameter(<search term>)',
			'type' => 'float',
			'internaltype' => \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT,
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
						$str .= $part . '* ';
					}
				}

				$params[0] = '\'' . $str . '\'';
			}

			return $params;
		};

		$this->attr['sort:catalog:relevance']['function'] = $func;
		$this->attr['catalog:relevance']['function'] = $func;
	}


	public function getSearchAttributes( bool $sub = true ) : array
	{
		return parent::getSearchAttributes( $sub ) + $this->createAttributes( $this->attr );
	}
}
