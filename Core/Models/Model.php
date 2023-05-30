<?php
class Model{
    protected $pdo;
    public function __construct()
    {
        $config     =   include Core_Dir.'config.php';
        $dsn = "mysql:host=".$config['DB_Host'].";dbname=".$config['DB_Name'];
        $username = $config["DB_User"];
        $password = $config["DB_Password"];
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    public function execute($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            // Обработка ошибки выполнения запроса
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
    public function fetchAll($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            var_dump($params);
            $stmt->execute($params);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            // Обработка ошибки выполнения запроса
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }    
    public function fetch($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Обработка ошибки выполнения запроса
            die("Ошибка выполнения запроса: " . $e->getMessage());
        }
    }
    public function selectAll($table,$order = 'id',$order_type = 'DESC') {
        $query = "SELECT * FROM $table ORDER BY `$table`.`$order` $order_type";
        return $this->fetchAll($query);
    }

    public function selectById($table, $id) {
        $query = "SELECT * FROM $table WHERE id = ?";
        return $this->fetch($query, [$id]);
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        return $this->execute($query, array_values($data));
    }

    public function update($table, $id, $data) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $set = implode(', ', $set);

        $query = "UPDATE $table SET $set WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;

        return $this->execute($query, $values);
    }

    public function delete($table, $id) {
        $query = "DELETE FROM $table WHERE id = ?";
        return $this->execute($query, [$id]);
    }
}

/*
// Создание экземпляра модели с передачей объекта базы данных


// SELECT: Получение всех записей из таблицы
$allRecords = $model->selectAll('your_table');
var_dump($allRecords);

// SELECT: Получение записи по идентификатору
$record = $model->selectById('your_table', 1);
var_dump($record);

// INSERT: Вставка новой записи
$data = [
    'column1' => 'value1',
    'column2' => 'value2',
    'column3' => 'value3'
];
$result = $model->insert('your_table', $data);
if ($result) {
    echo "Запись успешно добавлена.";
} else {
    echo "Ошибка при добавлении записи.";
}

// UPDATE: Обновление существующей записи
$data = [
    'column1' => 'new_value1',
    'column2' => 'new_value2',
    'column3' => 'new_value3'
];
$result = $model->update('your_table', 1, $data);
if ($result) {
    echo "Запись успешно обновлена.";
} else {
    echo "Ошибка при обновлении записи.";
}

// DELETE: Удаление записи по идентификатору
$result = $model->delete('your_table', 1);
if ($result) {
    echo "Запись успешно удалена.";
} else {
    echo "Ошибка при удалении записи.";
}
*/