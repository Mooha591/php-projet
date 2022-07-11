<?php
session_start();
require_once("./pdo.php");
$success = $_SESSION["success"] ?? false;
$error = $_SESSION["error"] ?? false;
$name= $_GET["user"] ?? '';


if(!isset($_SESSION["member"])){
  die("ACCÈS REFUSÉ");
}

  if(isset($_POST["add"])){
   if(!empty($_POST["task"])){
    $sql = 'INSERT INTO tasks (title, user_id, task_id) VALUES (:title, :user_id, :task_id)';
    $insert = $pdo->prepare($sql);
    $insert->execute([
        ":title" => $_POST['task'],
        ":user_id" => $_SESSION['member'],
        ":task_id"=> $_SESSION['task_id']
        ]);
      $_SESSION["success"] = "tâche correctement ajouté";
      header("Location: app.php");
      return;
  }
    else{
    $_SESSION["error"] = "Le champs est requis!";
    header("Location: app.php");
    return;
    }
  }

  // Selectionner toutes les tâches "user_id"
  $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ":user_id" => $_SESSION["member"]
  ]);
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($pdo);

// supprime la tache que j'ai selectionné
  if(isset($_POST['supprimer']) && isset($_POST["task_id"])){
    $sql = "DELETE FROM tasks WHERE task_id=:task_id ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":task_id" => $_POST["task_id"]
    ]);
    $_SESSION["success"]= "la suppression à été faite avec succès";
    header("Location: app.php");
    return;
  }

  if (isset($_POST["editer"]) && isset($_POST["user_id"])) {
    $_SESSION["user_id"]=$_POST["user_id"];
    header("Location: ./edit.php");
}

  if (isset($_POST["registrate"])) {
  if (empty($_POST["title"])){
    $_SESSION["error"] = "veuillez remplir le champs";
    header("Location: edit.php?task_id=" . $task_id);
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
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./normalize.css">
    <title>Todo App</title>
  </head>
  <body>
     <p style="color:red">
        <?php
         if(isset($_SESSION["tasks"])) {
      echo "<p style='color:red'>{$_SESSION["tasks"]}</p>";
      unset($_SESSION["tasks"]);}
      ?>
      </p>
    <form method="POST">
    <div class= form-row>
        <?php
    if(isset($_SESSION["success"])) {
      echo "<p style='color:green'>{$_SESSION["success"]}</p>";
      unset($_SESSION["success"]);
    }

    if(isset($_SESSION["error"])) {
      echo "<p style='color:red'>{$_SESSION["error"]}</p>";
      unset($_SESSION["error"]);
    }
    
    ?>
    <h1>Tâche à faire de<?php echo $_SESSION["user"];?> </h1>
    <!-- <h4> Tâches à faire de</h4> -->

        <div class="added">
            <input type="text" name="task">
            <input type="submit" name="add" value="Ajouter" class="btn">
            </div>
  
        <div class="todo">
         <?php
          foreach ($data as $tasks) {
            $tasks = <<<EOL
      <div>
      <p>{$tasks['title']}</p>
      <form method="POST" >
            <input type="hidden" name="task_id" value="{$tasks['task_id']}" >
            <input type="submit" name="supprimer" value="supprimer" class="btn_delete">
           <a href="./edit.php?task_id={$tasks['task_id']}">modifier</a>
          </form>
        </div>
  EOL;
  echo $tasks;
}
          ?>
        </div>
        <a href="./logout.php">Se déconnecter</a>
         </div>
        </div>
</body>
</html>