<?php

if (isset($_POST['delete_btn_set'])){
    global $pdo;
    $del_id = $_POST['delete_id'];

    $query = "DELETE FROM categories WHERE id = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute($del_id);
}