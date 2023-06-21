class MySQLDB implements IDB
{
    private $conn;

    public function connect(
        string $host = "",
        string $username = "",
        string $password = "",
        string $database = ""
    ): ?static { 
        $this->conn = new mysqli($host, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this;
    }

    function select(string $query): array {
        $result = $this->conn->query($query);

        if (!$result) {
            return array();
        }

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    function insert(string $table, array $data): bool {
        $keys = implode(',', array_keys($data));
        $values = "'" . implode("','", array_values($data)) . "'";

        $query = "INSERT INTO $table ($keys) VALUES ($values)";

        return $this->conn->query($query);
    }

    function update(string $table, int $id, array $data): bool {
        $set = array();
        foreach ($data as $key => $value) {
            $set[] = "$key='$value'";
        }
        $set = implode(',', $set);

        $query = "UPDATE $table SET $set WHERE id=$id";

        return $this->conn->query($query);
    }

    function delete(string $table, int $id): bool {
        $query = "DELETE FROM $table WHERE id=$id";

        return $this->conn->query($query);
    }
}
