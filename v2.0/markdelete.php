<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('tasks.php');

  if(isset($_POST['submit']) && isset($tasklist[0]->tasks[$_POST['mark']])){
    $tasklist[0]->tasks[$_POST['mark']]->MarkComplete();
    exit;
  }

  if(isset($_POST['submit']) && isset($tasklist[0]->tasks[$_POST['delete']])){
    $tasklist[0]->tasks[$_POST['delete']]->DeleteTask();
    exit;
  }
  else{
    echo "Sorry, something went wrong, please try again.";
    exit;
  }
?>
