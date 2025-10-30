<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pacientes - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de pacientes do médico no Hospital Público de Matlhovele">
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

        .patient-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .patient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .patient-card.urgent {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }

        .patient-card.follow-up {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .patient-card.control {
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
            max-width: 600px;
            width: 90%;
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
                    <a href="<?php echo site_url('medico/pacientes'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Meus Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('medico/prontuarios'); ?>" class="block text-gray-700">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Meus Pacientes</h2>
                            <p class="text-gray-600">Gerencie e acompanhe o histórico dos seus pacientes.</p>
                        </div>
                        <button onclick="novoPaciente()" class="action-btn flex items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Novo Paciente
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="metric-card" onclick="filterByStatus('active')">
                            <div class="text-2xl font-bold text-blue-600" id="total-pacientes">0</div>
                            <div class="text-sm text-gray-600">Total de Pacientes</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('high-risk')">
                            <div class="text-2xl font-bold text-red-600" id="pacientes-risco">0</div>
                            <div class="text-sm text-gray-600">Alto Risco</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('follow-up')">
                            <div class="text-2xl font-bold text-orange-600" id="pacientes-acompanhamento">0</div>
                            <div class="text-sm text-gray-600">Em Acompanhamento</div>
                        </div>
                        <div class="metric-card" onclick="filterByLastVisit()">
                            <div class="text-2xl font-bold text-purple-600" id="consultas-mes">0</div>
                            <div class="text-sm text-gray-600">Consultas/Mês</div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <div class="filter-tab active" data-filter="todos">Todos os Pacientes</div>
                            <div class="filter-tab" data-filter="ativos">Ativos</div>
                            <div class="filter-tab" data-filter="alto-risco">Alto Risco</div>
                            <div class="filter-tab" data-filter="acompanhamento">Em Acompanhamento</div>
                            <div class="filter-tab" data-filter="inativos">Inativos</div>
                        </div>

                        <!-- Search -->
                        <div class="flex gap-3 w-full md:w-auto">
                            <div class="relative flex-1 md:w-64">
                                <input type="text" id="search-input" placeholder="Pesquisar paciente..." 
                                       class="pl-10 pr-4 py-2 border rounded-lg w-full">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button onclick="exportPacientes()" class="action-btn warning flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Patients List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Pacientes</h3>
                            <div class="text-sm text-gray-600">
                                <span id="patients-count">0</span> pacientes encontrados
                            </div>
                        </div>
                        
                        <div id="patients-list">
                            <!-- Pacientes serão carregados via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando pacientes...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-user-slash"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum paciente encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há pacientes que correspondam aos seus critérios de busca.</p>
                            <button onclick="novoPaciente()" class="action-btn">
                                <i class="fas fa-user-plus mr-2"></i>Cadastrar Novo Paciente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Detalhes do Paciente -->
    <div id="patient-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Detalhes do Paciente</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="patient-details">
                <!-- Detalhes serão carregados via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Prontuário -->
    <div id="prontuario-modal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Prontuário Médico</h3>
                <button onclick="closeProntuarioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="prontuario-content">
                <!-- Conteúdo do prontuário será carregado via JavaScript -->
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
                const response = await fetch('<?php echo site_url('api/medico/pacientes_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-pacientes').textContent = stats.total || 0;
                document.getElementById('pacientes-risco').textContent = stats.alto_risco || 0;
                document.getElementById('pacientes-acompanhamento').textContent = stats.acompanhamento || 0;
                document.getElementById('consultas-mes').textContent = stats.consultas_mes || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar pacientes
        async function loadPatients() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('patients-list');
            const count = document.getElementById('patients-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todos') params.append('filter', currentFilter);
                if (currentSearch) params.append('search', currentSearch);

                const response = await fetch(`<?php echo site_url('api/medico/pacientes'); ?>?${params}`);
                const pacientes = await response.json();

                if (loading) loading.classList.add('hidden');

                if (pacientes.error) {
                    showNotification(pacientes.error, 'error');
                    return;
                }

                if (count) count.textContent = pacientes.length || 0;

                if (pacientes.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderPatients(pacientes);

            } catch (error) {
                console.error('Erro ao carregar pacientes:', error);
                showNotification('Erro ao carregar pacientes.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar pacientes
        function renderPatients(pacientes) {
            const list = document.getElementById('patients-list');
            if (!list) return;

            list.innerHTML = pacientes.map(paciente => {
                const cardClass = getCardClass(paciente);
                const statusClass = getStatusClass(paciente.status);
                const initials = getInitials(paciente.nome);

                return `
                    <div class="patient-card ${cardClass}" data-patient-id="${paciente.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Avatar e Info Básica -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="patient-avatar">
                                    ${initials}
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${paciente.nome}</h4>
                                        <span class="status-badge ${statusClass}">${paciente.status}</span>
                                        ${paciente.alto_risco ? '<span class="status-badge status-high-risk">ALTO RISCO</span>' : ''}
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Idade:</span>
                                            <p class="font-medium">${paciente.idade || 'N/A'} anos</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">BI:</span>
                                            <p class="font-medium">${paciente.bi || 'N/A'}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Telefone:</span>
                                            <p class="font-medium">${paciente.telefone || 'N/A'}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-3">
                                        <div>
                                            <span class="text-gray-600">Última Consulta:</span>
                                            <p class="font-medium">${paciente.ultima_consulta || 'Nunca'}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Próxima Consulta:</span>
                                            <p class="font-medium">${paciente.proxima_consulta || 'Não agendada'}</p>
                                        </div>
                                    </div>

                                    ${paciente.condicao ? `
                                        <div class="mt-3">
                                            <span class="text-gray-600">Condição Principal:</span>
                                            <p class="text-sm text-gray-700 mt-1">${paciente.condicao}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                <button class="action-btn ver-prontuario" data-id="${paciente.id}">
                                    <i class="fas fa-file-medical mr-1"></i>Prontuário
                                </button>
                                <button class="action-btn ver-detalhes" data-id="${paciente.id}">
                                    <i class="fas fa-eye mr-1"></i>Detalhes
                                </button>
                                <button class="action-btn success nova-consulta" data-id="${paciente.id}">
                                    <i class="fas fa-calendar-plus mr-1"></i>Nova Consulta
                                </button>
                                ${paciente.alto_risco ? `
                                    <button class="action-btn warning acompanhamento" data-id="${paciente.id}">
                                        <i class="fas fa-heartbeat mr-1"></i>Acompanhamento
                                    </button>
                                ` : ''}
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

        // Obter classe CSS do card baseado no paciente
        function getCardClass(paciente) {
            if (paciente.alto_risco) return 'urgent';
            if (paciente.status === 'acompanhamento') return 'follow-up';
            if (paciente.status === 'control') return 'control';
            return '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'ativo': 'status-active',
                'inativo': 'status-inactive',
                'acompanhamento': 'status-follow-up',
                'alto-risco': 'status-high-risk'
            };
            return statusMap[status] || 'status-active';
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Ver detalhes
            document.querySelectorAll('.ver-detalhes').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const pacienteId = e.target.closest('button').dataset.id;
                    verDetalhesPaciente(pacienteId);
                });
            });

            // Ver prontuário
            document.querySelectorAll('.ver-prontuario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const pacienteId = e.target.closest('button').dataset.id;
                    verProntuario(pacienteId);
                });
            });

            // Nova consulta
            document.querySelectorAll('.nova-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const pacienteId = e.target.closest('button').dataset.id;
                    novaConsultaPaciente(pacienteId);
                });
            });

            // Acompanhamento
            document.querySelectorAll('.acompanhamento').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const pacienteId = e.target.closest('button').dataset.id;
                    verAcompanhamento(pacienteId);
                });
            });
        }

        // Funções de ação
        async function verDetalhesPaciente(pacienteId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/paciente/'); ?>${pacienteId}`);
                const paciente = await response.json();

                if (paciente.error) {
                    showNotification(paciente.error, 'error');
                    return;
                }

                const modal = document.getElementById('patient-modal');
                const details = document.getElementById('patient-details');

                details.innerHTML = `
                    <div class="space-y-6">
                        <!-- Informações Pessoais -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Informações Pessoais</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nome Completo</span>
                                    <span class="info-value">${paciente.nome}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">BI/Identificação</span>
                                    <span class="info-value">${paciente.bi || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Data de Nascimento</span>
                                    <span class="info-value">${paciente.data_nascimento || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Idade</span>
                                    <span class="info-value">${paciente.idade || 'N/A'} anos</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Gênero</span>
                                    <span class="info-value">${paciente.genero || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Telefone</span>
                                    <span class="info-value">${paciente.telefone || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">${paciente.email || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Endereço</span>
                                    <span class="info-value">${paciente.endereco || 'N/A'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Médicas -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Informações Médicas</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Tipo Sanguíneo</span>
                                    <span class="info-value">${paciente.tipo_sanguineo || 'N/A'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Alergias</span>
                                    <span class="info-value">${paciente.alergias || 'Nenhuma'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Medicações</span>
                                    <span class="info-value">${paciente.medicacoes || 'Nenhuma'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Condições Crônicas</span>
                                    <span class="info-value">${paciente.condicoes_cronicas || 'Nenhuma'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Estatísticas -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Estatísticas</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Total de Consultas</span>
                                    <span class="info-value">${paciente.total_consultas || 0}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Última Consulta</span>
                                    <span class="info-value">${paciente.ultima_consulta || 'Nunca'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Próxima Consulta</span>
                                    <span class="info-value">${paciente.proxima_consulta || 'Não agendada'}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span class="status-badge ${getStatusClass(paciente.status)}">${paciente.status}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar detalhes:', error);
                showNotification('Erro ao carregar detalhes do paciente.', 'error');
            }
        }

        async function verProntuario(pacienteId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/prontuario/'); ?>${pacienteId}`);
                const prontuario = await response.json();

                if (prontuario.error) {
                    showNotification(prontuario.error, 'error');
                    return;
                }

                const modal = document.getElementById('prontuario-modal');
                const content = document.getElementById('prontuario-content');

                content.innerHTML = `
                    <div class="space-y-6">
                        <!-- Header do Prontuário -->
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-semibold text-gray-800">${prontuario.paciente_nome}</h4>
                            <p class="text-gray-600">BI: ${prontuario.paciente_bi} | Idade: ${prontuario.idade} anos</p>
                        </div>

                        <!-- Histórico de Consultas -->
                        <div>
                            <h5 class="font-medium text-gray-700 mb-3">Histórico de Consultas</h5>
                            ${prontuario.consultas && prontuario.consultas.length > 0 ? 
                                prontuario.consultas.map(consulta => `
                                    <div class="border rounded p-3 mb-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <strong>${consulta.data}</strong> - ${consulta.motivo}
                                            </div>
                                            <span class="status-badge ${getStatusClass(consulta.status)}">${consulta.status}</span>
                                        </div>
                                        ${consulta.diagnostico ? `<p class="text-sm mt-2"><strong>Diagnóstico:</strong> ${consulta.diagnostico}</p>` : ''}
                                        ${consulta.prescricao ? `<p class="text-sm mt-1"><strong>Prescrição:</strong> ${consulta.prescricao}</p>` : ''}
                                    </div>
                                `).join('') : 
                                '<p class="text-gray-600">Nenhuma consulta registrada.</p>'
                            }
                        </div>

                        <!-- Exames -->
                        <div>
                            <h5 class="font-medium text-gray-700 mb-3">Exames Realizados</h5>
                            ${prontuario.exames && prontuario.exames.length > 0 ? 
                                prontuario.exames.map(exame => `
                                    <div class="border rounded p-3 mb-2">
                                        <div class="flex justify-between items-center">
                                            <strong>${exame.tipo}</strong>
                                            <span class="text-sm text-gray-600">${exame.data}</span>
                                        </div>
                                        ${exame.resultado ? `<p class="text-sm mt-2"><strong>Resultado:</strong> ${exame.resultado}</p>` : ''}
                                    </div>
                                `).join('') : 
                                '<p class="text-gray-600">Nenhum exame registrado.</p>'
                            }
                        </div>

                        <!-- Observações Gerais -->
                        <div>
                            <h5 class="font-medium text-gray-700 mb-3">Observações Gerais</h5>
                            <textarea class="w-full border rounded p-3" rows="4" placeholder="Adicionar observações...">${prontuario.observacoes || ''}</textarea>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2 justify-end">
                            <button class="action-btn">Salvar Observações</button>
                            <button class="action-btn success">Imprimir Prontuário</button>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar prontuário:', error);
                showNotification('Erro ao carregar prontuário.', 'error');
            }
        }

        function novaConsultaPaciente(pacienteId) {
            showNotification('Abrindo agendamento de consulta...', 'info');
            window.location.href = `<?php echo site_url('medico/nova_consulta/'); ?>${pacienteId}`;
        }

        function verAcompanhamento(pacienteId) {
            showNotification('Abrindo acompanhamento...', 'info');
            window.location.href = `<?php echo site_url('medico/acompanhamento/'); ?>${pacienteId}`;
        }

        function closeModal() {
            document.getElementById('patient-modal').classList.remove('show');
        }

        function closeProntuarioModal() {
            document.getElementById('prontuario-modal').classList.remove('show');
        }

        function novoPaciente() {
            window.location.href = '<?php echo site_url('medico/novo_paciente'); ?>';
        }

        function exportPacientes() {
            showNotification('Exportando lista de pacientes...', 'info');
            // Implementar lógica de exportação
        }

        // Filtros
        function filterByStatus(status) {
            currentFilter = status;
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`[data-filter="${status}"]`).classList.add('active');
            loadPatients();
        }

        function filterByLastVisit() {
            currentFilter = 'ultima-visita';
            loadPatients();
        }

        // Configurar filtros
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadPatients();
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
                        loadPatients();
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
            loadPatients();

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