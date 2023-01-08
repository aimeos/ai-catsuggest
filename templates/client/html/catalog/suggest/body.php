<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */

$enc = $this->encoder();
$items = [];


foreach( $this->get( 'suggestSupplierItems', [] ) as $id => $supItem )
{
	$name = strip_tags( $supItem->getName() );
	$params = ['s_name' => $supItem->getName( 'url' ), 'f_supid' => $supItem->getId()];

	$items[] = [
		'label' => $name,
		'html' => '
			<div class="aimeos catalog-suggest">
				<a class="suggest-item" href="' . $enc->attr( $this->link( 'client/html/supplier/detail/url', $params ) ) . '">
					<div class="item-name">' . $enc->html( $name ) . '</div>
				</a>
			</div>
		'
	];
}

foreach( $this->get( 'suggestCatalogItems', [] ) as $id => $catItem )
{
	$name = strip_tags( $catItem->getName() );
	$params = ['f_name' => $catItem->getName( 'url' ), 'f_catid' => $catItem->getId()];

	$items[] = [
		'label' => $name,
		'html' => '
			<div class="aimeos catalog-suggest">
				<a class="suggest-item" href="' . $enc->attr( $this->link( 'client/html/catalog/tree/url', $params ) ) . '">
					<div class="item-name">' . $enc->html( $name ) . '</div>
				</a>
			</div>
		'
	];
}

foreach( $this->get( 'suggestItems', [] ) as $id => $productItem )
{
	$supplier = $productItem->getRefItems( 'supplier' )->getName()->first();
	$name = strip_tags( $productItem->getName() ) . ( $supplier ? ' • ' . $supplier : '' );
	$params = ['d_name' => $productItem->getName( 'url' ), 'd_prodid' => $productItem->getId(), 'd_pos' => ''];

	$items[] = [
		'label' => $name,
		'html' => '
			<div class="aimeos catalog-suggest">
				<a class="suggest-item" href="' . $enc->attr( $this->link( 'client/html/catalog/detail/url', $params ) ) . '">
					<div class="item-name">' . $enc->html( $name ) . '</div>
				</a>
			</div>
		'
	];
}

echo json_encode( $items );
