<?php
session_start();
require_once("pdo.php");
$success = $_SESSION["success"] ?? false;
$error = $_SESSION["error"] ?? false;

    // editer notre tâche "title"
  if (
        isset($_POST["editer"]) && isset($_POST["title"])

  ) {
      if (empty($_POST["title"])){
      $_SESSION["error"] = "veuillez remplir le champs";
      header("Location: edit.php?task_id=" . $_GET["task_id"]);
      return;
  }
    // Mise à jour de notre table  tâche (title, task id pour récupérer l'id de notre tâche)
    $sql = "UPDATE tasks SET title = :title WHERE task_id = :task_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":title" => $_POST["title"],
      ":task_id" => $_GET["task_id"]
      
    ]);

     $_SESSION["success"] = "tâche modifié";
     header("Location: app.php?todos_id=".$_REQUEST['id']);
     return;
  }  

      
    // selectionner les titre de la table tasks pour la task_id
      $sql = "SELECT title FROM tasks WHERE task_id = :task_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
      ":task_id" => $_GET["task_id"]
      
  ]);
      $tasks = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<h4> Éditer Une Tâche </h4>
 <?php
   if(isset($_SESSION["error"])) {
         echo "<p style='color:red'>{$_SESSION["error"]}</p>";
         unset($_SESSION["error"]);
    }
  ?>
  <!-- méthode POST petite def: sert à transmettre des données d'une page PHP à l'autre mais contrairement à la méthode GET, ces données ne sont pas visibles dans l'URL -->
  <form method="POST">
            
          <p><input type="text" name="title" value="<?= $tasks["title"] ?>"></p>
          <input type="submit" name="editer" value="editer" class="btn">
                
        </form>
          <a href="./app.php">Annuler</a>
    </div>
  </div>