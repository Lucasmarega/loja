<?php
session_start();
// Apenas gerentes podem entrar, se não for gerente chuta devolta para index
if (!isset($_SESSION['logado']) || $_SESSION['cargo'] !== 'Gerente') {
    header("Location: index.php");
    exit;
}

require_once "configs/database.php";

// Lógica de exclusão dentro do painel
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["excluir_funcionario"])) {
    $dbWorker = new Database();
    $conWorker = $dbWorker->conectar();
    $stmt = $conWorker->prepare("DELETE FROM funcionario WHERE ra = :ra");
    $stmt->execute([':ra' => $_GET['excluir_funcionario']]);
    header("Location: painel_gerente.php");
    exit;
}

// Carregar funcionários para a tabela
$dbWorker = new Database();
$conWorker = $dbWorker->conectar();
$funcionariosQuery = $conWorker->query("SELECT * FROM funcionario ORDER BY nome ASC");
$funcionarios = $funcionariosQuery ? $funcionariosQuery->fetchAll(PDO::FETCH_OBJ) : [];
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Gerencial - Loja Senac</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-panel {
            background: #fff;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        .manager-table { margin-top: 20px; width: 100%; border-collapse: collapse; text-align: left; font-size: 15px; }
        .manager-table th { padding: 15px; color: #333; border-bottom: 2px solid #ddd; }
        .manager-table td { padding: 15px; color: #555; border-bottom: 1px solid #eee; }
        .manager-table tr:hover { background-color: #f9f9f9; }
        .badge {
            padding: 5px 10px; border-radius: 20px; font-weight: bold; font-size: 12px;
        }
        .badge-gerente { background-color: #fff159; color: #333; }
        .badge-funcionario { background-color: #e3f2fd; color: #1565c0; }
        .header-panel { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1>Loja Senac <span style="font-size:14px; font-weight:normal; display:block;">Painel Administrativo da Gerência</span></h1>
            <div class="header-actions">
                <a href="index.php" class="btn-cadastrar" style="background: #fff; padding: 10px 15px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,.1);">Voltar para Loja</a>
            </div>
        </div>
    </header>

    <main class="main-container">
        <div class="table-panel">
            <div class="header-panel">
                <h2 style="font-size:24px; color:#333;">Equipe de Funcionários</h2>
                <a href="cadastrar_funcionario.php" class="btn-cadastrar" style="color: #3483fa; text-decoration: none; font-weight: bold;">+ Novo Funcionário</a>
            </div>
            
            <table class="manager-table">
                <thead>
                    <tr>
                        <th>RA/ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cargo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($funcionarios)): ?>
                        <?php foreach($funcionarios as $func): ?>
                        <tr>
                            <td><?= $func->ra ?></td>
                            <td style="font-weight:500; color:#333;"><?= htmlspecialchars($func->nome) ?></td>
                            <td><?= htmlspecialchars($func->email) ?></td>
                            <td><?= htmlspecialchars($func->telefone) ?></td>
                            <td>
                                <span class="badge <?= $func->cargo === 'Gerente' ? 'badge-gerente' : 'badge-funcionario' ?>">
                                    <?= htmlspecialchars($func->cargo) ?>
                                </span>
                            </td>
                            <td>
                                <a href="atualizar_funcionario.php?alterar=<?= $func->ra ?>" style="color:#3483fa; text-decoration:none; margin-right:15px; font-weight:500;">✎ Editar</a>
                                <?php if($func->ra != $_SESSION['usuario_id']): ?>
                                    <a href="#" onclick="solicitarExclusaoFuncionario(<?= $func->ra ?>)" style="color:#e3242b; text-decoration:none; font-weight:500;">✖ Excluir</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center;">Nenhum funcionário encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de Confirmação Customizado para Funcionários -->
    <div id="delete-modal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h2>⚠ Demissão de Funcionário</h2>
            <p>Tem certeza que deseja desativar permanentemente o acesso deste colaborador em todo o sistema?</p>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:25px;">
                <button onclick="closeDeleteModal()" style="padding:10px 15px; border:none; border-radius:4px; cursor:pointer; background:#f0f0f0; color:#333; font-weight:bold;">Cancelar</button>
                <button onclick="confirmDeleteModal()" style="padding:10px 15px; border:none; border-radius:4px; cursor:pointer; background:#e3242b; color:#fff; font-weight:bold;">Sim, Excluir</button>
            </div>
        </div>
    </div>

    <script>
        let pendingFuncId = null;

        function solicitarExclusaoFuncionario(id) {
            pendingFuncId = id;
            document.getElementById('delete-modal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
            pendingFuncId = null;
        }

        function confirmDeleteModal() {
            if(!pendingFuncId) return;
            window.location.href = "painel_gerente.php?excluir_funcionario=" + pendingFuncId;
        }
    </script>
</body>
</html>
