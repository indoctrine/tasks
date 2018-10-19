<?php declare(strict_types=1);
      require_once('../strict-typing.php');
/*                             Task List
              Stores a list of tasks and allows completion.
                      An exercise in OO programming.
                      SEE addtasks.php FOR TODO

    CHANGES:
      14 October 2018 - File created
*/

  function connectdb(){
    $pdo = new PDO('mysql:host=localhost;dbname=tasks;charset=utf8', 'tasks', 'abc123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Sets error mode to throw PDOException so that it can be caught
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Turns off prepared statement(?) emulate.
    return $pdo;
  }
?>
