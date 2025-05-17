<?php
    require_once 'Model.php';
    require_once 'Treatment.php';
    require_once 'Pet.php';
    
    class Product extends Model {
        
        protected static $table = "treatment_product";

        public $id;
        public $treatment_id;
        public $product_name;
        public $cost_price;
        public $retail_price;
        public $quantity;
        public $cost;
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
               'treatment_id' => $this->treatment_id,
                'product_name' => $this->product_name,
                'cost_price' => $this->cost_price,
                'retail_price' => $this->retail_price,
                'quantity' => $this->quantity,
                'cost' => $this->cost,
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

    // public function treatments() {
    //     $treatments = Treatment::where('id', '=', $this->treatment_id);
    //     return $treatments ?? null;    
    // }
}

?>