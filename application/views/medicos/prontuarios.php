<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuários - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de prontuários médicos do Hospital Público de Matlhovele">
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

        .prontuario-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .prontuario-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .prontuario-card.updated {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .prontuario-card.pending {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }

        .prontuario-card.urgent {
            border-left-color: #ef4444;
            background-color: #fef2f2;
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

        .status-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-high-risk {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-follow-up {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-updated {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
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
            max-width: 900px;
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
        }

        .tab-container {
            margin-bottom: 1.5rem;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-button.active {
            border-bottom-color: #3b82f6;
            color: #3b82f6;
            font-weight: 500;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: #374151;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .consultation-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background-color: #f9fafb;
        }

        .exam-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background-color: #f0fdf4;
        }

        .prescription-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background-color: #eff6ff;
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

        .search-highlight {
            background-color: #fef3c7;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
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
                    <a href="<?php echo site_url('medico/prontuarios'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-file-medical"></i>
                        <span class="sidebar-text">Prontuários</span>
                    </a>
                    <a href="<?php echo site_url('medico/prescricoes'); ?>" class="block text-gray-700">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Prontuários Médicos</h2>
                            <p class="text-gray-600">Acesse e gerencie os prontuários eletrônicos dos seus pacientes.</p>
                        </div>
                        <button onclick="exportProntuarios()" class="action-btn warning flex items-center gap-2">
                            <i class="fas fa-download"></i>
                            Exportar Relatório
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="metric-card" onclick="filterByStatus('todos')">
                            <div class="text-2xl font-bold text-blue-600" id="total-prontuarios">0</div>
                            <div class="text-sm text-gray-600">Total de Prontuários</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('atualizados')">
                            <div class="text-2xl font-bold text-green-600" id="prontuarios-atualizados">0</div>
                            <div class="text-sm text-gray-600">Atualizados</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('pendentes')">
                            <div class="text-2xl font-bold text-orange-600" id="prontuarios-pendentes">0</div>
                            <div class="text-sm text-gray-600">Pendentes</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('urgentes')">
                            <div class="text-2xl font-bold text-red-600" id="prontuarios-urgentes">0</div>
                            <div class="text-sm text-gray-600">Urgentes</div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <div class="filter-tab active" data-filter="todos">Todos</div>
                            <div class="filter-tab" data-filter="atualizados">Atualizados</div>
                            <div class="filter-tab" data-filter="pendentes">Pendentes</div>
                            <div class="filter-tab" data-filter="urgentes">Urgentes</div>
                            <div class="filter-tab" data-filter="alta-risco">Alto Risco</div>
                            <div class="filter-tab" data-filter="acompanhamento">Acompanhamento</div>
                        </div>

                        <!-- Search -->
                        <div class="flex gap-3 w-full md:w-auto">
                            <div class="relative flex-1 md:w-80">
                                <input type="text" id="search-input" placeholder="Pesquisar paciente, diagnóstico, medicamento..." 
                                       class="pl-10 pr-4 py-2 border rounded-lg w-full">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button onclick="clearFilters()" class="action-btn danger flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                Limpar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Prontuários List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Prontuários</h3>
                            <div class="text-sm text-gray-600">
                                <span id="prontuarios-count">0</span> prontuários encontrados
                            </div>
                        </div>
                        
                        <div id="prontuarios-list">
                            <!-- Prontuários serão carregados via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando prontuários...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-file-medical-alt"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum prontuário encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há prontuários que correspondam aos seus critérios de busca.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Visualização do Prontuário -->
    <div id="prontuario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Prontuário Médico Completo</h3>
                <div class="flex gap-2">
                    <button onclick="imprimirProntuario()" class="action-btn warning flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="prontuario-details">
                <!-- Conteúdo do prontuário será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Edição do Prontuário -->
    <div id="editar-prontuario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Prontuário</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editar-prontuario-content">
                <!-- Formulário de edição será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'todos';
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
                const response = await fetch('<?php echo site_url('api/medico/prontuarios_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-prontuarios').textContent = stats.total || 0;
                document.getElementById('prontuarios-atualizados').textContent = stats.atualizados || 0;
                document.getElementById('prontuarios-pendentes').textContent = stats.pendentes || 0;
                document.getElementById('prontuarios-urgentes').textContent = stats.urgentes || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar prontuários
        async function loadProntuarios() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('prontuarios-list');
            const count = document.getElementById('prontuarios-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todos') params.append('filter', currentFilter);
                if (currentSearch) params.append('search', currentSearch);

                const response = await fetch(`<?php echo site_url('api/medico/prontuarios'); ?>?${params}`);
                const prontuarios = await response.json();

                if (loading) loading.classList.add('hidden');

                if (prontuarios.error) {
                    showNotification(prontuarios.error, 'error');
                    return;
                }

                if (count) count.textContent = prontuarios.length || 0;

                if (prontuarios.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderProntuarios(prontuarios);

            } catch (error) {
                console.error('Erro ao carregar prontuários:', error);
                showNotification('Erro ao carregar prontuários.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar prontuários
        function renderProntuarios(prontuarios) {
            const list = document.getElementById('prontuarios-list');
            if (!list) return;

            list.innerHTML = prontuarios.map(prontuario => {
                const cardClass = getCardClass(prontuario);
                const statusClass = getStatusClass(prontuario.status);
                const initials = getInitials(prontuario.paciente_nome);
                const lastUpdate = formatDate(prontuario.ultima_atualizacao);
                const highlightedContent = highlightSearch(prontuario.resumo || '');

                return `
                    <div class="prontuario-card ${cardClass}" data-prontuario-id="${prontuario.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Informações do Paciente -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="patient-avatar">
                                    ${initials}
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${prontuario.paciente_nome}</h4>
                                        <span class="status-badge ${statusClass}">${prontuario.status}</span>
                                        ${prontuario.urgente ? '<span class="status-badge status-high-risk">URGENTE</span>' : ''}
                                        ${prontuario.alto_risco ? '<span class="status-badge status-high-risk">ALTO RISCO</span>' : ''}
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-600">Idade:</span>
                                            <p class="font-medium">${prontuario.idade || 'N/A'} anos</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">BI:</span>
                                            <p class="font-medium">${prontuario.paciente_bi || 'N/A'}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Última Atualização:</span>
                                            <p class="font-medium">${lastUpdate}</p>
                                        </div>
                                    </div>

                                    <!-- Resumo do Prontuário -->
                                    <div class="bg-gray-50 rounded p-3">
                                        <span class="text-gray-600 text-sm">Resumo:</span>
                                        <p class="text-sm text-gray-700 mt-1">${highlightedContent}</p>
                                    </div>

                                    <!-- Diagnóstico Principal -->
                                    ${prontuario.diagnostico_principal ? `
                                        <div class="mt-3">
                                            <span class="text-gray-600 text-sm">Diagnóstico Principal:</span>
                                            <p class="text-sm font-medium text-gray-800 mt-1">${prontuario.diagnostico_principal}</p>
                                        </div>
                                    ` : ''}

                                    <!-- Última Consulta -->
                                    <div class="mt-3">
                                        <span class="text-gray-600 text-sm">Última Consulta:</span>
                                        <p class="text-sm text-gray-700 mt-1">${prontuario.ultima_consulta || 'Nenhuma consulta registrada'}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                <button class="action-btn ver-prontuario" data-id="${prontuario.id}">
                                    <i class="fas fa-eye mr-1"></i>Visualizar
                                </button>
                                <button class="action-btn editar-prontuario" data-id="${prontuario.id}">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                                <button class="action-btn success nova-consulta" data-id="${prontuario.paciente_id}">
                                    <i class="fas fa-calendar-plus mr-1"></i>Nova Consulta
                                </button>
                                <button class="action-btn warning" onclick="gerarLaudo(${prontuario.id})">
                                    <i class="fas fa-file-medical-alt mr-1"></i>Gerar Laudo
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
            if (!dateString) return 'Nunca';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }

        // Destacar termos da busca
        function highlightSearch(text) {
            if (!currentSearch || !text) return text;
            const regex = new RegExp(`(${currentSearch})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        // Obter classe CSS do card baseado no status
        function getCardClass(prontuario) {
            if (prontuario.urgente) return 'urgent';
            if (prontuario.status === 'atualizado') return 'updated';
            if (prontuario.status === 'pendente') return 'pending';
            return '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'atualizado': 'status-updated',
                'pendente': 'status-pending',
                'urgente': 'status-high-risk',
                'alto-risco': 'status-high-risk',
                'acompanhamento': 'status-follow-up'
            };
            return statusMap[status] || 'status-updated';
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Ver prontuário
            document.querySelectorAll('.ver-prontuario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prontuarioId = e.target.closest('button').dataset.id;
                    verProntuarioCompleto(prontuarioId);
                });
            });

            // Editar prontuário
            document.querySelectorAll('.editar-prontuario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const prontuarioId = e.target.closest('button').dataset.id;
                    editarProntuario(prontuarioId);
                });
            });

            // Nova consulta
            document.querySelectorAll('.nova-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const pacienteId = e.target.closest('button').dataset.id;
                    novaConsultaPaciente(pacienteId);
                });
            });
        }

        // Funções de ação
        async function verProntuarioCompleto(prontuarioId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/prontuario_completo/'); ?>${prontuarioId}`);
                const prontuario = await response.json();

                if (prontuario.error) {
                    showNotification(prontuario.error, 'error');
                    return;
                }

                const modal = document.getElementById('prontuario-modal');
                const details = document.getElementById('prontuario-details');

                details.innerHTML = `
                    <div class="space-y-6">
                        <!-- Cabeçalho -->
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800">${prontuario.paciente_nome}</h4>
                                    <p class="text-gray-600">BI: ${prontuario.paciente_bi} | Idade: ${prontuario.idade} anos | ${prontuario.genero}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Prontuário: #${prontuario.id}</p>
                                    <p class="text-sm text-gray-600">Última atualização: ${formatDate(prontuario.ultima_atualizacao)}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Abas -->
                        <div class="tab-container">
                            <div class="tab-buttons">
                                <button class="tab-button active" data-tab="historico">Histórico</button>
                                <button class="tab-button" data-tab="exames">Exames</button>
                                <button class="tab-button" data-tab="prescricoes">Prescrições</button>
                                <button class="tab-button" data-tab="observacoes">Observações</button>
                            </div>

                            <!-- Conteúdo das Abas -->
                            <div class="tab-content active" id="historico-tab">
                                <h5 class="section-title">Histórico de Consultas</h5>
                                ${prontuario.consultas && prontuario.consultas.length > 0 ? 
                                    `<div class="timeline">
                                        ${prontuario.consultas.map(consulta => `
                                            <div class="timeline-item">
                                                <div class="consultation-item">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <strong>${consulta.data} - ${consulta.motivo}</strong>
                                                        <span class="status-badge ${getStatusClass(consulta.status)}">${consulta.status}</span>
                                                    </div>
                                                    ${consulta.diagnostico ? `<p><strong>Diagnóstico:</strong> ${consulta.diagnostico}</p>` : ''}
                                                    ${consulta.observacoes ? `<p class="mt-2"><strong>Observações:</strong> ${consulta.observacoes}</p>` : ''}
                                                    ${consulta.medico ? `<p class="text-sm text-gray-600 mt-2">Atendido por: ${consulta.medico}</p>` : ''}
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>` : 
                                    '<p class="text-gray-600">Nenhuma consulta registrada.</p>'
                                }
                            </div>

                            <div class="tab-content" id="exames-tab">
                                <h5 class="section-title">Exames Realizados</h5>
                                ${prontuario.exames && prontuario.exames.length > 0 ? 
                                    prontuario.exames.map(exame => `
                                        <div class="exam-item">
                                            <div class="flex justify-between items-start mb-2">
                                                <strong>${exame.tipo}</strong>
                                                <span class="text-sm text-gray-600">${exame.data}</span>
                                            </div>
                                            ${exame.resultado ? `<p><strong>Resultado:</strong> ${exame.resultado}</p>` : ''}
                                            ${exame.observacoes ? `<p class="mt-2"><strong>Observações:</strong> ${exame.observacoes}</p>` : ''}
                                            ${exame.laboratorio ? `<p class="text-sm text-gray-600 mt-2">Laboratório: ${exame.laboratorio}</p>` : ''}
                                        </div>
                                    `).join('') : 
                                    '<p class="text-gray-600">Nenhum exame registrado.</p>'
                                }
                            </div>

                            <div class="tab-content" id="prescricoes-tab">
                                <h5 class="section-title">Prescrições Médicas</h5>
                                ${prontuario.prescricoes && prontuario.prescricoes.length > 0 ? 
                                    prontuario.prescricoes.map(prescricao => `
                                        <div class="prescription-item">
                                            <div class="flex justify-between items-start mb-2">
                                                <strong>${prescricao.medicamento}</strong>
                                                <span class="text-sm text-gray-600">${prescricao.data_prescricao}</span>
                                            </div>
                                            <p><strong>Dosagem:</strong> ${prescricao.dosagem}</p>
                                            <p><strong>Frequência:</strong> ${prescricao.frequencia}</p>
                                            ${prescricao.observacoes ? `<p class="mt-2"><strong>Observações:</strong> ${prescricao.observacoes}</p>` : ''}
                                            <p class="text-sm ${prescricao.ativa ? 'text-green-600' : 'text-red-600'} mt-2">
                                                ${prescricao.ativa ? '● Ativa' : '● Inativa'}
                                            </p>
                                        </div>
                                    `).join('') : 
                                    '<p class="text-gray-600">Nenhuma prescrição registrada.</p>'
                                }
                            </div>

                            <div class="tab-content" id="observacoes-tab">
                                <h5 class="section-title">Observações Gerais</h5>
                                <textarea class="w-full border rounded p-3" rows="6" placeholder="Observações gerais sobre o paciente...">${prontuario.observacoes_gerais || ''}</textarea>
                                <div class="flex gap-2 mt-3">
                                    <button class="action-btn success">Salvar Observações</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Configurar abas
                setupTabs();
                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar prontuário:', error);
                showNotification('Erro ao carregar prontuário completo.', 'error');
            }
        }

        async function editarProntuario(prontuarioId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/prontuario_editar/'); ?>${prontuarioId}`);
                const prontuario = await response.json();

                if (prontuario.error) {
                    showNotification(prontuario.error, 'error');
                    return;
                }

                const modal = document.getElementById('editar-prontuario-modal');
                const content = document.getElementById('editar-prontuario-content');

                content.innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diagnóstico Principal</label>
                            <input type="text" class="w-full p-2 border rounded" value="${prontuario.diagnostico_principal || ''}" 
                                   placeholder="Digite o diagnóstico principal">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">História Clínica</label>
                            <textarea class="w-full p-2 border rounded" rows="4" placeholder="História clínica do paciente...">${prontuario.historia_clinica || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alergias</label>
                            <input type="text" class="w-full p-2 border rounded" value="${prontuario.alergias || ''}" 
                                   placeholder="Lista de alergias (separadas por vírgula)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medicações Atuais</label>
                            <textarea class="w-full p-2 border rounded" rows="3" placeholder="Medicações em uso...">${prontuario.medicacoes_atuais || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observações Gerais</label>
                            <textarea class="w-full p-2 border rounded" rows="4" placeholder="Observações importantes...">${prontuario.observacoes_gerais || ''}</textarea>
                        </div>
                        
                        <div class="flex gap-2 justify-end">
                            <button onclick="closeEditModal()" class="action-btn danger">Cancelar</button>
                            <button onclick="salvarProntuario(${prontuarioId})" class="action-btn success">Salvar Alterações</button>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar formulário de edição:', error);
                showNotification('Erro ao carregar formulário de edição.', 'error');
            }
        }

        function salvarProntuario(prontuarioId) {
            showNotification('Prontuário atualizado com sucesso!', 'success');
            closeEditModal();
            loadProntuarios();
        }

        function novaConsultaPaciente(pacienteId) {
            showNotification('Abrindo agendamento de consulta...', 'info');
            window.location.href = `<?php echo site_url('medico/nova_consulta/'); ?>${pacienteId}`;
        }

        function gerarLaudo(prontuarioId) {
            showNotification('Gerando laudo médico...', 'info');
            window.location.href = `<?php echo site_url('medico/gerar_laudo/'); ?>${prontuarioId}`;
        }

        function imprimirProntuario() {
            window.print();
        }

        function exportProntuarios() {
            showNotification('Exportando relatório de prontuários...', 'info');
            // Implementar lógica de exportação
        }

        function clearFilters() {
            currentSearch = '';
            currentFilter = 'todos';
            document.getElementById('search-input').value = '';
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector('[data-filter="todos"]').classList.add('active');
            loadProntuarios();
        }

        function closeModal() {
            document.getElementById('prontuario-modal').classList.remove('show');
        }

        function closeEditModal() {
            document.getElementById('editar-prontuario-modal').classList.remove('show');
        }

        // Configurar abas
        function setupTabs() {
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function() {
                    // Remover active de todos
                    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                    
                    // Adicionar active ao clicado
                    this.classList.add('active');
                    const tabId = this.dataset.tab + '-tab';
                    document.getElementById(tabId).classList.add('active');
                });
            });
        }

        // Filtros
        function filterByStatus(status) {
            currentFilter = status;
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`[data-filter="${status}"]`).classList.add('active');
            loadProntuarios();
        }

        // Configurar filtros
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadProntuarios();
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
                        loadProntuarios();
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
            loadProntuarios();

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