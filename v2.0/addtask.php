<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
      require_once('tasks.php');

  if(isset($_POST['submittask'])){
    $tasklist[0]->ValidatePost();
    exit;
  }

  else{
    echo "Sorry, something went wrong, please try again.";
    exit;
  }
?>
