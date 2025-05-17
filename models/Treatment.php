<?php
    require_once 'Model.php';
    require_once 'Pet.php';
    require_once 'Product.php';
    
    class Treatment extends Model {
        
        protected static $table = "treatments";

        public $id;
        public $pet_id;
        public $treatment_name;
        public $diagnosis;
        public $description;
        public $date;
        public $doctor_fee;
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
                'pet_id' => $this->pet_id,
                'treatment_name' => $this->treatment_name,
                'diagnosis' => $this->diagnosis,
                'description' => $this->description,
                'date' => $this->date,
                'doctor_fee' => $this->doctor_fee,
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

        public function products() {
            $products = Product::where('treatment_id', '=', $this->id);
            return $products ?? null;
        }   
}

?>