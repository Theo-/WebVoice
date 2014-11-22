<?php
session_start();
require '../../../lib/Db.php';
require '../../../lib/Db-auth.php';
$id = ceil($_SESSION['sessionID']);
$db = basePDO();

$friends = $db->select("* FROM link WHERE id1=".$id." OR id2 = ".$id)->all();
?>
<ul>
	<?php 
	foreach ($friends as $link) {
		$idLinked = $link->id1;
		if($idLinked == $id) {
			$idLinked = $link->id2;
		}

		$user = $db->select("* FROM users WHERE id=".$idLinked)->one();

		echo '<li onclick="createDiscussionTab('.$user->id.')"><i class="icon-comment"></i> '.$user->display.'</li>';
	}
	?>
</ul>