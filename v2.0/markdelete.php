<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('tasks.php');

  if(isset($_POST['submit'])){
    $tasklist[0]->tasks[$_POST['mark']]->MarkComplete();
  }
?>
