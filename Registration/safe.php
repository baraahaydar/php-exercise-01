<?php
    session_start();
    if(isset($_SESSION['token'])) {
        if($_SESSION['token'] != 123123) {
            $_SESSION['goneBack'] = "You should login first";
            header('Location: home.php');
        }
    }
    else {
        $_SESSION['goneBack'] = "You should login first";
        header('Location: home.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Welcome Page</title>
</head>

<body>
    <h1>Welcome user <?php if(isset($_SESSION['welcome'])) echo $_SESSION['welcome']; ?></h1>
</body>

</html>
