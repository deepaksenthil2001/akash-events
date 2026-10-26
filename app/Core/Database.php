<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;
    private string $activeDriver = 'mysql';

    private function __construct()
    {
        $config = require dirname(__DIR__) . '/Config/config.php';
        $dbConfig = $config['database'];

        // Attempt MySQL connection first
        if ($dbConfig['driver'] === 'mysql') {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                    $dbConfig['host'],
                    $dbConfig['port'],
                    $dbConfig['database'],
                    $dbConfig['charset']
                );
                $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
                $this->activeDriver = 'mysql';
                return;
            } catch (PDOException $e) {
                // If MySQL is not running on localhost, fallback to SQLite for local CLI dev testing
                if (!empty($dbConfig['sqlite_path'])) {
                    $sqlitePath = $dbConfig['sqlite_path'];
                    if (getenv('VERCEL') && file_exists($sqlitePath)) {
                        $tmpDb = '/tmp/database.sqlite';
                        if (!file_exists($tmpDb)) {
                            @copy($sqlitePath, $tmpDb);
                        }
                        $sqlitePath = $tmpDb;
                    }
                    $sqliteDir = dirname($sqlitePath);
                    if (!is_dir($sqliteDir)) {
                        @mkdir($sqliteDir, 0755, true);
                    }
                    $this->pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    $this->activeDriver = 'sqlite';
                    $this->pdo->exec('PRAGMA foreign_keys = ON;');
                    return;
                }
                // If no sqlite fallback allowed, throw the exception
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        } elseif ($dbConfig['driver'] === 'sqlite') {
            $sqlitePath = $dbConfig['sqlite_path'];
            if (getenv('VERCEL') && file_exists($sqlitePath)) {
                $tmpDb = '/tmp/database.sqlite';
                if (!file_exists($tmpDb)) {
                    @copy($sqlitePath, $tmpDb);
                }
                $sqlitePath = $tmpDb;
            }
            $this->pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $this->activeDriver = 'sqlite';
            $this->pdo->exec('PRAGMA foreign_keys = ON;');
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function getActiveDriver(): string
    {
        return $this->activeDriver;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $res = $stmt->fetch();
        return $res !== false ? $res : null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetchColumn(string $sql, array $params = [], int $column = 0)
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn($column);
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
