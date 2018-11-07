<?php

include('includes/config.php');

$userid = intval($_GET['Donor_id']);
$sql = "SELECT * from  tableorgandonors where Donor_id=:uid ";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $userid, PDO::PARAM_STR);

$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
if ($query->rowCount() > 0) {
    foreach ($results as $result) {

        header('Content-Type:' . $result->mime);
        echo $result->data;
    }
}
?>