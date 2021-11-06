<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
require_once '../../functions/check-login.php';

if (isset($_POST['delete_btn_set'])){
    global $pdo;
    $del_id = $_POST['delete_id'];

    $query = "DELETE FROM categories WHERE id = ? ";
    $statement = $pdo->prepare($query);
    $statement->execute([$del_id]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
    <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?= asset("assets/css/bootstrap.min.css"); ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset("assets/css/style.css"); ?>" media="all" type="text/css">
</head>

<body>
    <section id="app">

        <?php require_once '../layouts/top-nav.php'; ?>

        <section class="container-fluid">
            <section class="row">
                <section class="col-md-2 p-0">

                    <?php require_once '../layouts/sidebar.php'; ?>

                </section>
                <section class="col-md-10 pt-3">

                    <section class="mb-2 d-flex justify-content-between align-items-center">
                        <h2 class="h4">Categories</h2>
                        <a href="<?= url('admin/category/create.php') ?>" class="btn btn-sm btn-success">Create</a>
                    </section>

                    <section class="table-responsive">
                        <table class="table table-striped table-">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>name</th>
                                    <th>setting</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                global $pdo;
                                $query = "SELECT * FROM php_project.categories";
                                $statement = $pdo->prepare($query);
                                $statement->execute();
                                $categories = $statement->fetchAll();
                                // dd($categories);
                                foreach ($categories as $category) {
                                ?>
                                    <tr>
                                        <td><?= $category->id ?></td>
                                        <td><?= $category->name ?></td>
                                        <td>
                                            <a href="<?= url('admin/category/edit.php?cat_id=') . $category->id ?>" class="btn btn-info btn-sm">Edit</a>
                                            
                                        </td>
                                        <td>
                                            <input type="hidden" class="delete_id_value" value="<?= $category->id ?>">
                                            <a href="javascript:void(0)" class="delete_btn_ajax btn btn-danger btn-sm">Confirm Delete</a>
                                        </td>
                                    </tr>

                                <?php } ?>


                            </tbody>
                        </table>
                    </section>


                </section>
            </section>
        </section>





    </section>
    <script>
        $(document).ready(function() {
            $('.delete_btn_ajax').click(function(e) {
                e.preventDefault();
                // console.log("helloman");
                var deleteid = $(this).closest("tr").find(".delete_id_value").val();
                // console.log(deleteid);
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this imaginary file!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                           $.ajax({
                                type: "POST",
                                url: "index.php",
                                data: {
                                    "delete_btn_set":1,
                                    "delete_id": deleteid,
                                },
                                success: function (response){
                                    swal("Data Deleted Successfuly..", {
                                        icon: "success",
                                    }).then((result) => {
                                        location.reload();
                                    });
                                }
                           });
                        } 
                    });
            });
        });
    </script>
    <script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>