<?php
    require_once 'Model.php';
    class AvailableDate extends Model {
        
        protected static $table = "available_dates";

        public $id;
        public $date;
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
                'id' => $this->id,
                'date' => $this->date,
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

        public static function doubleWhere($column1, $operator1, $value1, $column2, $operator2, $value2) {
            $result = parent::doubleWhere($column1, $operator1, $value1, $column2, $operator2, $value2);

            return $result ? array_map(fn($data) => new self($data), $result) : [];
        }

        public static function hasAppointments($id) {
            $sql = "SELECT COUNT(*) FROM appointment_requests WHERE aptdate_id = :id";
            
            $stmt = self::$conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            
            return $stmt->fetchColumn() > 0;
        
        }

        public function approved_appointments(){
            $appointments = Appointment::doubleWhere('aptdate_id', '=', $this->id, 'status', '=', 'Accepted');
            return $appointments ? array_map(fn($data) => new Appointment($data), $appointments) : [];
        }
}

?>