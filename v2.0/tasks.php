<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      /*
                                Task List
                  Stores a list of tasks and allows completion.
                          An exercise in OO programming.

        CHANGES:
          20 October 2018 - File created.

        TO DO:
          ***Basic Functionality***
          - Insert records incl. sanitising
          - Delete records
          - Mark completion of tasks
          - Output entire task list

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
          $inittask = new Task;
          $inittask->populatetasks();
          //$inittask = 0;
        }
      }

      class Task extends DatabaseConnectedClass{
        private $task_id;
        private $priority;
        private $due_date;
        private $description;
        private $completed;
        private $alltasks;

        function __construct(){
          $i = 0;
          parent::__construct();
          //print_r($this->conn->query("SELECT * FROM tasks"));
          //$this->conn->prepare('INSERT INTO tasks (due_date, priority, description) VALUES (:duedate, :priority, :description)');
          //$this->conn->execute(['duedate' => '2018-11-03', 'priority' => 5, 'description' => 'Hello World']);
        }

        public function populatetasks(){
          $i = 0;
          $alltasks = "SELECT * from tasks";

          foreach($this->conn->query($alltasks) as $row){
            //print_r($key[$value]);
            $this->tasks[$i] = new Task();
            $tasks[$i]->task_id = $row['task_id'];
            //$this->$task_id = $key[$value];
            echo "Loop $i";
            print_r($this->task_id[$i]);
            $i++;
          }
        }

        private function validatepost(){ //NOT IN USE YET
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

      $foo = new TaskList;
echo "</pre>";
?>
