<?php
/*
 * Example PHP implementation used for the index.html example
 */
// DataTables PHP library
include( "./php/DataTables.php" );

// Alias Editor classes so they are easy to use
use
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Join,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate;
 header('Content-Type:text/html;charset=utf-8');
// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'auth_log_report' )
    ->fields(
        Field::inst( 'name' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'id' ),
        Field::inst( 'width' )->validator( 'Validate::notEmpty' )
    )
    ->process( $_POST )
    ->json();