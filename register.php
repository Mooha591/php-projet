<?php 
session_start();
require_once("./pdo.php");
$error = $_SESSION["error"] ?? false;
$name= $_SESSION["user"] ?? '';

// $name= $_POST["name"] ?? '';
// $confirmPassword= $_POST['confirmPassword'] ?? '';
// $password= $_POST["password"] ?? '';

// $message=false;
// $salt='XyZzy12\*\_';

// afficher message d'erreur

if(isset($_POST['name']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
  if(!empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])){
    $name= htmlspecialchars($_POST['name']);
    $password= htmlspecialchars($_POST['password']);
    $confirmPassword= htmlspecialchars($_POST['confirmPassword']);
    $password = sha1($password);
    $confirmPassword = sha1($confirmPassword);

    $stmt = $pdo->prepare("SELECT name FROM users WHERE name = ?");
    $stmt->execute(array($name));
    $nameExist = $stmt->rowCount();

    
    if($password === $confirmPassword) {
      // echo "enregistré";
    } else {
      $_SESSION["error"] = "Vous n'avez pas entrer le mots de passe identique veuillez réessayer";
      header("Location: register.php");
      return;
    }

    if($nameExist === 0){
      $insert=$pdo->prepare("INSERT INTO users(name,password) VALUES(?,?)");
      $insert->execute(array($name, $password));
      $_SESSION["success"] = "utilisateur enregistré";
      header("Location: indeeex.php");
      return;
    } else{
      $_SESSION["error"] = "Le nom existe deja";
      header("Location: register.php");
      return;
    } 
    
    
  } else{
    $_SESSION["error"] = "veuillez remplir tous les champs";
    header("Location: register.php");
    return;
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
    <title>Enregistrez-vous</title>
</head>
<body>
  <div class="container">
    <form method="POST" class="form">
      <h4>Enregistrez-vous</h4>
  
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
        <input type="text" name="name" id="name">
      </div>

      <div class="form-row">
        <label for="password" class="block">mot de passe : </label>
        <input type="password" name="password" id="password" >
      </div>

      <div class="form-row">
        <label for="confirmPassword" class="block">confirmez le mot passe : </label>
        <input type="password" name="confirmPassword" id="confirmPassword" >
      </div>

      <input type="submit" class="btn" value="s'enregistrer" >
      <a href="./indeeex.php" class="btn">annuler</a>

    </form>
  </div>
</body>
</html>