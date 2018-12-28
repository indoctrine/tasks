<!doctype html>
<html>
  <head>
    <title>Task List</title>
    <style>
      table{
        border-collapse: collapse;
      }
      table,th,td{
        border: solid 1px;
        padding: 4px;
      }
    </style>
  </head>
  <body>
    <?php require_once('tasks.php');
    $tasklist[0]->Render(); ?>
    <p id = "output"></p>
    <script src="../../jquery-3.3.1.js"></script>
    <script src="tasks.js"></script>
  </body>
</html>
