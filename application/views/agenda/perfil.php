<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Hospital Matlhovele</title>
    <meta name="description" content="Gerencie suas informações de perfil no Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        #notification {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
            padding: 1rem;
            border-radius: 0.25rem;
            color: white;
            max-width: 300px;
        }
        #notification.error {
            background-color: #ef4444;
        }
        #notification.success {
            background-color: #10b981;
        }
        #notification.info {
            background-color: #3b82f6;
        }
        #notification.show {
            display: block;
        }
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            width: 250px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar.desktop {
            transform: translateX(0);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40;
            width: 80px;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar.desktop .sidebar-text {
            display: none;
        }
        .sidebar.desktop .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }
        .sidebar.desktop .close-sidebar-btn {
            display: none;
        }
        .sidebar.desktop.expanded {
            width: 250px;
        }
        .sidebar.desktop.expanded .sidebar-text {
            display: inline;
        }
        .sidebar.desktop.expanded .sidebar-header {
            justify-content: space-between;
            padding: 1rem;
        }
        
        /* Header com largura total e centralizado */
        header {
            position: relative;
            z-index: 30;
            width: 100%;
            margin-left: 0;
        }
        
        /* NOVA ESTRUTURA: PAGE WRAPPER */
        .page-wrapper {
            margin-left: 80px;
            transition: margin-left 0.3s ease-in-out;
            width: calc(100% - 80px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .page-wrapper.expanded {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        /* Main Content - CORRIGIDO */
        .main-content {
            flex: 1;
            width: 100%;
            padding: 1rem;
            min-height: calc(100vh - 80px);
        }
        
        @media (min-width: 768px) {
            #mobile-menu-btn {
                display: none;
            }
            .sidebar.desktop {
                display: block;
            }
        }
        @media (max-width: 767px) {
            .sidebar.desktop {
                display: none;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .page-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .page-wrapper.expanded {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        .sidebar-nav {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
        }
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        .sidebar-nav a:hover, .sidebar-nav button:hover {
            background-color: #eff6ff;
            transform: translateX(2px);
        }
        .sidebar-nav i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }
        .sidebar.desktop .sidebar-nav a, 
        .sidebar.desktop .sidebar-nav button {
            justify-content: center;
            padding: 12px;
        }
        .sidebar.desktop.expanded .sidebar-nav a,
        .sidebar.desktop.expanded .sidebar-nav button {
            justify-content: flex-start;
            padding: 12px 16px;
        }
        .sidebar-nav .logout {
            margin-top: auto;
        }
        .profile-container[aria-hidden="true"] {
            display: none;
        }
        .profile-container[aria-hidden="false"] {
            display: block;
        }
        .loading {
            display: none;
        }
        .loading.show {
            display: inline-block;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Notification -->
    <div id="notification" role="alert">
        <span id="notification-message"></span>
        <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
    </div>

    <!-- Left Sidebar -->
    <div id="sidebar-menu" class="sidebar bg-white shadow-lg desktop">
        <div class="sidebar-header flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Paciente</h2>
            <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <div class="main-menu">
                <a href="<?= site_url('agenda') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Home</span>
                </a>
                <a href="<?= site_url('agenda/agendamentos') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Meus Agendamentos</span>
                </a>
                <a href="<?= site_url('agenda/perfil') ?>" class="block bg-blue-50 text-blue-600 rounded active">
                    <i class="fas fa-user"></i>
                    <span class="sidebar-text">Perfil</span>
                </a>
            </div>
            <button id="logout-btn" class="block w-full text-left text-gray-700 hover:bg-blue-50 rounded logout">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text">Sair</span>
            </button>
        </nav>
    </div>

    <!-- Page Wrapper que contém todo o conteúdo exceto sidebar -->
    <div class="page-wrapper">
        <!-- Header/Navbar - SEM alterações de margem -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-gray-200" aria-label="Abrir menu">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Meu Perfil</h2>
                        <button id="edit-profile-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center" aria-label="Editar perfil">
                            <i class="fas fa-edit mr-2"></i>
                            <span>Editar</span>
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="profile-loading" class="loading text-center py-4">
                        <i class="fas fa-spinner fa-spin text-blue-600 mr-2"></i>
                        Carregando dados do perfil...
                    </div>

                    <!-- View Profile -->
                    <div id="view-profile" class="profile-container" aria-hidden="false">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-user mr-2 text-blue-600"></i>
                                    Nome Completo
                                </dt>
                                <dd id="view-name" class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars(($paciente->Nome ?? '') . ' ' . ($paciente->Sobrenome ?? '')) ?>
                                </dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-phone mr-2 text-blue-600"></i>
                                    Número de Telefone
                                </dt>
                                <dd id="view-phone" class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars($paciente->Telefone ?? 'Não informado') ?>
                                </dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-id-card mr-2 text-blue-600"></i>
                                    Número do BI
                                </dt>
                                <dd id="view-bi" class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars($paciente->BI ?? 'Não informado') ?>
                                </dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                    Email
                                </dt>
                                <dd id="view-email" class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars($paciente->email ?? 'Não informado') ?>
                                </dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-venus-mars mr-2 text-blue-600"></i>
                                    Gênero
                                </dt>
                                <dd id="view-gender" class="text-gray-900 font-semibold">
                                    <?= htmlspecialchars($paciente->Genero ?? 'Não informado') ?>
                                </dd>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="font-medium text-gray-700 mb-2 flex items-center">
                                    <i class="fas fa-birthday-cake mr-2 text-blue-600"></i>
                                    Data de Nascimento
                                </dt>
                                <dd id="view-birthdate" class="text-gray-900 font-semibold">
                                    <?php 
                                    if (isset($paciente->data_nascimento) && !empty($paciente->data_nascimento)) {
                                        echo date('d/m/Y', strtotime($paciente->data_nascimento));
                                    } else {
                                        echo 'Não informado';
                                    }
                                    ?>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Edit Profile -->
                    <div id="edit-profile" class="profile-container" aria-hidden="true">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="edit-nome" class="block text-gray-700 mb-2 font-medium">Nome *</label>
                                    <input type="text" id="edit-nome" value="<?= htmlspecialchars($paciente->Nome ?? '') ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-required="true">
                                </div>
                                <div>
                                    <label for="edit-sobrenome" class="block text-gray-700 mb-2 font-medium">Sobrenome *</label>
                                    <input type="text" id="edit-sobrenome" value="<?= htmlspecialchars($paciente->Sobrenome ?? '') ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-required="true">
                                </div>
                            </div>
                            
                            <div>
                                <label for="edit-telefone" class="block text-gray-700 mb-2 font-medium">Número de Telefone *</label>
                                <input type="tel" id="edit-telefone" value="<?= htmlspecialchars($paciente->Telefone ?? '') ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-required="true" placeholder="+258 8X XXXXXXX">
                                <p class="text-sm text-gray-500 mt-1">Formato: +258 8X XXXXXXX</p>
                            </div>
                            
                            <div>
                                <label for="edit-bi" class="block text-gray-700 mb-2 font-medium">Número do BI *</label>
                                <input type="text" id="edit-bi" value="<?= htmlspecialchars($paciente->BI ?? '') ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" aria-required="true">
                            </div>

                            <div class="flex space-x-4 pt-4">
                                <button id="save-profile-btn" class="bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition flex items-center flex-1 justify-center">
                                    <i class="fas fa-save mr-2"></i>
                                    <span>Salvar Alterações</span>
                                    <i id="save-loading" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                                </button>
                                <button id="cancel-edit-btn" class="bg-gray-300 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-400 transition flex items-center flex-1 justify-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-medium mb-4">Hospital Matlhovele</h3>
                        <p class="text-gray-300">Av. 25 de Setembro, Maputo</p>
                        <p class="text-gray-300">Telefone: +258 84 123 4567</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4">Horário de Funcionamento</h3>
                        <p class="text-gray-300">Segunda a Sexta: 7h30 - 16h30</p>
                        <p class="text-gray-300">Sábado: 8h00 - 12h00</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4">Links Rápidos</h3>
                        <ul class="space-y-2">
                            <li><a href="/sobre" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                            <li><a href="/servicos" class="text-gray-300 hover:text-white">Serviços</a></li>
                            <li><a href="/contactos" class="text-gray-300 hover:text-white">Contactos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                    <p>© 2025 Hospital Público de Matlhovele. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div> <!-- Fim do page-wrapper -->

    <script>
        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Alternar entre visualização e edição
        function toggleEditMode(isEditing) {
            document.getElementById('view-profile').setAttribute('aria-hidden', isEditing);
            document.getElementById('edit-profile').setAttribute('aria-hidden', !isEditing);
            
            const editBtn = document.getElementById('edit-profile-btn');
            if (isEditing) {
                editBtn.innerHTML = '<i class="fas fa-times mr-2"></i><span>Cancelar</span>';
                editBtn.className = 'bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex items-center';
            } else {
                editBtn.innerHTML = '<i class="fas fa-edit mr-2"></i><span>Editar</span>';
                editBtn.className = 'bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex items-center';
            }
        }

        // Validar telefone moçambicano
        function validatePhone(phone) {
            const phoneRegex = /^\+258\s?[8][0-49][0-9]{7}$/;
            return phoneRegex.test(phone);
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            // Esconder loading
            document.getElementById('profile-loading').style.display = 'none';

            // Fechar notificação
            document.getElementById('notification-close').addEventListener('click', function () {
                document.getElementById('notification').classList.remove('show');
            });

            // Sidebar handlers - ATUALIZADO
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function () {
                    sidebarMenu.classList.add('show');
                    sidebarMenu.classList.remove('collapsed');
                    pageWrapper.classList.add('expanded');
                });
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', function () {
                    sidebarMenu.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                });
            }

            if (toggleSidebarBtn) {
                toggleSidebarBtn.addEventListener('click', function () {
                    sidebarMenu.classList.toggle('expanded');
                    pageWrapper.classList.toggle('expanded');
                });
            }

            // Evento para botão de edição
            document.getElementById('edit-profile-btn').addEventListener('click', function () {
                const isEditing = document.getElementById('edit-profile').getAttribute('aria-hidden') === 'false';
                toggleEditMode(!isEditing);
            });

            // Evento para salvar perfil
            document.getElementById('save-profile-btn').addEventListener('click', function () {
                const nome = document.getElementById('edit-nome').value.trim();
                const sobrenome = document.getElementById('edit-sobrenome').value.trim();
                const telefone = document.getElementById('edit-telefone').value.trim();
                const bi = document.getElementById('edit-bi').value.trim();

                // Validações
                if (!nome || !sobrenome || !telefone || !bi) {
                    showNotification('Por favor, preencha todos os campos obrigatórios.', 'error');
                    return;
                }

                if (!validatePhone(telefone)) {
                    showNotification('Número de telefone inválido. Use o formato: +258 8X XXXXXXX', 'error');
                    return;
                }

                // Mostrar loading
                const saveBtn = document.getElementById('save-profile-btn');
                const saveLoading = document.getElementById('save-loading');
                saveBtn.disabled = true;
                saveLoading.classList.remove('hidden');

                // Preparar dados para envio
                const formData = new FormData();
                formData.append('nome', nome);
                formData.append('sobrenome', sobrenome);
                formData.append('telefone', telefone);
                formData.append('bi', bi);

                // Enviar para o backend
                fetch('<?= site_url("agenda/atualizar_perfil") ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showNotification(data.message, 'success');
                        // Recarregar a página para mostrar dados atualizados
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao atualizar perfil. Tente novamente.', 'error');
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveLoading.classList.add('hidden');
                });
            });

            // Evento para cancelar edição
            document.getElementById('cancel-edit-btn').addEventListener('click', function () {
                toggleEditMode(false);
                // Resetar valores originais
                document.getElementById('edit-nome').value = '<?= htmlspecialchars($paciente->Nome ?? '') ?>';
                document.getElementById('edit-sobrenome').value = '<?= htmlspecialchars($paciente->Sobrenome ?? '') ?>';
                document.getElementById('edit-telefone').value = '<?= htmlspecialchars($paciente->Telefone ?? '') ?>';
                document.getElementById('edit-bi').value = '<?= htmlspecialchars($paciente->BI ?? '') ?>';
            });

            // Evento para logout
            document.getElementById('logout-btn').addEventListener('click', function () {
                window.location.href = '<?= site_url("auth/logout") ?>';
            });

            // Fechar sidebar ao clicar fora (mobile)
            document.addEventListener('click', function (e) {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen) {
                    sidebarMenu.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });
        });
    </script>
</body>
</html>