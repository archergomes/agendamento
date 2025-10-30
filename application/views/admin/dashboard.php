<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Dashboard de administração do Hospital Público de Matlhovele">
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

        /* NOVOS ESTILOS PARA DASHBOARD MODERNA */
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
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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

        .chart-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 300px;
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
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
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

        .search-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
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
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Administrador</h2>
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
                    <a href="<?php echo site_url('admin'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="<?php echo site_url('admin/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('admin/medicos'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="<?php echo site_url('admin/secretarios'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Secretários</span>
                    </a>
                    <a href="<?php echo site_url('admin/agendamentos'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_paciente'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Paciente</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_secretario'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Secretário</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_medico'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Médico</span>
                    </a>
                    <a href="<?php echo site_url('admin/relatorios'); ?>" class="block text-gray-700">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="<?php echo site_url('admin/configuracoes'); ?>" class="block text-gray-700">
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
                        <p class="text-sm">Administrador</p>
                        <p class="text-xs text-blue-200">Sistema Hospitalar</p>
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Dashboard do Administrador</h2>
                            <p class="text-gray-600">Visão geral completa do sistema do Hospital Matlhovele</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="gerarRelatorioRapido()" class="action-btn success flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                Relatório
                            </button>
                            <button onclick="abrirModalConfiguracoes()" class="action-btn secondary flex items-center gap-2">
                                <i class="fas fa-cog"></i>
                                Configurações
                            </button>
                        </div>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pacientes">0</div>
                            <div class="kpi-label">Total de Pacientes</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>12%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-medicos">0</div>
                            <div class="kpi-label">Médicos Ativos</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>5%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-agendamentos">0</div>
                            <div class="kpi-label">Agendamentos Hoje</div>
                            <div class="trend-indicator trend-down">
                                <i class="fas fa-arrow-down mr-1"></i>3%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-ocupacao">0%</div>
                            <div class="kpi-label">Taxa de Ocupação</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>8%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Ações Rápidas</h3>
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('admin/cad_paciente'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Novo Paciente</h4>
                            <p class="text-sm text-gray-600 mt-2">Cadastrar novo paciente</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('admin/cad_medico'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Novo Médico</h4>
                            <p class="text-sm text-gray-600 mt-2">Cadastrar novo médico</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('admin/cad_secretario'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Novo Secretário</h4>
                            <p class="text-sm text-gray-600 mt-2">Cadastrar novo secretário</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='<?php echo site_url('admin/relatorios'); ?>'">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatórios</h4>
                            <p class="text-sm text-gray-600 mt-2">Gerar relatórios</p>
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

                    <!-- Consultas por Especialidade -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Consultas por Especialidade</h3>
                            <button onclick="exportChart('consultas-especialidade')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="especialidadeChart"></canvas>
                    </div>
                </div>

                <!-- Search and Activity Section -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Atividade Recente do Sistema</h3>
                            <div class="flex items-center gap-4">
                                <button onclick="carregarAtividade()" class="action-btn secondary text-sm">
                                    <i class="fas fa-sync-alt mr-1"></i>Atualizar
                                </button>
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <div class="search-container mb-4">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="search-input" class="search-input" 
                                   placeholder="Pesquisar por nome, BI ou tipo de atividade...">
                        </div>

                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Detalhes</th>
                                        <th>Usuário</th>
                                        <th>Data/Hora</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="activity-list">
                                    <!-- Atividades serão carregadas via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando atividades...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-calendar-times"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma atividade encontrada</h3>
                            <p class="text-gray-600 mb-4">Não há atividades que correspondam aos seus critérios.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Configurações Rápidas -->
    <div id="configuracoes-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Configurações Rápidas</h3>
                <button onclick="fecharModalConfiguracoes()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Notificações por Email</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="notificacoes-email" class="rounded">
                        <span class="text-sm text-gray-600">Receber notificações por email</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Relatórios Automáticos</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="relatorios-automaticos" class="rounded">
                        <span class="text-sm text-gray-600">Gerar relatórios automaticamente</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Fuso Horário</label>
                    <select class="form-input" id="fuso-horario">
                        <option value="Africa/Maputo" selected>Maputo (UTC+2)</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 justify-end mt-6">
                <button onclick="fecharModalConfiguracoes()" class="action-btn secondary">Cancelar</button>
                <button onclick="salvarConfiguracoes()" class="action-btn success">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Usuário -->
    <div id="editar-usuario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700" id="editar-usuario-titulo">Editar Usuário</h3>
                <button onclick="fecharModalEditarUsuario()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-editar-usuario">
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-input" id="editar-usuario-nome" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" id="editar-usuario-email">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-input" id="editar-usuario-telefone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">BI/Número</label>
                        <input type="text" class="form-input" id="editar-usuario-bi" readonly>
                    </div>
                    <div class="form-group" id="editar-usuario-especialidade-container" style="display: none;">
                        <label class="form-label">Especialidade</label>
                        <input type="text" class="form-input" id="editar-usuario-especialidade">
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalEditarUsuario()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let charts = {};
        let usuarioEditando = null;
        let tipoUsuarioEditando = null;

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
                const response = await fetch('<?php echo site_url('api/admin/kpis'); ?>');
                const kpis = await response.json();
                
                if (kpis.error) {
                    showNotification(kpis.error, 'error');
                    return;
                }

                document.getElementById('kpi-pacientes').textContent = kpis.pacientes || 0;
                document.getElementById('kpi-medicos').textContent = kpis.medicos || 0;
                document.getElementById('kpi-agendamentos').textContent = kpis.agendamentos || 0;
                document.getElementById('kpi-ocupacao').textContent = kpis.ocupacao ? kpis.ocupacao + '%' : '0%';

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
                showNotification('Erro ao carregar métricas.', 'error');
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de consultas por especialidade
            const especialidadeCtx = document.getElementById('especialidadeChart').getContext('2d');
            charts.especialidadeChart = new Chart(especialidadeCtx, {
                type: 'bar',
                data: {
                    labels: ['Cardiologia', 'Pediatria', 'Dermatologia', 'Ortopedia', 'Ginecologia'],
                    datasets: [{
                        label: 'Consultas Realizadas',
                        data: [25, 30, 15, 20, 18],
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

        // Carregar atividade recente
        async function carregarAtividade(searchQuery = '') {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('activity-list');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (searchQuery) params.append('query', searchQuery);

                const response = await fetch(`<?php echo site_url('api/admin/atividade'); ?>?${params}`);
                const atividades = await response.json();

                if (loading) loading.classList.add('hidden');

                if (atividades.error) {
                    showNotification(atividades.error, 'error');
                    return;
                }

                if (atividades.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderAtividade(atividades);

            } catch (error) {
                console.error('Erro ao carregar atividades:', error);
                showNotification('Erro ao carregar atividades.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar atividade
        function renderAtividade(atividades) {
            const list = document.getElementById('activity-list');
            if (!list) return;

            list.innerHTML = atividades.map(atividade => {
                const tipoIcone = getTipoIcone(atividade.tipo);
                const tipoClasse = getTipoClasse(atividade.tipo);

                return `
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="${tipoIcone} ${tipoClasse}"></i>
                                <span class="font-medium">${atividade.tipo}</span>
                            </div>
                        </td>
                        <td>${atividade.detalhes}</td>
                        <td>${atividade.usuario || 'Sistema'}</td>
                        <td>${formatDateTime(atividade.data_criacao)}</td>
                        <td>
                            <div class="flex gap-2">
                                ${atividade.tipo !== 'agendamento' ? `
                                    <button class="action-btn text-sm editar-usuario-btn" 
                                            data-bi="${atividade.bi}" 
                                            data-tipo="${atividade.tipo_usuario}">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </button>
                                ` : ''}
                                <button class="action-btn secondary text-sm" onclick="verDetalhes('${atividade.id}')">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            // Adicionar eventos aos botões de edição
            document.querySelectorAll('.editar-usuario-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bi = this.dataset.bi;
                    const tipo = this.dataset.tipo;
                    abrirModalEditarUsuario(bi, tipo);
                });
            });
        }

        // Funções auxiliares
        function getTipoIcone(tipo) {
            const iconMap = {
                'paciente': 'fas fa-user',
                'médico': 'fas fa-user-md',
                'secretário': 'fas fa-user-tie',
                'agendamento': 'fas fa-calendar-check',
                'sistema': 'fas fa-cog'
            };
            return iconMap[tipo] || 'fas fa-info-circle';
        }

        function getTipoClasse(tipo) {
            const classMap = {
                'paciente': 'text-blue-500',
                'médico': 'text-green-500',
                'secretário': 'text-purple-500',
                'agendamento': 'text-orange-500',
                'sistema': 'text-gray-500'
            };
            return classMap[tipo] || 'text-gray-500';
        }

        function formatDateTime(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleString('pt-PT');
        }

        // Funções de modal
        function abrirModalConfiguracoes() {
            document.getElementById('configuracoes-modal').classList.add('show');
        }

        function fecharModalConfiguracoes() {
            document.getElementById('configuracoes-modal').classList.remove('show');
        }

        async function abrirModalEditarUsuario(bi, tipo) {
            usuarioEditando = bi;
            tipoUsuarioEditando = tipo;

            try {
                const response = await fetch(`<?php echo site_url('api/admin/usuario/'); ?>${bi}?tipo=${tipo}`);
                const usuario = await response.json();

                if (usuario.error) {
                    showNotification(usuario.error, 'error');
                    return;
                }

                document.getElementById('editar-usuario-titulo').textContent = `Editar ${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`;
                document.getElementById('editar-usuario-nome').value = usuario.nome || '';
                document.getElementById('editar-usuario-email').value = usuario.email || '';
                document.getElementById('editar-usuario-telefone').value = usuario.telefone || '';
                document.getElementById('editar-usuario-bi').value = usuario.bi || '';

                // Mostrar campo de especialidade apenas para médicos
                const especialidadeContainer = document.getElementById('editar-usuario-especialidade-container');
                const especialidadeInput = document.getElementById('editar-usuario-especialidade');
                
                if (tipo === 'médico') {
                    especialidadeContainer.style.display = 'block';
                    especialidadeInput.value = usuario.especialidade || '';
                } else {
                    especialidadeContainer.style.display = 'none';
                }

                document.getElementById('editar-usuario-modal').classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar dados do usuário:', error);
                showNotification('Erro ao carregar dados do usuário.', 'error');
            }
        }

        function fecharModalEditarUsuario() {
            document.getElementById('editar-usuario-modal').classList.remove('show');
            usuarioEditando = null;
            tipoUsuarioEditando = null;
            document.getElementById('form-editar-usuario').reset();
        }

        // Funções de ação
        function gerarRelatorioRapido() {
            showNotification('Gerando relatório rápido...', 'info');
            // Implementar geração de relatório
        }

        function exportChart(chartId) {
            showNotification(`Exportando gráfico ${chartId}...`, 'info');
            // Implementar exportação de gráfico
        }

        function verDetalhes(atividadeId) {
            showNotification(`Visualizando detalhes da atividade ${atividadeId}...`, 'info');
            // Implementar visualização de detalhes
        }

        async function salvarConfiguracoes() {
            // Implementar salvamento de configurações
            showNotification('Configurações salvas com sucesso!', 'success');
            fecharModalConfiguracoes();
        }

        // Configurar eventos
        function setupEventListeners() {
            // Busca em tempo real
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        carregarAtividade(this.value);
                    }, 500);
                });
            }

            // Formulário de edição de usuário
            const formEditarUsuario = document.getElementById('form-editar-usuario');
            if (formEditarUsuario) {
                formEditarUsuario.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const dados = {
                        bi: usuarioEditando,
                        tipo: tipoUsuarioEditando,
                        nome: document.getElementById('editar-usuario-nome').value,
                        email: document.getElementById('editar-usuario-email').value,
                        telefone: document.getElementById('editar-usuario-telefone').value
                    };

                    if (tipoUsuarioEditando === 'médico') {
                        dados.especialidade = document.getElementById('editar-usuario-especialidade').value;
                    }

                    try {
                        const response = await fetch('<?php echo site_url('api/admin/atualizar_usuario'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(dados)
                        });

                        const resultado = await response.json();

                        if (resultado.error) {
                            showNotification(resultado.error, 'error');
                            return;
                        }

                        showNotification('Usuário atualizado com sucesso!', 'success');
                        fecharModalEditarUsuario();
                        carregarAtividade(searchInput?.value || '');

                    } catch (error) {
                        console.error('Erro ao atualizar usuário:', error);
                        showNotification('Erro ao atualizar usuário.', 'error');
                    }
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

            // Configurar funcionalidades
            setupEventListeners();

            // Carregar dados iniciais
            loadKPIs();
            initCharts();
            carregarAtividade();

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