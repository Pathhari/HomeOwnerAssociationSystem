<?php

// Alias Editor classes for easier referencing
use
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Mjoin,
    DataTables\Editor\Options,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate;

// Load the DataTables bootstrap file
include( "lib/DataTables.php" );

// Create an Editor instance and set up the table and primary key
$editor = Editor::inst( $db, 'myTable', 'primaryKey' )

    // Define the fields you want to include in your table
    ->fields(
        Field::inst( 'column1' )->validator( 'Validate::notEmpty' ),
        Field::inst( 'column2' ),
        Field::inst( 'column3' ),
        // ... Add more fields as needed
    )

    // Process the request from the Editor
    ->process( $_POST )
    ->json();
?>