<?php

    require_once '../../functions/helpers.php';
    require_once '../../functions/pdo_connection.php';

    global $pdo;

    if(isset($_GET['post_id']) && $_GET['post_id'] !== ''){
        
        $query = "SELECT * FROM php_project.posts WHERE id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$_GET['post_id']]);
        $post = $statement->fetch();
        
        $basePath = dirname(dirname(__DIR__));      //we are in this project now
        if(file_exists($basePath . $post->image)){      //all of your image address

            unlink($basePath . $post->Image);           //delete image
        }   

        $query = "DELETE FROM php_project.posts WHERE id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$_GET['post_id']]);

    }

    redirect('admin/post');