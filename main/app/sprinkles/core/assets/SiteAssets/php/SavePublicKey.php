<?php
if (isset($_POST["UserID"]) && isset($_POST["PublicKeyString"])) {
    require "DataBaseConf.php";
    $CheckIfAlreadySetStmt = $conn->prepare("SELECT count(*) FROM `PublicKeys` WHERE UserID = :UserID");
    $CheckIfAlreadySetStmt->bindValue(':UserID', $_POST['UserID']);
    $CheckIfAlreadySetStmt->execute();
    $CheckIfAlreadySetRes = $CheckIfAlreadySetStmt->fetchColumn();
    if ($CheckIfAlreadySetRes == 1) {
        $UpdatePublicKeyStmt = $conn->prepare("UPDATE `PublicKeys` SET PublicKeyString = :PublicKeyString WHERE UserID = :UserID");
        $UpdatePublicKeyStmt->execute(array('PublicKeyString' => $_POST["PublicKeyString"], 'UserID' => $_POST["UserID"]));
    } else if ($CheckIfAlreadySetRes == 0) {
        $InsertPublicKeyStmt = $conn->prepare("INSERT INTO `PublicKeys` (UserID, PublicKeyString) VALUES (:UserID, :PublicKeyString)");
        $InsertPublicKeyStmt->execute(array('PublicKeyString' => $_POST["PublicKeyString"], 'UserID' => $_POST["UserID"]));
    }
} else {
    http_response_code(400);
}