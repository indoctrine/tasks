<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('tasks.php');

  if(isset($_POST['submit']) && isset($tasklist[0]->tasks[$_POST['mark']])){
    $tasklist[0]->tasks[$_POST['mark']]->MarkComplete();
  }
  else{
    echo "Sorry, something went wrong, please try again.";
  }
?>
