<?php
    require_once 'Model.php';

    class Appointment extends Model{
        protected static $table = 'appointment_requests';

        public $id;
        public $user_id;
        public $pet_id;
        public $aptdate_id;
        public $status;
        public $services_needed;
        public $declined_reason;
        public $created_at;
        public $updated_at;
        public $declined_by;

        public function __construct(array $data = []){
            foreach($data as $key => $value){
                if(property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }

        public static function all(){
            $result = parent::all();
    
            return $result
                ? array_map(fn ($data) => new self($data), $result)
                : [];
        }

        public static function find($id){
            $result = parent::find($id);

            return $result
                ? new self($result)
                : null;
        }

        public static function create(array $data){
            $result = parent::create($data);

            return $result
                ? new self($result)
                : null;
        }

        public function update(array $data){
            $result = parent::updateById($this->id, $data);

            if($result){
                foreach($data as $key => $value){
                    if(property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
                return true;
            }
            else{
                return false;
            }
        }

        public function save(){
            $data = [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'pet_id' => $this->pet_id,
                'aptdate_id' => $this->aptdate_id,
                'status' => $this->status,
                'services_needed' => $this->services_needed,
                'declined_reason' => $this->declined_reason,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];

            return $this->update($data);
        }

        public function delete(){
            $result = parent::deleteById($this->id);

            if($result){
                foreach($this as $key => $value){
                    unset($this->$key);
                }
                return true;
            }
            else{
                return false;
            }
        }

        public static function hasAppointments($aptdate_id) {
            try {
                $query = "SELECT COUNT(*) as count FROM " . static::$table . " WHERE aptdate_id = :aptdate_id AND (status = 'pending' OR status = 'accepted')";
                $stmt = static::$conn->prepare($query);
                $stmt->execute(['aptdate_id' => $aptdate_id]);
                $result = $stmt->fetch();

                return $result && $result['count'] > 0;
            } catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }

        public static function countByStatus($status) {
            try {
                $query = "SELECT COUNT(*) as count FROM " . static::$table . " WHERE status = :status";
                $stmt = static::$conn->prepare($query);
                $stmt->execute(['status' => $status]);
                $result = $stmt->fetch();

                return $result ? (int)$result['count'] : 0;
            } catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }

        public static function where($column, $operator, $value) {
            $result = parent::where($column, $operator, $value);

            return $result ? array_map(fn($data) => new self($data), $result) : [];

        }
    }
?>