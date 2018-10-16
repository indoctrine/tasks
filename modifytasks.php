<?php declare(strict_types=1);
      require_once('../strict-typing.php');
/*                             Task List
              Stores a list of tasks and allows completion.
                      An exercise in OO programming.
                      SEE addtasks.php FOR TODO

    CHANGES:
      14 October 2018 - File created
                      - Transferred modification and output to this separate file.
                      - Show/Hide Completed functionality added.
      16 October 2018 - Added mark complete and delete functionality.
*/
?>
<!doctype html>
<html>
  <head>
    <title>Modify Tasks</title>
    <style>
      table {
        border-collapse: collapse;
      }

      table, th, td {
        border: 1px solid black;
        text-align: center;
        padding: 2px;
      }
    </style>
  </head>
  <body>
    <a href="addtasks.php">Add More Tasks</a>
    <br><br>
    <a href="#" onClick="showhidecomp()" id="showhide">Hide Complete</a>
    <br><br>
    <?php
      class modifytasks{
        public $taskid = [];

        function __construct(){
          $this->pdo = new PDO('mysql:host=localhost;dbname=tasks;charset=utf8', 'tasks', 'abc123');
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Sets error mode to throw PDOException so that it can be caught
          $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Turns off prepared statement(?) emulate.
          }

        public function markdelete($task_id, $action){
          if($this->isvalidtaskid($task_id)){
            if($action == "delete"){
              $sql = $this->pdo->prepare('DELETE FROM tasks WHERE task_id = :taskid');
              $sql->execute(['taskid' => $task_id]);
            }
            else if($action == "mark"){
              $sql = $this->pdo->prepare('UPDATE tasks set completed = NOT completed WHERE task_id = :taskid');
              $sql->execute(['taskid' => $task_id]);
            }
            else{
              echo "Unable to perform that task";
            }
          }
        }

        private function isvalidtaskid($id){
          foreach($this->taskid as $key => $value){
            if($id == $value){
              return 1;
            }
          }
          return 0;
        }

        public function displaytasks(){
          $getself = htmlspecialchars($_SERVER['PHP_SELF']);
          $sql = 'SELECT task_id, due_date, priority, completed, description FROM tasks ORDER BY completed, due_date ASC';
          echo "<table id='tasks'>
            <tbody>
              <tr>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Completed</th>
                <th>Description</th>
              </tr>";
            ?>
          <form method='post' id='table' action='<?=$getself;?>'>
            <?php
          $i = 1;
          foreach($this->pdo->query($sql) as $row){
            $this->taskid[$i] = $row['task_id'];
            echo "<tr id='tbl_" . $this->taskid[$i] . "'><td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['priority'] . "</td>";
            echo "<td id='comp_" . $this->taskid[$i] . "'>" . $this->iscomplete($row['completed']) . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><button type='submit' id='delete' name='delete' form='table' value='" . $this->taskid[$i] . "' formaction='" . $getself . "'>X</button></td>";
            echo "<td><button type='submit' id='markcomplete' name='mark' form='table' value='" . $this->taskid[$i] . "' formaction='" . $getself . "'>✓</button></td>";
            $i++;
          }
          echo "</tbody></table></form>";
        }

        private function iscomplete($item){
          return $item == 0 ? "No" : "Yes";
        }
      }

      $task = new modifytasks;
      $task->displaytasks();
      foreach(array("mark", "delete") as $index){
        if(!isset($_POST[$index])){
          continue;
        }
        switch($index){
          case "mark":
            $task->markdelete($_POST[$index], $index);
            break;
          case "delete":
            $task->markdelete($_POST[$index], $index);
            break;
          default:
            break;
        }
        echo "<meta http-equiv='refresh' content='0'>";
      }
    ?>
    <script type="text/javascript">
      var flag = true;
      var tasks = <?= json_encode($task->taskid) ?>;
      var numtasks = Object.keys(tasks).length;
      var i;

      function showhidecomp(){
        for(i = 1; i <= numtasks; i++){
          if(flag == true){
            if(document.getElementById('comp_' + tasks[i]).innerHTML == "Yes"){
              document.getElementById('tbl_' + tasks[i]).style.display = "none";
              document.getElementById('showhide').innerHTML = "Show Complete";
            }
          }
          else{
            if(document.getElementById('comp_' + tasks[i]).innerHTML == "Yes"){
              document.getElementById('tbl_' + tasks[i]).style.display = "table-row";
              document.getElementById('showhide').innerHTML = "Hide Complete";
            }
          }
        }
        flag = !flag;
      }
    </script>
  </body>
</html>
