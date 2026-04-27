document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('produtoForm');
    const tableBody = document.querySelector('#produtosTable tbody');
    const searchInput = document.getElementById('searchInput');
    const btnVenderTop = document.getElementById('btnVenderTop');

    let produtos = [];

    const formatCurrency = (value) => {
        return parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    };

    const runFetch = async (url, options = {}) => {
        try {
            const response = await fetch(url, options);
            const textContent = await response.text();
            
            try {
                return JSON.parse(textContent);
            } catch (jsonError) {
                alert("O PHP retornou um texto que quebrou a estrutura da página. Aqui está o erro exato do servidor:\n\n" + textContent);
                return null;
            }
        } catch (networkError) {
            alert('Falha na comunicação HTTP (servidor offline?).');
            return null;
        }
    };

    const loadProdutos = async () => {
        const data = await runFetch('backend.php?action=read');
        if (!data) return;
        
        if (Array.isArray(data)) {
            produtos = data;
            renderTable();
        } else if (data.status === 'error') {
            alert('Erro reportado: ' + data.message);
        } else {
            alert('O PHP não retornou uma lista de produtos válida.');
        }
    };

    const renderTable = (filter = '') => {
        tableBody.innerHTML = '';
        
        const filteredProdutos = produtos.filter(p => 
            (p.descricao && p.descricao.toLowerCase().includes(filter.toLowerCase())) || 
            (p.categoria && p.categoria.toLowerCase().includes(filter.toLowerCase()))
        );

        filteredProdutos.forEach(p => {
            const row = document.createElement('tr');
            
            const valorVenda = parseFloat(p.valor_venda || 0);
            const lucroUnitario = parseFloat(p.lucro_unitario || 0);

            row.innerHTML = `
                <td>${p.descricao}</td>
                <td>${p.categoria}</td>
                <td>${formatCurrency(valorVenda)}</td>
                <td>${formatCurrency(lucroUnitario)}</td>
                <td>${p.estoque}</td>
                <td>
                    <button class="btn-delete" onclick="deleteProduto(this.dataset.desc)" data-desc="${p.descricao}">X</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('action', 'create');

        const data = await runFetch('backend.php', {
            method: 'POST',
            body: formData
        });

        if (data && data.status === 'success') {
            form.reset();
            loadProdutos();
            alert('Produto cadastrado com sucesso!');
        } else if (data) {
            alert('Erro do banco de dados: ' + (data.message || 'Desconhecido'));
        }
    });

    searchInput.addEventListener('input', (e) => {
        renderTable(e.target.value);
    });

    const sellProduto = async (descricao, quantidade) => {
        const formData = new FormData();
        formData.append('action', 'sell');
        formData.append('descricao', descricao);
        formData.append('quantidade', quantidade);

        const data = await runFetch('backend.php', {
            method: 'POST',
            body: formData
        });

        if (data && data.status === 'success') {
            loadProdutos();
            alert('Venda efetuada e Estoque deduzido!');
        } else if (data) {
            alert('Erro de Venda: ' + (data.message || 'Desconhecido'));
        }
    };

    window.deleteProduto = async (descricao) => {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('descricao', descricao);

        const data = await runFetch('backend.php', {
            method: 'POST',
            body: formData
        });

        if (data && data.status === 'success') {
            loadProdutos();
            alert('Produto eliminado do inventário!');
        } else if (data) {
            alert('Erro ao excluir: ' + (data.message || 'Desconhecido'));
        }
    };

    if (btnVenderTop) {
        btnVenderTop.addEventListener('click', () => {
            const descInput = document.getElementById('descricao').value;
            const estoqueInput = document.getElementById('estoque').value;
            const qtdVendida = parseInt(estoqueInput, 10);
            
            const quantidade = (isNaN(qtdVendida) || qtdVendida <= 0) ? 1 : qtdVendida;

            if (descInput.trim() !== '') {
                sellProduto(descInput, quantidade);
            } else {
                alert('Aviso: Preencha o campo "Descrição" do produto para realizar a venda e abater o estoque.');
            }
        });
    }

    loadProdutos();
});
