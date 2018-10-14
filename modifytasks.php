<?php declare(strict_types=1);
      require_once('../strict-typing.php');
/*                             Task List
              Stores a list of tasks and allows completion.
                      An exercise in OO programming.
                      SEE addtasks.php FOR TODO

    CHANGES:
      14 October 2018 - Transferred modification and output to separate file.
                      - Show/Hide Completed functionality added.
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
    <a href="#" onClick="completed()" id="showhide">Hide Complete</a>
    <br><br>
    <?php
      class modifytasks{
        public $taskid = [];

        function __construct(){
          $this->pdo = new PDO('mysql:host=localhost;dbname=tasks;charset=utf8', 'tasks', 'abc123');
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Sets error mode to throw PDOException so that it can be caught
          $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Turns off prepared statement(?) emulate.
          }

        private function iscomplete($item){
          return $item == 0 ? "No" : "Yes";
        }

        public function markcomplete($task_id){
          $sql = $this->pdo->prepare('UPDATE tasks set completed = 1 where task_id = :taskid');
          $sql->execute(['taskid' => $task_id]);
        }

        public function displayincompletetasks(){
          $sql = 'SELECT task_id, due_date, priority, completed, description FROM tasks'; //WHERE completed = 0
          echo "<table id='tasks'>
            <tbody>
              <tr>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Completed</th>
                <th>Description</th>
              </tr>";
            ?>
          <form method='post' id='table' action='<?=htmlspecialchars($_SERVER['PHP_SELF']);?>'>
            <?php
          $i = 1;
          foreach($this->pdo->query($sql) as $row){
            $this->taskid[$i] = $row['task_id'];
            echo "<tr id='tbl_" . $this->taskid[$i] . "'><td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['priority'] . "</td>";
            echo "<td id='comp_" . $this->taskid[$i] . "'>" . $this->iscomplete($row['completed']) . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td><input type='button' id='delete' name='del_" . $this->taskid[$i] . "' value='X' onClick='this.form.submit()'></td>";
            echo "<td><input type='button' id='markcomplete' name='mark_" . $this->taskid[$i] . "' value='✓' onClick='this.form.submit()'></td>";
            $i++;
          }
          echo "</tbody></table></form>";
        }
      }

      $task = new modifytasks;
      $task->displayincompletetasks();
    ?>
    <script type="text/javascript">
        var counter = 1;
        var rows = "<?= count($task->taskid) ?>";
        var tasks = <?= json_encode($task->taskid) ?>;
        var numtasks = Object.keys(tasks).length;
        var i;

        function completed(){
          for(i = 1; i <= numtasks; i++){
            if(counter % 2 != 0){
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
          counter++;
        }
    </script>
  </body>
</html>
