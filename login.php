<?php
session_start();
require_once("./pdo.php");
$error = $_SESSION["error"] ?? false;
$name= $_POST["user"] ?? '';
$_SESSION['user']=$_POST['user'] ?? '';


if(isset($_POST['login'])) {
  if(!empty($_POST["name"]) AND !empty($_POST["password"])){
    $name= htmlspecialchars($_POST['name']);
    $password= htmlspecialchars($_POST['password']);
    $password = sha1($password);
   
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ? and password = ?");
    $stmt->execute(array($name, $password));
    $nameExist = $stmt->rowCount();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);


    if($nameExist !== 0) {
      $_SESSION["success"] = "Utilisateur connecté";
      $_SESSION["member"] = $userData["user_id"];

      header("Location: app.php");
      return;
    } else{
      $_SESSION["error"] = "Mauvais name ou password";
      header("Location: login.php");
      return;
    }
  } else{
    $_SESSION["error"] = "veuillez remplir tous les champs";
    header("Location: login.php");
    return;
  }  


if (isset($_POST["title"]) && isset($_POST["user_id"]) && isset($_POST["task_id"])) {
  $sql = "INSERT INTO tasks (title, user_id,task_id) VALUE (:title, :user_id, :task_id)";

  echo ("<pre>" . $sql . "</pre>");

  $stmt = $pdo->prepare($sql);

  $stmt->execute([
    ":title" => $_SESSION["title"],
    ":user_id" => $_SESSION["user_id"],
    ":task_id" => $_SESSION["task_id"],
    ":id" => $_SESSION["id"]
  ]);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="./style.css">
    <title>Se connecter</title>
</head>
<body>
  <div class="container">
    <form method="POST" class="form">
      <h4> connectez-vous</h4>
      <p style="color:red">
        <?php
        if(isset($error)) { 
        echo $error;
        unset($_SESSION["error"]);
        }  
        ?>
      </p>

      <div class="form-row">
      <label for="name" class="block">nom d'utilisateur : </label>
      <input type="text" name="name" id="name" >
      </div>


      <div class="form-row">
      <label for="password" class="block">mot de passe : </label>
      <input type="password" name="password" id="password" >
      </div>

      <input type="submit" name="login" class="btn" value="Se connecter"> 
      <a href="./indeeex.php"  class="btn  "> annuler </a>
    </form>
</boby>