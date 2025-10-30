<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudos - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de laudos médicos do Hospital Público de Matlhovele">
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

        .laudo-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .laudo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .laudo-card.urgent {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }

        .laudo-card.pending {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }

        .laudo-card.completed {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .laudo-card.revision {
            border-left-color: #8b5cf6;
            background-color: #faf5ff;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pendente {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-finalizado {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-revisao {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-cancelado {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-urgente {
            background-color: #fef2f2;
            color: #dc2626;
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

        .type-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 500;
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
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
            max-width: 1000px;
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

        .laudo-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #8b5cf6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .laudo-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .laudo-content {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 2rem;
            background-color: white;
            font-family: 'Roboto', serif;
        }

        .laudo-section {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .laudo-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #374151;
            margin-bottom: 1rem;
            text-align: center;
            text-transform: uppercase;
        }

        .laudo-subtitle {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 0.25rem;
        }

        .signature-area {
            border-top: 2px solid #374151;
            padding-top: 1rem;
            margin-top: 3rem;
            text-align: center;
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

        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .template-item {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .template-item:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .template-item.selected {
            border-color: #3b82f6;
            background-color: #dbeafe;
        }

        .findings-list {
            list-style-type: none;
            padding: 0;
        }

        .finding-item {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .finding-actions {
            display: flex;
            gap: 0.5rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            
            .laudo-content {
                border: none;
                box-shadow: none;
                padding: 0;
            }
            
            .modal-content {
                box-shadow: none;
                max-width: none;
                width: 100%;
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
                    <a href="<?php echo site_url('medico/laudos'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Laudos Médicos</h2>
                            <p class="text-gray-600">Gerencie e emita laudos e relatórios médicos.</p>
                        </div>
                        <button onclick="novoLaudo()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Novo Laudo
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="metric-card" onclick="filterByStatus('pendentes')">
                            <div class="text-2xl font-bold text-orange-600" id="total-pendentes">0</div>
                            <div class="text-sm text-gray-600">Pendentes</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('finalizados')">
                            <div class="text-2xl font-bold text-green-600" id="total-finalizados">0</div>
                            <div class="text-sm text-gray-600">Finalizados</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('revisao')">
                            <div class="text-2xl font-bold text-purple-600" id="total-revisao">0</div>
                            <div class="text-sm text-gray-600">Em Revisão</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('urgentes')">
                            <div class="text-2xl font-bold text-red-600" id="total-urgentes">0</div>
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
                            <div class="filter-tab" data-filter="pendentes">Pendentes</div>
                            <div class="filter-tab" data-filter="finalizados">Finalizados</div>
                            <div class="filter-tab" data-filter="revisao">Em Revisão</div>
                            <div class="filter-tab" data-filter="urgentes">Urgentes</div>
                            <div class="filter-tab" data-filter="meus-laudos">Meus Laudos</div>
                        </div>

                        <!-- Search -->
                        <div class="flex gap-3 w-full md:w-auto">
                            <div class="relative flex-1 md:w-80">
                                <input type="text" id="search-input" placeholder="Pesquisar paciente, tipo de laudo..." 
                                       class="pl-10 pr-4 py-2 border rounded-lg w-full">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button onclick="exportLaudos()" class="action-btn warning flex items-center gap-2">
                                <i class="fas fa-download"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Laudos List -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Lista de Laudos</h3>
                            <div class="text-sm text-gray-600">
                                <span id="laudos-count">0</span> laudos encontrados
                            </div>
                        </div>
                        
                        <div id="laudos-list">
                            <!-- Laudos serão carregados via JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando laudos...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-file-medical-alt"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum laudo encontrado</h3>
                            <p class="text-gray-600 mb-4">Não há laudos que correspondam aos seus critérios de busca.</p>
                            <button onclick="novoLaudo()" class="action-btn success">
                                <i class="fas fa-plus mr-2"></i>Criar Novo Laudo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Visualização do Laudo -->
    <div id="laudo-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4 no-print">
                <h3 class="text-lg font-medium text-gray-700">Visualizar Laudo</h3>
                <div class="flex gap-2">
                    <button onclick="imprimirLaudo()" class="action-btn warning flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="laudo-details">
                <!-- Conteúdo do laudo será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Novo Laudo -->
    <div id="novo-laudo-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Criar Novo Laudo</h3>
                <button onclick="closeNovoLaudoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="novo-laudo-content">
                <!-- Formulário de novo laudo será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Edição do Laudo -->
    <div id="editar-laudo-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Laudo</h3>
                <button onclick="closeEditarLaudoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editar-laudo-content">
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
                const response = await fetch('<?php echo site_url('api/medico/laudos_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-pendentes').textContent = stats.pendentes || 0;
                document.getElementById('total-finalizados').textContent = stats.finalizados || 0;
                document.getElementById('total-revisao').textContent = stats.revisao || 0;
                document.getElementById('total-urgentes').textContent = stats.urgentes || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Carregar laudos
        async function loadLaudos() {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('laudos-list');
            const count = document.getElementById('laudos-count');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            try {
                const params = new URLSearchParams();
                if (currentFilter !== 'todos') params.append('filter', currentFilter);
                if (currentSearch) params.append('search', currentSearch);

                const response = await fetch(`<?php echo site_url('api/medico/laudos'); ?>?${params}`);
                const laudos = await response.json();

                if (loading) loading.classList.add('hidden');

                if (laudos.error) {
                    showNotification(laudos.error, 'error');
                    return;
                }

                if (count) count.textContent = laudos.length || 0;

                if (laudos.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderLaudos(laudos);

            } catch (error) {
                console.error('Erro ao carregar laudos:', error);
                showNotification('Erro ao carregar laudos.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar laudos
        function renderLaudos(laudos) {
            const list = document.getElementById('laudos-list');
            if (!list) return;

            list.innerHTML = laudos.map(laudo => {
                const cardClass = getCardClass(laudo);
                const statusClass = getStatusClass(laudo.status);
                const priorityClass = getPriorityClass(laudo.prioridade);
                const initials = getInitials(laudo.paciente_nome);
                const dataCriacao = formatDate(laudo.data_criacao);
                const dataValidade = formatDate(laudo.data_validade);

                return `
                    <div class="laudo-card ${cardClass}" data-laudo-id="${laudo.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Informações do Laudo -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="laudo-icon">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${laudo.tipo_laudo}</h4>
                                        <span class="status-badge ${statusClass}">${laudo.status}</span>
                                        <span class="priority-badge ${priorityClass}">${laudo.prioridade}</span>
                                        <span class="type-badge">${laudo.tipo_exame || 'Geral'}</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-600">Paciente:</span>
                                            <p class="font-medium">${laudo.paciente_nome}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Médico:</span>
                                            <p class="font-medium">${laudo.medico_nome}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Data do Laudo:</span>
                                            <p class="font-medium">${dataCriacao}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Validade:</span>
                                            <p class="font-medium ${isExpired(laudo.data_validade) ? 'text-red-600' : ''}">${dataValidade}</p>
                                        </div>
                                    </div>

                                    <!-- Resumo do Laudo -->
                                    <div class="bg-gray-50 rounded p-3 mb-3">
                                        <span class="text-gray-600 text-sm">Resumo:</span>
                                        <p class="text-sm text-gray-700 mt-1">${laudo.resumo || 'Sem resumo disponível'}</p>
                                    </div>

                                    <!-- Achados Principais -->
                                    ${laudo.achados_principais ? `
                                        <div class="text-sm">
                                            <span class="text-gray-600">Achados Principais:</span>
                                            <p class="text-gray-700">${laudo.achados_principais}</p>
                                        </div>
                                    ` : ''}

                                    <!-- Conclusão -->
                                    ${laudo.conclusao ? `
                                        <div class="mt-3 p-3 bg-blue-50 rounded border border-blue-200">
                                            <span class="text-gray-600 text-sm font-medium">Conclusão:</span>
                                            <p class="text-sm text-gray-800 mt-1">${laudo.conclusao}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                <button class="action-btn ver-laudo" data-id="${laudo.id}">
                                    <i class="fas fa-eye mr-1"></i>Visualizar
                                </button>
                                <button class="action-btn editar-laudo" data-id="${laudo.id}">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </button>
                                ${laudo.status === 'pendente' ? `
                                    <button class="action-btn success finalizar-laudo" data-id="${laudo.id}">
                                        <i class="fas fa-check mr-1"></i>Finalizar
                                    </button>
                                ` : ''}
                                ${laudo.status === 'finalizado' ? `
                                    <button class="action-btn warning reabrir-laudo" data-id="${laudo.id}">
                                        <i class="fas fa-redo mr-1"></i>Reabrir
                                    </button>
                                ` : ''}
                                <button class="action-btn danger cancelar-laudo" data-id="${laudo.id}">
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

        // Verificar se o laudo está expirado
        function isExpired(dataValidade) {
            if (!dataValidade) return false;
            return new Date(dataValidade) < new Date();
        }

        // Obter classe CSS do card baseado no status
        function getCardClass(laudo) {
            if (laudo.prioridade === 'alta') return 'urgent';
            if (laudo.status === 'pendente') return 'pending';
            if (laudo.status === 'finalizado') return 'completed';
            if (laudo.status === 'revisao') return 'revision';
            return '';
        }

        // Obter classe CSS do status
        function getStatusClass(status) {
            const statusMap = {
                'pendente': 'status-pendente',
                'finalizado': 'status-finalizado',
                'revisao': 'status-revisao',
                'cancelado': 'status-cancelado',
                'urgente': 'status-urgente'
            };
            return statusMap[status] || 'status-pendente';
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
            // Ver laudo
            document.querySelectorAll('.ver-laudo').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const laudoId = e.target.closest('button').dataset.id;
                    verLaudoCompleto(laudoId);
                });
            });

            // Editar laudo
            document.querySelectorAll('.editar-laudo').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const laudoId = e.target.closest('button').dataset.id;
                    editarLaudo(laudoId);
                });
            });

            // Finalizar laudo
            document.querySelectorAll('.finalizar-laudo').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const laudoId = e.target.closest('button').dataset.id;
                    finalizarLaudo(laudoId);
                });
            });

            // Reabrir laudo
            document.querySelectorAll('.reabrir-laudo').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const laudoId = e.target.closest('button').dataset.id;
                    reabrirLaudo(laudoId);
                });
            });

            // Cancelar laudo
            document.querySelectorAll('.cancelar-laudo').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const laudoId = e.target.closest('button').dataset.id;
                    cancelarLaudo(laudoId);
                });
            });
        }

        // Funções de ação
        async function verLaudoCompleto(laudoId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/laudo_completo/'); ?>${laudoId}`);
                const laudo = await response.json();

                if (laudo.error) {
                    showNotification(laudo.error, 'error');
                    return;
                }

                const modal = document.getElementById('laudo-modal');
                const details = document.getElementById('laudo-details');

                details.innerHTML = `
                    <div class="laudo-content">
                        <!-- Cabeçalho do Laudo -->
                        <div class="laudo-header text-center">
                            <h2 class="text-2xl font-bold">HOSPITAL PÚBLICO DE MATLHOVELE</h2>
                            <p class="text-lg">Departamento de ${laudo.departamento || 'Médico'}</p>
                            <p class="text-sm">${laudo.endereco_hospital || 'Maputo, Moçambique'}</p>
                        </div>

                        <div class="laudo-section">
                            <h3 class="laudo-title">LAUDO MÉDICO</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <strong>Nº do Laudo:</strong> ${laudo.id}
                                </div>
                                <div>
                                    <strong>Data de Emissão:</strong> ${formatDate(laudo.data_criacao)}
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Paciente -->
                        <div class="laudo-section">
                            <h4 class="laudo-subtitle">DADOS DO PACIENTE</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><strong>Nome:</strong> ${laudo.paciente_nome}</div>
                                <div><strong>BI/Identificação:</strong> ${laudo.paciente_bi || 'N/A'}</div>
                                <div><strong>Idade:</strong> ${laudo.idade || 'N/A'} anos</div>
                                <div><strong>Gênero:</strong> ${laudo.genero || 'N/A'}</div>
                            </div>
                        </div>

                        <!-- Informações do Médico -->
                        <div class="laudo-section">
                            <h4 class="laudo-subtitle">DADOS DO MÉDICO</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><strong>Médico Responsável:</strong> ${laudo.medico_nome}</div>
                                <div><strong>CRM:</strong> ${laudo.crm || 'N/A'}</div>
                                <div><strong>Especialidade:</strong> ${laudo.especialidade || 'N/A'}</div>
                            </div>
                        </div>

                        <!-- Tipo de Exame/Laudo -->
                        <div class="laudo-section">
                            <h4 class="laudo-subtitle">TIPO DE EXAME/LAUDO</h4>
                            <p><strong>${laudo.tipo_laudo}</strong> - ${laudo.tipo_exame || 'Exame Geral'}</p>
                            ${laudo.data_exame ? `<p><strong>Data do Exame:</strong> ${formatDate(laudo.data_exame)}</p>` : ''}
                        </div>

                        <!-- Descrição do Caso -->
                        ${laudo.descricao_caso ? `
                            <div class="laudo-section">
                                <h4 class="laudo-subtitle">DESCRIÇÃO DO CASO</h4>
                                <p>${laudo.descricao_caso}</p>
                            </div>
                        ` : ''}

                        <!-- Achados -->
                        <div class="laudo-section">
                            <h4 class="laudo-subtitle">ACHADOS</h4>
                            <p>${laudo.achados_principais || 'Sem achados registrados.'}</p>
                        </div>

                        <!-- Análise -->
                        ${laudo.analise ? `
                            <div class="laudo-section">
                                <h4 class="laudo-subtitle">ANÁLISE</h4>
                                <p>${laudo.analise}</p>
                            </div>
                        ` : ''}

                        <!-- Conclusão -->
                        <div class="laudo-section">
                            <h4 class="laudo-subtitle">CONCLUSÃO</h4>
                            <p>${laudo.conclusao || 'Sem conclusão definida.'}</p>
                        </div>

                        <!-- Recomendações -->
                        ${laudo.recomendacoes ? `
                            <div class="laudo-section">
                                <h4 class="laudo-subtitle">RECOMENDAÇÕES</h4>
                                <p>${laudo.recomendacoes}</p>
                            </div>
                        ` : ''}

                        <!-- Observações -->
                        ${laudo.observacoes ? `
                            <div class="laudo-section">
                                <h4 class="laudo-subtitle">OBSERVAÇÕES</h4>
                                <p>${laudo.observacoes}</p>
                            </div>
                        ` : ''}

                        <!-- Assinatura -->
                        <div class="signature-area">
                            <p>___________________________________</p>
                            <p><strong>${laudo.medico_nome}</strong></p>
                            <p>${laudo.especialidade || 'Médico'} - CRM: ${laudo.crm || 'N/A'}</p>
                            <p>Hospital Público de Matlhovele</p>
                            <p>Data: ${formatDate(laudo.data_criacao)}</p>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar laudo:', error);
                showNotification('Erro ao carregar laudo completo.', 'error');
            }
        }

        async function editarLaudo(laudoId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/laudo_editar/'); ?>${laudoId}`);
                const laudo = await response.json();

                if (laudo.error) {
                    showNotification(laudo.error, 'error');
                    return;
                }

                const modal = document.getElementById('editar-laudo-modal');
                const content = document.getElementById('editar-laudo-content');

                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Tipo de Laudo</label>
                                <select class="form-select" id="tipo-laudo">
                                    <option value="clinico" ${laudo.tipo_laudo === 'clinico' ? 'selected' : ''}>Clínico</option>
                                    <option value="laboratorial" ${laudo.tipo_laudo === 'laboratorial' ? 'selected' : ''}>Laboratorial</option>
                                    <option value="imagem" ${laudo.tipo_laudo === 'imagem' ? 'selected' : ''}>Imagem</option>
                                    <option value="patologico" ${laudo.tipo_laudo === 'patologico' ? 'selected' : ''}>Patológico</option>
                                    <option value="outro" ${laudo.tipo_laudo === 'outro' ? 'selected' : ''}>Outro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo de Exame</label>
                                <input type="text" class="form-input" value="${laudo.tipo_exame || ''}" placeholder="ex: Hemograma, Raio-X...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Descrição do Caso</label>
                            <textarea class="form-textarea" placeholder="Descreva o caso clínico...">${laudo.descricao_caso || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Achados Principais</label>
                            <textarea class="form-textarea" placeholder="Descreva os achados do exame..." required>${laudo.achados_principais || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Análise</label>
                            <textarea class="form-textarea" placeholder="Análise dos resultados...">${laudo.analise || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Conclusão</label>
                            <textarea class="form-textarea" placeholder="Conclusão do laudo..." required>${laudo.conclusao || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Recomendações</label>
                            <textarea class="form-textarea" placeholder="Recomendações médicas...">${laudo.recomendacoes || ''}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Observações</label>
                            <textarea class="form-textarea" placeholder="Observações adicionais...">${laudo.observacoes || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Data de Validade</label>
                                <input type="date" class="form-input" value="${laudo.data_validade || ''}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prioridade</label>
                                <select class="form-select">
                                    <option value="baixa" ${laudo.prioridade === 'baixa' ? 'selected' : ''}>Baixa</option>
                                    <option value="media" ${laudo.prioridade === 'media' ? 'selected' : ''}>Média</option>
                                    <option value="alta" ${laudo.prioridade === 'alta' ? 'selected' : ''}>Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <button onclick="closeEditarLaudoModal()" class="action-btn danger">Cancelar</button>
                            <button onclick="salvarLaudo(${laudoId})" class="action-btn success">Salvar Alterações</button>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar formulário de edição:', error);
                showNotification('Erro ao carregar formulário de edição.', 'error');
            }
        }

        function novoLaudo() {
            const modal = document.getElementById('novo-laudo-modal');
            const content = document.getElementById('novo-laudo-content');

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Paciente</label>
                        <select class="form-select" id="paciente-select">
                            <option value="">Selecione um paciente</option>
                            <!-- Pacientes serão carregados via JavaScript -->
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Tipo de Laudo</label>
                            <select class="form-select" id="tipo-laudo">
                                <option value="clinico">Clínico</option>
                                <option value="laboratorial">Laboratorial</option>
                                <option value="imagem">Imagem</option>
                                <option value="patologico">Patológico</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de Exame</label>
                            <input type="text" class="form-input" placeholder="ex: Hemograma, Raio-X...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descrição do Caso</label>
                        <textarea class="form-textarea" placeholder="Descreva o caso clínico..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Achados Principais</label>
                        <textarea class="form-textarea" placeholder="Descreva os achados do exame..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Conclusão</label>
                        <textarea class="form-textarea" placeholder="Conclusão do laudo..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Recomendações</label>
                        <textarea class="form-textarea" placeholder="Recomendações médicas..."></textarea>
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

                    <!-- Templates de Laudo -->
                    <div class="form-group">
                        <label class="form-label">Usar Template</label>
                        <div class="template-grid">
                            <div class="template-item" onclick="selecionarTemplate('hemograma')">
                                <strong>Hemograma</strong>
                                <p class="text-sm text-gray-600">Template padrão para hemograma completo</p>
                            </div>
                            <div class="template-item" onclick="selecionarTemplate('radiografia')">
                                <strong>Radiografia</strong>
                                <p class="text-sm text-gray-600">Template para laudos de raio-X</p>
                            </div>
                            <div class="template-item" onclick="selecionarTemplate('ultrassom')">
                                <strong>Ultrassom</strong>
                                <p class="text-sm text-gray-600">Template para exames de ultrassonografia</p>
                            </div>
                            <div class="template-item" onclick="selecionarTemplate('clinico')">
                                <strong>Clínico Geral</strong>
                                <p class="text-sm text-gray-600">Template para laudos clínicos gerais</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeNovoLaudoModal()" class="action-btn danger">Cancelar</button>
                        <button onclick="criarLaudo()" class="action-btn success">Criar Laudo</button>
                    </div>
                </div>
            `;

            modal.classList.add('show');
            carregarPacientesParaLaudo();
        }

        async function carregarPacientesParaLaudo() {
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

        function selecionarTemplate(tipo) {
            // Preencher automaticamente com base no template selecionado
            showNotification(`Template ${tipo} selecionado!`, 'info');
            // Implementar preenchimento automático dos campos
        }

        function criarLaudo() {
            showNotification('Laudo criado com sucesso!', 'success');
            closeNovoLaudoModal();
            loadLaudos();
        }

        function salvarLaudo(laudoId) {
            showNotification('Laudo atualizado com sucesso!', 'success');
            closeEditarLaudoModal();
            loadLaudos();
        }

        function finalizarLaudo(laudoId) {
            if (confirm('Tem certeza que deseja finalizar este laudo? Esta ação não pode ser desfeita.')) {
                showNotification('Laudo finalizado com sucesso!', 'success');
                loadLaudos();
            }
        }

        function reabrirLaudo(laudoId) {
            showNotification('Laudo reaberto para edição!', 'info');
            loadLaudos();
        }

        function cancelarLaudo(laudoId) {
            if (confirm('Tem certeza que deseja cancelar este laudo?')) {
                showNotification('Laudo cancelado com sucesso!', 'success');
                loadLaudos();
            }
        }

        function imprimirLaudo() {
            window.print();
        }

        function exportLaudos() {
            showNotification('Exportando lista de laudos...', 'info');
            // Implementar lógica de exportação
        }

        function closeModal() {
            document.getElementById('laudo-modal').classList.remove('show');
        }

        function closeNovoLaudoModal() {
            document.getElementById('novo-laudo-modal').classList.remove('show');
        }

        function closeEditarLaudoModal() {
            document.getElementById('editar-laudo-modal').classList.remove('show');
        }

        // Filtros
        function filterByStatus(status) {
            currentFilter = status;
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`[data-filter="${status}"]`).classList.add('active');
            loadLaudos();
        }

        // Configurar filtros
        function setupFilters() {
            // Filtros por tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    loadLaudos();
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
                        loadLaudos();
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
            loadLaudos();

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