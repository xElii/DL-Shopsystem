<?php
require_once("../lib/config.php");
session_start();
$username=$_SESSION["username"];
if ($username!="ekoeppl") {echo '<script type="text/javascript">alert("Das Admin Panel ist nur für Admins! Du ('.$username.') bist kein Administrator!");window.location.href = "../";</script>';}
if ($_GET["u"]) {$user=$_GET["u"];} else {header("Location: ./");}

// Edit Money
if ($_SERVER['REQUEST_METHOD']=='POST' && $_POST["form"]=="1") {
    $newmoney=$_POST["newmoney"];
    if ($link->query("UPDATE shop SET coins='$newmoney' WHERE username='$user';")===TRUE) {$msg = '<div class="alert alert-success my-3">Geld auf <u>'.$newmoney.'</u> gesetzt.</div>';}
}

// Get userdata
$result = mysqli_query($link, "SELECT * FROM shop WHERE username='$user'");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $usermoney=$row["coins"];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Datalok Shop | Admin</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lib/style.css">
    <link rel="shortcut icon" href="../lib/logo.webp" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
        <div class="container-md">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbar">
                <a class="navbar-brand" href="../"><img src="../lib/logo.webp" alt="SUPPORT" width="35" style="transform: translate(0,-1.5px);"> Shop Admin</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../"><i class="bi bi-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/"><i class="bi bi-wrench-adjustable"></i> Admin</a></li>
                </ul>
                <div class="d-flex">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"></i> <?php echo $username?> </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="../settings"><i class="bi bi-gear-fill"></i> Einstellungen</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../logout"><i class="bi bi-door-open"></i> Ausloggen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-sm mt-3">
        <div class="card mb-3">
            <div class="card-header text-center"><h3 class="fs-4" style="margin:0;"><i class="bi bi-person-circle"></i> User Info</h3></div>
            <div class="card-body text-center"><?php echo $user.' • '.$usermoney;?>  <i class="bi bi-coin"></i></div>
        </div>
        <div class="card">
            <div class="card-header text-center"><h3 class="fs-4" style="margin:0;"><i class="bi bi-coin"></i> Göld bearbeiten</h3></div>
            <div class="card-body text-center">
                <form action="./money?u=<?php echo $user;?>" method="POST" class="mx-auto" style="width: 260px;">
                    <input type="hidden" name="form" value="1">
                    <div class="mb-3">
                        <label class="form-label">Neuer Geldbetrag:</label>
                        <input name="newmoney" type="number" class="form-control" value="<?php echo $usermoney?>" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle-fill"></i> NEW MONEY</button>
                </form>
            </div>
        </div>
        <?php echo $msg;?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>
<?php $link->close();?>