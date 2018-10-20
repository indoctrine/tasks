<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
/*                Database Class
        For interfacing with the database.

        CHANGES:
          19 October 2018 - File created.
                          - Added connect and disconnect functions.
          20 October 2018 - Added query, execute and prepare functions for
                            abstracting PDO
*/
      class Database{
        private $con = false;
        public $pdo;
        public $execstmt;

        function __construct($hostname, $dbname, $dbuser, $dbpwd){
          $this->connectdb($hostname, $dbname, $dbuser, $dbpwd);
        }

        function __destruct(){
          $this->disconnectdb();
        }

        public function connectdb($hostname, $dbname, $dbuser, $dbpwd){
          try{
            if($this->con == false){
              $this->pdo = new PDO("mysql:host={$hostname};dbname={$dbname};charset=utf8", "{$dbuser}", "{$dbpwd}");
              $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
              $this->con = true;
            }
            else{
              echo "Connection already established!";
            }
          }
          catch(PDOException $exception){
            echo "An error has occurred: " . $exception->getMessage();
          }
        }

        public function disconnectdb(){
          $this->pdo = null;
          $this->con = false;
        }

        public function querydb($querystring){
          return $this->pdo->query($querystring)->fetchAll(PDO::FETCH_ASSOC);
        }

        public function preparedb($pstmt){
          $this->execstmt = $this->pdo->prepare($pstmt);
        }

        public function executedb(){
          $this->execstmt->execute();
          return $results = $this->execstmt->fetchAll();
        }
      }
?>
