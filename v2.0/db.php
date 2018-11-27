<?php declare(strict_types=1);
      require_once('../../strict-typing.php');
/*                Database Class
        For interfacing with the database.

        CHANGES:
          19 October 2018 - File created.
                          - Added connect and disconnect functions.
          20 October 2018 - Added query, execute and prepare functions for
                            abstracting PDO
          3 November 2018 - Added ability to pass values to a prepared statement.
          27 November 2018 - Added support for different fetch modes on query().
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

        public function query($querystring, $fetchmode = "assoc", $optclass = null){
          /*
          $querystring (string) = SQL query,
          $fetchmode (string) = Human readable PDO fetch mode
          $optclass (string) = Optional class to output to when using PDO::FETCH_CLASS
          */

          switch($fetchmode){
            case "class":
              $fetchmode = PDO::FETCH_CLASS;
              break;
            case "assoc":
              $fetchmode = PDO::FETCH_ASSOC;
              break;
            case "numeric":
              $fetchmode = PDO::FETCH_NUM;
              break;
            default:
              $fetchmode = PDO::FETCH_ASSOC;
          }

          return $fetchmode == PDO::FETCH_CLASS ? $this->pdo->query($querystring)->fetchAll($fetchmode, $optclass) : $this->pdo->query($querystring)->fetchAll($fetchmode);
        }

        public function prepare($pstmt){
          $this->execstmt = $this->pdo->prepare($pstmt);
        }

        public function execute($params = null){ //Where $params is an array of values.
          //Can be daisychained to fetchAll() or other methods as required
          $results = $this->execstmt->execute($params);
          return $this->execstmt;
        }
      }
?>
