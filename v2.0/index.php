<!doctype html>
<html>
  <head>
    <title>Task List</title>
    <link href="tasks.css" rel="stylesheet" type="text/css">
    <script src="../../jquery-3.3.1.js"></script>
    <script src="../../jQuery.date.js"></script>
    <script src="tasks.js"></script>
    <script src="../../jquery.tablesorter.min.js"></script>
  </head>
  <body>
    <div class="split left">
      <form id="addtask" method="post">
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
        <input type="submit" name="submittask">
        <input type="reset">
      </form>
    </div>
    <div class="split right">
      <?php require_once('tasks.php');
      $tasklist[0]->Render(); ?>
      <button class="addbutton" value="addtaskbutton">Add New Task</button>
      <p id = "output"></p>
    </div>
  </body>
</html>
