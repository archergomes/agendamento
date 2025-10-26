<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - Hospital Matlhovele</title>
    <meta name="description" content="Visualize e gerencie seus agendamentos no Hospital Público de Matlhovele">
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
        
        /* Container principal que envolve todo o conteúdo exceto sidebar */
        .page-wrapper {
            margin-left: 80px; /* Margem para menu fechado */
            transition: margin-left 0.3s ease-in-out;
            width: calc(100% - 80px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .page-wrapper.expanded {
            margin-left: 250px; /* Margem para menu expandido */
            width: calc(100% - 250px);
        }
        
        /* Main content sem padding lateral adicional */
        .main-content {
            flex: 1;
            width: 100%;
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
            .sidebar.collapsed {
                width: 250px;
            }
            .sidebar.collapsed .sidebar-text {
                display: inline;
            }
            .sidebar.collapsed .sidebar-header {
                justify-content: space-between;
                padding: 1rem;
            }
            .sidebar.collapsed .close-sidebar-btn {
                display: block;
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
        #confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            justify-content: center;
            align-items: center;
        }
        #confirmation-modal.show {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .appointment-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .appointment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .appointment-card.cancelado {
            border-left-color: #ef4444;
            opacity: 0.7;
        }
        .appointment-card.confirmado {
            border-left-color: #10b981;
        }
        .status-pendente {
            color: #f59e0b;
            font-weight: 500;
        }
        .status-confirmado {
            color: #10b981;
            font-weight: 500;
        }
        .status-cancelado {
            color: #ef4444;
            font-weight: 500;
        }
        .cancel-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .cancel-btn:hover {
            background-color: #dc2626;
        }
        .cancel-btn:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        .no-appointments {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 4rem 2rem;
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            background-color: #f9fafb;
        }
        .loading {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 2rem;
        }
        .appointment-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }
        .appointment-status.pendente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .appointment-status.confirmado {
            background-color: #d1fae5;
            color: #065f46;
        }
        .appointment-status.cancelado {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .doctor-info {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .appointment-date {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .appointment-motive {
            color: #4b5563;
            font-style: italic;
            margin-top: 0.5rem;
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
                <a href="<?= site_url('agenda/agendamentos') ?>" class="block bg-blue-50 text-blue-600 rounded active">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Meus Agendamentos</span>
                </a>
                <a href="<?= site_url('agenda/perfil') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
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
        <main class="container mx-auto px-4 py-8 main-content">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Meus Agendamentos</h2>
                        <p class="text-gray-600 mt-1">Gerencie suas consultas agendadas</p>
                    </div>
                    <a href="<?= site_url('agenda') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-plus mr-2"></i>Novo Agendamento
                    </a>
                </div>

                <!-- Loading State -->
                <div id="appointments-loading" class="loading">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Carregando agendamentos...
                </div>

                <!-- Appointments Container -->
                <div id="appointments-container">
                    <?php if (!empty($agendamentos)): ?>
                        <div class="space-y-4">
                            <?php foreach ($agendamentos as $appt): ?>
                                <?php 
                                $status_class = '';
                                $card_class = 'appointment-card';
                                switch ($appt['Status']) {
                                    case 'Confirmado':
                                        $status_class = 'confirmado';
                                        $card_class .= ' confirmado';
                                        break;
                                    case 'Cancelado':
                                        $status_class = 'cancelado';
                                        $card_class .= ' cancelado';
                                        break;
                                    default:
                                        $status_class = 'pendente';
                                        break;
                                }
                                ?>
                                <div class="<?= $card_class ?>">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="doctor-info">
                                                <i class="fas fa-user-md text-blue-500 mr-2"></i>
                                                <?php if (isset($appt['medico_nome']) && isset($appt['medico_sobrenome'])): ?>
                                                    Dr. <?= htmlspecialchars($appt['medico_nome'] . ' ' . $appt['medico_sobrenome']) ?> - <?= htmlspecialchars($appt['Especialidade']) ?>
                                                <?php else: ?>
                                                    Médico: <?= htmlspecialchars($appt['Especialidade']) ?>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="appointment-date mb-2">
                                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                <strong>Data:</strong> <?= date('d/m/Y', strtotime($appt['Data_Agendamento'])) ?>
                                                <i class="fas fa-clock text-gray-400 ml-4 mr-2"></i>
                                                <strong>Hora:</strong> <?= substr($appt['Hora_Agendamento'], 0, 5) ?>
                                            </div>

                                            <div class="mb-2">
                                                <span class="appointment-status <?= $status_class ?>">
                                                    <i class="fas fa-circle mr-1" style="font-size: 0.5rem;"></i>
                                                    <?= $appt['Status'] ?>
                                                </span>
                                            </div>

                                            <?php if (!empty($appt['Motivo'])): ?>
                                                <div class="appointment-motive">
                                                    <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                                                    <strong>Motivo:</strong> <?= htmlspecialchars($appt['Motivo']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($appt['Status'] === 'Pendente'): ?>
                                            <button class="cancel-btn ml-4" onclick="confirmCancel(<?= $appt['ID_Agendamento'] ?>)">
                                                <i class="fas fa-times mr-1"></i>Cancelar
                                            </button>
                                        <?php elseif ($appt['Status'] === 'Cancelado'): ?>
                                            <button class="cancel-btn ml-4" disabled>
                                                <i class="fas fa-ban mr-1"></i>Cancelado
                                            </button>
                                        <?php else: ?>
                                            <span class="ml-4 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i>Confirmado
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-appointments">
                            <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                            <p class="text-lg mb-2">Nenhum agendamento encontrado</p>
                            <p class="text-gray-500 mb-4">Você ainda não possui consultas agendadas.</p>
                            <a href="<?= site_url('agenda') ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                                <i class="fas fa-plus mr-2"></i>Fazer Primeiro Agendamento
                            </a>
                        </div>
                    <?php endif; ?>
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

    <!-- Confirmation Modal for Cancellation -->
    <div id="confirmation-modal">
        <div class="modal-content">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Confirmar Cancelamento</h3>
            <p class="mb-4 text-gray-600">Tem certeza que deseja cancelar este agendamento? Esta ação não pode ser desfeita.</p>
            <div class="flex space-x-3">
                <button id="confirm-cancel-btn" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex-1 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Sim, Cancelar
                </button>
                <button id="cancel-cancel-btn" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                    Manter Agendamento
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentCancelId = null;

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

        // Confirmar cancelamento
        function confirmCancel(id) {
            currentCancelId = id;
            document.getElementById('confirmation-modal').classList.add('show');
        }

        // Event listeners for modal
        document.addEventListener('DOMContentLoaded', function() {
            // Esconder loading
            setTimeout(() => {
                const loading = document.getElementById('appointments-loading');
                if (loading) loading.style.display = 'none';
            }, 500);

            const confirmBtn = document.getElementById('confirm-cancel-btn');
            const cancelBtn = document.getElementById('cancel-cancel-btn');
            const modal = document.getElementById('confirmation-modal');
            const notificationClose = document.getElementById('notification-close');

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (currentCancelId) {
                        // AJAX to cancel appointment
                        fetch('<?= site_url("agenda/cancelar_agendamento") ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: `id=${currentCancelId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showNotification(data.message, 'success');
                                // Recarregar a página após 1 segundo
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showNotification(data.message, 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Erro:', err);
                            showNotification('Erro ao cancelar agendamento. Tente novamente.', 'error');
                        });

                        modal.classList.remove('show');
                        currentCancelId = null;
                    }
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.classList.remove('show');
                    currentCancelId = null;
                });
            }

            if (notificationClose) {
                notificationClose.addEventListener('click', function() {
                    document.getElementById('notification').classList.remove('show');
                });
            }

            // Sidebar handlers
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebarMenu.classList.add('show');
                    pageWrapper.classList.add('expanded');
                });
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', function() {
                    sidebarMenu.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                });
            }

            if (toggleSidebarBtn) {
                toggleSidebarBtn.addEventListener('click', function() {
                    sidebarMenu.classList.toggle('expanded');
                    pageWrapper.classList.toggle('expanded');
                });
            }

            // Logout
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function() {
                    window.location.href = '<?= site_url("auth/logout") ?>';
                });
            }
        });
    </script>
</body>
</html>