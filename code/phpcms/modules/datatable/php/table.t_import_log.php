<?php

/*
 * Editor server script for DB table t_import_log
 * Created by http://editor.datatables.net/generator
 */

// DataTables PHP library and database connection
include( "lib/DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Join,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate;


// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'v9_import_log', 'id' )
	->fields(
		Field::inst( 'f_task_id' ),
		Field::inst( 'f_starttime' )
			->validator( 'Validate::dateFormat', array( 'format'=>'D, j M y' ) )
			->getFormatter( 'Format::date_sql_to_format', 'D, j M y' )
			->setFormatter( 'Format::date_format_to_sql', 'D, j M y' ),
		Field::inst( 'f_duration' ),
		Field::inst( 'f_responser' ),
		Field::inst( 'f_success' ),
		Field::inst( 'f_failed' )
	)
	->process( $_POST )
	->json();
