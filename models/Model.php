<?php

    class Model {
        protected static $conn;
        protected static $table;

        public static function setConnection($conn) {
            self::$conn = $conn;
        }

        protected static function all() {
            try {
                $sql = "SELECT * FROM " . static::$table;

                $result = self::$conn->query($sql);

                $data = $result->fetchAll();

                return count($data) > 0 ? $data : null;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        protected static function find($id) {
            try {
                $sql = "SELECT * FROM " . static::$table . " WHERE id = :id";

                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(":id", $id);

                $stmt->execute();
                $data = $stmt->fetch();

                // return count($data) > 0 ? $data : null;

                return $data ?? null;

            } catch (PDOException $e) {
                die("Fetch failed: " . $e->getMessage());
            }
        }

        protected static function create(array $data) {
            try {
                $associatives = implode(", ", array_keys($data)); //output will be like name, age, gender because it joins the array_keys
                $values = implode(", ", array_map(fn($key) => ":$key", array_keys($data)));  //joins the array element and put a ? placeholder
    
                $sql = "INSERT INTO " . static::$table . " ($associatives) VALUES ($values)";

                $stmt = self::$conn->prepare($sql);

                foreach($data as $key => $value) {
                    $stmt->bindValue(":$key", $value); //binds the value to the placeholder
                }

                $stmt->execute();

                $id = self::$conn->lastInsertId();

                return self::find($id); //returns the last inserted id

            } 
            catch (PDOException $e) {
                die("Creation failed: " . $e->getMessage());
            }
        }

        protected static function updateById($id, array $data) {

            try {
                $set = implode(",", array_map(fn($key) => "$key = :$key", array_keys($data)));

                $sql = "UPDATE " . static::$table . " SET $set WHERE id = :id";

                $stmt = self::$conn->prepare($sql);

                foreach($data as $key => $value) {
                    $stmt->bindValue(":$key", $value); //binds the value to the placeholder
                }
                $stmt->bindValue(":id", $id); //binds the value to the placeholder

                $stmt->execute();

                return self::find($id);
            } 
            catch (PDOException $e) {
                die("Update failed: " . $e->getMessage());
            }
        }

        protected static function deleteById($id) {
            try {
                $sql = "DELETE FROM " . static::$table . " WHERE id = ?";

                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(1, $id); 

                return $stmt->execute(); 
            }
            catch (PDOException $e) {
                die("Delete failed: " . $e->getMessage());
            }

        }

        protected static function findName($name){
            try {
                $sql = "SELECT * FROM " . static::$table . " WHERE name = :name";
                $stmt = self::$conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->execute();
                $row = $stmt->fetch();
                return $row ?? null;
            } catch (PDOException $e) {
                die("Query Failed: " . $e->getMessage());
            }
        }

        public static function allPetOwners(){
        try{

            $sql = "SELECT * FROM " . static::$table . " WHERE role = 'pet_owner'";
    
            $result = self::$conn->query($sql);
    
            $rows = $result->fetchAll();
    
            return count($rows) > 0 ? $rows : null;
            
           } catch(PDOException $e){
                die("Fetch failed: " . $e->getMessage());
            }
    
        }

        public static function allAdmins(){
        try{

            $sql = "SELECT * FROM " . static::$table . " WHERE role = 'admin'";
    
            $result = self::$conn->query($sql);
    
            $rows = $result->fetchAll();
    
            return count($rows) > 0 ? $rows : null;
            
           } catch(PDOException $e){
                die("Fetch failed: " . $e->getMessage());
            }
    
        }

        protected static function where($column, $operator, $value) {
            try {
                $sql = "SELECT * FROM " . static::$table . " WHERE $column $operator :value";
    
                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(':value', $value);
                $stmt->execute();
    
                return $stmt->fetchAll();
    
            } catch (PDOException $e) {
                die("Fetch failed: " . $e->getMessage());
            }
        }
        protected static function doubleWhere($column1, $operator1, $value1, $column2, $operator2, $value2) {
            try {
                $sql = "SELECT * FROM " . static::$table . " WHERE $column1 $operator1 :value1 AND $column2 $operator2 :value2";
    
                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(':value1', $value1);
                $stmt->bindValue(':value2', $value2);
                $stmt->execute();
    
                return $stmt->fetchAll();
    
            } catch (PDOException $e) {
                die("Fetch failed: " . $e->getMessage());
            }
        }

        protected static function whereOption($column, $operator, $value, $options) {
            try {
                $sql = "SELECT * FROM " . static::$table . " WHERE $column $operator :value";
    
                if (isset($options['order'])) {
                    $sql .= " ORDER BY " . $options['order'];
                }
    
                if (isset($options['limit'])) {
                    $sql .= " LIMIT " . $options['limit'];
                }
    
                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(':value', $value);
                $stmt->execute();
    
                return $stmt->fetchAll();
    
            } catch (PDOException $e) {
                die("Fetch failed: " . $e->getMessage());
            }
        }

    }

?>