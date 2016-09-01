<?php

/*
 * Editor server script for DB table plugins
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
Editor::inst( $db, 'plugins', 'id' )
	->fields(
		Field::inst( 'plugins.text' ),
		Field::inst( 'plugins.password' ),
		Field::inst( 'plugins.textarea' ),
		Field::inst( 'plugins.selector' ),
		Field::inst( 'plugins.checkbox' ),
		Field::inst( 'plugins.radio' ),
		Field::inst( 'plugins.date' )
			->validator( 'Validate::dateFormat', array( 'format'=>'D, j M y' ) )
			->getFormatter( 'Format::date_sql_to_format', 'D, j M y' )
			->setFormatter( 'Format::date_format_to_sql', 'D, j M y' ),
		Field::inst( 'plugins.readonly' ),
		Field::inst( 'plugins.notes' )
		//Field::inst( 'plugins.image')
	)
	->process( $_POST )
	->json();
