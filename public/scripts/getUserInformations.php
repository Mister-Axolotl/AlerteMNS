<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

if (isset ($_POST['userId'])) {
    $user_id = $_POST['userId'];

    $sql = "SELECT user_firstname, user_lastname, user_picture FROM table_user WHERE user_id= :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $user_id]);

    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        // Si des données sont trouvées, les encoder en JSON et les renvoyer
        echo json_encode($user_info);
    } else {
        // Si aucun résultat n'est trouvé, renvoyer un message d'erreur
        echo json_encode(["error" => "Aucun utilisateur trouvé avec l'ID spécifié"]);
    }
}