<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Produtos</h1>
        <form id="produtoForm">
            <div class="form-group-row">
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <input type="text" id="categoria" name="categoria" required>
                </div>
                <div class="form-group">
                    <label for="valor_compra">Valor de Compra (R$):</label>
                    <input type="number" id="valor_compra" name="valor_compra" step="0.01" required>
                </div>
            </div>
            <div class="form-group-row">
                <div class="form-group">
                    <label for="valor_venda">Valor de Venda (R$):</label>
                    <input type="number" id="valor_venda" name="valor_venda" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="estoque">Estoque:</label>
                    <input type="number" id="estoque" name="estoque" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-blue" id="btnCadastrar">CADASTRAR PRODUTO</button>
                <button type="button" class="btn-green" id="btnVenderTop">VENDER PRODUTO</button>
            </div>
        </form>

        <h2>Inventário</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Buscar por Descrição ou Categoria...">
        </div>
        
        <table id="produtosTable">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Venda</th>
                    <th>Lucro Unit.</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script src="script.js?v=3"></script>
</body>
</html>
