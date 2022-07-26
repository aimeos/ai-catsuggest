<?php


namespace Aimeos\Upscheme\Task;


class SupplierIndex extends Base
{
	public function after() : array
	{
		return ['Supplier'];
	}


	public function up()
	{
		$db = $this->db( 'db-supplier' );

		if( !$db->hasIndex( 'mshop_supplier', 'idx_mssup_label' ) )
		{
			$this->info( 'Creating supplier fulltext index', 'vv' );

			$db->for( 'mysql', 'CREATE FULLTEXT INDEX `idx_mssup_label` ON `mshop_supplier` (`label`)' );

			try {
				$db->for( 'postgresql', 'CREATE INDEX "idx_mssup_label" ON "mshop_supplier" USING GIN (to_tsvector(\'english\', "label"))' );
			} catch( \Exception $e ) {
				// Doctrine DBAL bug: https://github.com/doctrine/dbal/issues/5351
			}
		}
	}
}
