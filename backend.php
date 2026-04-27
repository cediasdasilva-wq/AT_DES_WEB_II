<?php
require 'conexao.php';

header('Content-Type: application/json');

$action = '';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action === 'create') {
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $valor_compra = isset($_POST['valor_compra']) ? $_POST['valor_compra'] : 0;
    $valor_venda = isset($_POST['valor_venda']) ? $_POST['valor_venda'] : 0;
    $estoque = isset($_POST['estoque']) ? $_POST['estoque'] : 0;

    $lucro_unitario = (float)$valor_venda - (float)$valor_compra;

    try {
        $stmt = $pdo->prepare("INSERT INTO produtos (descricao, categoria, valor_venda, lucro_unitario, estoque) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array($descricao, $categoria, $valor_venda, $lucro_unitario, $estoque));
        echo json_encode(array('status' => 'success'));
    } catch (Exception $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
    exit;
}

if ($action === 'read') {
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY descricao ASC");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($produtos);
    exit;
}

if ($action === 'sell') {
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;

    try {
        $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE descricao = ? AND estoque >= ?");
        $stmt->execute(array($quantidade, $descricao, $quantidade));
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Estoque insuficiente ou produto incoreto.'));
        }
    } catch (Exception $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
    exit;
}

if ($action === 'delete') {
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    try {
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE descricao = ?");
        $stmt->execute(array($descricao));
        echo json_encode(array('status' => 'success'));
    } catch (Exception $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
    exit;
}

echo json_encode(array('status' => 'error', 'message' => 'Invalid action'));
?>
