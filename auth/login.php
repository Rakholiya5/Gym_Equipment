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
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="login.php">
                            <?php include('../errors.php'); ?>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input class="form-control" type="text" name="username">
                            </div>
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password">
                            </div>
                            <button type="submit" name="login_user" class="btn btn-primary mb-3">Login</button>

                            <p>
                                Not yet a member? <a href="register.php">Sign up</a>
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