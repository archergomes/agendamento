<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Visualizar relatórios de agendamentos no Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            max-width: 100vw;
            position: relative;
        }
        #notification {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        #notification.error { background-color: #ef4444; }
        #notification.success { background-color: #10b981; }
        #notification.info { background-color: #3b82f6; }
        #notification.show { display: block; }
        .sidebar {
            position: fixed;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            z-index: 900;
            display: none;
            flex-direction: column;
        }
        .sidebar.show {
            display: flex;
            transform: translateX(0);
        }
        .sidebar.mobile {
            left: 0;
        }
        .sidebar.mobile:not(.show) {
            transform: translateX(-100%);
        }
        .sidebar.desktop {
            left: 0;
            display: flex;
            transform: translateX(0);
        }
        .sidebar.desktop.collapsed {
            width: 60px;
        }
        .sidebar#sidebar-menu.desktop:not(.collapsed) {
            width: 250px;
        }
        .sidebar.desktop.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.desktop.collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }
        .sidebar.desktop.collapsed .close-sidebar-btn {
            display: none;
        }
        .sidebar.mobile .sidebar-text {
            display: inline;
        }
        .sidebar.mobile .sidebar-header {
            justify-content: space-between;
            padding: 1rem;
        }
        .sidebar.mobile .sidebar-nav a, .sidebar.mobile .sidebar-nav button {
            justify-content: flex-start;
            padding: 8px 16px;
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
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 800;
            background-color: #2563eb;
        }
        .page-wrapper {
            position: relative;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden;
        }
        .main-content {
            margin-left: 60px;
            margin-top: 64px;
            padding: 1rem;
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
            min-height: calc(100vh - 64px - 128px);
            width: calc(100% - 60px);
        }
        .main-content.expanded {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        @media (max-width: 767px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
                display: none;
            }
            .sidebar.show {
                display: flex;
                transform: translateX(0);
            }
            .sidebar.desktop {
                display: none;
            }
            .sidebar.mobile {
                display: none;
                left: 0;
            }
            .sidebar.mobile.show {
                display: flex;
            }
            .main-content {
                margin-left: 0;
                margin-top: 64px;
                width: 100%;
            }
            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
            #toggle-sidebar-btn {
                display: none;
            }
            #mobile-menu-btn {
                display: block;
                margin-right: 1rem;
                z-index: 901;
            }
            #close-sidebar-btn {
                display: block;
            }
            .header-content {
                justify-content: space-between;
                align-items: center;
            }
        }
        @media (min-width: 768px) {
            #mobile-menu-btn { display: none; }
            #close-sidebar-btn { display: none; }
            .sidebar.desktop { display: flex; }
            .sidebar.mobile { display: none; }
            .header-content { justify-content: flex-start; }
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
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 0.375rem;
            color: #374151;
            transition: background-color 0.2s, color 0.2s, padding 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .sidebar-nav a:hover, .sidebar-nav button:hover {
            background-color: #eff6ff;
            color: #1e40af;
            padding-left: 20px;
        }
        .sidebar-nav a.active {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
        }
        .sidebar-nav i {
            font-size: 1.5rem;
            width: 28px;
            text-align: center;
        }
        .sidebar.desktop.collapsed .sidebar-nav a, .sidebar.desktop.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 8px;
        }
        .sidebar-nav .logout {
            margin-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 0.5rem;
        }
        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        footer {
            width: 100%;
            position: relative;
            margin-left: 0;
        }
        .summary-card {
            background: linear-gradient(135deg, #ffffff, #f3f4f6);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e5e7eb;
        }
        .summary-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .summary-card.total {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: white;
        }
        .summary-card.status {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }
        .summary-card.doctor {
            background: linear-gradient(135deg, #14b8a6, #5eead4);
            color: white;
        }
        .summary-card .card-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        .doctor-breakdown {
            max-height: 100px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb #ffffff;
        }
        .doctor-breakdown::-webkit-scrollbar {
            width: 6px;
        }
        .doctor-breakdown::-webkit-scrollbar-track {
            background: #ffffff;
        }
        .doctor-breakdown::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 3px;
        }
        .filter-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }
        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            overflow-x: auto;
            border: 1px solid #e5e7eb;
        }
        .export-btn, .reset-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .export-btn:hover, .reset-btn:hover {
            background-color: #059669;
        }
        .reset-btn {
            background-color: #6b7280;
        }
        .reset-btn:hover {
            background-color: #4b5563;
        }
        .form-input, .form-select {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            width: 100%;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .form-input[readonly] {
            background-color: #f3f4f6;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
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
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .modal.show {
            display: flex;
            opacity: 1;
        }
        .modal-content {
            background-color: white;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
        }
        .modal.show .modal-content {
            transform: scale(1);
        }
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #374151;
            cursor: pointer;
            transition: color 0.2s;
        }
        .modal-close:hover {
            color: #1e40af;
        }
        .modal-search-container {
            position: relative;
            margin-bottom: 1rem;
        }
        .modal-search {
            padding: 0.75rem 2.5rem 0.75rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            width: 100%;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .modal-search:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .modal-search-clear {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #6b7280;
            cursor: pointer;
            display: none;
        }
        .modal-search-clear.show {
            display: block;
        }
        .modal-search-clear:hover {
            color: #1e40af;
        }
        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }
        .modal-table th, .modal-table td {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .modal-table th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .modal-table tr:hover {
            background-color: #dbeafe;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .table-row:nth-child(even) {
            background-color: #f9fafb;
        }
        .table-row:hover {
            background-color: #eff6ff;
        }
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        .loading::after {
            content: '';
            position: absolute;
            width: 1.5rem;
            height: 1.5rem;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        @media (max-width: 767px) {
            .modal-content {
                width: 95%;
                max-height: 90vh;
            }
            .summary-card {
                margin-bottom: 1rem;
            }
            .filter-container {
                padding: 1.5rem;
            }
            .table-container {
                padding: 1.5rem;
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
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg" data-testid="sidebar-menu" data-state="collapsed">
            <div class="sidebar-header flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Administrador</h2>
                <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu" data-testid="toggle-sidebar-btn">
                    <i id="toggle-sidebar-icon" class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="/admin/dashboard" class="block text-gray-700" data-testid="nav-dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="/admin/pacientes" class="block text-gray-700" data-testid="nav-pacientes">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="/admin/medicos" class="block text-gray-700" data-testid="nav-medicos">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="/admin/secretarios" class="block text-gray-700" data-testid="nav-secretarios">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Secretários</span>
                    </a>
                    <a href="/admin/agendamentos" class="block text-gray-700" data-testid="nav-agendamentos">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="/admin/cadastrar-paciente" class="block text-gray-700" data-testid="nav-cadastrar-paciente">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Paciente</span>
                    </a>
                    <a href="/admin/cadastrar-secretario" class="block text-gray-700" data-testid="nav-cadastrar-secretario">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Secretário</span>
                    </a>
                    <a href="/admin/cadastrar-medico" class="block text-gray-700" data-testid="nav-cadastrar-medico">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Médico</span>
                    </a>
                    <a href="/admin/relatorios" class="block text-gray-700 active" data-testid="nav-relatorios">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="/admin/configuracoes" class="block text-gray-700" data-testid="nav-configuracoes">
                        <i class="fas fa-cog"></i>
                        <span class="sidebar-text">Configurações</span>
                    </a>
                </div>
                <button id="logout-btn" class="block w-full text-left text-gray-700 logout" data-testid="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Sair</span>
                </button>
            </nav>
        </div>

        <!-- Patient Selection Modal -->
        <div id="patient-modal" class="modal">
            <div class="modal-content">
                <span class="modal-close" id="patient-modal-close">×</span>
                <h3 class="text-lg font-semibold mb-4">Selecionar Paciente</h3>
                <div class="modal-search-container">
                    <input type="text" id="patient-search" class="modal-search" placeholder="Pesquisar por nome ou BI...">
                    <i class="fas fa-times modal-search-clear" id="patient-search-clear"></i>
                </div>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>BI</th>
                        </tr>
                    </thead>
                    <tbody id="patient-table">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
                <p id="patient-no-results" class="text-center text-gray-500 mt-4 hidden">Nenhum paciente encontrado.</p>
            </div>
        </div>

        <!-- Doctor Selection Modal -->
        <div id="doctor-modal" class="modal">
            <div class="modal-content">
                <span class="modal-close" id="doctor-modal-close">×</span>
                <h3 class="text-lg font-semibold mb-4">Selecionar Médico</h3>
                <div class="modal-search-container">
                    <input type="text" id="doctor-search" class="modal-search" placeholder="Pesquisar por nome, BI ou especialidade...">
                    <i class="fas fa-times modal-search-clear" id="doctor-search-clear"></i>
                </div>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>BI</th>
                            <th>Especialidade</th>
                        </tr>
                    </thead>
                    <tbody id="doctor-table">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
                <p id="doctor-no-results" class="text-center text-gray-500 mt-4 hidden">Nenhum médico encontrado.</p>
            </div>
        </div>

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu" data-testid="mobile-menu-btn">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <i class="fas fa-hospital-alt text-2xl" aria-hidden="true"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Relatórios de Agendamentos</h2>

                <!-- Summary Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="summary-card total">
                        <i class="fas fa-calendar-alt card-icon"></i>
                        <h3 class="text-lg font-semibold mb-2">Total de Agendamentos</h3>
                        <p id="total-appointments" class="text-3xl font-bold">0</p>
                    </div>
                    <div class="summary-card status">
                        <i class="fas fa-check-circle card-icon"></i>
                        <h3 class="text-lg font-semibold mb-2">Por Estado</h3>
                        <p id="status-breakdown" class="text-sm">
                            Confirmado: 0<br>Pendente: 0<br>Cancelado: 0
                        </p>
                    </div>
                    <div class="summary-card doctor">
                        <i class="fas fa-user-md card-icon"></i>
                        <h3 class="text-lg font-semibold mb-2">Agendamentos por Médico</h3>
                        <div class="doctor-breakdown text-sm">
                            <p id="doctor-breakdown"></p>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-container">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="start-date" class="block text-gray-700 font-medium mb-2">Data Início</label>
                            <input type="date" id="start-date" class="form-input">
                        </div>
                        <div>
                            <label for="end-date" class="block text-gray-700 font-medium mb-2">Data Fim</label>
                            <input type="date" id="end-date" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="patient-input" class="block text-gray-700 font-medium mb-2">Paciente</label>
                            <input type="text" id="patient-input" class="form-input" readonly placeholder="Selecione um paciente">
                            <input type="hidden" id="patient-bi">
                        </div>
                        <div>
                            <label for="doctor-input" class="block text-gray-700 font-medium mb-2">Médico</label>
                            <input type="text" id="doctor-input" class="form-input" readonly placeholder="Selecione um médico">
                            <input type="hidden" id="doctor-bi">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <button id="search-btn" class="export-btn">
                            <i class="fas fa-search mr-2"></i> Filtrar
                        </button>
                        <button id="reset-btn" class="reset-btn">
                            <i class="fas fa-undo mr-2"></i> Limpar Filtros
                        </button>
                        <button id="export-btn" class="export-btn">
                            <i class="fas fa-download mr-2"></i> Exportar CSV
                        </button>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="table-container">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-3 font-medium">Paciente</th>
                                <th class="px-4 py-3 font-medium">Médico</th>
                                <th class="px-4 py-3 font-medium">Data</th>
                                <th class="px-4 py-3 font-medium">Hora</th>
                                <th class="px-4 py-3 font-medium">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="appointments-table">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                    <p id="no-results" class="text-center text-gray-500 mt-4 hidden">Nenhum agendamento encontrado.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-medium mb-4">Hospital Matlhovele</h3>
                        <p class="text-gray-300">Av. 25 de Setembro, Maputo</p>
                        <p class="text-gray-300">Telefone: +258 84 123 4567</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4">Horário de Funcionamento</h3>
                        <p class="text-gray-300">Segunda a Sexta: 7h30 - 16h30</p>
                        <p class="text-gray-300">Sábado: 8h00 - 12h00</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4">Links Rápidos</h3>
                        <ul class="space-y-2">
                            <li><a href="/sobre" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                            <li><a href="/servicos" class="text-gray-300 hover:text-white">Serviços</a></li>
                            <li><a href="/contactos" class="text-gray-300 hover:text-white">Contactos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                    <p>© 2025 Hospital Público de Matlhovele. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initial data
        let appointments = JSON.parse(localStorage.getItem('appointments')) || [];
        let patients = JSON.parse(localStorage.getItem('patients')) || [];
        let doctors = JSON.parse(localStorage.getItem('doctors')) || [];

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) {
                console.error('Notification elements not found:', { notification, messageEl });
                return;
            }
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Get name by BI
        function getNameByBi(bi, array) {
            const record = array.find(item => item.bi === bi);
            return record ? record.name : 'Desconhecido';
        }

        // Get specialty by BI
        function getSpecialtyByBi(bi) {
            const doctor = doctors.find(doc => doc.bi === bi);
            return doctor ? doctor.specialty : 'Desconhecido';
        }

        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Populate patient table
        function populatePatientTable(filter = '') {
            const tableBody = document.getElementById('patient-table');
            const noResults = document.getElementById('patient-no-results');
            if (!tableBody || !noResults) {
                console.error('Patient table elements not found:', { tableBody, noResults });
                return;
            }

            tableBody.innerHTML = '';
            const filteredPatients = patients.filter(patient => {
                const searchLower = filter.toLowerCase().trim();
                return patient.name.toLowerCase().includes(searchLower) || 
                       patient.bi.toLowerCase().includes(searchLower);
            });

            if (filteredPatients.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }

            noResults.classList.add('hidden');
            filteredPatients.forEach(patient => {
                const row = document.createElement('tr');
                row.dataset.bi = patient.bi;
                row.innerHTML = `
                    <td>${patient.name}</td>
                    <td>${patient.bi}</td>
                `;
                row.addEventListener('click', () => {
                    document.getElementById('patient-input').value = patient.name;
                    document.getElementById('patient-bi').value = patient.bi;
                    document.getElementById('patient-modal').classList.remove('show');
                });
                tableBody.appendChild(row);
            });
        }

        // Populate doctor table
        function populateDoctorTable(filter = '') {
            const tableBody = document.getElementById('doctor-table');
            const noResults = document.getElementById('doctor-no-results');
            if (!tableBody || !noResults) {
                console.error('Doctor table elements not found:', { tableBody, noResults });
                return;
            }

            tableBody.innerHTML = '';
            const filteredDoctors = doctors.filter(doctor => {
                const searchLower = filter.toLowerCase().trim();
                return doctor.name.toLowerCase().includes(searchLower) || 
                       doctor.bi.toLowerCase().includes(searchLower) || 
                       doctor.specialty.toLowerCase().includes(searchLower);
            });

            if (filteredDoctors.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }

            noResults.classList.add('hidden');
            filteredDoctors.forEach(doctor => {
                const row = document.createElement('tr');
                row.dataset.bi = doctor.bi;
                row.innerHTML = `
                    <td>${doctor.name}</td>
                    <td>${doctor.bi}</td>
                    <td>${doctor.specialty}</td>
                `;
                row.addEventListener('click', () => {
                    document.getElementById('doctor-input').value = doctor.name;
                    document.getElementById('doctor-bi').value = doctor.bi;
                    document.getElementById('doctor-modal').classList.remove('show');
                });
                tableBody.appendChild(row);
            });
        }

        // Render summary
        function renderSummary(filteredAppointments) {
            const totalAppointments = document.getElementById('total-appointments');
            const statusBreakdown = document.getElementById('status-breakdown');
            const doctorBreakdown = document.getElementById('doctor-breakdown');

            if (!totalAppointments || !statusBreakdown || !doctorBreakdown) {
                console.error('Summary elements not found:', { totalAppointments, statusBreakdown, doctorBreakdown });
                return;
            }

            totalAppointments.textContent = filteredAppointments.length;
            const statusCounts = {
                Confirmado: 0,
                Pendente: 0,
                Cancelado: 0
            };
            filteredAppointments.forEach(appt => {
                if (statusCounts[appt.status] !== undefined) {
                    statusCounts[appt.status]++;
                }
            });
            statusBreakdown.innerHTML = `
                Confirmado: ${statusCounts.Confirmado}<br>
                Pendente: ${statusCounts.Pendente}<br>
                Cancelado: ${statusCounts.Cancelado}
            `;
            const doctorCounts = {};
            filteredAppointments.forEach(appt => {
                const doctorName = getNameByBi(appt.doctorBi, doctors);
                const specialty = getSpecialtyByBi(appt.doctorBi);
                const key = `${doctorName} (${specialty})`;
                doctorCounts[key] = (doctorCounts[key] || 0) + 1;
            });
            doctorBreakdown.innerHTML = Object.entries(doctorCounts)
                .map(([name, count]) => `${name}: ${count}`)
                .join('<br>') || 'Nenhum agendamento';
        }

        // Render appointments table
        function renderTable(startDate = '', endDate = '', patientBi = '', doctorBi = '') {
            const tableBody = document.getElementById('appointments-table');
            const noResults = document.getElementById('no-results');
            if (!tableBody || !noResults) {
                console.error('Table elements not found:', { tableBody, noResults });
                return [];
            }

            tableBody.innerHTML = '';
            const filteredAppointments = appointments.filter(appt => {
                let inDateRange = true;
                if (startDate) {
                    inDateRange = inDateRange && new Date(appt.date) >= new Date(startDate);
                }
                if (endDate) {
                    inDateRange = inDateRange && new Date(appt.date) <= new Date(endDate);
                }
                const matchPatient = !patientBi || appt.patientBi === patientBi;
                const matchDoctor = !doctorBi || appt.doctorBi === doctorBi;
                return inDateRange && matchPatient && matchDoctor;
            });

            if (filteredAppointments.length === 0) {
                noResults.classList.remove('hidden');
                return filteredAppointments;
            }

            noResults.classList.add('hidden');
            filteredAppointments.forEach(appointment => {
                const row = document.createElement('tr');
                row.classList.add('table-row');
                row.innerHTML = `
                    <td class="px-4 py-3 border">${getNameByBi(appointment.patientBi, patients)}</td>
                    <td class="px-4 py-3 border">${getNameByBi(appointment.doctorBi, doctors)}</td>
                    <td class="px-4 py-3 border">${appointment.date}</td>
                    <td class="px-4 py-3 border">${appointment.time}</td>
                    <td class="px-4 py-3 border">${appointment.status}</td>
                `;
                tableBody.appendChild(row);
            });

            return filteredAppointments;
        }

        // Export to CSV
        function exportToCsv(filteredAppointments) {
            const headers = ['Paciente', 'Médico', 'Data', 'Hora', 'Estado'];
            const rows = filteredAppointments.map(appt => [
                `"${getNameByBi(appt.patientBi, patients)}"`,
                `"${getNameByBi(appt.doctorBi, doctors)}"`,
                appt.date,
                appt.time,
                appt.status
            ]);
            const csvContent = [
                headers.join(','),
                ...rows.map(row => row.join(','))
            ].join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'relatorio_agendamentos.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showNotification('Relatório exportado com sucesso!', 'success');
        }

        // Sidebar initialization
        let isSidebarExpanded = false;
        function initializeSidebar() {
            const sidebarMenu = document.getElementById('sidebar-menu');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const toggleSidebarIcon = document.getElementById('toggle-sidebar-icon');

            if (!sidebarMenu || !mainContent || !sidebarOverlay || !toggleSidebarIcon) {
                console.error('Sidebar initialization failed: Missing elements', { sidebarMenu, mainContent, sidebarOverlay, toggleSidebarIcon });
                showNotification('Erro ao inicializar o menu.', 'error');
                return;
            }

            // Clear all classes
            sidebarMenu.classList.remove('desktop', 'mobile', 'show', 'collapsed');
            mainContent.classList.remove('expanded');
            sidebarOverlay.classList.remove('show');
            toggleSidebarIcon.classList.remove('fa-times', 'fa-bars');

            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('desktop', 'collapsed');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                mainContent.classList.remove('expanded');
                toggleSidebarIcon.classList.add('fa-bars');
                isSidebarExpanded = false;
            } else {
                sidebarMenu.classList.add('mobile');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                mainContent.classList.remove('expanded');
                toggleSidebarIcon.classList.add('fa-bars');
                isSidebarExpanded = false;
            }
            console.log('Sidebar initialized:', { isSidebarExpanded, width: window.innerWidth, sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
        }

        // DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            // DOM elements
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const toggleSidebarIcon = document.getElementById('toggle-sidebar-icon');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const startDate = document.getElementById('start-date');
            const endDate = document.getElementById('end-date');
            const patientInput = document.getElementById('patient-input');
            const patientModal = document.getElementById('patient-modal');
            const patientModalClose = document.getElementById('patient-modal-close');
            const patientSearch = document.getElementById('patient-search');
            const patientSearchClear = document.getElementById('patient-search-clear');
            const doctorInput = document.getElementById('doctor-input');
            const doctorModal = document.getElementById('doctor-modal');
            const doctorModalClose = document.getElementById('doctor-modal-close');
            const doctorSearch = document.getElementById('doctor-search');
            const doctorSearchClear = document.getElementById('doctor-search-clear');
            const searchBtn = document.getElementById('search-btn');
            const resetBtn = document.getElementById('reset-btn');
            const exportBtn = document.getElementById('export-btn');
            const logoutBtn = document.getElementById('logout-btn');

            // Check DOM elements
            const requiredElements = {
                notificationClose, mobileMenuBtn, sidebarMenu, closeSidebarBtn, toggleSidebarBtn,
                toggleSidebarIcon, mainContent, sidebarOverlay, startDate, endDate, patientInput,
                patientModal, patientModalClose, patientSearch, patientSearchClear, doctorInput,
                doctorModal, doctorModalClose, doctorSearch, doctorSearchClear, searchBtn, resetBtn,
                exportBtn, logoutBtn
            };
            for (const [key, value] of Object.entries(requiredElements)) {
                if (!value) {
                    console.error(`DOM element not found: ${key}`);
                    showNotification('Erro ao carregar elementos da página.', 'error');
                    return;
                }
            }
            console.log('All DOM elements loaded successfully.');

            // Initialize sidebar
            initializeSidebar();

            // Check for data
            if (appointments.length === 0) {
                showNotification('Nenhum agendamento cadastrado.', 'error');
                renderSummary([]);
                renderTable();
                exportBtn.disabled = true;
                return;
            }

            // Initial render
            const initialAppointments = renderTable();
            renderSummary(initialAppointments);

            // Toggle sidebar (Desktop) with event delegation
            document.addEventListener('click', (e) => {
                if (e.target.closest('#toggle-sidebar-btn')) {
                    if (window.innerWidth >= 768) {
                        isSidebarExpanded = !isSidebarExpanded;
                        sidebarMenu.classList.toggle('collapsed', !isSidebarExpanded);
                        mainContent.classList.toggle('expanded', isSidebarExpanded);
                        toggleSidebarIcon.classList.toggle('fa-bars', !isSidebarExpanded);
                        toggleSidebarIcon.classList.toggle('fa-times', isSidebarExpanded);
                        sidebarMenu.setAttribute('data-state', isSidebarExpanded ? 'expanded' : 'collapsed');
                        console.log('Desktop sidebar toggled:', { isSidebarExpanded, sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                    } else {
                        console.log('Toggle button clicked on mobile, no action taken.');
                    }
                }
            });

            // Mobile menu
            mobileMenuBtn.addEventListener('click', () => {
                sidebarMenu.classList.add('show', 'mobile');
                sidebarOverlay.classList.add('show');
                sidebarMenu.setAttribute('data-state', 'expanded');
                console.log('Mobile menu opened:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
            });

            closeSidebarBtn.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Mobile menu closed:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Mobile menu closed via overlay:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
            });

            // Navigation links (simulate navigation)
            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = link.getAttribute('href') || 'unknown';
                    showNotification(`Navegando para ${href}`, 'info');
                    console.log('Navigation link clicked:', { href });
                    // Uncomment for actual navigation
                    // window.location.href = href;
                });
            });

            // Open modals
            patientInput.addEventListener('click', () => {
                if (patients.length === 0) {
                    showNotification('Nenhum paciente cadastrado.', 'error');
                    return;
                }
                patientModal.classList.add('show');
                patientSearch.value = '';
                patientSearchClear.classList.remove('show');
                populatePatientTable();
                patientSearch.focus();
            });

            doctorInput.addEventListener('click', () => {
                if (doctors.length === 0) {
                    showNotification('Nenhum médico cadastrado.', 'error');
                    return;
                }
                doctorModal.classList.add('show');
                doctorSearch.value = '';
                doctorSearchClear.classList.remove('show');
                populateDoctorTable();
                doctorSearch.focus();
            });

            // Close modals
            patientModalClose.addEventListener('click', () => {
                patientModal.classList.remove('show');
            });

            doctorModalClose.addEventListener('click', () => {
                doctorModal.classList.remove('show');
            });

            patientModal.addEventListener('click', (e) => {
                if (e.target === patientModal) {
                    patientModal.classList.remove('show');
                }
            });

            doctorModal.addEventListener('click', (e) => {
                if (e.target === doctorModal) {
                    doctorModal.classList.remove('show');
                }
            });

            // Search functionality with debounce
            const debouncedPopulatePatientTable = debounce(populatePatientTable, 300);
            const debouncedPopulateDoctorTable = debounce(populateDoctorTable, 300);

            patientSearch.addEventListener('input', () => {
                const value = patientSearch.value.trim();
                debouncedPopulatePatientTable(value);
                patientSearchClear.classList.toggle('show', value.length > 0);
            });

            doctorSearch.addEventListener('input', () => {
                const value = doctorSearch.value.trim();
                debouncedPopulateDoctorTable(value);
                doctorSearchClear.classList.toggle('show', value.length > 0);
            });

            // Clear search
            patientSearchClear.addEventListener('click', () => {
                patientSearch.value = '';
                patientSearchClear.classList.remove('show');
                populatePatientTable();
                patientSearch.focus();
            });

            doctorSearchClear.addEventListener('click', () => {
                doctorSearch.value = '';
                doctorSearchClear.classList.remove('show');
                populateDoctorTable();
                doctorSearch.focus();
            });

            // Select with Enter key
            patientSearch.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const firstRow = document.querySelector('#patient-table tr');
                    if (firstRow) {
                        firstRow.click();
                    }
                }
            });

            doctorSearch.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const firstRow = document.querySelector('#doctor-table tr');
                    if (firstRow) {
                        firstRow.click();
                    }
                }
            });

            // Filter table
            searchBtn.addEventListener('click', () => {
                searchBtn.classList.add('loading');
                setTimeout(() => {
                    const start = startDate.value;
                    const end = endDate.value;
                    const patientBi = document.getElementById('patient-bi').value;
                    const doctorBi = document.getElementById('doctor-bi').value;

                    if (start && end && new Date(start) > new Date(end)) {
                        showNotification('A data de início deve ser anterior ou igual à data de fim.', 'error');
                        searchBtn.classList.remove('loading');
                        return;
                    }
                    if (start && new Date(start) < new Date('2025-07-03')) {
                        showNotification('A data de início deve ser igual ou posterior a 2025-07-03.', 'error');
                        searchBtn.classList.remove('loading');
                        return;
                    }

                    const filteredAppointments = renderTable(start, end, patientBi, doctorBi);
                    renderSummary(filteredAppointments);
                    exportBtn.disabled = filteredAppointments.length === 0;
                    searchBtn.classList.remove('loading');
                }, 500);
            });

            // Reset filters
            resetBtn.addEventListener('click', () => {
                startDate.value = '';
                endDate.value = '';
                patientInput.value = '';
                patientBi.value = '';
                doctorInput.value = '';
                doctorBi.value = '';
                const filteredAppointments = renderTable();
                renderSummary(filteredAppointments);
                exportBtn.disabled = filteredAppointments.length === 0;
                showNotification('Filtros limpos com sucesso!', 'success');
            });

            // Export CSV
            exportBtn.addEventListener('click', () => {
                exportBtn.classList.add('loading');
                setTimeout(() => {
                    const start = startDate.value;
                    const end = endDate.value;
                    const patientBi = document.getElementById('patient-bi').value;
                    const doctorBi = document.getElementById('doctor-bi').value;
                    const filteredAppointments = renderTable(start, end, patientBi, doctorBi);
                    if (filteredAppointments.length === 0) {
                        showNotification('Nenhum dado para exportar.', 'error');
                        exportBtn.classList.remove('loading');
                        return;
                    }
                    exportToCsv(filteredAppointments);
                    exportBtn.classList.remove('loading');
                }, 500);
            });

            // Notification close
            notificationClose.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Mobile sidebar behavior
            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    sidebarMenu.setAttribute('data-state', 'collapsed');
                    console.log('Mobile menu closed via outside click:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                }
            });

            let touchStartX = 0;
            let touchEndX = 0;
            let isTouchingSidebar = false;

            document.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                isTouchingSidebar = sidebarMenu.contains(e.target);
            });

            document.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (window.innerWidth < 768) {
                    const deltaX = touchEndX - touchStartX;
                    if (!sidebarMenu.classList.contains('show') && touchStartX < 50 && deltaX > 100) {
                        sidebarMenu.classList.add('show', 'mobile');
                        sidebarOverlay.classList.add('show');
                        sidebarMenu.setAttribute('data-state', 'expanded');
                        console.log('Mobile menu opened via swipe:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                    }
                    if (isTouchingSidebar && sidebarMenu.classList.contains('show') && deltaX < -100) {
                        sidebarMenu.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        sidebarMenu.setAttribute('data-state', 'collapsed');
                        console.log('Mobile menu closed via swipe:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                    }
                }
            });

            // Window resize
            window.addEventListener('resize', () => {
                initializeSidebar();
                console.log('Window resized:', { width: window.innerWidth, isSidebarExpanded, sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
            });

            // Logout
            logoutBtn.addEventListener('click', () => {
                showNotification('Sessão encerrada com sucesso!', 'success');
                sidebarMenu.classList.remove('show', 'desktop', 'collapsed', 'mobile');
                mainContent.classList.remove('expanded');
                sidebarOverlay.classList.remove('show');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Logout clicked:', { sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                // window.location.href = '/login';
            });
        });

        // Fallback for toggle button if DOMContentLoaded misses it
        setTimeout(() => {
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            if (toggleSidebarBtn && !toggleSidebarBtn.dataset.listenerAdded) {
                toggleSidebarBtn.dataset.listenerAdded = 'true';
                console.log('Adding fallback toggle listener');
                toggleSidebarBtn.addEventListener('click', () => {
                    const sidebarMenu = document.getElementById('sidebar-menu');
                    const mainContent = document.querySelector('.main-content');
                    const toggleSidebarIcon = document.getElementById('toggle-sidebar-icon');
                    if (window.innerWidth >= 768) {
                        isSidebarExpanded = !isSidebarExpanded;
                        sidebarMenu.classList.toggle('collapsed', !isSidebarExpanded);
                        mainContent.classList.toggle('expanded', isSidebarExpanded);
                        toggleSidebarIcon.classList.toggle('fa-bars', !isSidebarExpanded);
                        toggleSidebarIcon.classList.toggle('fa-times', isSidebarExpanded);
                        sidebarMenu.setAttribute('data-state', isSidebarExpanded ? 'expanded' : 'collapsed');
                        console.log('Fallback toggle triggered:', { isSidebarExpanded, sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
                    }
                });
            }
        }, 1000);
    </script>
</body>
</html>