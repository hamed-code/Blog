<?php

    require '../functions/helpers.php';
    require '../functions/pdo_connection.php';

    global $pdo;

    if(!isset($_GET['post_id'])){
        redirect('admin/post');
    }

    $query = "SELECT * FROM `posts` WHERE id = ?";
    $statemetn = $pdo->prepare($query);
    $statemetn->execute([$_GET['post_id']]);
    $posts = $statemetn->fetch();
    if($post === false){
        redirect('admin/post');
    }

    if (isset($_POST['title']) && $_POST['title'] !== '' && isset($_POST['cat_id']) && $_POST['cat_id'] !== '' && isset($_POST['body']) && $_POST['body'] !== ''){

        $query = "SELECT * FROM `categories` WHERE cat_id = ?";
        $statemetn = $pdo->prepare($query);
        $statemetn->execute($_POST['cat_id']);
        $category = $statemetn->fetch();

        if(isset($_FILES['image']) && $_FILES['image']['name'] !== ''){

            $allowedMimes = ['png', 'jpeg', 'jpg', 'gif'];

            $imgageMime = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            if(!in_array($imgageMime, $allowedMimes)){
                redirect('admin/post');
            }

            $basePath = dirname(dirname(__DIR__));
            if(file_exists($basePath . $post->image)){
                unlink($basePath . $post->image);
            }
            $image = 'assets/images/posts/' . date("Y-m-d-H-i-s") . '.' . $imgageMime;
            $image_upload = move_uploaded_file($_FILES['image']['tmp_name'], $basePath . $image);

            if($category !== false && $image_upload !== false){

                $query = "UPDATE `posts` SET title = ?, cat_id = ?, body = ?, image = ?, updated_at = now() WHERE id = ? ;";
                $statemetn = $pdo->prepare($query);
                $statemetn->execute([$_POST['title'], $_POST['cat_id'], $_POST['body'], $image, $_GET['post_id']]);
            }
        }
        else{
            if($category !== false){
                $query = "UPDATE php_project.posts SET title = ?, cat_id = ?, body = ?, updated_at = now() WHERE id = ?;";
                $statement = $pdo->prepare($query);
                $statement->execute([$_POST['title'], $_POST['cat_id'], $_POST['body'], $_GET['post_id']]);

            }
        }
        redirect('admin/post');

    }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" media="all" type="text/css">
    <link rel="stylesheet" href="../../assets/css/style.css" media="all" type="text/css">
</head>

<body>
    <section id="app">


        <section class="container-fluid">
            <section class="row">
                <section class="col-md-2 p-0">
                </section>
                <section class="col-md-10 pt-3">

                    <form action="<?= url('admin/post/edit.php?post_id' . $_GET['post_id']) ?>" method="post" enctype="multipart/form-data">
                        <section class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="title ..." value="w">
                        </section>
                        <section class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </section>
                        <section class="form-group">
                            <label for="cat_id">Category</label>
                            <select class="form-control" name="cat_id" id="cat_id">
                                <?php
                                    global $pdo;
                                    $query = "SELECT * FROM `categories`";
                                    $statemetn = $pdo->prepare($query);
                                    $statemetn->execute();
                                    $categories = $statemetn->fetchAll();
                                    foreach ($categories as $category){
                                ?>
                                <option value="<?= $category->id ?>" <?php if($category->id == $post->cat_id) echo 'selected' ?>><?= $category->name ?></option>
                                <?php } ?>
                        </select>
                        </section>
                        <section class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" name="body" id="body" rows="5" placeholder="body ...">sss</textarea>
                        </section>
                        <section class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </section>
                    </form>

                </section>
            </section>
        </section>

    </section>

    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
</body>

</html>