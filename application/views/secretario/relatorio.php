<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Página de relatórios para secretários do Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        /* ESTILOS ESPECÍFICOS PARA RELATÓRIOS */
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

        .filter-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .search-container {
            position: relative;
            margin-bottom: 1rem;
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

        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
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

        .chart-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            height: 300px;
        }

        .report-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 0.375rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3b82f6;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
            }

            .chart-container {
                height: 250px;
            }
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .date-range {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
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
                    <a href="<?php echo site_url('secretario'); ?>" class="block text-gray-700">
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
                    <a href="<?php echo site_url('secretario/relatorios'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Relatórios e Estatísticas</h2>
                            <p class="text-gray-600">Acompanhe métricas e gere relatórios do hospital</p>
                        </div>
                        <button onclick="gerarRelatorioCompleto()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            Relatório Completo
                        </button>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-consultas">0</div>
                            <div class="kpi-label">Consultas Este Mês</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pacientes">0</div>
                            <div class="kpi-label">Novos Pacientes</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-taxa">0%</div>
                            <div class="kpi-label">Taxa de Ocupação</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-receita">0</div>
                            <div class="kpi-label">Receita (MT)</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Relatórios Rápidos</h3>
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="gerarRelatorioConsultas()">
                            <div class="quick-action-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Consultas</h4>
                            <p class="text-sm text-gray-600 mt-2">Relatório de consultas</p>
                        </div>
                        <div class="quick-action-card" onclick="gerarRelatorioPacientes()">
                            <div class="quick-action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Pacientes</h4>
                            <p class="text-sm text-gray-600 mt-2">Relatório de pacientes</p>
                        </div>
                        <div class="quick-action-card" onclick="gerarRelatorioMedicos()">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Médicos</h4>
                            <p class="text-sm text-gray-600 mt-2">Relatório de médicos</p>
                        </div>
                        <div class="quick-action-card" onclick="gerarRelatorioFinanceiro()">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Financeiro</h4>
                            <p class="text-sm text-gray-600 mt-2">Relatório financeiro</p>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filter-section">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Filtros do Relatório</h3>
                    <div class="filter-row">
                        <div class="form-group">
                            <label class="form-label">Tipo de Relatório</label>
                            <select class="form-select" id="report-type">
                                <option value="consultas">Consultas</option>
                                <option value="pacientes">Pacientes</option>
                                <option value="medicos">Médicos</option>
                                <option value="financeiro">Financeiro</option>
                                <option value="atendimento">Atendimento</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Período</label>
                            <select class="form-select" id="period">
                                <option value="today">Hoje</option>
                                <option value="week">Esta Semana</option>
                                <option value="month" selected>Este Mês</option>
                                <option value="quarter">Este Trimestre</option>
                                <option value="year">Este Ano</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>
                        <div class="form-group date-range hidden" id="custom-date-range">
                            <div>
                                <label class="form-label">Data Inicial</label>
                                <input type="date" class="form-input" id="start-date">
                            </div>
                            <div>
                                <label class="form-label">Data Final</label>
                                <input type="date" class="form-input" id="end-date">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button onclick="limparFiltros()" class="action-btn secondary">
                            <i class="fas fa-times mr-1"></i>Limpar
                        </button>
                        <button onclick="aplicarFiltros()" class="action-btn success">
                            <i class="fas fa-filter mr-1"></i>Aplicar Filtros
                        </button>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Consultas por Especialidade -->
                    <div class="chart-container">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Consultas por Especialidade</h3>
                        <canvas id="specialtyChart"></canvas>
                    </div>

                    <!-- Evolução Mensal -->
                    <div class="chart-container">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Evolução Mensal</h3>
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Detailed Stats -->
                <div class="report-card">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Estatísticas Detalhadas</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="stat-total-consultas">0</div>
                            <div class="stat-label">Total Consultas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="stat-media-diaria">0</div>
                            <div class="stat-label">Média Diária</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="stat-taxa-comparecimento">0%</div>
                            <div class="stat-label">Comparecimento</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="stat-tempo-medio">0min</div>
                            <div class="stat-label">Tempo Médio</div>
                        </div>
                    </div>
                </div>

                <!-- Top Médicos -->
                <div class="report-card">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Médicos Mais Ativos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 text-left text-gray-700">Médico</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Especialidade</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Consultas</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Taxa Ocupação</th>
                                </tr>
                            </thead>
                            <tbody id="top-doctors-list">
                                <!-- Dados serão carregados via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Exportar Relatórios</h3>
                    <div class="flex flex-wrap gap-4">
                        <button onclick="exportarPDF()" class="action-btn danger flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i>
                            Exportar PDF
                        </button>
                        <button onclick="exportarExcel()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-file-excel"></i>
                            Exportar Excel
                        </button>
                        <button onclick="exportarCSV()" class="action-btn secondary flex items-center gap-2">
                            <i class="fas fa-file-csv"></i>
                            Exportar CSV
                        </button>
                        <button onclick="enviarPorEmail()" class="action-btn warning flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            Enviar por Email
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Exportação -->
    <div id="export-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Exportar Relatório</h3>
                <button onclick="fecharModalExport()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Formato</label>
                    <select class="form-select" id="export-format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Período</label>
                    <select class="form-select" id="export-period">
                        <option value="current">Período Atual</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Incluir Gráficos</label>
                    <input type="checkbox" id="include-charts" checked class="mr-2">
                </div>
            </div>
            <div class="flex gap-2 justify-end mt-6">
                <button onclick="fecharModalExport()" class="action-btn secondary">Cancelar</button>
                <button onclick="confirmarExport()" class="action-btn success">Exportar</button>
            </div>
        </div>
    </div>

    <script>
        let currentFilters = {
            type: 'consultas',
            period: 'month',
            startDate: null,
            endDate: null
        };

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
                // Simulação de dados - em produção, buscar da API
                const kpis = {
                    consultas: 342,
                    pacientes: 45,
                    taxa: '78%',
                    receita: '125.640'
                };

                document.getElementById('kpi-consultas').textContent = kpis.consultas;
                document.getElementById('kpi-pacientes').textContent = kpis.pacientes;
                document.getElementById('kpi-taxa').textContent = kpis.taxa;
                document.getElementById('kpi-receita').textContent = kpis.receita;

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
            }
        }

        // Carregar estatísticas
        async function loadStatistics() {
            try {
                // Simulação de dados
                const stats = {
                    totalConsultas: 1245,
                    mediaDiaria: 42,
                    taxaComparecimento: '85%',
                    tempoMedio: '35min'
                };

                document.getElementById('stat-total-consultas').textContent = stats.totalConsultas;
                document.getElementById('stat-media-diaria').textContent = stats.mediaDiaria;
                document.getElementById('stat-taxa-comparecimento').textContent = stats.taxaComparecimento;
                document.getElementById('stat-tempo-medio').textContent = stats.tempoMedio;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar top médicos
        async function loadTopDoctors() {
            try {
                const topDoctors = [
                    { nome: 'Dr. João Silva', especialidade: 'Cardiologia', consultas: 45, ocupacao: '92%' },
                    { nome: 'Dra. Maria Santos', especialidade: 'Pediatria', consultas: 38, ocupacao: '88%' },
                    { nome: 'Dr. Carlos Pereira', especialidade: 'Ortopedia', consultas: 35, ocupacao: '85%' },
                    { nome: 'Dra. Ana Costa', especialidade: 'Ginecologia', consultas: 32, ocupacao: '82%' },
                    { nome: 'Dr. Pedro Almeida', especialidade: 'Neurologia', consultas: 28, ocupacao: '78%' }
                ];

                const list = document.getElementById('top-doctors-list');
                list.innerHTML = topDoctors.map(doctor => `
                    <tr class="border-t">
                        <td class="py-2 px-4">${doctor.nome}</td>
                        <td class="py-2 px-4">${doctor.especialidade}</td>
                        <td class="py-2 px-4">${doctor.consultas}</td>
                        <td class="py-2 px-4">${doctor.ocupacao}</td>
                    </tr>
                `).join('');

            } catch (error) {
                console.error('Erro ao carregar top médicos:', error);
            }
        }

        // Inicializar gráficos
        function initCharts() {
            // Gráfico de especialidades
            const specialtyCtx = document.getElementById('specialtyChart').getContext('2d');
            charts.specialty = new Chart(specialtyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cardiologia', 'Pediatria', 'Ortopedia', 'Ginecologia', 'Neurologia', 'Outros'],
                    datasets: [{
                        data: [25, 20, 18, 15, 12, 10],
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'
                        ]
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

            // Gráfico mensal
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            charts.monthly = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Consultas',
                        data: [320, 290, 350, 380, 420, 390, 410],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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

        // Funções de relatório
        function gerarRelatorioCompleto() {
            showNotification('Gerando relatório completo...', 'info');
            // Implementar geração de relatório completo
        }

        function gerarRelatorioConsultas() {
            currentFilters.type = 'consultas';
            aplicarFiltros();
            showNotification('Relatório de consultas gerado', 'success');
        }

        function gerarRelatorioPacientes() {
            currentFilters.type = 'pacientes';
            aplicarFiltros();
            showNotification('Relatório de pacientes gerado', 'success');
        }

        function gerarRelatorioMedicos() {
            currentFilters.type = 'medicos';
            aplicarFiltros();
            showNotification('Relatório de médicos gerado', 'success');
        }

        function gerarRelatorioFinanceiro() {
            currentFilters.type = 'financeiro';
            aplicarFiltros();
            showNotification('Relatório financeiro gerado', 'success');
        }

        // Funções de exportação
        function exportarPDF() {
            abrirModalExport('pdf');
        }

        function exportarExcel() {
            abrirModalExport('excel');
        }

        function exportarCSV() {
            abrirModalExport('csv');
        }

        function enviarPorEmail() {
            showNotification('Relatório enviado por email com sucesso!', 'success');
        }

        function abrirModalExport(format) {
            if (format) {
                document.getElementById('export-format').value = format;
            }
            document.getElementById('export-modal').classList.add('show');
        }

        function fecharModalExport() {
            document.getElementById('export-modal').classList.remove('show');
        }

        function confirmarExport() {
            const format = document.getElementById('export-format').value;
            showNotification(`Exportando relatório em formato ${format.toUpperCase()}...`, 'info');
            fecharModalExport();
        }

        // Funções de filtro
        function aplicarFiltros() {
            const reportType = document.getElementById('report-type').value;
            const period = document.getElementById('period').value;
            
            currentFilters.type = reportType;
            currentFilters.period = period;

            if (period === 'custom') {
                currentFilters.startDate = document.getElementById('start-date').value;
                currentFilters.endDate = document.getElementById('end-date').value;
            } else {
                currentFilters.startDate = null;
                currentFilters.endDate = null;
            }

            // Atualizar dados baseado nos filtros
            loadKPIs();
            loadStatistics();
            loadTopDoctors();
            
            showNotification('Filtros aplicados com sucesso!', 'success');
        }

        function limparFiltros() {
            document.getElementById('report-type').value = 'consultas';
            document.getElementById('period').value = 'month';
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';
            document.getElementById('custom-date-range').classList.add('hidden');
            
            currentFilters = {
                type: 'consultas',
                period: 'month',
                startDate: null,
                endDate: null
            };

            aplicarFiltros();
        }

        // Configurar eventos
        function setupEventListeners() {
            // Mostrar/ocultar datas personalizadas
            const periodSelect = document.getElementById('period');
            const dateRangeDiv = document.getElementById('custom-date-range');
            
            periodSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    dateRangeDiv.classList.remove('hidden');
                } else {
                    dateRangeDiv.classList.add('hidden');
                }
            });

            // Definir datas padrão para o período personalizado
            const today = new Date();
            const oneMonthAgo = new Date();
            oneMonthAgo.setMonth(today.getMonth() - 1);
            
            document.getElementById('start-date').value = oneMonthAgo.toISOString().split('T')[0];
            document.getElementById('end-date').value = today.toISOString().split('T')[0];
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
            loadStatistics();
            loadTopDoctors();
            initCharts();

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