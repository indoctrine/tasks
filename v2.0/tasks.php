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
          $tasks[0] = new Task;
        }
      }

      class Task extends DatabaseConnectedClass{
        private $task_id;
        private $priority;
        private $due_date;
        private $description;
        private $completed;

        function __construct(){
          parent::__construct();
          $this->conn->preparedb('SELECT * FROM tasks');
          print_r($this->conn->executedb());
        }

        public function validatepost(){
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
