<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescrições - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de prescrições médicas do Hospital Público de Matlhovele">
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
            z-index: 1000;
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
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 80px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            z-index: 900;
            display: flex;
            flex-direction: column;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar.desktop {
            transform: translateX(0);
        }

        .sidebar.desktop.expanded {
            width: 250px;
        }

        .sidebar.desktop .sidebar-text {
            display: none;
        }

        .sidebar.desktop.expanded .sidebar-text {
            display: inline;
        }

        .sidebar.desktop .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }

        .sidebar.desktop.expanded .sidebar-header {
            justify-content: space-between;
            padding: 1rem;
        }

        header {
            position: relative;
            z-index: 800;
            background-color: #2563eb;
            width: 100%;
            margin-left: 0;
        }

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

        .main-content {
            flex: 1;
            width: 100%;
            padding: 1rem;
            min-height: calc(100vh - 80px);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 899;
        }

        .sidebar-overlay.show {
            display: block;
        }

        @media (min-width: 768px) {
            #mobile-menu-btn {
                display: none;
            }

            .sidebar.desktop {
                display: flex;
            }
        }

        @media (max-width: 767px) {
            .sidebar.desktop {
                display: none;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
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
            display: flex;
            flex-direction: column;
            height: calc(100% - 64px);
            padding: 0.5rem;
        }

        .main-menu {
            overflow-y: auto;
            flex-grow: 1;
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #e5e7eb;
        }

        .main-menu::-webkit-scrollbar {
            width: 6px;
        }

        .main-menu::-webkit-scrollbar-track {
            background: #e5e7eb;
        }

        .main-menu::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 3px;
        }

        .sidebar-nav a,
        .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 0.375rem;
            color: #374151;
            transition: background-color 0.2s, color 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover,
        .sidebar-nav button:hover {
            background-color: #eff6ff;
            color: #1e40af;
        }

        .sidebar-nav a.active {
            background-color: #3b82f6;
            color: white;
        }

        .sidebar-nav i {
            font-size: 1.5rem;
            width: 28px;
            text-align: center;
        }

        .sidebar.desktop .sidebar-nav a,
        .sidebar.desktop .sidebar-nav button {
            justify-content: center;
            padding: 8px;
        }

        .sidebar.desktop.expanded .sidebar-nav a,
        .sidebar.desktop.expanded .sidebar-nav button {
            justify-content: flex-start;
            padding: 8px 16px;
        }

        .sidebar-nav .logout {
            margin-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.5rem;
        }

        .metric-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .prescription-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .prescription-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .prescription-card.active {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .prescription-card.expired {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }

        .prescription-card.warning {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-expired {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-suspended {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .priority-high {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .priority-medium {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fed7aa;
        }

        .priority-low {
            background-color: #f0fdf4;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .action-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .action-btn:hover {
            background-color: #2563eb;
        }

        .action-btn.success {
            background-color: #10b981;
        }

        .action-btn.success:hover {
            background-color: #059669;
        }

        .action-btn.warning {
            background-color: #f59e0b;
        }

        .action-btn.warning:hover {
            background-color: #d97706;
        }

        .action-btn.danger {
            background-color: #ef4444;
        }

        .action-btn.danger:hover {
            background-color: #dc2626;
        }

        .action-btn.secondary {
            background-color: #6b7280;
        }

        .action-btn.secondary:hover {
            background-color: #4b5563;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }

        .filter-tab.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .filter-tab:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 800px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }

        .medicine-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .prescription-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .dosage-tag {
            display: inline-block;
            background-color: #eff6ff;
            color: #1e40af;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .interaction-warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 0.375rem;
            padding: 1rem;
            margin: 1rem 0;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #3b82f6;
            border: 2px solid white;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            ring: 2px solid #3b82f6;
            border-color: #3b82f6;
        }

        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
        }

        .medicine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .medicine-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .medicine-item:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .medicine-item.selected {
            border-color: #3b82f6;
            background-color: #dbeafe;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="page-wrapper">
        <!-- Notification -->
        <div id="notification" role="alert">
            <span id="notification-message"></span>
            <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
        </div>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        <!-- Left Sidebar -->
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg desktop">
            <div class="sidebar-header flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Médico</h2>
                <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn"
                    aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="<?php echo site_url('medico'); ?>" class="block text-gray-700">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="<?php echo site_url('medico/consultas'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Minhas Consultas</span>
                    </a>
                    <a href="<?php echo site_url('medico/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Meus Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('medico/prontuarios'); ?>" class="block text-gray-700">
                        <i class="fas fa-file-medical"></i>
                        <span class="sidebar-text">Prontuários</span>
                    </a>
                    <a href="<?php echo site_url('medico/prescricoes'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-prescription"></i>
                        <span class="sidebar-text">Prescrições</span>
                    </a>
                    <a href="<?php echo site_url('medico/laudos'); ?>" class="block text-gray-700">
                        <i class="fas fa-file-medical-alt"></i>
                        <span class="sidebar-text">Laudos</span>
                    </a>
                    <a href="<?php echo site_url('medico/horarios'); ?>" class="block text-gray-700">
                        <i class="fas fa-clock"></i>
                        <span class="sidebar-text">Meus Horários</span>
                    </a>
                    <a href="<?php echo site_url('medico/relatorios'); ?>" class="block text-gray-700">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="<?php echo site_url('medico/perfil'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Meu Perfil</span>
                    </a>
                </div>
                <button id="logout-btn" class="block w-full text-left text-gray-700 logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Sair</span>
                </button>
            </nav>
        </div>

        <!-- Header/Navbar -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm">Dr. <?php echo $medico_nome ?? 'Médico'; ?></p>
                        <p class="text-xs text-blue-200"><?php echo $especialidade ?? 'Especialidade'; ?></p>
                    </div>
                    <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Prescrições Médicas</h2>
                            <p class="text-gray-600">Gerencie e acompanhe as prescrições dos seus pacientes.</p>
                        </div>
                        <button onclick="novaPrescricao()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Nova Prescrição
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="metric-card" onclick="filterByStatus('ativas')">
                            <div class="text-2xl font-bold text-green-600" id="total-ativas">0</div>
                            <div class="text-sm text-gray-600">Ativas</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('pendentes')">
                            <div class="text-2xl font-bold text-orange-600" id="total-pendentes">0</div>
                            <div class="text-sm text-gray-600">Pendentes</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('expiradas')">
                            <div class="text-2xl font-bold text-red-600" id="total-expiradas">0</div>
                            <div class="text-sm text-gray-600">Expiradas</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('suspensas')">
                            <div class="text-2xl font-bold text-gray-600" id="total-suspensas">0</div>
                            <div class="text-sm text-gray-600">Suspensas</div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <div class="filter-tab active" data-filter="todas">Todas</div>
                            <div class="filter-tab" data-filter="ativas">Ativas</div>
                            <div class="filter-tab" data-filter="pendentes">Pendentes</div>
                            <div class="filter-tab" data-filter="expiradas">Expiradas</div>
                            <div class="filter-tab" data-filter="suspensas">Suspensas</div>
                            <div class="filter-tab" data-filter="alta-prioridade">Alta Prioridade</div>
                        </div>

                        <!-- Search -->
                        <div class="flex gap-3 w-full md:w-auto">
                            <div class="relative flex-1 md:w-80">
                                <input type="text" id="search-input" placeholder="Pesquisar paciente, medicamento..." 
                                       class="pl-10 pr-4 py-2 border rounded-lg w-full">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button onclick="exportPrescricoes()" class="action-btn warning flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Prescriptions List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Prescrições</h3>
                            <div class="text-sm text-gray-600">
                                <span id="prescricoes-count">0</span> prescrições encontradas
                            </div>
                        </div>
                        
                        <div id="prescricoes-list">
                            <!-- Prescrições serão carregadas via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando prescrições...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-prescription-bottle-alt"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma prescrição encontrada</h3>
                            <p class="text-gray-600 mb-4">Não há prescrições que correspondam aos seus critérios de busca.</p>
                            <button onclick="novaPrescricao()" class="action-btn success">
                                <i class="fas fa-plus mr-2"></i>Criar Nova Prescrição
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Visualização da Prescrição -->
    <div id="prescricao-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Detalhes da Prescrição</h3>
                <div class="flex gap-2">
                    <button onclick="imprimirPrescricao()" class="action-btn warning flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="prescricao-details">
                <!-- Detalhes da prescrição serão carregados via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Nova Prescrição -->
    <div id="nova-prescricao-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Nova Prescrição Médica</h3>
                <button onclick="closeNovaPrescricaoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="nova-prescricao-content">
                <!-- Formulário de nova prescrição será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Edição da Prescrição -->
    <div id="editar-prescricao-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Prescrição</h3>
                <button onclick="closeEditarPrescricaoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editar-prescricao-content">
                <!-- Formulário de edição será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'todas';
        let currentSearch = '';

        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) {
                console.error('Elementos de notificação não encontrados');
                return;
            }
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Carregar estatísticas
        async function loadStats() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/prescricoes_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-ativas').textContent = stats.ativas || 0;
                document.getElementById('total-pendentes').textContent = stats.pendentes || 0;
                document.getElementById('total-expiradas').textContent = stats.expiradas || 0;
                document.getElementById('total-suspensas').textContent = stats.suspensas || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar prescrições
        async function loadPrescricoes() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('prescricoes-list');
            const count = document.getElementById('prescricoes-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todas') params.append('filter', currentFilter);
                if (currentSearch) params.append('search', currentSearch);

                const response = await fetch(`<?php echo site_url('api/medico/prescricoes'); ?>?${params}`);
                const prescricoes = await response.json();

                if (loading) loading.classList.add('hidden');

                if (prescricoes.error) {
                    showNotification(prescricoes.error, 'error');
                    return;
                }

                if (count) count.textContent = prescricoes.length || 0;

                if (prescricoes.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderPrescricoes(prescricoes);

            } catch (error) {
                console.error('Erro ao carregar prescrições:', error);
                showNotification('Erro ao carregar prescrições.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar prescrições
        function renderPrescricoes(prescricoes) {
            const list = document.getElementById('prescricoes-list');
            if (!list) return;

            list.innerHTML = prescricoes.map(prescricao => {
                const cardClass = getCardClass(prescricao);
                const statusClass = getStatusClass(prescricao.status);
                const priorityClass = getPriorityClass(prescricao.prioridade);
                const initials = getInitials(prescricao.paciente_nome);
                const dataPrescricao = formatDate(prescricao.data_prescricao);
                const dataValidade = formatDate(prescricao.data_validade);

                return `
                    <div class="prescription-card ${cardClass}" data-prescricao-id="${prescricao.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Informações da Prescrição -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="medicine-icon">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${prescricao.medicamento}</h4>
                                        <span class="status-badge ${statusClass}">${prescricao.status}</span>
                                        <span class="priority-badge ${priorityClass}">${prescricao.prioridade}</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-600">Paciente:</span>
                                            <p class="font-medium">${prescricao.paciente_nome}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Data da Prescrição:</span>
                                            <p class="font-medium">${dataPrescricao}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Validade:</span>
                                            <p class="font-medium ${isExpired(prescricao.data_validade) ? 'text-red-600' : ''}">${dataValidade}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Médico:</span>
                                            <p class="font-medium">${prescricao.medico_nome}</p>
                                        </div>
                                    </div>

                                    <!-- Dosagem e Instruções -->
                                    <div class="bg-gray-50 rounded p-3 mb-3">
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <span class="dosage-tag">
                                                <i class="fas fa-syringe mr-1"></i>${prescricao.dosagem}
                                            </span>
                                            <span class="dosage-tag">
                                                <i class="fas fa-clock mr-1"></i>${prescricao.frequencia}
                                            </span>
                                            <span class="dosage-tag">
                                                <i class="fas fa-calendar mr-1"></i>${prescricao.duracao}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700">
                                            <strong>Instruções:</strong> ${prescricao.instrucoes || 'Sem instruções específicas'}
                                        </p>
                                    </div>

                                    <!-- Observações -->
                                    ${prescricao.observacoes ? `
                                        <div class="text-sm">
                                            <span class="text-gray-600">Observações:</span>
                                            <p class="text-gray-700">${prescricao.observacoes}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                <button class="action-btn ver-prescricao" data-id="${prescricao.id}">
                                    <i class="fas fa-eye mr-1"></i>Visualizar
                                </button>
                                <button class="action-btn editar-prescricao" data-id="${prescricao.id}">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                                ${prescricao.status === 'ativa' ? `
                                    <button class="action-btn warning suspender-prescricao" data-id="${prescricao.id}">
                                        <i class="fas fa-pause mr-1"></i>Suspender
                                    </button>
                                ` : ''}
                                ${prescricao.status === 'suspensa' ? `
                                    <button class="action-btn success ativar-prescricao" data-id="${prescricao.id}">
                                        <i class="fas fa-play mr-1"></i>Reativar
                                    </button>
                                ` : ''}
                                <button class="action-btn danger cancelar-prescricao" data-id="${prescricao.id}">
                                    <i class="fas fa-times mr-1"></i>Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Adicionar event listeners aos botões
            addEventListeners();
        }

        // Obter iniciais do nome
        function getInitials(nome) {
            return nome.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2);
        }

        // Formatar data
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }

        // Verificar se a prescrição está expirada
        function isExpired(dataValidade) {
            if (!dataValidade) return false;
            return new Date(dataValidade) < new Date();
        }

        // Obter classe CSS do card baseado no status
        function getCardClass(prescricao) {
            if (prescricao.status === 'expirada') return 'expired';
            if (prescricao.status === 'ativa') return 'active';
            if (prescricao.status === 'pendente') return 'warning';
            return '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'ativa': 'status-active',
                'expirada': 'status-expired',
                'suspensa': 'status-suspended',
                'pendente': 'status-warning',
                'concluida': 'status-completed'
            };
            return statusMap[status] || 'status-active';
        }

        // Obter classe CSS da prioridade
        function getPriorityClass(prioridade) {
            const priorityMap = {
                'alta': 'priority-high',
                'media': 'priority-medium',
                'baixa': 'priority-low'
            };
            return priorityMap[prioridade] || 'priority-medium';
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Ver prescrição
            document.querySelectorAll('.ver-prescricao').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prescricaoId = e.target.closest('button').dataset.id;
                    verPrescricaoCompleta(prescricaoId);
                });
            });

            // Editar prescrição
            document.querySelectorAll('.editar-prescricao').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prescricaoId = e.target.closest('button').dataset.id;
                    editarPrescricao(prescricaoId);
                });
            });

            // Suspender prescrição
            document.querySelectorAll('.suspender-prescricao').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prescricaoId = e.target.closest('button').dataset.id;
                    suspenderPrescricao(prescricaoId);
                });
            });

            // Ativar prescrição
            document.querySelectorAll('.ativar-prescricao').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prescricaoId = e.target.closest('button').dataset.id;
                    ativarPrescricao(prescricaoId);
                });
            });

            // Cancelar prescrição
            document.querySelectorAll('.cancelar-prescricao').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prescricaoId = e.target.closest('button').dataset.id;
                    cancelarPrescricao(prescricaoId);
                });
            });
        }

        // Funções de ação
        async function verPrescricaoCompleta(prescricaoId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/prescricao_completa/'); ?>${prescricaoId}`);
                const prescricao = await response.json();

                if (prescricao.error) {
                    showNotification(prescricao.error, 'error');
                    return;
                }

                const modal = document.getElementById('prescricao-modal');
                const details = document.getElementById('prescricao-details');

                details.innerHTML = `
                    <div class="space-y-6">
                        <!-- Cabeçalho -->
                        <div class="prescription-header">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-xl font-semibold">Prescrição Médica</h4>
                                    <p class="text-blue-100">Hospital Matlhovele</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm">Nº ${prescricao.id}</p>
                                    <p class="text-sm">Data: ${formatDate(prescricao.data_prescricao)}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Paciente -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border rounded">
                            <div>
                                <strong>Paciente:</strong> ${prescricao.paciente_nome}
                            </div>
                            <div>
                                <strong>BI:</strong> ${prescricao.paciente_bi}
                            </div>
                            <div>
                                <strong>Idade:</strong> ${prescricao.idade} anos
                            </div>
                            <div>
                                <strong>Médico:</strong> ${prescricao.medico_nome}
                            </div>
                        </div>

                        <!-- Medicamento -->
                        <div class="border rounded p-4">
                            <h5 class="font-semibold text-lg mb-3">${prescricao.medicamento}</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <strong>Dosagem:</strong> ${prescricao.dosagem}
                                </div>
                                <div>
                                    <strong>Frequência:</strong> ${prescricao.frequencia}
                                </div>
                                <div>
                                    <strong>Duração:</strong> ${prescricao.duracao}
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Instruções:</strong>
                                <p class="mt-1">${prescricao.instrucoes || 'Sem instruções específicas'}</p>
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded p-4">
                                <h6 class="font-semibold mb-2">Status</h6>
                                <span class="status-badge ${getStatusClass(prescricao.status)}">${prescricao.status}</span>
                                <p class="text-sm text-gray-600 mt-2">
                                    <strong>Validade:</strong> ${formatDate(prescricao.data_validade)}
                                </p>
                            </div>
                            <div class="border rounded p-4">
                                <h6 class="font-semibold mb-2">Prioridade</h6>
                                <span class="priority-badge ${getPriorityClass(prescricao.prioridade)}">${prescricao.prioridade}</span>
                            </div>
                        </div>

                        <!-- Observações -->
                        ${prescricao.observacoes ? `
                            <div class="border rounded p-4">
                                <h6 class="font-semibold mb-2">Observações</h6>
                                <p>${prescricao.observacoes}</p>
                            </div>
                        ` : ''}

                        <!-- Interações Medicamentosas -->
                        ${prescricao.interacoes && prescricao.interacoes.length > 0 ? `
                            <div class="interaction-warning">
                                <h6 class="font-semibold mb-2 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Interações Medicamentosas
                                </h6>
                                <ul class="text-sm text-orange-700">
                                    ${prescricao.interacoes.map(interacao => `
                                        <li>• ${interacao}</li>
                                    `).join('')}
                                </ul>
                            </div>
                        ` : ''}
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar prescrição:', error);
                showNotification('Erro ao carregar prescrição completa.', 'error');
            }
        }

        async function editarPrescricao(prescricaoId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/prescricao_editar/'); ?>${prescricaoId}`);
                const prescricao = await response.json();

                if (prescricao.error) {
                    showNotification(prescricao.error, 'error');
                    return;
                }

                const modal = document.getElementById('editar-prescricao-modal');
                const content = document.getElementById('editar-prescricao-content');

                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Medicamento</label>
                                <input type="text" class="form-input" value="${prescricao.medicamento}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dosagem</label>
                                <input type="text" class="form-input" value="${prescricao.dosagem}" placeholder="ex: 500mg" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Frequência</label>
                                <input type="text" class="form-input" value="${prescricao.frequencia}" placeholder="ex: 8/8 horas" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Duração</label>
                                <input type="text" class="form-input" value="${prescricao.duracao}" placeholder="ex: 7 dias" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Instruções</label>
                            <textarea class="form-input" rows="3" placeholder="Instruções de uso...">${prescricao.instrucoes || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Observações</label>
                            <textarea class="form-input" rows="3" placeholder="Observações adicionais...">${prescricao.observacoes || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Data de Validade</label>
                                <input type="date" class="form-input" value="${prescricao.data_validade}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prioridade</label>
                                <select class="form-select">
                                    <option value="baixa" ${prescricao.prioridade === 'baixa' ? 'selected' : ''}>Baixa</option>
                                    <option value="media" ${prescricao.prioridade === 'media' ? 'selected' : ''}>Média</option>
                                    <option value="alta" ${prescricao.prioridade === 'alta' ? 'selected' : ''}>Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <button onclick="closeEditarPrescricaoModal()" class="action-btn danger">Cancelar</button>
                            <button onclick="salvarPrescricao(${prescricaoId})" class="action-btn success">Salvar Alterações</button>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar formulário de edição:', error);
                showNotification('Erro ao carregar formulário de edição.', 'error');
            }
        }

        function novaPrescricao() {
            const modal = document.getElementById('nova-prescricao-modal');
            const content = document.getElementById('nova-prescricao-content');

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Paciente</label>
                        <select class="form-select" id="paciente-select">
                            <option value="">Selecione um paciente</option>
                            <!-- Pacientes serão carregados via JavaScript -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Medicamento</label>
                        <input type="text" class="form-input" id="medicamento-input" placeholder="Nome do medicamento" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label class="form-label">Dosagem</label>
                            <input type="text" class="form-input" placeholder="ex: 500mg" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Frequência</label>
                            <input type="text" class="form-input" placeholder="ex: 8/8 horas" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Duração</label>
                            <input type="text" class="form-input" placeholder="ex: 7 dias" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Instruções</label>
                        <textarea class="form-input" rows="3" placeholder="Instruções de uso..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-input" rows="3" placeholder="Observações adicionais..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Data de Validade</label>
                            <input type="date" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prioridade</label>
                            <select class="form-select">
                                <option value="baixa">Baixa</option>
                                <option value="media" selected>Média</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeNovaPrescricaoModal()" class="action-btn danger">Cancelar</button>
                        <button onclick="criarPrescricao()" class="action-btn success">Criar Prescrição</button>
                    </div>
                </div>
            `;

            modal.classList.add('show');
            carregarPacientesParaPrescricao();
        }

        async function carregarPacientesParaPrescricao() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/pacientes_lista'); ?>');
                const pacientes = await response.json();

                if (pacientes.error) {
                    showNotification(pacientes.error, 'error');
                    return;
                }

                const select = document.getElementById('paciente-select');
                select.innerHTML = '<option value="">Selecione um paciente</option>' +
                    pacientes.map(paciente => 
                        `<option value="${paciente.id}">${paciente.nome} - ${paciente.bi}</option>`
                    ).join('');

            } catch (error) {
                console.error('Erro ao carregar pacientes:', error);
            }
        }

        function criarPrescricao() {
            showNotification('Prescrição criada com sucesso!', 'success');
            closeNovaPrescricaoModal();
            loadPrescricoes();
        }

        function salvarPrescricao(prescricaoId) {
            showNotification('Prescrição atualizada com sucesso!', 'success');
            closeEditarPrescricaoModal();
            loadPrescricoes();
        }

        function suspenderPrescricao(prescricaoId) {
            if (confirm('Tem certeza que deseja suspender esta prescrição?')) {
                showNotification('Prescrição suspensa com sucesso!', 'success');
                loadPrescricoes();
            }
        }

        function ativarPrescricao(prescricaoId) {
            showNotification('Prescrição reativada com sucesso!', 'success');
            loadPrescricoes();
        }

        function cancelarPrescricao(prescricaoId) {
            if (confirm('Tem certeza que deseja cancelar esta prescrição?')) {
                showNotification('Prescrição cancelada com sucesso!', 'success');
                loadPrescricoes();
            }
        }

        function imprimirPrescricao() {
            window.print();
        }

        function exportPrescricoes() {
            showNotification('Exportando lista de prescrições...', 'info');
            // Implementar lógica de exportação
        }

        function closeModal() {
            document.getElementById('prescricao-modal').classList.remove('show');
        }

        function closeNovaPrescricaoModal() {
            document.getElementById('nova-prescricao-modal').classList.remove('show');
        }

        function closeEditarPrescricaoModal() {
            document.getElementById('editar-prescricao-modal').classList.remove('show');
        }

        // Filtros
        function filterByStatus(status) {
            currentFilter = status;
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`[data-filter="${status}"]`).classList.add('active');
            loadPrescricoes();
        }

        // Configurar filtros
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadPrescricoes();
                });
            });

            // Busca
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        currentSearch = this.value;
                        loadPrescricoes();
                    }, 500);
                });
            }
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            const notificationClose = document.getElementById('notification-close');
            if (notificationClose) {
                notificationClose.addEventListener('click', function() {
                    document.getElementById('notification').classList.remove('show');
                });
            }

            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (!mobileMenuBtn || !sidebarMenu || !closeSidebarBtn || !toggleSidebarBtn || !pageWrapper || !sidebarOverlay) {
                console.error('Um ou mais elementos do DOM não foram encontrados');
                return;
            }

            // Sidebar handlers
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.add('show');
                sidebarOverlay.classList.add('show');
                pageWrapper.classList.add('expanded');
            });

            closeSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });

            toggleSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.toggle('expanded');
                pageWrapper.classList.toggle('expanded');
            });

            sidebarOverlay.addEventListener('click', (e) => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });

            // Configurar filtros
            setupFilters();

            // Carregar dados iniciais
            loadStats();
            loadPrescricoes();

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('login'); ?>';
                    }, 1000);
                });
            }
        });
    </script>
</body>

</html>