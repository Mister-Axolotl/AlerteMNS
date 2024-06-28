<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

if (isset($_POST['userId'])) {
    $sql = "SELECT user_firstname, user_lastname, user_picture,
			GROUP_CONCAT(role_badge) AS roles_badge,
			GROUP_CONCAT(role_name) AS roles_name
			FROM table_user
			INNER JOIN table_user_role ON table_user.user_id = table_user_role.user_role_user_id
			INNER JOIN table_role ON table_user_role.user_role_role_id = table_role.role_id
			WHERE user_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_POST['userId']]);

    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_info) {
        // Si des données sont trouvées, les encoder en JSON et les renvoyer
        echo json_encode($user_info);
    } else {
        // Si aucun résultat n'est trouvé, renvoyer un message d'erreur
        echo json_encode(["error" => "Aucun utilisateur trouvé avec l'ID spécifié"]);
    }
}