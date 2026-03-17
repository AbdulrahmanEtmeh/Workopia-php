<?php

class Database
{
  public $connection;

  /**
   * Constructor for Database class
   * 
   * @param array $config
   */
  public function __construct($config)
  {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ];

    try {
      $this->connection = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $exception) {
      throw new Exception("Database connection failed: " . $exception->getMessage());
    }
  }

  /**
   * Query the database
   * 
   * @param string $query
   * @return PDOStatement
   * @throws Exception
   */
  public function query($query, $params = [])
  {
    try {
      $statement = $this->connection->prepare($query);

      // Bind named parameters if provided
      foreach ($params as $param => $value) {
        $statement->bindValue(':' . $param, $value);
      }
      $statement->execute();
      return $statement;
    } catch (PDOException $exception) {
      throw new Exception("Database query failed: " . $exception->getMessage());
    }
  }
}
