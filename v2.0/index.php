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
    <?php require_once('tasks.php');
    $tasklist[0]->Render(); ?>
    <p id = "output"></p>

  </body>
</html>
