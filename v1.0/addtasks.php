<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
  /*
                            Task List
              Stores a list of tasks and allows completion.
                      An exercise in OO programming.

    CHANGES:
      13 October 2018 - File created. Current State: Form data stored in objects.
                      - Date and priority validation.
      14 October 2018 - Added addtask() method. Interfaces correctly with database.
                      - Separated the getpost component out of the constructor as it
                        caused issues with intialising the object and being able todo
                        output the contents of the database without submitting the form.
                      - Added iscomplete() and markcomplete() methods.
                      - Separated display and modify to its own file.

    TO DO:
      ***Basic Functionality***
      - Insert records incl. sanitising ✓
      - Delete records ✓
      - Mark completion of tasks ✓
      - Output entire task list ✓

      ***Intermediate Functions***
      - Show/Hide Completed ✓
      - Update/edit records
      - Sort by date/priority
      - Search by date/priority

      ***Advanced Functions***
      - Multiple users
      - Extend due date
      - Span across multiple pages (eg. 5 records to a page) possibly using AJAX.
  */
?>

<!doctype html>
<html>
  <head>
    <title>Add Tasks</title>
  </head>
  <body>
    <form method="post" id="form" action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>">
      <label>Priority</label>
      <select name = "priority">
        <option value = "5">5 - Least Urgent</option>
        <option value = "4">4</option>
        <option value = "3">3</option>
        <option value = "2">2</option>
        <option value = "1">1 - Most Urgent</option>
      </select>
      <br>
      <label>Due Date: </label><input type = "date" name = "duedate">
      <br>
      <textarea name = "description" rows = "10" cols = "50"></textarea>
      <br>
      <input type="submit" name="addtask">
      <input type="reset" name="reset">
    </form>
    <?php
      class addtask{
        private $pdo;
        private $priority;
        private $duedate;
        private $description;

        function __construct(){
          $this->pdo = new PDO('mysql:host=localhost;dbname=tasks;charset=utf8', 'tasks', 'abc123');
          $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Sets error mode to throw PDOException so that it can be caught
          $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //Turns off prepared statement(?) emulate.
        }

        public function getformdata(){
          $this->priority = GetPost('priority', INT, 5);

          if($this->priority < 1 || $this->priority > 5){ //Valid range for priority is 1-5.
            $this->priority = 5; //5 is least urgent therefore default value.
          }

          $now = new DateTime();
          $now->setTime(23, 59, 59); //Set current time to avoid time-related calc errors

          $defaultdate = clone $now;
          $defaultdate->modify('+1 week'); //Set a sane default

          if(isset($_POST['duedate'])){
            $this->duedate = new DateTime($_POST['duedate']);
          }
          else{
            $this->duedate = clone $defaultdate;
          }

          $this->duedate->setTime(23, 59, 59);
          $this->duedate->format('Y-m-d');

          if($this->duedate < $now){
            //Checks if due date is before today and set to sensible default if not.
            $this->duedate = clone $defaultdate;
          }

          $this->description = GetPost('description', STRING, null);
        }

        public function addtask(){
          try{
            $stmt = $this->pdo->prepare('INSERT INTO tasks (due_date, priority, description) VALUES (:duedate, :priority, :description)');
            $stmt->execute(['duedate' => $this->duedate->format('Y-m-d'), 'priority' => $this->priority, 'description' => $this->description]);
            echo "<br>Task Successfully Recorded";
          }
          catch(PDOException $e){
              echo "<br>Something Went Wrong";
            }
        }
      }
      $task = new addtask;

        if(isset($_POST['addtask'])){
          $task->getformdata();
          $task->addtask();
        }
    ?>
    <br>
    <a href="modifytasks.php">View/Edit Tasks</a>
  </body>
</html>
