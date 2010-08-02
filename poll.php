<?php
require_once('config.php');
require_once('functions.php');

$conn = connect();

$p = select(
	'poll', array('*')
);

$p = $p['data'][0];

$c = query('
	SELECT
		c.id, c.name, COUNT( v.id ) as votes
	FROM choices c
	INNER JOIN poll p ON p.id = c.fk
	LEFT JOIN votes v ON c.id = v.fk
	WHERE
		p.id = '. $p['id'] .'
	GROUP BY
		c.name
	ORDER BY
		c.id
');

$total = query('
	SELECT COUNT( v.id ) as total
	FROM votes v
	INNER JOIN choices c ON v.fk = c.id
	WHERE c.fk = ' . $p['id']
);

$total = $total['data'][0]['total'];

?>
