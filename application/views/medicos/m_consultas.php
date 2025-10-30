<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Consultas - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de consultas médicas do Hospital Público de Matlhovele">
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

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-agendada {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-confirmada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-em-andamento {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-concluida {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-cancelada {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-falta {
            background-color: #f3f4f6;
            color: #6b7280;
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

        .consultation-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .consultation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .consultation-card.urgent {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }

        .consultation-card.return {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .consultation-card.emergency {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
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
            max-width: 500px;
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
                    <a href="<?php echo site_url('medico/consultas'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Minhas Consultas</h2>
                            <p class="text-gray-600">Gerencie e acompanhe todas as suas consultas médicas.</p>
                        </div>
                        <button onclick="novaConsulta()" class="action-btn flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Nova Consulta
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-lg p-4 text-center shadow">
                            <div class="text-2xl font-bold text-blue-600" id="total-hoje">0</div>
                            <div class="text-sm text-gray-600">Hoje</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center shadow">
                            <div class="text-2xl font-bold text-orange-600" id="total-agendadas">0</div>
                            <div class="text-sm text-gray-600">Agendadas</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center shadow">
                            <div class="text-2xl font-bold text-green-600" id="total-concluidas">0</div>
                            <div class="text-sm text-gray-600">Concluídas</div>
                        </div>
                        <div class="bg-white rounded-lg p-4 text-center shadow">
                            <div class="text-2xl font-bold text-red-600" id="total-canceladas">0</div>
                            <div class="text-sm text-gray-600">Canceladas</div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <div class="filter-tab active" data-filter="todas">Todas</div>
                            <div class="filter-tab" data-filter="hoje">Hoje</div>
                            <div class="filter-tab" data-filter="agendada">Agendadas</div>
                            <div class="filter-tab" data-filter="confirmada">Confirmadas</div>
                            <div class="filter-tab" data-filter="em-andamento">Em Andamento</div>
                            <div class="filter-tab" data-filter="concluida">Concluídas</div>
                            <div class="filter-tab" data-filter="cancelada">Canceladas</div>
                        </div>

                        <!-- Search and Date -->
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Pesquisar paciente..." 
                                       class="pl-10 pr-4 py-2 border rounded-lg w-full md:w-64">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <input type="date" id="date-filter" class="px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Consultations List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Lista de Consultas</h3>
                        
                        <div id="consultations-list">
                            <!-- Consultas serão carregadas via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando consultas...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-calendar-times"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma consulta encontrada</h3>
                            <p class="text-gray-600 mb-4">Não há consultas que correspondam aos seus critérios de busca.</p>
                            <button onclick="novaConsulta()" class="action-btn">
                                <i class="fas fa-plus mr-2"></i>Agendar Nova Consulta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Detalhes da Consulta -->
    <div id="consultation-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Detalhes da Consulta</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="consultation-details">
                <!-- Detalhes serão carregados via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'todas';
        let currentSearch = '';
        let currentDate = '';

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
                const response = await fetch('<?php echo site_url('api/medico/consultas_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-hoje').textContent = stats.hoje || 0;
                document.getElementById('total-agendadas').textContent = stats.agendadas || 0;
                document.getElementById('total-concluidas').textContent = stats.concluidas || 0;
                document.getElementById('total-canceladas').textContent = stats.canceladas || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar consultas
        async function loadConsultations() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('consultations-list');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todas') params.append('filter', currentFilter);
                if (currentSearch) params.append('search', currentSearch);
                if (currentDate) params.append('date', currentDate);

                const response = await fetch(`<?php echo site_url('api/medico/consultas'); ?>?${params}`);
                const consultas = await response.json();

                if (loading) loading.classList.add('hidden');

                if (consultas.error) {
                    showNotification(consultas.error, 'error');
                    return;
                }

                if (consultas.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderConsultations(consultas);

            } catch (error) {
                console.error('Erro ao carregar consultas:', error);
                showNotification('Erro ao carregar consultas.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar consultas
        function renderConsultations(consultas) {
            const list = document.getElementById('consultations-list');
            if (!list) return;

            list.innerHTML = consultas.map(consulta => {
                const cardClass = getCardClass(consulta);
                const statusClass = getStatusClass(consulta.status);
                const actions = getActions(consulta);

                return `
                    <div class="consultation-card ${cardClass}" data-consultation-id="${consulta.id}">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                    <h4 class="font-semibold text-gray-800 text-lg">${consulta.paciente_nome}</h4>
                                    <span class="status-badge ${statusClass}">${consulta.status}</span>
                                    ${consulta.urgente ? '<span class="status-badge status-emergency">URGENTE</span>' : ''}
                                    ${consulta.retorno ? '<span class="status-badge status-return">RETORNO</span>' : ''}
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Data/Hora:</span>
                                        <p class="font-medium">${consulta.data} às ${consulta.hora}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Motivo:</span>
                                        <p class="font-medium">${consulta.motivo}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Idade:</span>
                                        <p class="font-medium">${consulta.idade || 'N/A'} anos</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Telefone:</span>
                                        <p class="font-medium">${consulta.telefone || 'N/A'}</p>
                                    </div>
                                </div>

                                ${consulta.observacoes ? `
                                    <div class="mt-3">
                                        <span class="text-gray-600">Observações:</span>
                                        <p class="text-sm text-gray-700 mt-1">${consulta.observacoes}</p>
                                    </div>
                                ` : ''}
                            </div>

                            <div class="flex flex-col gap-2">
                                ${actions}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Adicionar event listeners aos botões
            addEventListeners();
        }

        // Obter classe CSS do card baseado no tipo de consulta
        function getCardClass(consulta) {
            if (consulta.urgente) return 'urgent';
            if (consulta.retorno) return 'return';
            if (consulta.tipo === 'emergencia') return 'emergency';
            return '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'agendada': 'status-agendada',
                'confirmada': 'status-confirmada',
                'em-andamento': 'status-em-andamento',
                'concluida': 'status-concluida',
                'cancelada': 'status-cancelada',
                'falta': 'status-falta'
            };
            return statusMap[status] || 'status-agendada';
        }

        // Obter ações disponíveis baseado no status
        function getActions(consulta) {
            const actions = [];
            
            if (['agendada', 'confirmada'].includes(consulta.status)) {
                actions.push(`
                    <button class="action-btn success iniciar-consulta" data-id="${consulta.id}">
                        <i class="fas fa-play mr-1"></i>Iniciar
                    </button>
                `);
            }

            if (consulta.status === 'em-andamento') {
                actions.push(`
                    <button class="action-btn success finalizar-consulta" data-id="${consulta.id}">
                        <i class="fas fa-check mr-1"></i>Finalizar
                    </button>
                `);
            }

            actions.push(`
                <button class="action-btn ver-detalhes" data-id="${consulta.id}">
                    <i class="fas fa-eye mr-1"></i>Detalhes
                </button>
            `);

            if (['agendada', 'confirmada'].includes(consulta.status)) {
                actions.push(`
                    <button class="action-btn danger cancelar-consulta" data-id="${consulta.id}">
                        <i class="fas fa-times mr-1"></i>Cancelar
                    </button>
                `);
            }

            return actions.join('');
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Iniciar consulta
            document.querySelectorAll('.iniciar-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const consultaId = e.target.closest('button').dataset.id;
                    iniciarConsulta(consultaId);
                });
            });

            // Finalizar consulta
            document.querySelectorAll('.finalizar-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const consultaId = e.target.closest('button').dataset.id;
                    finalizarConsulta(consultaId);
                });
            });

            // Ver detalhes
            document.querySelectorAll('.ver-detalhes').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const consultaId = e.target.closest('button').dataset.id;
                    verDetalhesConsulta(consultaId);
                });
            });

            // Cancelar consulta
            document.querySelectorAll('.cancelar-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const consultaId = e.target.closest('button').dataset.id;
                    cancelarConsulta(consultaId);
                });
            });
        }

        // Funções de ação
        function iniciarConsulta(consultaId) {
            showNotification('Iniciando consulta...', 'info');
            window.location.href = `<?php echo site_url('medico/consulta/'); ?>${consultaId}`;
        }

        function finalizarConsulta(consultaId) {
            showNotification('Finalizando consulta...', 'info');
            // Implementar lógica de finalização
        }

        async function verDetalhesConsulta(consultaId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/consulta/'); ?>${consultaId}`);
                const consulta = await response.json();

                if (consulta.error) {
                    showNotification(consulta.error, 'error');
                    return;
                }

                const modal = document.getElementById('consultation-modal');
                const details = document.getElementById('consultation-details');

                details.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-medium text-gray-700">Paciente:</label>
                                <p>${consulta.paciente_nome}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700">Status:</label>
                                <span class="status-badge ${getStatusClass(consulta.status)}">${consulta.status}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-medium text-gray-700">Data:</label>
                                <p>${consulta.data}</p>
                            </div>
                            <div>
                                <label class="font-medium text-gray-700">Hora:</label>
                                <p>${consulta.hora}</p>
                            </div>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Motivo:</label>
                            <p>${consulta.motivo}</p>
                        </div>
                        ${consulta.observacoes ? `
                            <div>
                                <label class="font-medium text-gray-700">Observações:</label>
                                <p>${consulta.observacoes}</p>
                            </div>
                        ` : ''}
                        ${consulta.historico ? `
                            <div>
                                <label class="font-medium text-gray-700">Histórico:</label>
                                <p>${consulta.historico}</p>
                            </div>
                        ` : ''}
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar detalhes:', error);
                showNotification('Erro ao carregar detalhes da consulta.', 'error');
            }
        }

        function cancelarConsulta(consultaId) {
            if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
                showNotification('Cancelando consulta...', 'info');
                // Implementar lógica de cancelamento
            }
        }

        function closeModal() {
            document.getElementById('consultation-modal').classList.remove('show');
        }

        function novaConsulta() {
            window.location.href = '<?php echo site_url('medico/nova_consulta'); ?>';
        }

        // Filtros e busca
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadConsultations();
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
                        loadConsultations();
                    }, 500);
                });
            }

            // Filtro por data
            const dateFilter = document.getElementById('date-filter');
            if (dateFilter) {
                dateFilter.addEventListener('change', function() {
                    currentDate = this.value;
                    loadConsultations();
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
            loadConsultations();

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