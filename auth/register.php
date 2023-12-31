<?php include('../Controllers/server.php') ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registration system PHP and MySQL</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header p-3">
                        <h4>Registration Form</h4>
                    </div>
                    <div class="card-body">

                        <form method="post" action="register.php">
                            <?php include('../errors.php'); ?>
                            <div class="form-group mb-3">
                                <label>Username</label>
                                <input class="form-control" type="text" name="username"
                                    value="">
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" value="">
                            </div>
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password_1">
                            </div>
                            <div class="form-group mb-3">
                                <label>Confirm password</label>
                                <input class="form-control" type="password" name="password_2">
                            </div>
                            <div class="form-group mb-3">
                                <label>User Type</label>
                                <select class="form-control" name="usertype">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" name="reg_user" class="btn btn-primary mb-3">Register</button>
                            <p>
                                Already a member? <a href="login.php">Sign in</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>