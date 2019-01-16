<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('db.php');

      $_db = new Database('localhost', 'tasks', 'tasks', 'abc123');

      class TaskList{

        public $tasks = [];

        function __construct(){
          $this->PopulateTasks();
        }

        public function PopulateTasks(){
          global $_db;
          $i = 0;
          $alltasks = "SELECT * FROM tasks ORDER BY due_date DESC";

          foreach($_db->query($alltasks, "class", "Task") as $row){
            $i = $row->task_id;
            $this->tasks[$i] = $row;
          }
        }

        public function Render(){
          if($this->tasks){ //Don't render anything if there aren't any tasks.
            echo '<table id="taskoutput">';
            echo '<thead>
              <tr>
                <th><span>Task ID</span></th>
                <th><span>Due Date</span></th>
                <th><span>Priority</span></th>
                <th><span>Description</span></th>
                <th><span>Completed</span></th>
                <td>Mark?</td>
                <td>Delete?</td>
              </tr>
            </thead>
            <tbody>';

            foreach($this->tasks as $task){
              $task->Render();
            }
            echo '</tbody>
              </table>';
          }
          else{
            exit;
          }
        }

        public function ValidatePost(){
          $this->priority = GetPost('priority', INT, 5);
          $this->ValidatePriority($this->priority);

          $this->date = $_POST['due_date'];
          $this->ValidateDate($this->date);

          $this->description = GetPost('description', STRING, null);
        }

        private function ValidatePriority($pri){
          if($pri < 1 || $pri > 5 || !is_numeric($pri)){ //Valid range for priority is 1-5.
            $this->priority = 5; //5 is least urgent therefore default value.
          }
        }

        private function ValidateDate($date){
          $now = new DateTime();
          $now->setTime(23, 59, 59); //Set current time to avoid time-related calc errors
          $default_date = clone $now;
          $default_date->modify('+1 week'); //Set a sane default

          if(DateTime::createFromFormat('Y-m-d', $date) !== false){ //Attempt to force input to preferred format
            $this->due_date = new DateTime($date);
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
        }

        public function AddTask(){
          echo "Hello!~";
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
          echo '<td class="desc">' . $this->description . '</td>';
          echo $this->completed == 1 ? '<td class="comp_col"> Yes </td>' : '<td class="comp_col"> No </td>';
          echo '<td><button class="mark" value="' . $this->task_id . '">';
          echo $this->completed == 1 ? '✕' : '✓';
          echo '</button></td>';
          echo '<td><button class="delete" value="' . $this->task_id . '">X</button></td>';
          echo '</tr>';
        }

        public function MarkComplete(){
          global $_db;

          if(isset($_POST['mark']) && $_POST['mark'] == $this->task_id){
            try{
              $_db->prepare('UPDATE tasks SET completed = NOT completed WHERE task_id = :taskid');
              $_db->execute(['taskid' => $this->task_id]);
              $this->completed ^= true; //XOR bit flip.
              echo $this->completed;
            }
            catch(Exception $e){
              echo "Something went wrong, task " . $this->task_id . " could not be marked as completed - " . $e;
            }
          }
        }

        public function DeleteTask(){
          global $_db;

          if(isset($_POST['delete']) && $_POST['delete'] == $this->task_id){
            try{
              $_db->prepare('DELETE FROM tasks WHERE task_id = :taskid');
              $_db->execute(['taskid' => $this->task_id]);
              echo "Task " . $this->task_id . " successfully deleted.";
            }
            catch(Exception $e){
            echo "Task " . $this->task_id . " not deleted. Error: " . $e;
            }
          }
        }
      }

      $tasklist[0] = new TaskList;
?>
