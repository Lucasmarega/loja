<?php
session_start();
// Acesso liberado para a vitrine pública de clientes.
// Funcionalidades restritas estarão ocultas no front-end para não logados.

include_once "objetos/produtoControler.php";

$controller = new produtoControler();
$produtos = $controller->index();
$a = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["pesquisar"]) && !empty($_POST["pesquisar"])) {
        $a = $controller->pesquisarProduto($_POST["pesquisar"]);
        if ($a) {
            $produtos = [$a]; // Exibe apenas o Produto pesquisado
        } else {
            $produtos = []; // Nenhum encontrado
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["excluir"])) {
        $autorizado = false;
        
        // Se já for um gerente logado usando o sistema, ele exclui de primeira
        if (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'Gerente') {
            $autorizado = true;
        } else {
            // Se for um funcionário comum, verifica se a senha repassada via prompt pertence a qualquer gerente
            $senha_fornecida = $_GET["senha_gerente"] ?? '';
            require_once "configs/database.php";
            $dbValider = new Database();
            $conValider = $dbValider->conectar();
            
            $stmt_gerente = $conValider->prepare("SELECT ra FROM funcionario WHERE cargo = 'Gerente' AND senha = :senha");
            $stmt_gerente->execute([':senha' => $senha_fornecida]);
            
            if ($stmt_gerente->rowCount() > 0) {
                $autorizado = true;
            }
        }
        
        if ($autorizado) {
            $a = $controller->excluirProduto($_GET["excluir"]);
        } else {
            echo "<script>alert('❌ Acesso Negado! Senha incorreta ou a senha digitada não pertence a um Gerente.'); window.location.href='index.php';</script>";
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senac Rio Claro - Vitrine</title>
    <!-- Adicionada a Fonte Roboto e o CSS Externo (estilo.css ou style.css) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="main-header">
        <div class="header-content">
            <h1>
                Loja Senac 
                <?php if(isset($_SESSION['logado'])): ?>
                    <span style="font-size:14px; font-weight:normal; display:block;">Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? '') ?> (<?= htmlspecialchars($_SESSION['cargo'] ?? '') ?>)</span>
                <?php endif; ?>
            </h1>
            <form class="search-form" method="post" action="index.php">
                <!-- Como a pesquisa é por ID internamente, mantemos type text mas placeholder instrui id -->
                <input type="number" name="pesquisar" placeholder="Pesquisar produto por ID...">
                <button type="submit">🔍</button>
            </form>
            <div class="header-actions">
                <?php if(isset($_SESSION['logado'])): ?>
                    <?php if(isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'Gerente'): ?>
                        <a href="painel_gerente.php" class="btn-cadastrar" style="color: #3483fa; font-weight: bold; margin-right: 15px;">Painel Gerencial</a>
                    <?php endif; ?>
                    <a href="cadastro.php" class="btn-cadastrar">Cadastrar Produto</a>
                    <a href="logout.php" class="btn-cadastrar" style="color: #e3242b;">Sair (Logout)</a>
                <?php else: /* Seção de visualização do CLiente */ ?>
                    <a href="login.php" class="btn-area-funcionario">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                        </svg>
                        Área do Funcionário
                    </a>
                    <div class="cart-container" title="Carrinho de Compras" onclick="toggleCartModal()">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                        <span id="cart-count" class="cart-badge">0</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="main-container">

        <div class="product-grid">
            <?php if ($produtos): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <!-- Imagem do produto com fallback -->
                            <?php
                            if (!empty($produto->imagens)) {
                                $imagem_url = "uploads/" . htmlspecialchars($produto->imagens);
                            } else {
                                $imagem_url = "https://via.placeholder.com/300x300?text=Sem+Foto";
                            }
                            ?>
                            <img src="<?= $imagem_url ?>" alt="<?= htmlspecialchars($produto->nome); ?>">
                        </div>
                        <div class="product-info">
                            <?php
                            // O preço pode vir como string, formatamos garantindo que seja float
                            $preco = floatval(str_replace(',', '.', $produto->preco));
                            ?>
                            <p class="product-price">R$ <?= number_format($preco, 2, ',', '.') ?></p>

                            <!-- Lógica de exibir parcelamento inteligente (minimo 5 reais por parcela) -->
                            <?php 
                            $maxParcelas = floor($preco / 5); 
                            $maxParcelas = $maxParcelas > 10 ? 10 : $maxParcelas;
                            if ($maxParcelas >= 2): ?>
                                <p class="product-installments">em <?= $maxParcelas ?>x R$ <?= number_format($preco / $maxParcelas, 2, ',', '.') ?> sem juros</p>
                            <?php else: ?>
                                <p class="product-installments" style="visibility:hidden;">-</p>
                            <?php endif; ?>
                            
                            <!-- Regra do Frete Grátis -->
                            <?php if ($preco >= 10): ?>
                                <p class="product-shipping">Frete grátis</p>
                            <?php else: ?>
                                <p class="product-shipping" style="color: #666; font-weight: normal;">Frete na entrega</p>
                            <?php endif; ?>

                            <p class="product-name"><?= htmlspecialchars($produto->nome); ?></p>
                            
                            <?php $qtd = isset($produto->quantidade) ? intval($produto->quantidade) : 0; ?>
                            <p class="product-stock" style="font-size: 13px; color: <?= $qtd > 0 ? '#666' : '#e3242b' ?>; font-weight: 500; margin-bottom: 15px; display: inline-block; background: <?= $qtd > 0 ? '#f5f5f5' : '#ffebee' ?>; padding: 4px 8px; border-radius: 4px;">
                                <?= $qtd > 0 ? "Estoque: {$qtd} un." : 'Esgotado' ?>
                            </p>

                            <div class="action-buttons">
                                <button class="btn-cart" onclick='addToCart(<?= $produto->id ?>, <?= json_encode($produto->nome) ?>, <?= $preco ?>, <?= json_encode($imagem_url) ?>, this)'>Adicionar ao carrinho</button>
                            </div>

                            <!-- Funções administrativas ocultadas do cliente público -->
                            <?php if(isset($_SESSION['logado'])): ?>
                            <div class="admin-actions">
                                <a href="atualizar.php?alterar=<?= $produto->id ?>" class="btn-edit">✎ Editar</a>
                                <?php $ehGerente = (isset($_SESSION['cargo']) && $_SESSION['cargo'] === 'Gerente') ? 'true' : 'false'; ?>
                                <a href="#" onclick="solicitarSenhaExclusao(<?= $produto->id ?>, <?= $ehGerente ?>)" class="btn-delete">✖ Excluir</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum produto encontrado na loja.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal do Carrinho -->
    <div id="cart-modal" class="cart-modal-overlay">
        <div class="cart-modal-content">
            <div class="cart-modal-header">
                <h2>Seu Carrinho</h2>
                <button class="close-modal" onclick="toggleCartModal()">X</button>
            </div>
            <div id="cart-items-container" class="cart-items-container">
                <!-- injetado via JS -->
            </div>
            <div class="cart-modal-footer">
                <h3>Total: <span id="cart-total">R$ 0,00</span></h3>
                <button class="btn-comprar" onclick="finalizarCompra()">Finalizar Compra</button>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão Customizado -->
    <div id="delete-modal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h2 id="delete-title">⚠ Atenção Restrita</h2>
            <p id="delete-msg">Tem certeza que deseja excluir o produto permanentemente?</p>
            
            <div id="password-group" style="display:none; margin-top:20px;">
                <label style="display:block; font-weight:bold; margin-bottom:8px; color:#444;" id="delete-label">Senha do Gerente:</label>
                <input type="password" id="delete-password" style="width:100%; padding:12px; border:1px solid #ccc; border-radius:4px; font-size:16px;">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:25px;">
                <button onclick="closeDeleteModal()" style="padding:10px 15px; border:none; border-radius:4px; cursor:pointer; background:#f0f0f0; color:#333; font-weight:bold;">Cancelar</button>
                <button onclick="confirmDeleteModal()" style="padding:10px 15px; border:none; border-radius:4px; cursor:pointer; background:#e3242b; color:#fff; font-weight:bold;">Sim, Excluir</button>
            </div>
        </div>
    </div>

    <!-- Lógica do Carrinho no Frontend -->
    <script>
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        
        function updateCartUI() {
            let count = cartItems.reduce((acc, item) => acc + item.qty, 0);
            document.getElementById('cart-count').innerText = count;
        }

        function addToCart(id, nome, preco, imagem, btn) {
            let existingItem = cartItems.find(item => item.id === id);
            if(existingItem) {
                existingItem.qty += 1;
            } else {
                cartItems.push({ id, nome, preco, imagem, qty: 1 });
            }
            
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartUI(); 
            
            let originalText = btn.innerText;
            btn.innerText = "Adicionado ✓";
            btn.style.backgroundColor = "#00a650";
            
            setTimeout(() => {
                btn.innerText = originalText;
                btn.style.backgroundColor = "";
            }, 1200);
        }

        function toggleCartModal() {
            const modal = document.getElementById('cart-modal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
            if(modal.style.display === 'flex') {
                renderCartItems();
            }
        }
        
        function renderCartItems() {
            const container = document.getElementById('cart-items-container');
            const totalEl = document.getElementById('cart-total');
            container.innerHTML = '';
            
            if(cartItems.length === 0) {
                container.innerHTML = '<p style="text-align:center; padding: 20px;">Seu carrinho está vazio.</p>';
                totalEl.innerText = 'R$ 0,00';
                return;
            }
            
            let total = 0;
            cartItems.forEach((item, index) => {
                total += item.preco * item.qty;
                container.innerHTML += `
                    <div class="cart-item">
                        <img src="${item.imagem}" width="50" height="50" style="object-fit:cover; border-radius:4px;">
                        <div class="cart-item-info">
                            <h4>${item.nome}</h4>
                            <p>Qtd: ${item.qty} x R$ ${item.preco.toFixed(2).replace('.', ',')}</p>
                        </div>
                        <button onclick="removeCartItem(${index})" class="btn-remove-item">X</button>
                    </div>
                `;
            });
            totalEl.innerText = 'R$ ' + total.toFixed(2).replace('.', ',');
        }

        function removeCartItem(index) {
            cartItems.splice(index, 1);
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartUI();
            renderCartItems();
        }

        function finalizarCompra() {
            if(cartItems.length === 0) {
                alert("Adicione um produto antes de comprar!");
                return;
            }
            
            alert("Sucesso! Compra finalizada com sucesso!");
            
            // Limpa o carrinho
            cartItems = [];
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            
            // Volta status inicial
            updateCartUI();
            toggleCartModal();
            window.scrollTo(0, 0);
        }

        let pendingDeleteId = null;
        let pendingDeleteNeedsManager = false;

        function solicitarSenhaExclusao(id, ehGerente) {
            pendingDeleteId = id;
            pendingDeleteNeedsManager = !ehGerente;
            
            document.getElementById('delete-modal').style.display = 'flex';
            
            if (ehGerente) {
                document.getElementById('delete-msg').innerText = "Tem certeza que deseja excluir permanentemente o produto #" + id + "?";
                document.getElementById('password-group').style.display = 'none';
            } else {
                document.getElementById('delete-msg').innerText = "Esta é uma ação restrita da gerência. Solicite o passe livre do administrador para efetivar a remoção.";
                document.getElementById('password-group').style.display = 'block';
                document.getElementById('delete-password').value = '';
            }
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
            pendingDeleteId = null;
        }

        function confirmDeleteModal() {
            if(!pendingDeleteId) return;
            
            if(pendingDeleteNeedsManager) {
                let pwd = document.getElementById('delete-password').value;
                if(!pwd) {
                    alert("Por favor, a senha gerencial é obrigatória!");
                    return;
                }
                window.location.href = "index.php?excluir=" + pendingDeleteId + "&senha_gerente=" + encodeURIComponent(pwd);
            } else {
                window.location.href = "index.php?excluir=" + pendingDeleteId;
            }
        }

        updateCartUI();
    </script>
</body>

</html>