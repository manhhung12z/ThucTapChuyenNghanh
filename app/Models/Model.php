<?php
require_once("core/connectdatabase.php");
class Model extends connectdatabase
{
    protected $dbconnect;
    protected $table;
    protected $data;
    protected $primarykey;
    protected $fillable = [];
    public function __construct()
    {
        $this->dbconnect = connectdatabase::connect();
    }
    public function findAll()
    {
        $sql = "select * from {$this->table}";
        $result = $this->dbconnect->query($sql);
        $this->data = $result->fetch_all(MYSQLI_ASSOC);
        return $this->data;
    }
    public function find($id)
    {
        $sql = "select * from {$this->table} where {$this->primarykey} = '{$id}'";
        $result = $this->dbconnect->query($sql);
        $this->data = $result->fetch_assoc();
        return $this->data;
    }
    public function anyfind($sql)
    {
        $result = $this->dbconnect->query($sql);
        $this->data = $result->fetch_all(MYSQLI_ASSOC);
        return $this->data;
    }
    public function execute($sql)
    {
        return $this->dbconnect->query($sql);
    }

    public function insert($data)
    {
        $data = array_filter($data, function ($key) {
            return in_array($key, $this->fillable);
        }, ARRAY_FILTER_USE_KEY);
        $values = array_map(function ($item) {
            return "'$item'";
        }, array_values($data));
        $columns = implode(',', array_keys($data));
        $values_key = implode(',', $values);
        $sql = "insert into {$this->table}($columns) values ($values_key)";
        return $this->dbconnect->query($sql);
    }
 

    public function update($data, $id)
    {
        if (count($data) > 0) {
            // lọc dữ liệu đổ vào bảng
            $data = array_filter($data, function ($key) {
                return in_array($key, $this->fillable);
            }, ARRAY_FILTER_USE_KEY);
            //Chuyển mảng cần update thành chuỗi hợp lệ lên sql
            $updateDataString = implode(',', array_map(function ($key, $value) {
                return "$key = '$value'";
            }, array_keys($data), array_values($data)));
            $sql = "UPDATE {$this->table} SET $updateDataString WHERE {$this->primarykey} = '$id'";
            return $result = $this->dbconnect->query($sql);

        }
        return false;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primarykey} = ?";
        if ($stmt = $this->dbconnect->prepare($sql)) {
            $stmt->bind_param("s", $id); // đổ dữ liệu vào "?" với kiểu string
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
        public function countDelete($table, $column, $value) // Kiểm tra ràng buộc trước khi xóa
    {
        $sql = "SELECT COUNT(*) as total FROM $table WHERE $column = ?";
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['total'];
    }
public function getAllNumericIds() // Lấy tất cả các ID chỉ chứa số từ bảng
    {
        $column = $this->primarykey;
        // Lấy tất cả các mã chỉ chứa số và sắp xếp từ nhỏ đến lớn
        $sql = "SELECT $column 
                FROM {$this->table}
                WHERE $column REGEXP '^[0-9]+$' 
                ORDER BY CAST($column AS UNSIGNED) ASC";
        $result = $this->dbconnect->query($sql);
        $ids = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ids[] = (int)$row[$column]; // Ép về kiểu số nguyên [1, 3, 4]
            }
        }
        return $ids;
    }


    public function generateNextId()
    {
        $existingIds = $this->getAllNumericIds(); //Trả về mảng ID cần kiểm tra
        $i = 1;
        while (true) {
            if (!in_array($i, $existingIds)) {
                return str_pad($i, 3, '0', STR_PAD_LEFT); 
            }
            $i++; 
        }
    }
   public function getPaginated($limit, $offset, $sql = null)
    {
        $sql = ($sql !== null) ? $sql : "SELECT * FROM {$this->table}";
        $sql = rtrim($sql, ';') . " LIMIT ?, ?";
        
        $stmt = $this->dbconnect->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close(); 
        return $data;
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->dbconnect->query($sql);
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

 


}
