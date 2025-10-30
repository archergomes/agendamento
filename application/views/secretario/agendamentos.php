<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Página de gerenciamento de agendamentos para secretários do Hospital Público de Matlhovele">
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

        /* NOVOS ESTILOS PARA AGENDAMENTOS */
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

        .filter-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
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

        .date-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .date-input {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
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

        .appointment-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: all 0.2s;
        }

        .appointment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .appointment-card.confirmed {
            border-left-color: #10b981;
        }

        .appointment-card.pending {
            border-left-color: #f59e0b;
        }

        .appointment-card.cancelled {
            border-left-color: #ef4444;
        }

        .appointment-card.completed {
            border-left-color: #8b5cf6;
        }

        @media (max-width: 768px) {
            .date-filter {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
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
                    <a href="<?php echo site_url('secretario/agendamentos'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Gestão de Agendamentos</h2>
                            <p class="text-gray-600">Gerencie todos os agendamentos do sistema</p>
                        </div>
                        <button onclick="abrirModalNovoAgendamento()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Novo Agendamento
                        </button>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-total">0</div>
                            <div class="kpi-label">Total de Agendamentos</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-hoje">0</div>
                            <div class="kpi-label">Agendamentos Hoje</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-pendentes">0</div>
                            <div class="kpi-label">Pendentes</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-confirmados">0</div>
                            <div class="kpi-label">Confirmados</div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filter-section">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                        <!-- Filter Tabs -->
                        <div class="filter-tabs">
                            <div class="filter-tab active" data-filter="todos">Todos</div>
                            <div class="filter-tab" data-filter="hoje">Hoje</div>
                            <div class="filter-tab" data-filter="pendentes">Pendentes</div>
                            <div class="filter-tab" data-filter="confirmados">Confirmados</div>
                            <div class="filter-tab" data-filter="cancelados">Cancelados</div>
                        </div>

                        <!-- Date Filter -->
                        <div class="date-filter">
                            <input type="date" id="data-inicio" class="date-input">
                            <span>até</span>
                            <input type="date" id="data-fim" class="date-input">
                            <button onclick="aplicarFiltroData()" class="action-btn secondary">
                                <i class="fas fa-filter mr-1"></i>Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search-input" class="search-input" 
                               placeholder="Pesquisar por paciente, médico ou especialidade...">
                    </div>
                </div>

                <!-- Appointments List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Agendamentos</h3>
                            <div class="flex items-center gap-2">
                                <button onclick="exportarAgendamentos()" class="action-btn secondary text-sm">
                                    <i class="fas fa-download mr-1"></i>Exportar
                                </button>
                                <button onclick="carregarAgendamentos()" class="action-btn secondary text-sm">
                                    <i class="fas fa-sync-alt mr-1"></i>Atualizar
                                </button>
                            </div>
                        </div>

                        <!-- Table View (Desktop) -->
                        <div class="hidden md:block">
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
                                <tbody id="appointment-table">
                                    <!-- Agendamentos serão carregados via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Card View (Mobile) -->
                        <div id="appointment-cards" class="md:hidden">
                            <!-- Cards serão carregados via JavaScript -->
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
                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" id="input-observacoes" placeholder="Observações adicionais..."></textarea>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalNovoAgendamento()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Agendar Consulta</button>
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

    <!-- Modal de Edição de Agendamento -->
    <div id="editar-agendamento-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Agendamento</h3>
                <button onclick="fecharModalEditarAgendamento()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-editar-agendamento">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Paciente</label>
                            <input type="text" class="form-input" id="editar-paciente" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Médico</label>
                            <input type="text" class="form-input" id="editar-medico" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-input" id="editar-data" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Horário</label>
                            <select class="form-input" id="editar-horario" required>
                                <option value="">Selecione um horário</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="editar-status">
                            <option value="pending">Pendente</option>
                            <option value="confirmed">Confirmado</option>
                            <option value="cancelled">Cancelado</option>
                            <option value="completed">Concluído</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" id="editar-observacoes" placeholder="Observações..."></textarea>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalEditarAgendamento()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentFilter = 'todos';
        let currentSearch = '';
        let appointmentToCancel = null;
        let appointmentToEdit = null;

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
                    total: 45,
                    hoje: 8,
                    pendentes: 12,
                    confirmados: 28
                };

                document.getElementById('kpi-total').textContent = kpis.total;
                document.getElementById('kpi-hoje').textContent = kpis.hoje;
                document.getElementById('kpi-pendentes').textContent = kpis.pendentes;
                document.getElementById('kpi-confirmados').textContent = kpis.confirmados;

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
            }
        }

        // Carregar agendamentos
        async function carregarAgendamentos() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const table = document.getElementById('appointment-table');
            const cards = document.getElementById('appointment-cards');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (table) table.innerHTML = '';
            if (cards) cards.innerHTML = '';

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
                        status: 'confirmed',
                        motivo: 'Consulta de rotina',
                        observacoes: 'Paciente com histórico familiar'
                    },
                    {
                        id: 2,
                        paciente: 'João Pereira',
                        medico: 'Dra. Fernandes',
                        especialidade: 'Dermatologia',
                        data: '2024-01-15',
                        horario: '10:30',
                        status: 'pending',
                        motivo: 'Avaliação de lesão na pele',
                        observacoes: ''
                    },
                    {
                        id: 3,
                        paciente: 'Ana Costa',
                        medico: 'Dr. Oliveira',
                        especialidade: 'Pediatria',
                        data: '2024-01-16',
                        horario: '14:00',
                        status: 'confirmed',
                        motivo: 'Consulta infantil',
                        observacoes: 'Primeira consulta'
                    }
                ];

                // Aplicar filtros
                let agendamentosFiltrados = agendamentos.filter(agendamento => {
                    // Filtro por status
                    if (currentFilter !== 'todos' && currentFilter !== 'hoje') {
                        if (currentFilter === 'pendentes' && agendamento.status !== 'pending') return false;
                        if (currentFilter === 'confirmados' && agendamento.status !== 'confirmed') return false;
                        if (currentFilter === 'cancelados' && agendamento.status !== 'cancelled') return false;
                    }

                    // Filtro por data (hoje)
                    if (currentFilter === 'hoje') {
                        const hoje = new Date().toISOString().split('T')[0];
                        if (agendamento.data !== hoje) return false;
                    }

                    // Filtro por busca
                    if (currentSearch) {
                        const searchLower = currentSearch.toLowerCase();
                        if (!agendamento.paciente.toLowerCase().includes(searchLower) &&
                            !agendamento.medico.toLowerCase().includes(searchLower) &&
                            !agendamento.especialidade.toLowerCase().includes(searchLower)) {
                            return false;
                        }
                    }

                    return true;
                });

                if (loading) loading.classList.add('hidden');

                if (agendamentosFiltrados.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderAgendamentos(agendamentosFiltrados);

            } catch (error) {
                console.error('Erro ao carregar agendamentos:', error);
                showNotification('Erro ao carregar agendamentos.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar agendamentos
        function renderAgendamentos(agendamentos) {
            const table = document.getElementById('appointment-table');
            const cards = document.getElementById('appointment-cards');

            // Table View (Desktop)
            if (table) {
                table.innerHTML = agendamentos.map(agendamento => {
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
                                <div class="flex gap-2 action-buttons">
                                    ${agendamento.status !== 'cancelled' ? `
                                        <button class="action-btn success text-sm" onclick="confirmarAgendamento(${agendamento.id})">
                                            <i class="fas fa-check mr-1"></i>
                                        </button>
                                        <button class="action-btn warning text-sm" onclick="editarAgendamento(${agendamento.id})">
                                            <i class="fas fa-edit mr-1"></i>
                                        </button>
                                        <button class="action-btn danger text-sm" onclick="solicitarCancelamento(${agendamento.id})">
                                            <i class="fas fa-times mr-1"></i>
                                        </button>
                                    ` : ''}
                                    <button class="action-btn secondary text-sm" onclick="verDetalhes(${agendamento.id})">
                                        <i class="fas fa-eye mr-1"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // Card View (Mobile)
            if (cards) {
                cards.innerHTML = agendamentos.map(agendamento => {
                    const statusClass = getStatusClass(agendamento.status);
                    const statusText = getStatusText(agendamento.status);
                    const cardClass = getCardClass(agendamento.status);

                    return `
                        <div class="appointment-card ${cardClass}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-800">${agendamento.paciente}</h4>
                                    <p class="text-sm text-gray-600">${agendamento.medico} - ${agendamento.especialidade}</p>
                                </div>
                                <span class="status-badge ${statusClass}">${statusText}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Data:</span>
                                    <p class="font-medium">${formatDate(agendamento.data)}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Horário:</span>
                                    <p class="font-medium">${agendamento.horario}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 action-buttons">
                                ${agendamento.status !== 'cancelled' ? `
                                    <button class="action-btn success flex-1 text-sm" onclick="confirmarAgendamento(${agendamento.id})">
                                        <i class="fas fa-check mr-1"></i>Confirmar
                                    </button>
                                    <button class="action-btn warning flex-1 text-sm" onclick="editarAgendamento(${agendamento.id})">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </button>
                                    <button class="action-btn danger flex-1 text-sm" onclick="solicitarCancelamento(${agendamento.id})">
                                        <i class="fas fa-times mr-1"></i>Cancelar
                                    </button>
                                ` : ''}
                                <button class="action-btn secondary flex-1 text-sm" onclick="verDetalhes(${agendamento.id})">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }
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

        function getCardClass(status) {
            const classMap = {
                'confirmed': 'confirmed',
                'pending': 'pending',
                'cancelled': 'cancelled',
                'completed': 'completed'
            };
            return classMap[status] || 'pending';
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

        function solicitarCancelamento(agendamentoId) {
            appointmentToCancel = agendamentoId;
            document.getElementById('confirmacao-cancelamento-modal').classList.add('show');
        }

        function fecharModalConfirmacaoCancelamento() {
            document.getElementById('confirmacao-cancelamento-modal').classList.remove('show');
            appointmentToCancel = null;
            document.getElementById('input-motivo-cancelamento').value = '';
        }

        function editarAgendamento(agendamentoId) {
            appointmentToEdit = agendamentoId;
            // Carregar dados do agendamento e abrir modal de edição
            carregarDadosEdicao(agendamentoId);
            document.getElementById('editar-agendamento-modal').classList.add('show');
        }

        function fecharModalEditarAgendamento() {
            document.getElementById('editar-agendamento-modal').classList.remove('show');
            appointmentToEdit = null;
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

        function verDetalhes(agendamentoId) {
            showNotification(`Visualizando detalhes do agendamento ${agendamentoId}...`, 'info');
            // Implementar visualização de detalhes
        }

        function exportarAgendamentos() {
            showNotification('Exportando agendamentos...', 'info');
            // Implementar exportação
        }

        function aplicarFiltroData() {
            // Implementar filtro por data
            carregarAgendamentos();
        }

        function carregarDadosModalAgendamento() {
            // Simulação de carregamento de dados para o modal
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

        function carregarDadosEdicao(agendamentoId) {
            // Simulação de carregamento de dados para edição
            // Em produção, buscar dados do agendamento da API
        }

        // Configurar eventos
        function setupEventListeners() {
            // Filtros
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    carregarAgendamentos();
                });
            });

            // Busca em tempo real
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        currentSearch = this.value;
                        carregarAgendamentos();
                    }, 500);
                });
            }

            // Formulário de novo agendamento
            const formNovoAgendamento = document.getElementById('form-novo-agendamento');
            if (formNovoAgendamento) {
                formNovoAgendamento.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Simulação de criação de agendamento
                    showNotification('Agendamento criado com sucesso!', 'success');
                    fecharModalNovoAgendamento();
                    carregarAgendamentos();
                });
            }

            // Formulário de edição de agendamento
            const formEditarAgendamento = document.getElementById('form-editar-agendamento');
            if (formEditarAgendamento) {
                formEditarAgendamento.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Simulação de edição de agendamento
                    showNotification('Agendamento atualizado com sucesso!', 'success');
                    fecharModalEditarAgendamento();
                    carregarAgendamentos();
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