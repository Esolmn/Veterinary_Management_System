<?php
    require_once 'Model.php';
    require_once 'Pet.php';
    class User extends Model {
        
        protected static $table = "users";

        public $id;
        public $name;
        public $email;
        public $password;
        public $role;
        public $status;
        public $created_at;
        public $updated_at;

        public function __construct(array $data = []) {
            foreach($data as $key => $value) {
                if(property_exists($this, $key)) { // tinitignan kung may nag eexist na property tulad sa ininput ng user
                    $this->$key = $value; //assings value to a key property
                }
            }
        }

        public static function all() {
            $result = parent::all();

            return $result ? array_map(fn($data) => new self($data), $result) : [];
        }

        public static function allPetOwners() {
            $result = parent::allPetOwners();

            return $result ? array_map(fn($data) => new self($data), $result) : [];
        }

        public static function allAdmins() {
            $result = parent::allAdmins();

            return $result ? array_map(fn($data) => new self($data), $result) : [];
        }


        public static function find($id) {
            $result = parent::find($id);

            return $result ? new self($result) : null;
        }

        public static function create(array $data) {
            $result = parent::create($data);

            return $result ? new self($result) : null;
        }

        public function update(array $data) {
            $result =  parent::updateById($this->id, $data);

            if($result) {
                foreach($data as $key => $value) {
                    if(property_exists($this, $key)) {
                        $this->$key = $value;
                    }
                } 
                return true;
            } else {  
                return false;
            }
        }

        public function save() {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'role' => $this->role,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];

            $this->update($data);
        }

        public function delete() {
            $result = parent::deleteById($this->id);

            if($result) {
                foreach($this as $key => $value) {
                    unset($this->$key);
                }
                return true;
            } else {
                return false;
            }
        }

        public static function authByEmail($check_email) {
            $userEmails = parent::where('email', '=', $check_email);

            if (!empty($userEmails)) {
                return new self($userEmails[0]);//una lng irereturn kasi naka fetch all ang where
            }

            return null;
        }

        public static function confirmPassword($password, $confirm_password) {
            if($password === $confirm_password) {
                return true;
            } else {
                return false;
            }
        }

        public function updateStatus($status) {
            $result = parent::updateById($this->id, ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
        
            if ($result) {
                $this->status = $status;
                return true;
            }
        
            return false;
        }

        public static function where($column, $operator, $value) {
            $result = parent::where($column, $operator, $value);

            return $result ? array_map(fn($data) => new self($data), $result) : [];

        }

        public static function countByRole($role) {
            try {
                $sql = "SELECT COUNT(*) as count FROM users WHERE role = :role";
                $stmt = self::$conn->prepare($sql);
                $stmt->bindParam(':role', $role);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                return $result['count'] ?? 0; 
            } catch (PDOException $e) {
                die("Failed to count users: " . $e->getMessage());
            }
        }

        public static function resetPassword($id) {
            try {
                // $new_password = 'wxyz1234';
                $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8); //kinukuha ung unang walong character na nakuha sa pag shuffle
        
                $sql = "UPDATE " . static::$table . " SET password = :password WHERE id = :id";
                $stmt = self::$conn->prepare($sql);
                $stmt->bindValue(':password', password_hash($new_password, PASSWORD_DEFAULT));
                $stmt->bindValue(':id', $id);
                $stmt->execute();
        
                return $new_password; // Rereturn ung bagong password na hindi nakahash para madisplay 
            } catch (PDOException $e) {
                die("Password Reset Failed: " . $e->getMessage());
            }
        }
    }

?>