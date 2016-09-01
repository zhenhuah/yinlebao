<?php

/*
 * Editor server script for DB table t_import_tasks
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


//
//			->validator( 'Validate::dateFormat', array( 'format'=>'P hh:ii' ) )
//			->getFormatter( 'Format::date_sql_to_format', 'P hh:ii' )
//			->setFormatter( 'Format::date_format_to_sql', 'P hh:ii' ),
//

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'v9_import_tasks', 'id' )
	->fields(
		Field::inst( 'f_task_id' )
			->validator( 'Validate::notEmpty' )
			->validator( 'Validate::unique' ),
		Field::inst( 'f_task_name' ),
		Field::inst( 'f_data_id' )
			->validator( 'Validate::url' ),
		Field::inst( 'f_template_id' ),
		Field::inst( 'f_peroid' ),
		Field::inst( 'f_time' ),
		Field::inst( 'f_auto' ),
		Field::inst( 'f_status' ),
		Field::inst( 'f_pre_scripts' ),
		Field::inst( 'f_scripts' ),
		Field::inst( 'f_post_scripts' )
		//Field::inst( 'f_mid' )
		//	->validator( 'Validate::unique' )
		//	->setValue('id')
	)
	->process( $_POST )
	->json();
