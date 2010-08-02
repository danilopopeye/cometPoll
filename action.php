<?php
require_once('config.php');
require_once('functions.php');

$conn = connect();

if( isset( $_POST['vote'] ) ){
	$id = $_POST['vote'];
	$channel = $_POST['channel'];

	$ins = insert('votes', array( 'fk', 'date' ), array( $id, time() ));

	echo json_encode( $ins );

	if( $ins['status'] === TRUE ){
		CPush( $channel, array(
			'vote' => $id
		) );
	}
}

?>
