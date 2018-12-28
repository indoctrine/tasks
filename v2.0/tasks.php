<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('db.php');
      
      $_db = new Database('localhost', 'tasks', 'tasks', 'abc123');

      class TaskList{
        private $name;
        public $tasks = [];

        function __construct(){
          $this->PopulateTasks();
        }

        public function PopulateTasks(){
          global $_db;
          $i = 0;
          $alltasks = "SELECT * from tasks";

          foreach($_db->query($alltasks, "class", "Task") as $row){
            $i = $row->task_id;
            $this->tasks[$i] = $row;
          }
        }

        public function Render(){
          echo '<table>';
          echo '<tr>
            <th>Task ID</th>
            <th>Due Date</th>
            <th>Priority</th>
            <th>Completed</th>
            <th>Description</th>
          </tr>';

          foreach($this->tasks as $task){
            $task->Render();
          }
          echo '</table>';
        }
      }

      class Task{

        function __construct(){
        }

        public function Render(){
          echo '<tr id="row_' . $this->task_id . '">';
          echo '<td>' . $this->task_id . '</td>';
          echo '<td class="due_col">' . $this->due_date . '</td>';
          echo '<td>' . $this->priority . '</td>';
          echo $this->completed == 1 ? '<td class="comp_col"> Yes </td>' : '<td class="comp_col"> No </td>';
          echo '<td>' . $this->description . '</td>';
          echo '<td><button class="mark" value="' . $this->task_id . '">';
          echo $this->completed == 1 ? '✕' : '✓';
          echo '</button></td>';
          echo '</tr>';
        }

        public function MarkComplete(){
          global $_db;

          if(isset($_POST['mark']) && $_POST['mark'] == $this->task_id){
            $_db->prepare('UPDATE tasks SET completed = NOT completed WHERE task_id = :taskid');
            $_db->execute(['taskid' => $this->task_id]);
            $this->completed ^= true; //XOR bit flip.
            echo $this->completed;
          }
        }

        private function ValidatePost(){ //NOT IN USE YET
          $this->priority = GetPost('priority', INT, 5);

          if($this->priority < 1 || $this->priority > 5){ //Valid range for priority is 1-5.
            $this->priority = 5; //5 is least urgent therefore default value.
          }

          $now = new DateTime();
          $now->setTime(23, 59, 59); //Set current time to avoid time-related calc errors

          $default_date = clone $now;
          $default_date->modify('+1 week'); //Set a sane default

          if(isset($_POST['duedate'])){
            $this->due_date = new DateTime($_POST['duedate']);
          }
          else{
            $this->due_date = clone $default_date;
          }

          $this->due_date->setTime(23, 59, 59);
          $this->due_date->format('Y-m-d');

          if($this->due_date < $now){
            //Checks if due date is before today and set to sensible default if so.
            $this->due_date = clone $default_date;
          }

          $this->description = GetPost('description', STRING, null);
        }
      }

      $tasklist[0] = new TaskList;
?>
