<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Painel de gerenciamento para secretários do Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .kpi-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .kpi-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .trend-up {
            color: #10b981;
        }

        .trend-down {
            color: #ef4444;
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

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-cancelled {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .chart-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 300px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .data-table tr:hover {
            background-color: #f9fafb;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .quick-action-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #3b82f6;
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
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
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

        .form-textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            min-height: 100px;
            resize: vertical;
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

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
            background-color: white;
        }

        .filter-tab.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .filter-tab:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .loading-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            border-radius: 0.5rem;
            z-index: 10;
        }

        .loading-overlay.show {
            display: flex;
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
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Secretário</h2>
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
                    <a href="<?php echo site_url('secretario'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="<?php echo site_url('secretario/agendamentos'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('secretario/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('secretario/medicos'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="<?php echo site_url('secretario/relatorios'); ?>" class="block text-gray-700">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="<?php echo site_url('secretario/configuracoes'); ?>" class="block text-gray-700">
                        <i class="fas fa-cog"></i>
                        <span class="sidebar-text">Configurações</span>
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
                        <p class="text-sm">Secretário</p>
                        <p class="text-xs text-blue-200"><?php echo $nome_secretario ?? 'Nome do Secretário'; ?></p>
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Dashboard do Secretário</h2>
                            <p class="text-gray-600">Visão geral do sistema e gerenciamento rápido</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="abrirModalNovoAgendamento()" class="action-btn success flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Novo Agendamento
                            </button>
                            <button onclick="abrirModalNovoPaciente()" class="action-btn flex items-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                Novo Paciente
                            </button>
                        </div>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-hoje">0</div>
                            <div class="kpi-label">Consultas Hoje</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>12%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pendentes">0</div>
                            <div class="kpi-label">Pendentes</div>
                            <div class="trend-indicator trend-down">
                                <i class="fas fa-arrow-down mr-1"></i>5%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pacientes">0</div>
                            <div class="kpi-label">Total de Pacientes</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>8%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-medicos">0</div>
                            <div class="kpi-label">Médicos Ativos</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>3%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Ações Rápidas</h3>
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="abrirModalNovoAgendamento()">
                            <div class="quick-action-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Novo Agendamento</h4>
                            <p class="text-sm text-gray-600 mt-2">Agendar consulta para paciente</p>
                        </div>
                        <div class="quick-action-card" onclick="abrirModalNovoPaciente()">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Cadastrar Paciente</h4>
                            <p class="text-sm text-gray-600 mt-2">Adicionar novo paciente ao sistema</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('secretario/agendamentos'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Ver Agendamentos</h4>
                            <p class="text-sm text-gray-600 mt-2">Lista completa de agendamentos</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('secretario/relatorios'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatórios</h4>
                            <p class="text-sm text-gray-600 mt-2">Gerar relatórios do sistema</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Agendamentos por Status -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Agendamentos por Status</h3>
                            <button onclick="exportChart('status-agendamentos')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="statusChart"></canvas>
                    </div>

                    <!-- Consultas por Médico -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Consultas por Médico</h3>
                            <button onclick="exportChart('consultas-medico')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="medicoChart"></canvas>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Agendamentos Recentes</h3>
                            <div class="flex items-center gap-4">
                                <div class="filter-tabs">
                                    <div class="filter-tab active" data-filter="todos">Todos</div>
                                    <div class="filter-tab" data-filter="hoje">Hoje</div>
                                    <div class="filter-tab" data-filter="pendentes">Pendentes</div>
                                </div>
                                <button onclick="carregarAgendamentos()" class="action-btn secondary text-sm">
                                    <i class="fas fa-sync-alt mr-1"></i>Atualizar
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>Especialidade</th>
                                        <th>Data</th>
                                        <th>Horário</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="appointment-list">
                                    <!-- Agendamentos serão carregados via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando agendamentos...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-calendar-times"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum agendamento encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há agendamentos que correspondam aos seus critérios.</p>
                            <button onclick="abrirModalNovoAgendamento()" class="action-btn success">
                                <i class="fas fa-plus mr-2"></i>Criar Primeiro Agendamento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Novo Agendamento -->
    <div id="novo-agendamento-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Novo Agendamento</h3>
                <button onclick="fecharModalNovoAgendamento()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-novo-agendamento">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Paciente</label>
                            <select class="form-input" id="select-paciente" required>
                                <option value="">Selecione um paciente</option>
                                <!-- Pacientes serão carregados via JavaScript -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Médico</label>
                            <select class="form-input" id="select-medico" required>
                                <option value="">Selecione um médico</option>
                                <!-- Médicos serão carregados via JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-input" id="input-data" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Horário</label>
                            <select class="form-input" id="select-horario" required>
                                <option value="">Selecione um horário</option>
                                <!-- Horários serão carregados dinamicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Motivo da Consulta</label>
                        <textarea class="form-textarea" id="input-motivo" placeholder="Descreva o motivo da consulta..."></textarea>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalNovoAgendamento()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Agendar Consulta</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Novo Paciente -->
    <div id="novo-paciente-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Cadastrar Novo Paciente</h3>
                <button onclick="fecharModalNovoPaciente()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-novo-paciente">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-input" id="input-nome-paciente" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-input" id="input-nascimento-paciente" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Telefone</label>
                            <input type="tel" class="form-input" id="input-telefone-paciente">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" id="input-email-paciente">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-input" id="input-endereco-paciente">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" id="input-observacoes-paciente" placeholder="Observações sobre o paciente..."></textarea>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalNovoPaciente()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Cadastrar Paciente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmação de Cancelamento -->
    <div id="confirmacao-cancelamento-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Confirmar Cancelamento</h3>
                <button onclick="fecharModalConfirmacaoCancelamento()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <p class="text-gray-600">Tem certeza que deseja cancelar este agendamento?</p>
                <div class="form-group">
                    <label class="form-label">Motivo do Cancelamento</label>
                    <textarea class="form-textarea" id="input-motivo-cancelamento" placeholder="Informe o motivo do cancelamento..." required></textarea>
                </div>
                <div class="flex gap-2 justify-end">
                    <button onclick="fecharModalConfirmacaoCancelamento()" class="action-btn secondary">Cancelar</button>
                    <button onclick="confirmarCancelamento()" class="action-btn danger">Confirmar Cancelamento</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAppointmentFilter = 'todos';
        let charts = {};
        let appointmentToCancel = null;

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

        // Carregar KPIs
        async function loadKPIs() {
            try {
                // Simulação de dados - em produção, buscar da API
                const kpis = {
                    hoje: 15,
                    pendentes: 8,
                    pacientes: 124,
                    medicos: 28
                };

                document.getElementById('kpi-hoje').textContent = kpis.hoje;
                document.getElementById('kpi-pendentes').textContent = kpis.pendentes;
                document.getElementById('kpi-pacientes').textContent = kpis.pacientes;
                document.getElementById('kpi-medicos').textContent = kpis.medicos;

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
            }
        }

        // Inicializar gráficos
        function initCharts() {
            // Gráfico de agendamentos por status
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            charts.statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Confirmados', 'Pendentes', 'Cancelados', 'Concluídos'],
                    datasets: [{
                        data: [45, 15, 5, 35],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gráfico de consultas por médico
            const medicoCtx = document.getElementById('medicoChart').getContext('2d');
            charts.medicoChart = new Chart(medicoCtx, {
                type: 'bar',
                data: {
                    labels: ['Dr. Silva', 'Dra. Santos', 'Dr. Costa', 'Dra. Fernandes', 'Dr. Oliveira'],
                    datasets: [{
                        label: 'Consultas Realizadas',
                        data: [25, 20, 18, 22, 15],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });
        }

        // Carregar agendamentos
        async function carregarAgendamentos() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('appointment-list');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                // Simulação de dados - em produção, buscar da API
                const agendamentos = [
                    {
                        id: 1,
                        paciente: 'Maria Santos',
                        medico: 'Dr. Silva',
                        especialidade: 'Cardiologia',
                        data: '2024-01-15',
                        horario: '09:00',
                        status: 'confirmed'
                    },
                    {
                        id: 2,
                        paciente: 'João Pereira',
                        medico: 'Dra. Fernandes',
                        especialidade: 'Dermatologia',
                        data: '2024-01-15',
                        horario: '10:30',
                        status: 'pending'
                    },
                    {
                        id: 3,
                        paciente: 'Ana Costa',
                        medico: 'Dr. Oliveira',
                        especialidade: 'Pediatria',
                        data: '2024-01-16',
                        horario: '14:00',
                        status: 'confirmed'
                    }
                ];

                if (loading) loading.classList.add('hidden');

                if (agendamentos.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderAgendamentos(agendamentos);

            } catch (error) {
                console.error('Erro ao carregar agendamentos:', error);
                showNotification('Erro ao carregar agendamentos.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar agendamentos
        function renderAgendamentos(agendamentos) {
            const list = document.getElementById('appointment-list');
            if (!list) return;

            list.innerHTML = agendamentos.map(agendamento => {
                const statusClass = getStatusClass(agendamento.status);
                const statusText = getStatusText(agendamento.status);

                return `
                    <tr>
                        <td class="font-medium">${agendamento.paciente}</td>
                        <td>${agendamento.medico}</td>
                        <td>${agendamento.especialidade}</td>
                        <td>${formatDate(agendamento.data)}</td>
                        <td>${agendamento.horario}</td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                ${agendamento.status === 'pending' ? `
                                    <button class="action-btn success text-sm" onclick="confirmarAgendamento(${agendamento.id})">
                                        <i class="fas fa-check mr-1"></i>Confirmar
                                    </button>
                                ` : ''}
                                ${agendamento.status !== 'cancelled' ? `
                                    <button class="action-btn danger text-sm" onclick="solicitarCancelamento(${agendamento.id})">
                                        <i class="fas fa-times mr-1"></i>Cancelar
                                    </button>
                                ` : ''}
                                <button class="action-btn secondary text-sm" onclick="editarAgendamento(${agendamento.id})">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Funções auxiliares
        function getStatusClass(status) {
            const statusMap = {
                'confirmed': 'status-confirmed',
                'pending': 'status-pending',
                'cancelled': 'status-cancelled',
                'completed': 'status-completed'
            };
            return statusMap[status] || 'status-pending';
        }

        function getStatusText(status) {
            const statusMap = {
                'confirmed': 'Confirmado',
                'pending': 'Pendente',
                'cancelled': 'Cancelado',
                'completed': 'Concluído'
            };
            return statusMap[status] || 'Pendente';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }

        // Funções de modal
        function abrirModalNovoAgendamento() {
            document.getElementById('novo-agendamento-modal').classList.add('show');
            carregarDadosModalAgendamento();
        }

        function fecharModalNovoAgendamento() {
            document.getElementById('novo-agendamento-modal').classList.remove('show');
            document.getElementById('form-novo-agendamento').reset();
        }

        function abrirModalNovoPaciente() {
            document.getElementById('novo-paciente-modal').classList.add('show');
        }

        function fecharModalNovoPaciente() {
            document.getElementById('novo-paciente-modal').classList.remove('show');
            document.getElementById('form-novo-paciente').reset();
        }

        function solicitarCancelamento(agendamentoId) {
            appointmentToCancel = agendamentoId;
            document.getElementById('confirmacao-cancelamento-modal').classList.add('show');
        }

        function fecharModalConfirmacaoCancelamento() {
            document.getElementById('confirmacao-cancelamento-modal').classList.remove('show');
            appointmentToCancel = null;
            document.getElementById('input-motivo-cancelamento').value = '';
        }

        // Funções de ação
        async function confirmarAgendamento(agendamentoId) {
            try {
                // Simulação de confirmação - em produção, chamar API
                showNotification('Agendamento confirmado com sucesso!', 'success');
                carregarAgendamentos();
            } catch (error) {
                showNotification('Erro ao confirmar agendamento.', 'error');
            }
        }

        async function confirmarCancelamento() {
            if (!appointmentToCancel) return;

            const motivo = document.getElementById('input-motivo-cancelamento').value;
            if (!motivo) {
                showNotification('Por favor, informe o motivo do cancelamento.', 'error');
                return;
            }

            try {
                // Simulação de cancelamento - em produção, chamar API
                showNotification('Agendamento cancelado com sucesso!', 'success');
                fecharModalConfirmacaoCancelamento();
                carregarAgendamentos();
            } catch (error) {
                showNotification('Erro ao cancelar agendamento.', 'error');
            }
        }

        function editarAgendamento(agendamentoId) {
            // Redirecionar para página de edição ou abrir modal de edição
            showNotification('Redirecionando para edição...', 'info');
            // window.location.href = `<?php echo site_url('secretario/agendamentos/editar/'); ?>${agendamentoId}`;
        }

        function exportChart(chartId) {
            showNotification(`Exportando gráfico ${chartId}...`, 'info');
            // Implementar lógica de exportação de gráfico
        }

        function carregarDadosModalAgendamento() {
            // Simulação de carregamento de dados para o modal
            // Em produção, buscar pacientes e médicos da API
            const pacientes = [
                { id: 1, nome: 'Maria Santos' },
                { id: 2, nome: 'João Pereira' },
                { id: 3, nome: 'Ana Costa' }
            ];

            const medicos = [
                { id: 1, nome: 'Dr. Silva', especialidade: 'Cardiologia' },
                { id: 2, nome: 'Dra. Santos', especialidade: 'Dermatologia' },
                { id: 3, nome: 'Dr. Costa', especialidade: 'Pediatria' }
            ];

            const selectPaciente = document.getElementById('select-paciente');
            const selectMedico = document.getElementById('select-medico');

            selectPaciente.innerHTML = '<option value="">Selecione um paciente</option>' +
                pacientes.map(p => `<option value="${p.id}">${p.nome}</option>`).join('');

            selectMedico.innerHTML = '<option value="">Selecione um médico</option>' +
                medicos.map(m => `<option value="${m.id}">${m.nome} - ${m.especialidade}</option>`).join('');
        }

        // Configurar filtros
        function setupFilters() {
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentAppointmentFilter = this.dataset.filter;
                    carregarAgendamentos();
                });
            });
        }

        // Configurar formulários
        function setupForms() {
            document.getElementById('form-novo-agendamento').addEventListener('submit', function(e) {
                e.preventDefault();
                // Simulação de criação de agendamento
                showNotification('Agendamento criado com sucesso!', 'success');
                fecharModalNovoAgendamento();
                carregarAgendamentos();
            });

            document.getElementById('form-novo-paciente').addEventListener('submit', function(e) {
                e.preventDefault();
                // Simulação de criação de paciente
                showNotification('Paciente cadastrado com sucesso!', 'success');
                fecharModalNovoPaciente();
            });
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

            // Configurar funcionalidades
            setupFilters();
            setupForms();

            // Carregar dados iniciais
            loadKPIs();
            initCharts();
            carregarAgendamentos();

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