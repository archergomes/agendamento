<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Relatórios e estatísticas médicas do Hospital Público de Matlhovele">
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

        .report-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .report-card.clinical {
            border-left-color: #10b981;
        }

        .report-card.financial {
            border-left-color: #f59e0b;
        }

        .report-card.operational {
            border-left-color: #8b5cf6;
        }

        .report-card.performance {
            border-left-color: #ef4444;
        }

        .chart-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 300px;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-error {
            background-color: #fef2f2;
            color: #dc2626;
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

        .form-textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            min-height: 100px;
            resize: vertical;
        }

        .report-type-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .report-type-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .report-type-card:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .report-type-card.selected {
            border-color: #3b82f6;
            background-color: #dbeafe;
        }

        .report-type-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #3b82f6;
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

        .export-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .export-option {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .export-option:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
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

        .date-range-picker {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .date-range-picker {
                flex-direction: column;
                gap: 0.5rem;
            }
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
                    <a href="<?php echo site_url('medico/relatorios'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Relatórios e Estatísticas</h2>
                            <p class="text-gray-600">Acompanhe seu desempenho e gere relatórios detalhados.</p>
                        </div>
                        <button onclick="gerarRelatorioPersonalizado()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Novo Relatório
                        </button>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-consultas">0</div>
                            <div class="kpi-label">Consultas Realizadas</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>12%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pacientes">0</div>
                            <div class="kpi-label">Pacientes Atendidos</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>8%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-tempo-medio">0min</div>
                            <div class="kpi-label">Tempo Médio por Consulta</div>
                            <div class="trend-indicator trend-down">
                                <i class="fas fa-arrow-down mr-1"></i>5%
                            </div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-satisfacao">0%</div>
                            <div class="kpi-label">Taxa de Satisfação</div>
                            <div class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up mr-1"></i>3%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Date Range -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                        <!-- Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <div class="filter-tab active" data-filter="todos">Todos</div>
                            <div class="filter-tab" data-filter="clinicos">Clínicos</div>
                            <div class="filter-tab" data-filter="desempenho">Desempenho</div>
                            <div class="filter-tab" data-filter="operacionais">Operacionais</div>
                            <div class="filter-tab" data-filter="financeiros">Financeiros</div>
                        </div>

                        <!-- Date Range -->
                        <div class="date-range-picker">
                            <div class="flex items-center gap-2">
                                <label class="form-label mb-0">Período:</label>
                                <select class="form-select" id="period-select" onchange="updateDateRange()">
                                    <option value="hoje">Hoje</option>
                                    <option value="semana" selected>Esta Semana</option>
                                    <option value="mes">Este Mês</option>
                                    <option value="trimestre">Este Trimestre</option>
                                    <option value="semestre">Este Semestre</option>
                                    <option value="ano">Este Ano</option>
                                    <option value="personalizado">Personalizado</option>
                                </select>
                            </div>
                            <div id="custom-date-range" class="hidden flex items-center gap-2">
                                <input type="date" class="form-input" id="start-date">
                                <span>até</span>
                                <input type="date" class="form-input" id="end-date">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Consultas por Dia -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Consultas por Dia da Semana</h3>
                            <button onclick="exportChart('consultas-dia')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="consultasChart"></canvas>
                    </div>

                    <!-- Distribuição por Tipo -->
                    <div class="chart-container">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Distribuição por Tipo de Consulta</h3>
                            <button onclick="exportChart('tipo-consulta')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="tipoConsultaChart"></canvas>
                    </div>

                    <!-- Evolução Mensal -->
                    <div class="chart-container lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Evolução Mensal de Atendimentos</h3>
                            <button onclick="exportChart('evolucao-mensal')" class="action-btn secondary text-sm">
                                <i class="fas fa-download mr-1"></i>Exportar
                            </button>
                        </div>
                        <canvas id="evolucaoMensalChart"></canvas>
                    </div>
                </div>

                <!-- Report Types -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Tipos de Relatório Disponíveis</h3>
                    <div class="report-type-grid">
                        <div class="report-type-card" onclick="selecionarTipoRelatorio('consultas')">
                            <div class="report-type-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatório de Consultas</h4>
                            <p class="text-sm text-gray-600 mt-2">Detalhamento completo das consultas realizadas</p>
                        </div>
                        <div class="report-type-card" onclick="selecionarTipoRelatorio('pacientes')">
                            <div class="report-type-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatório de Pacientes</h4>
                            <p class="text-sm text-gray-600 mt-2">Análise do perfil e evolução dos pacientes</p>
                        </div>
                        <div class="report-type-card" onclick="selecionarTipoRelatorio('desempenho')">
                            <div class="report-type-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatório de Desempenho</h4>
                            <p class="text-sm text-gray-600 mt-2">Métricas de produtividade e eficiência</p>
                        </div>
                        <div class="report-type-card" onclick="selecionarTipoRelatorio('financeiro')">
                            <div class="report-type-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatório Financeiro</h4>
                            <p class="text-sm text-gray-600 mt-2">Análise de receitas e desempenho financeiro</p>
                        </div>
                    </div>
                </div>

                <!-- Generated Reports -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Relatórios Gerados</h3>
                            <div class="text-sm text-gray-600">
                                <span id="reports-count">0</span> relatórios encontrados
                            </div>
                        </div>
                        
                        <div id="reports-list">
                            <!-- Relatórios serão carregados via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando relatórios...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-file-alt"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum relatório encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há relatórios que correspondam aos seus critérios.</p>
                            <button onclick="gerarRelatorioPersonalizado()" class="action-btn success">
                                <i class="fas fa-plus mr-2"></i>Gerar Primeiro Relatório
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Gerar Relatório -->
    <div id="gerar-relatorio-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Gerar Novo Relatório</h3>
                <button onclick="closeGerarRelatorioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="gerar-relatorio-content">
                <!-- Formulário de geração de relatório será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Visualização de Relatório -->
    <div id="visualizar-relatorio-modal" class="modal">
        <div class="modal-content" style="max-width: 1000px;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Visualizar Relatório</h3>
                <div class="flex gap-2">
                    <button onclick="imprimirRelatorio()" class="action-btn warning flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <button onclick="closeVisualizarRelatorioModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="visualizar-relatorio-content">
                <!-- Conteúdo do relatório será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'todos';
        let selectedReportType = '';
        let charts = {};

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
                const response = await fetch('<?php echo site_url('api/medico/relatorios_kpis'); ?>');
                const kpis = await response.json();
                
                if (kpis.error) {
                    showNotification(kpis.error, 'error');
                    return;
                }

                document.getElementById('kpi-consultas').textContent = kpis.consultas || 0;
                document.getElementById('kpi-pacientes').textContent = kpis.pacientes || 0;
                document.getElementById('kpi-tempo-medio').textContent = kpis.tempo_medio ? kpis.tempo_medio + 'min' : '0min';
                document.getElementById('kpi-satisfacao').textContent = kpis.satisfacao ? kpis.satisfacao + '%' : '0%';

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
            }
        }

        // Inicializar gráficos
        function initCharts() {
            // Gráfico de consultas por dia da semana
            const consultasCtx = document.getElementById('consultasChart').getContext('2d');
            charts.consultasChart = new Chart(consultasCtx, {
                type: 'bar',
                data: {
                    labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Consultas Realizadas',
                        data: [12, 19, 15, 17, 14, 8, 5],
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

            // Gráfico de pizza - tipos de consulta
            const tipoConsultaCtx = document.getElementById('tipoConsultaChart').getContext('2d');
            charts.tipoConsultaChart = new Chart(tipoConsultaCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Consulta Rotina', 'Retorno', 'Emergência', 'Acompanhamento'],
                    datasets: [{
                        data: [45, 25, 15, 15],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gráfico de evolução mensal
            const evolucaoCtx = document.getElementById('evolucaoMensalChart').getContext('2d');
            charts.evolucaoMensalChart = new Chart(evolucaoCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    datasets: [{
                        label: 'Consultas Realizadas',
                        data: [85, 92, 78, 95, 88, 102, 115, 98, 110, 105, 120, 125],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Carregar relatórios
        async function loadReports() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('reports-list');
            const count = document.getElementById('reports-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todos') params.append('filter', currentFilter);

                const response = await fetch(`<?php echo site_url('api/medico/relatorios'); ?>?${params}`);
                const relatorios = await response.json();

                if (loading) loading.classList.add('hidden');

                if (relatorios.error) {
                    showNotification(relatorios.error, 'error');
                    return;
                }

                if (count) count.textContent = relatorios.length || 0;

                if (relatorios.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderReports(relatorios);

            } catch (error) {
                console.error('Erro ao carregar relatórios:', error);
                showNotification('Erro ao carregar relatórios.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar relatórios
        function renderReports(relatorios) {
            const list = document.getElementById('reports-list');
            if (!list) return;

            list.innerHTML = relatorios.map(relatorio => {
                const cardClass = getCardClass(relatorio.tipo);
                const statusClass = getStatusClass(relatorio.status);

                return `
                    <div class="report-card ${cardClass}" data-relatorio-id="${relatorio.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Informações do Relatório -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${relatorio.titulo}</h4>
                                        <span class="status-badge ${statusClass}">${relatorio.status}</span>
                                        <span class="text-sm text-gray-600">${formatDate(relatorio.data_criacao)}</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-600">Tipo:</span>
                                            <p class="font-medium">${relatorio.tipo}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Período:</span>
                                            <p class="font-medium">${relatorio.periodo}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Tamanho:</span>
                                            <p class="font-medium">${relatorio.tamanho}</p>
                                        </div>
                                    </div>

                                    <!-- Descrição -->
                                    <div class="bg-gray-50 rounded p-3 mb-3">
                                        <span class="text-gray-600 text-sm">Descrição:</span>
                                        <p class="text-sm text-gray-700 mt-1">${relatorio.descricao}</p>
                                    </div>

                                    <!-- Métricas Principais -->
                                    ${relatorio.metricas ? `
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                                            ${relatorio.metricas.map(metrica => `
                                                <div class="text-center p-2 bg-blue-50 rounded">
                                                    <div class="font-bold text-blue-600">${metrica.valor}</div>
                                                    <div class="text-xs text-gray-600">${metrica.label}</div>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                <button class="action-btn visualizar-relatorio" data-id="${relatorio.id}">
                                    <i class="fas fa-eye mr-1"></i>Visualizar
                                </button>
                                <button class="action-btn success" onclick="baixarRelatorio(${relatorio.id})">
                                    <i class="fas fa-download mr-1"></i>Baixar PDF
                                </button>
                                <button class="action-btn secondary" onclick="compartilharRelatorio(${relatorio.id})">
                                    <i class="fas fa-share mr-1"></i>Compartilhar
                                </button>
                                <button class="action-btn danger excluir-relatorio" data-id="${relatorio.id}">
                                    <i class="fas fa-trash mr-1"></i>Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Adicionar event listeners aos botões
            addEventListeners();
        }

        // Formatar data
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }

        // Obter classe CSS do card
        function getCardClass(tipo) {
            const classMap = {
                'clinico': 'clinical',
                'desempenho': 'performance',
                'operacional': 'operational',
                'financeiro': 'financial'
            };
            return classMap[tipo] || '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'concluido': 'status-completed',
                'processando': 'status-processing',
                'pendente': 'status-pending',
                'erro': 'status-error'
            };
            return statusMap[status] || 'status-pending';
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Visualizar relatório
            document.querySelectorAll('.visualizar-relatorio').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const relatorioId = e.target.closest('button').dataset.id;
                    visualizarRelatorio(relatorioId);
                });
            });

            // Excluir relatório
            document.querySelectorAll('.excluir-relatorio').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const relatorioId = e.target.closest('button').dataset.id;
                    excluirRelatorio(relatorioId);
                });
            });
        }

        // Funções de ação
        function gerarRelatorioPersonalizado() {
            const modal = document.getElementById('gerar-relatorio-modal');
            const content = document.getElementById('gerar-relatorio-content');

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Tipo de Relatório</label>
                        <select class="form-select" id="tipo-relatorio" onchange="updateReportForm()">
                            <option value="">Selecione o tipo</option>
                            <option value="consultas">Relatório de Consultas</option>
                            <option value="pacientes">Relatório de Pacientes</option>
                            <option value="desempenho">Relatório de Desempenho</option>
                            <option value="financeiro">Relatório Financeiro</option>
                            <option value="personalizado">Relatório Personalizado</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" class="form-input" id="data-inicio" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Data Final</label>
                            <input type="date" class="form-input" id="data-fim" required>
                        </div>
                    </div>

                    <!-- Campos específicos por tipo de relatório -->
                    <div id="campos-especificos">
                        <!-- Campos serão carregados dinamicamente -->
                    </div>

                    <div class="form-group">
                        <label class="form-label">Formato de Saída</label>
                        <div class="export-options">
                            <div class="export-option" onclick="selecionarFormato('pdf')">
                                <i class="fas fa-file-pdf text-2xl text-red-500 mb-2"></i>
                                <div>PDF</div>
                            </div>
                            <div class="export-option" onclick="selecionarFormato('excel')">
                                <i class="fas fa-file-excel text-2xl text-green-500 mb-2"></i>
                                <div>Excel</div>
                            </div>
                            <div class="export-option" onclick="selecionarFormato('csv')">
                                <i class="fas fa-file-csv text-2xl text-blue-500 mb-2"></i>
                                <div>CSV</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Observações (Opcional)</label>
                        <textarea class="form-textarea" placeholder="Observações sobre o relatório..."></textarea>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeGerarRelatorioModal()" class="action-btn danger">Cancelar</button>
                        <button onclick="processarGeracaoRelatorio()" class="action-btn success">Gerar Relatório</button>
                    </div>
                </div>
            `;

            // Configurar datas padrão
            const hoje = new Date();
            const umaSemanaAtras = new Date();
            umaSemanaAtras.setDate(hoje.getDate() - 7);

            document.getElementById('data-inicio').value = umaSemanaAtras.toISOString().split('T')[0];
            document.getElementById('data-fim').value = hoje.toISOString().split('T')[0];

            modal.classList.add('show');
        }

        function updateReportForm() {
            const tipo = document.getElementById('tipo-relatorio').value;
            const camposDiv = document.getElementById('campos-especificos');

            let camposHTML = '';

            switch(tipo) {
                case 'consultas':
                    camposHTML = `
                        <div class="form-group">
                            <label class="form-label">Filtrar por Status</label>
                            <select class="form-select" multiple>
                                <option value="realizada" selected>Realizadas</option>
                                <option value="cancelada">Canceladas</option>
                                <option value="remarcada">Remarcadas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Incluir Métricas</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" checked> Tempo médio por consulta
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" checked> Taxa de comparecimento
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2"> Distribuição por horário
                                </label>
                            </div>
                        </div>
                    `;
                    break;

                case 'pacientes':
                    camposHTML = `
                        <div class="form-group">
                            <label class="form-label">Faixa Etária</label>
                            <select class="form-select">
                                <option value="todas">Todas as idades</option>
                                <option value="criancas">Crianças (0-12)</option>
                                <option value="adolescentes">Adolescentes (13-17)</option>
                                <option value="adultos">Adultos (18-65)</option>
                                <option value="idosos">Idosos (65+)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Condições Específicas</label>
                            <input type="text" class="form-input" placeholder="Filtrar por condições de saúde...">
                        </div>
                    `;
                    break;

                case 'desempenho':
                    camposHTML = `
                        <div class="form-group">
                            <label class="form-label">Métricas de Desempenho</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" checked> Produtividade (consultas/hora)
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2" checked> Taxa de ocupação
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2"> Satisfação do paciente
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="mr-2"> Tempo de espera médio
                                </label>
                            </div>
                        </div>
                    `;
                    break;

                case 'personalizado':
                    camposHTML = `
                        <div class="form-group">
                            <label class="form-label">Campos Personalizados</label>
                            <textarea class="form-textarea" placeholder="Descreva os campos e métricas desejadas..."></textarea>
                        </div>
                    `;
                    break;

                default:
                    camposHTML = '';
            }

            camposDiv.innerHTML = camposHTML;
        }

        function selecionarFormato(formato) {
            // Remover seleção anterior
            document.querySelectorAll('.export-option').forEach(opt => {
                opt.style.borderColor = '#e5e7eb';
                opt.style.backgroundColor = 'white';
            });

            // Adicionar seleção atual
            event.target.closest('.export-option').style.borderColor = '#3b82f6';
            event.target.closest('.export-option').style.backgroundColor = '#eff6ff';
        }

        function selecionarTipoRelatorio(tipo) {
            selectedReportType = tipo;
            document.getElementById('tipo-relatorio').value = tipo;
            gerarRelatorioPersonalizado();
            updateReportForm();
        }

        async function visualizarRelatorio(relatorioId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/relatorio_completo/'); ?>${relatorioId}`);
                const relatorio = await response.json();

                if (relatorio.error) {
                    showNotification(relatorio.error, 'error');
                    return;
                }

                const modal = document.getElementById('visualizar-relatorio-modal');
                const content = document.getElementById('visualizar-relatorio-content');

                content.innerHTML = `
                    <div class="space-y-6">
                        <!-- Cabeçalho -->
                        <div class="border-b pb-4">
                            <h4 class="text-xl font-semibold text-gray-800">${relatorio.titulo}</h4>
                            <p class="text-gray-600">Gerado em: ${formatDate(relatorio.data_criacao)} | Período: ${relatorio.periodo}</p>
                        </div>

                        <!-- Resumo Executivo -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h5 class="font-semibold text-blue-800 mb-2">Resumo Executivo</h5>
                            <p class="text-blue-700">${relatorio.resumo_executivo || 'Relatório gerado automaticamente pelo sistema.'}</p>
                        </div>

                        <!-- Métricas Principais -->
                        <div>
                            <h5 class="font-semibold text-gray-700 mb-3">Métricas Principais</h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                ${relatorio.metricas_principais ? relatorio.metricas_principais.map(metrica => `
                                    <div class="text-center p-3 bg-white border rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">${metrica.valor}</div>
                                        <div class="text-sm text-gray-600">${metrica.label}</div>
                                    </div>
                                `).join('') : ''}
                            </div>
                        </div>

                        <!-- Tabela de Dados -->
                        ${relatorio.dados && relatorio.dados.length > 0 ? `
                            <div>
                                <h5 class="font-semibold text-gray-700 mb-3">Detalhamento</h5>
                                <div class="overflow-x-auto">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                ${Object.keys(relatorio.dados[0]).map(key => `
                                                    <th>${key}</th>
                                                `).join('')}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${relatorio.dados.map(linha => `
                                                <tr>
                                                    ${Object.values(linha).map(valor => `
                                                        <td>${valor}</td>
                                                    `).join('')}
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Conclusões -->
                        ${relatorio.conclusoes ? `
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h5 class="font-semibold text-gray-700 mb-2">Conclusões e Recomendações</h5>
                                <p class="text-gray-700">${relatorio.conclusoes}</p>
                            </div>
                        ` : ''}
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar relatório:', error);
                showNotification('Erro ao carregar relatório.', 'error');
            }
        }

        function processarGeracaoRelatorio() {
            showNotification('Relatório sendo gerado...', 'info');
            closeGerarRelatorioModal();
            
            // Simular processamento
            setTimeout(() => {
                showNotification('Relatório gerado com sucesso!', 'success');
                loadReports();
            }, 2000);
        }

        function baixarRelatorio(relatorioId) {
            showNotification('Iniciando download do relatório...', 'info');
            // Implementar lógica de download
        }

        function compartilharRelatorio(relatorioId) {
            showNotification('Funcionalidade de compartilhamento em desenvolvimento', 'info');
        }

        function excluirRelatorio(relatorioId) {
            if (confirm('Tem certeza que deseja excluir este relatório?')) {
                showNotification('Relatório excluído com sucesso!', 'success');
                loadReports();
            }
        }

        function imprimirRelatorio() {
            window.print();
        }

        function exportChart(chartId) {
            showNotification(`Exportando gráfico ${chartId}...`, 'info');
            // Implementar lógica de exportação de gráfico
        }

        function updateDateRange() {
            const periodSelect = document.getElementById('period-select');
            const customRange = document.getElementById('custom-date-range');
            
            if (periodSelect.value === 'personalizado') {
                customRange.classList.remove('hidden');
            } else {
                customRange.classList.add('hidden');
                // Atualizar datas baseado no período selecionado
                updateChartsData();
            }
        }

        function updateChartsData() {
            // Atualizar dados dos gráficos baseado no período selecionado
            showNotification('Atualizando dados do período selecionado...', 'info');
        }

        function closeGerarRelatorioModal() {
            document.getElementById('gerar-relatorio-modal').classList.remove('show');
        }

        function closeVisualizarRelatorioModal() {
            document.getElementById('visualizar-relatorio-modal').classList.remove('show');
        }

        // Configurar filtros
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadReports();
                });
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

            // Configurar filtros
            setupFilters();

            // Carregar dados iniciais
            loadKPIs();
            initCharts();
            loadReports();

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