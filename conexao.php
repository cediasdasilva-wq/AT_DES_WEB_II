<?php
$host = 'localhost';
$user = 'root';
$password = 'usbw';
$dbname = 'produtos_db';

try {
    $pdo_setup = new PDO("mysql:host=$host;port=3307;charset=utf8", $user, $password);
    $port_connected = 3307;
} catch (PDOException $e) {
    try {
        $pdo_setup = new PDO("mysql:host=$host;port=3306;charset=utf8", $user, $password);
        $port_connected = 3306;
    } catch (PDOException $e2) {
        die("SERVIDOR MYSQL DESLIGADO: O modulo MySQL nao esta rodando! Abra a telinha do USBWebserver e inicie o MySQL.");
    }
}

try {
    $pdo_setup->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_setup->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");

    $pdo = new PDO("mysql:host=$host;port=$port_connected;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        descricao VARCHAR(255) PRIMARY KEY,
        categoria VARCHAR(100) NOT NULL,
        valor_venda DECIMAL(10, 2) NOT NULL,
        lucro_unitario DECIMAL(10, 2) NOT NULL,
        estoque INT NOT NULL
    )");

} catch (PDOException $e) {
    die("Erro interno do MySQL: " . $e->getMessage());
}
?>
