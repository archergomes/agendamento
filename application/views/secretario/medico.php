<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicos - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Página de gerenciamento de médicos para secretários do Hospital Público de Matlhovele">
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

        /* ESTILOS ESPECÍFICOS PARA MÉDICOS */
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

        .doctor-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: all 0.2s;
        }

        .doctor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .doctor-card.active {
            border-left-color: #10b981;
        }

        .doctor-card.inactive {
            border-left-color: #6b7280;
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

        @media (max-width: 768px) {
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
                    <a href="<?php echo site_url('secretario/agendamentos'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('secretario/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('secretario/medicos'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Gestão de Médicos</h2>
                            <p class="text-gray-600">Gerencie todos os médicos do sistema</p>
                        </div>
                        <button onclick="abrirModalNovoMedico()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-user-md"></i>
                            Novo Médico
                        </button>
                    </div>

                    <!-- KPI Overview -->
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-total">0</div>
                            <div class="kpi-label">Total de Médicos</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-ativos">0</div>
                            <div class="kpi-label">Médicos Ativos</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-consultas">0</div>
                            <div class="kpi-label">Consultas Hoje</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-value" id="kpi-especialidades">0</div>
                            <div class="kpi-label">Especialidades</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Ações Rápidas</h3>
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="abrirModalNovoMedico()">
                            <div class="quick-action-icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Novo Médico</h4>
                            <p class="text-sm text-gray-600 mt-2">Cadastrar novo médico</p>
                        </div>
                        <div class="quick-action-card" onclick="exportarMedicos()">
                            <div class="quick-action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Exportar Dados</h4>
                            <p class="text-sm text-gray-600 mt-2">Exportar lista de médicos</p>
                        </div>
                        <div class="quick-action-card" onclick="gerarHorarios()">
                            <div class="quick-action-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Horários</h4>
                            <p class="text-sm text-gray-600 mt-2">Gerir horários</p>
                        </div>
                        <div class="quick-action-card" onclick="gerarRelatorio()">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Relatório</h4>
                            <p class="text-sm text-gray-600 mt-2">Gerar relatório de médicos</p>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="filter-section">
                    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                        <div class="search-container flex-1">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="search-input" class="search-input" 
                                   placeholder="Pesquisar por nome, especialidade, BI ou email...">
                        </div>
                        <div class="flex gap-2">
                            <button onclick="carregarMedicos()" class="action-btn secondary">
                                <i class="fas fa-sync-alt mr-1"></i>Atualizar
                            </button>
                            <button onclick="limparFiltros()" class="action-btn secondary">
                                <i class="fas fa-filter mr-1"></i>Limpar Filtros
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Doctors List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Médicos</h3>
                            <div class="text-sm text-gray-600">
                                <span id="doctors-count">0</span> médicos encontrados
                            </div>
                        </div>

                        <!-- Table View (Desktop) -->
                        <div class="hidden md:block">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Especialidade</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>BI/Identificação</th>
                                        <th>Agendamentos</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="doctors-table">
                                    <!-- Médicos serão carregados via JavaScript -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Card View (Mobile) -->
                        <div id="doctors-cards" class="md:hidden">
                            <!-- Cards serão carregados via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando médicos...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-user-md"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum médico encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há médicos que correspondam aos seus critérios.</p>
                            <button onclick="abrirModalNovoMedico()" class="action-btn success">
                                <i class="fas fa-user-md mr-2"></i>Cadastrar Primeiro Médico
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Novo Médico -->
    <div id="novo-medico-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Cadastrar Novo Médico</h3>
                <button onclick="fecharModalNovoMedico()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-novo-medico">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" class="form-input" id="input-nome" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Especialidade *</label>
                            <select class="form-input" id="input-especialidade" required>
                                <option value="">Selecione uma especialidade</option>
                                <option value="Cardiologia">Cardiologia</option>
                                <option value="Dermatologia">Dermatologia</option>
                                <option value="Ginecologia">Ginecologia</option>
                                <option value="Neurologia">Neurologia</option>
                                <option value="Ortopedia">Ortopedia</option>
                                <option value="Pediatria">Pediatria</option>
                                <option value="Psiquiatria">Psiquiatria</option>
                                <option value="Urologia">Urologia</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Telefone *</label>
                            <input type="tel" class="form-input" id="input-telefone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" id="input-email">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">BI/Identificação *</label>
                            <input type="text" class="form-input" id="input-bi" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">CRM/Registro *</label>
                            <input type="text" class="form-input" id="input-crm" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-input" id="input-endereco">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" id="input-observacoes" placeholder="Observações sobre o médico..."></textarea>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalNovoMedico()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Cadastrar Médico</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Edição de Médico -->
    <div id="editar-medico-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Médico</h3>
                <button onclick="fecharModalEditarMedico()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-editar-medico">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" class="form-input" id="editar-nome" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Especialidade *</label>
                            <select class="form-input" id="editar-especialidade" required>
                                <option value="">Selecione uma especialidade</option>
                                <option value="Cardiologia">Cardiologia</option>
                                <option value="Dermatologia">Dermatologia</option>
                                <option value="Ginecologia">Ginecologia</option>
                                <option value="Neurologia">Neurologia</option>
                                <option value="Ortopedia">Ortopedia</option>
                                <option value="Pediatria">Pediatria</option>
                                <option value="Psiquiatria">Psiquiatria</option>
                                <option value="Urologia">Urologia</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Telefone *</label>
                            <input type="tel" class="form-input" id="editar-telefone" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" id="editar-email">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">BI/Identificação</label>
                            <input type="text" class="form-input" id="editar-bi" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">CRM/Registro *</label>
                            <input type="text" class="form-input" id="editar-crm" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Endereço</label>
                        <input type="text" class="form-input" id="editar-endereco">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" id="editar-observacoes" placeholder="Observações sobre o médico..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" id="editar-status">
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="fecharModalEditarMedico()" class="action-btn secondary">Cancelar</button>
                    <button type="submit" class="action-btn success">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Detalhes do Médico -->
    <div id="detalhes-medico-modal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Detalhes do Médico</h3>
                <button onclick="fecharModalDetalhesMedico()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detalhes-medico-content">
                <!-- Conteúdo será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentSearch = '';
        let medicoEditando = null;

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
                    total: 24,
                    ativos: 22,
                    consultas: 35,
                    especialidades: 8
                };

                document.getElementById('kpi-total').textContent = kpis.total;
                document.getElementById('kpi-ativos').textContent = kpis.ativos;
                document.getElementById('kpi-consultas').textContent = kpis.consultas;
                document.getElementById('kpi-especialidades').textContent = kpis.especialidades;

            } catch (error) {
                console.error('Erro ao carregar KPIs:', error);
            }
        }

        // Carregar médicos
        async function carregarMedicos() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const table = document.getElementById('doctors-table');
            const cards = document.getElementById('doctors-cards');
            const count = document.getElementById('doctors-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (table) table.innerHTML = '';
            if (cards) cards.innerHTML = '';

            try {
                // Simulação de dados - em produção, buscar da API
                const medicos = [
                    {
                        id: 1,
                        nome: 'Dr. João Silva',
                        especialidade: 'Cardiologia',
                        telefone: '+258 84 123 4567',
                        bi: '123456789LA123',
                        crm: 'CRM-MZ-12345',
                        email: 'joao.silva@hospital.mz',
                        endereco: 'Av. 25 de Setembro, Maputo',
                        observacoes: 'Especialista em cardiologia intervencionista',
                        status: 'active',
                        agendamentos: 12,
                        data_cadastro: '2024-01-10'
                    },
                    {
                        id: 2,
                        nome: 'Dra. Maria Santos',
                        especialidade: 'Pediatria',
                        telefone: '+258 85 987 6543',
                        bi: '987654321LA456',
                        crm: 'CRM-MZ-67890',
                        email: 'maria.santos@hospital.mz',
                        endereco: 'Bairro Central, Matola',
                        observacoes: '',
                        status: 'active',
                        agendamentos: 8,
                        data_cadastro: '2024-01-12'
                    },
                    {
                        id: 3,
                        nome: 'Dr. Carlos Pereira',
                        especialidade: 'Ortopedia',
                        telefone: '+258 86 555 8888',
                        bi: '555888777LA789',
                        crm: 'CRM-MZ-54321',
                        email: 'carlos.pereira@hospital.mz',
                        endereco: 'Zona Verde, Maputo',
                        observacoes: 'Especialista em cirurgia do joelho',
                        status: 'active',
                        agendamentos: 15,
                        data_cadastro: '2024-01-15'
                    }
                ];

                // Aplicar filtro de busca
                let medicosFiltrados = medicos;
                if (currentSearch) {
                    const searchLower = currentSearch.toLowerCase();
                    medicosFiltrados = medicos.filter(medico =>
                        medico.nome.toLowerCase().includes(searchLower) ||
                        medico.especialidade.toLowerCase().includes(searchLower) ||
                        medico.bi.toLowerCase().includes(searchLower) ||
                        (medico.email && medico.email.toLowerCase().includes(searchLower))
                    );
                }

                if (loading) loading.classList.add('hidden');

                if (count) count.textContent = medicosFiltrados.length;

                if (medicosFiltrados.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderMedicos(medicosFiltrados);

            } catch (error) {
                console.error('Erro ao carregar médicos:', error);
                showNotification('Erro ao carregar médicos.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar médicos
        function renderMedicos(medicos) {
            const table = document.getElementById('doctors-table');
            const cards = document.getElementById('doctors-cards');

            // Table View (Desktop)
            if (table) {
                table.innerHTML = medicos.map(medico => {
                    const statusClass = medico.status === 'active' ? 'status-active' : 'status-inactive';
                    const statusText = medico.status === 'active' ? 'Ativo' : 'Inativo';

                    return `
                        <tr>
                            <td class="font-medium">${medico.nome}</td>
                            <td>${medico.especialidade}</td>
                            <td>${medico.telefone}</td>
                            <td>${medico.email || '-'}</td>
                            <td>${medico.bi}</td>
                            <td>${medico.agendamentos}</td>
                            <td>
                                <span class="status-badge ${statusClass}">${statusText}</span>
                            </td>
                            <td>
                                <div class="flex gap-2 action-buttons">
                                    <button class="action-btn text-sm" onclick="verDetalhesMedico(${medico.id})">
                                        <i class="fas fa-eye mr-1"></i>
                                    </button>
                                    <button class="action-btn warning text-sm" onclick="editarMedico(${medico.id})">
                                        <i class="fas fa-edit mr-1"></i>
                                    </button>
                                    <button class="action-btn secondary text-sm" onclick="gerirHorariosMedico(${medico.id})">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // Card View (Mobile)
            if (cards) {
                cards.innerHTML = medicos.map(medico => {
                    const statusClass = medico.status === 'active' ? 'status-active' : 'status-inactive';
                    const statusText = medico.status === 'active' ? 'Ativo' : 'Inativo';
                    const cardClass = medico.status === 'active' ? 'active' : 'inactive';

                    return `
                        <div class="doctor-card ${cardClass}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-800">${medico.nome}</h4>
                                    <p class="text-sm text-gray-600">${medico.especialidade}</p>
                                </div>
                                <span class="status-badge ${statusClass}">${statusText}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Telefone:</span>
                                    <p class="font-medium">${medico.telefone}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <p class="font-medium">${medico.email || '-'}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">CRM:</span>
                                    <p class="font-medium">${medico.crm}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Agendamentos:</span>
                                    <p class="font-medium">${medico.agendamentos}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 action-buttons">
                                <button class="action-btn flex-1 text-sm" onclick="verDetalhesMedico(${medico.id})">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </button>
                                <button class="action-btn warning flex-1 text-sm" onclick="editarMedico(${medico.id})">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                                <button class="action-btn secondary flex-1 text-sm" onclick="gerirHorariosMedico(${medico.id})">
                                    <i class="fas fa-calendar-alt mr-1"></i>Horários
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        // Funções de modal
        function abrirModalNovoMedico() {
            document.getElementById('novo-medico-modal').classList.add('show');
        }

        function fecharModalNovoMedico() {
            document.getElementById('novo-medico-modal').classList.remove('show');
            document.getElementById('form-novo-medico').reset();
        }

        function editarMedico(medicoId) {
            medicoEditando = medicoId;
            // Carregar dados do médico e abrir modal de edição
            carregarDadosEdicao(medicoId);
            document.getElementById('editar-medico-modal').classList.add('show');
        }

        function fecharModalEditarMedico() {
            document.getElementById('editar-medico-modal').classList.remove('show');
            medicoEditando = null;
        }

        function verDetalhesMedico(medicoId) {
            // Carregar dados completos do médico
            carregarDetalhesMedico(medicoId);
            document.getElementById('detalhes-medico-modal').classList.add('show');
        }

        function fecharModalDetalhesMedico() {
            document.getElementById('detalhes-medico-modal').classList.remove('show');
        }

        // Funções de ação
        function exportarMedicos() {
            showNotification('Exportando lista de médicos...', 'info');
            // Implementar exportação
        }

        function gerarHorarios() {
            showNotification('Abrindo gestão de horários...', 'info');
            // Implementar gestão de horários
        }

        function gerirHorariosMedico(medicoId) {
            showNotification(`Gerindo horários do médico...`, 'info');
            // Implementar gestão de horários específica do médico
        }

        function gerarRelatorio() {
            showNotification('Gerando relatório de médicos...', 'info');
            // Implementar geração de relatório
        }

        function limparFiltros() {
            document.getElementById('search-input').value = '';
            currentSearch = '';
            carregarMedicos();
        }

        // Funções auxiliares
        function carregarDadosEdicao(medicoId) {
            // Simulação de carregamento de dados para edição
            // Em produção, buscar dados do médico da API
            const medico = {
                id: medicoId,
                nome: 'Dr. João Silva',
                especialidade: 'Cardiologia',
                telefone: '+258 84 123 4567',
                bi: '123456789LA123',
                crm: 'CRM-MZ-12345',
                email: 'joao.silva@hospital.mz',
                endereco: 'Av. 25 de Setembro, Maputo',
                observacoes: 'Especialista em cardiologia intervencionista',
                status: 'active'
            };

            document.getElementById('editar-nome').value = medico.nome;
            document.getElementById('editar-especialidade').value = medico.especialidade;
            document.getElementById('editar-telefone').value = medico.telefone;
            document.getElementById('editar-email').value = medico.email;
            document.getElementById('editar-bi').value = medico.bi;
            document.getElementById('editar-crm').value = medico.crm;
            document.getElementById('editar-endereco').value = medico.endereco;
            document.getElementById('editar-observacoes').value = medico.observacoes;
            document.getElementById('editar-status').value = medico.status;
        }

        function carregarDetalhesMedico(medicoId) {
            // Simulação de carregamento de detalhes
            const medico = {
                id: medicoId,
                nome: 'Dr. João Silva',
                especialidade: 'Cardiologia',
                telefone: '+258 84 123 4567',
                bi: '123456789LA123',
                crm: 'CRM-MZ-12345',
                email: 'joao.silva@hospital.mz',
                endereco: 'Av. 25 de Setembro, Maputo',
                observacoes: 'Especialista em cardiologia intervencionista',
                status: 'Ativo',
                agendamentos: 12,
                data_cadastro: '10/01/2024',
                ultima_consulta: '15/01/2024'
            };

            const content = document.getElementById('detalhes-medico-content');
            content.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nome Completo</label>
                            <p class="font-medium">${medico.nome}</p>
                        </div>
                        <div>
                            <label class="form-label">Especialidade</label>
                            <p class="font-medium">${medico.especialidade}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">BI/Identificação</label>
                            <p class="font-medium">${medico.bi}</p>
                        </div>
                        <div>
                            <label class="form-label">CRM/Registro</label>
                            <p class="font-medium">${medico.crm}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Telefone</label>
                            <p class="font-medium">${medico.telefone}</p>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <p class="font-medium">${medico.email || 'Não informado'}</p>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Endereço</label>
                        <p class="font-medium">${medico.endereco || 'Não informado'}</p>
                    </div>
                    <div>
                        <label class="form-label">Observações</label>
                        <p class="font-medium">${medico.observacoes || 'Nenhuma observação'}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Status</label>
                            <p class="font-medium"><span class="status-badge status-active">${medico.status}</span></p>
                        </div>
                        <div>
                            <label class="form-label">Agendamentos Ativos</label>
                            <p class="font-medium">${medico.agendamentos}</p>
                        </div>
                        <div>
                            <label class="form-label">Data de Cadastro</label>
                            <p class="font-medium">${medico.data_cadastro}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
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
                        currentSearch = this.value;
                        carregarMedicos();
                    }, 500);
                });
            }

            // Formulário de novo médico
            const formNovoMedico = document.getElementById('form-novo-medico');
            if (formNovoMedico) {
                formNovoMedico.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Simulação de criação de médico
                    showNotification('Médico cadastrado com sucesso!', 'success');
                    fecharModalNovoMedico();
                    carregarMedicos();
                });
            }

            // Formulário de edição de médico
            const formEditarMedico = document.getElementById('form-editar-medico');
            if (formEditarMedico) {
                formEditarMedico.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Simulação de edição de médico
                    showNotification('Médico atualizado com sucesso!', 'success');
                    fecharModalEditarMedico();
                    carregarMedicos();
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
            carregarMedicos();

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