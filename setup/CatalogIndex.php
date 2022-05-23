<?php


namespace Aimeos\Upscheme\Task;


class CatalogIndex extends Base
{
	public function after() : array
	{
		return ['Catalog'];
	}


	public function up()
	{
		$db = $this->db( 'db-catalog' );

		if( !$db->hasIndex( 'mshop_catalog', 'idx_mscat_label' ) )
		{
			$this->info( 'Creating catalog fulltext index', 'vv' );

			$db->for( 'mysql', 'CREATE FULLTEXT INDEX `idx_mscat_label` ON `mshop_catalog` (`label`)' );

			try {
				$db->for( 'postgresql', 'CREATE INDEX "idx_mscat_label" ON "mshop_catalog" USING GIN (to_tsvector(\'english\', "label"))' );
			} catch( \Exception $e ) {
				// Doctrine DBAL bug: https://github.com/doctrine/dbal/issues/5351
			}
		}
	}
}
