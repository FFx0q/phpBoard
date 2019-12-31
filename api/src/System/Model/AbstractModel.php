<?php
    namespace System\Model;

    use PDO;
    use PDOException;
    use System\Database\DatabaseFactory;

    abstract class AbstractModel
    {
        protected $table;
        protected $connection;
                
        public function __construct()
        {
            $this->connection = DatabaseFactory::getFactory();

            $parts = explode('\\', static::class);
            $this->table = strtolower(end($parts));   
        }

        public function getDatabase()
        {
            return $this->connection->getConnection();
        }

        public function find($id)
        {
            try {
                $sql = "SELECT * FROM {$this->table} WHERE id = :id";
                $stmt = $this->getDatabase()->prepare($sql);
                $stmt->execute(['id' => $id]);

                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        public function findAll()
        {
            try {
                $sql = "SELECT * FROM {$this->table}";
                $stmt = $this->getDatabase()->query($sql);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
