<?php
session_start();
if ($_SESSION["username"] == null) {header("Location: //datalok.de/account/login?url=https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");}
$username=$_SESSION["username"];
$msg='';$get=0;$buy=0;
require_once("./lib/config.php");

$result = mysqli_query($link, "SELECT * FROM shop WHERE username='$username'");
if (mysqli_num_rows($result) > 0) {
    while($row = $result->fetch_assoc()) {
        $nextcoins=$row['nextcoins'];
        $coins=$row['coins'];
        $discord=$row['discord'];
        $newcoins=$row['randomcoins'];
    }
}

// Random Gold holen
$getcoins=$_GET["get"] ?? "";
if ($getcoins==1 && $nextcoins<time()) {
    $randomcoins=random_int(240,480);
    $nextcoinsday=strtotime('+86300 seconds', time());
    $sql = "UPDATE shop SET nextcoins=$nextcoinsday,coins=$coins+$newcoins,randomcoins=$randomcoins,noti_sent=0 WHERE username='$username'";
    if ($link->query($sql) === TRUE) {header("Location: ./");}
}
// ----------------------
//// Dinge Kaufen
$purchase=$_GET["buy"] ?? "";
// Prime Kaufen
if ($purchase=="prime" && $coins>=4000) {
    $sql = "UPDATE shop SET coins=$coins-4000 WHERE username='$username'";
    if ($link->query($sql) === TRUE) {}
    discordws($username." hat <@&978004069400596520> gekauft. <@493144615566704642> Bitte Befehl ausführen!\n`/temprole add @$discord 1month <@&978004069400596520>`");
    $msg='<div class="alert alert-success text-center">Kauf erfolgreich! Du wirst deinen Rang bald erhalten!</div>';
} elseif ($purchase=="prime" && $coins<4000) {$msg='<div class="alert alert-danger text-center">Du hast zu wenig Geld.</div>';}
// ELITE Kaufen
if ($purchase=="elite" && $coins>=8000) {
    $sql = "UPDATE shop SET coins=$coins-8000 WHERE username='$username'";
    if ($link->query($sql) === TRUE) {}
    discordws($username." hat <@&978006216997503046> gekauft. <@493144615566704642> Bitte Befehl ausführen!\n`/temprole add @$discord 1month <@&978006216997503046>`");
    $msg='<div class="alert alert-success text-center">Kauf erfolgreich! Du wirst deinen Rang bald erhalten!</div>';
} elseif ($purchase=="prime" && $coins<8000) {$msg='<div class="alert alert-danger text-center">Du hast zu wenig Geld.</div>';}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Datalok Shop</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./lib/style.css">
    <link rel="shortcut icon" href="./lib/logo.webp" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
        <div class="container-md">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbar">
                <a class="navbar-brand" href="./"><img src="./lib/logo.webp" alt="SUPPORT" width="35" style="transform: translate(0,-1.5px);"> Shop</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="./"><i class="bi bi-house"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./admin/"><i class="bi bi-wrench-adjustable"></i> Admin</a></li>
                </ul>
                <div class="d-flex">
                    <h4 class="my-auto me-3 text-success-emphasis"><i class="bi bi-coin"></i> <span id="coins"><?php echo $coins?></span></h4>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"></i> <?php echo $username?> </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="//datalok.de/account"><i class="bi bi-gear-fill"></i> Einstellungen</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="//datalok.de/account/logout?url=https://<?php echo($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);?>"><i class="bi bi-door-open"></i> Ausloggen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-sm mt-3">
        <div class="card mb-3">
            <div class="card-body text-center">
                <h3 class="mb-3"><i class="bi bi-gift-fill"></i> Hohl dir alle 24 Stunden zwischen 30 und 150 Göld.</h3>
                <h3><a href="?get=1" id="getcoinslink" class="btn btn-success" style="display:none;"><i class="bi bi-coin"></i> <?php echo $newcoins?> Göld holen</a></h3>
                <h2 class="text-success-emphasis" id="getcoins"></h2>
            </div>
        </div>
        <?php echo $msg;?>
        <div class="card mb-3">
            <div class="card-header text-center mb-2"><h3>Der <i class="bi bi-cart"></i> Shop selbst</h3></div>
            <div class="card-body text-center">
                <div class="row row-cols-1 row-cols-md-2 mb-3 text-center">
                    <div class="col">
                        <div class="card rounded-3 shadow-sm">
                            <div class="card-header py-3" style="background-color: #00D26A;">
                                <h4 class="my-0 fw-normal text-white">PRIME Rolle</h4>
                            </div>
                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-success fs-6">Empfohlen!</span>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><i class="bi bi-coin"></i> 4000<small class="text-body-secondary fw-light">/1 Monat</small></h1>
                                <ul class="list-unstyled mt-3">
                                    <li><i class="bi bi-check-lg"></i> Muten, Kicken, Moven von <span class="badge bg-primary">Mitglieder</span></li>
                                    <li><i class="bi bi-check-lg"></i> Emojis, Sounds und Sticker Hochladen</li>
                                    <li><i class="bi bi-check-lg"></i> Audit Log Ansehen</li>
                                    <li><i class="bi bi-check-lg"></i> Nicknames von anderen ändern</li>
                                    <li><i class="bi bi-check-lg"></i> TTS-Nachrichten</li>
                                    <li><i class="bi bi-check-lg"></i> Audit Log Ansehen</li>
                                </ul>
                                <?php if ($coins>=4000) {echo '<a href="?buy=prime" class="w-100 btn btn-lg btn-primary"><i class="bi bi-bag-fill"></i> Kaufen</a>';} 
                                else {echo '<div class="progress" style="height: 48px"><div class="progress-bar bg-secondary" style="width: '.round(($coins*100/4000)).'%">'.round(($coins*100/4000)).'%</div></div>';}?>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-3 shadow-sm">
                            <div class="card-header py-3" style="background-color: #8D65C5;">
                                <h4 class="my-0 fw-normal text-white">ELITE Rolle</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title"><i class="bi bi-coin"></i> 8000<small class="text-body-secondary fw-light">/1 Monat</small></h1>
                                <ul class="list-unstyled mt-3">
                                    <li><i class="bi bi-check-lg"></i> Alles von <span class="badge bg-success">Prime</span></li>
                                    <li><i class="bi bi-check-lg"></i> Muten, Kicken, Moven von <span class="badge bg-primary">Mitglieder</span> und <span class="badge bg-success">Prime</span></li>
                                    <li><i class="bi bi-check-lg"></i> Timeouts vergeben</li>
                                    <li><i class="bi bi-check-lg"></i> Nachrichten Löschen</li>
                                    <li><i class="bi bi-check-lg"></i> Events verwalten</li>
									<br>
                                </ul>
                                <?php if ($coins>=8000) {echo '<a href="?buy=elite" class="w-100 btn btn-lg btn-primary"><i class="bi bi-bag-fill"></i> Kaufen</a>';} 
                                else {echo '<div class="progress" style="height: 48px"><div class="progress-bar bg-secondary" style="width: '.round(($coins*100/8000)).'%">'.round(($coins*100/8000)).'%</div></div>';}?>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="fs-5">Du erhälst deinen Rang, nachdem dir ein Admin ihn gegeben hat. Der Admin erhält eine E-Mail wenn du etwas kaufst.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header text-center"><h3><i class="bi bi-trophy-fill"></i> Leaderboard</h3>Top 3 Göld Owner</div>
            <div class="card-body"><table class="w-75 table table-striped mx-auto">
                <tbody>
                <?php 
                $result = mysqli_query($link, "SELECT username,coins FROM shop ORDER BY coins DESC LIMIT 3");
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo('<tr>
                            <td>'.$row["username"].'</td>
                            <td>'.$row["coins"].' <i class="bi bi-coin"></i></td>
                            </tr>');
                    }
                }
                ?>
                </tbody>
            </table>
            </div>
        </div>
    <br></div>
</body>
<script>
var unixTimestamp = (<?php echo $nextcoins?> * 1000);
var countdownElement = document.getElementById('getcoins');
var linkElement = document.getElementById('getcoinslink');
var targetDate = new Date(unixTimestamp).getTime();
function updateCountdown() {
  var now = new Date().getTime();
  var timeDifference = targetDate - now;
  var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);
  var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
  var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var formattedSeconds = (seconds < 10) ? "0" + seconds : seconds;
  var formattedMinutes = (minutes < 10) ? "0" + minutes : minutes;
  var formattedHours = (hours < 10) ? "0" + hours : hours;
  countdownElement.innerHTML = formattedHours + ":" + formattedMinutes + ":" + formattedSeconds;
  if (timeDifference <= 0) {
    countdownElement.style.display = 'none';
    linkElement.style.display = 'block';
  } else {
    setTimeout(updateCountdown, 1000);
  }
}
updateCountdown();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</html>
<?php $link->close();?>