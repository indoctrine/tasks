<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      /*
                                Task List
                  Stores a list of tasks and allows completion.
                          An exercise in OO programming.

        CHANGES:
          20 October 2018     - File created.
          25-27 November 2018 - Wrote method to push all data into objects.
                              - Learnt about PDO::FETCH_CLASS
          28 November 2018    - Array is now indexed by task_id
          6 December 2018     - renderlist() method outputs entire list of tasks
          8 December 2018     - Changed renderlist() to two render() methods so
                                the task object will render itself.

        TO DO:
          ***Basic Functionality***
          - Insert records incl. sanitising
          - Delete records
          - Mark completion of tasks
          - Output entire task list ✔

          ***Intermediate Functions***
          - Show/Hide Completed
          - Update/edit records
          - Sort by date/priority
          - Search by date/priority

          ***Advanced Functions***
          - Multiple users
          - Extend due date
          - Span across multiple pages (eg. 5 records to a page) possibly using AJAX.
      */

echo "<pre>";

      class DatabaseConnectedClass{
        public $conn;

        function __construct(){
          require_once('db.php');
          $this->conn = new Database('localhost', 'tasks', 'tasks', 'abc123');
        }
      }

      class TaskList extends DatabaseConnectedClass{
        private $name;
        public $tasks = [];

        function __construct(){
          parent::__construct();
          $this->PopulateTasks();
        }

        public function PopulateTasks(){
          $i = 0;
          $alltasks = "SELECT * from tasks";

          foreach($this->conn->query($alltasks, "class", "Task") as $row){
            $i = $row->task_id;
            $this->tasks[$i] = $row;
          }
        }

        public function Render(){
          echo '<form method="post" id="table" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
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
          echo '</form>';
        }
      }

      class Task extends DatabaseConnectedClass{

        function __construct(){
          //$i = 0;
          //print_r($this->conn->query("SELECT * FROM tasks"));
          //$this->conn->prepare('INSERT INTO tasks (due_date, priority, description) VALUES (:duedate, :priority, :description)');
          //$this->conn->execute(['duedate' => '2018-11-03', 'priority' => 5, 'description' => 'Hello World']);
        }

        public function Render(){
          echo '<tr>';
          //$this->completed = $this->tasks->completed == 1 ? 'Yes' : 'No';
          echo '<td>' . $this->task_id . '</td>';
          echo '<td>' . $this->due_date . '</td>';
          echo '<td>' . $this->priority . '</td>';
          echo $this->completed == 1 ? '<td> Yes </td>' : '<td> No </td>';
          echo '<td>' . $this->description . '</td>';
          echo '<td><button type="submit" name="mark" form="table" value="' . $this->task_id . '">';
          echo $this->completed == 1 ? '✕' : '✓';
          echo '</button></td>'; //formaction="{$getself}"
          echo '</tr>';
        }

        private function MarkComplete(){
          //To do once figure out async. $_POST['mark'] will output the $task_id which is attached to the form element. Need to check this against the object on the row it was clicked on.
          if(isset($_POST['mark']) && $this->task_id == $_POST['mark']){
            echo "Hello World!";
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

echo "</pre>";
?>
