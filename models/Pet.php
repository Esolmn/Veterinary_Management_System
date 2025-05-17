<?php
    require_once 'Model.php';
    require_once 'User.php';
    class Pet extends Model {
        
        protected static $table = "pets";

        public $id;
        public $user_id;
        public $name;
        public $breed;
        public $specie;
        public $gender;
        public $birthdate;
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

        public static function find($id) {
            $result = parent::find($id);

            return $result ? new self($result) : null;
        }
        public static function findName($name){
            $result = parent::findName($name);
    
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
                'user_id' => $this->user_id,
                'name' => $this->name,
                'breed' => $this->breed,
                'specie' => $this->specie,
                'gender' => $this->gender,
                'birthdate' => $this->birthdate,
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

        public static function where($column, $operator, $value) {
            $result = parent::where($column, $operator, $value);

            return $result ? array_map(fn($data) => new self($data), $result) : [];
        }

        public static function countPet() {
            $sql = "SELECT COUNT(*) as count FROM pets";
            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result['count'] ?? 0;
        }
}

?>