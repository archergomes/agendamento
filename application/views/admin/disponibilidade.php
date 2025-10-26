<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidade - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciar disponibilidade de médicos no Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
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

        #notification.error { background-color: #ef4444; }
        #notification.success { background-color: #10b981; }
        #notification.info { background-color: #3b82f6; }
        #notification.show { display: block; }

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

        .sidebar-overlay.show { display: block; }

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

        .main-menu::-webkit-scrollbar { width: 6px; }
        .main-menu::-webkit-scrollbar-track { background: #e5e7eb; }
        .main-menu::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 3px; }
        
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 0.375rem;
            color: #374151;
            transition: background-color 0.2s, color 0.2s;
            font-size: 0.95rem;
        }
        
        .sidebar-nav a:hover, .sidebar-nav button:hover {
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

        footer {
            width: 100%;
            position: relative;
            margin-left: 0;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            overflow-x: auto;
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 0.25rem;
            color: white;
            transition: background-color 0.2s;
        }

        .edit-btn { background-color: #3b82f6; }
        .edit-btn:hover { background-color: #2563eb; }
        .delete-btn { background-color: #ef4444; }
        .delete-btn:hover { background-color: #dc2626; }
        
        .create-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        
        .create-btn:hover { background-color: #059669; }
        
        .search-input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            flex-grow: 1;
            max-width: 300px;
        }
        
        .search-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        
        .search-btn:hover { background-color: #2563eb; }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
            font-style: italic;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .pagination button {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            background-color: white;
            color: #374151;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .pagination button:hover {
            background-color: #eff6ff;
        }

        .pagination button.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Estilos para Disponibilidade */
        .calendar-grid {
            min-height: 200px;
        }
        .calendar-day {
            text-align: center;
            padding: 8px;
            border-radius: 0.25rem;
            min-height: 32px;
        }
        .calendar-day:hover:not(.calendar-day-past):not(.calendar-day-booked) {
            background-color: #e0f2fe;
            cursor: pointer;
        }
        .calendar-day-active {
            background-color: #3b82f6;
            color: white;
        }
        .calendar-day-past {
            color: #d1d5db;
        }
        .calendar-day-available {
            background-color: #dcfce7;
        }
        .calendar-day-booked {
            background-color: #fef9c3;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 50;
            min-width: 200px;
        }
        .dropdown-menu.show {
            display: block;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 60;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
        }
        @media (max-width: 640px) {
            .calendar-day {
                padding: 4px;
                font-size: 0.75rem;
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
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Administrador</h2>
                <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="<?php echo site_url('admin'); ?>" class="block text-gray-700">
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
                    <a href="<?php echo site_url('admin/disponibilidade'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="sidebar-text">Disponibilidade</span>
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

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-hospital-alt text-2xl" aria-hidden="true"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Gerenciar Disponibilidade de Médicos</h2>
                </div>

                <!-- Doctor Selection -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <label for="doctor-select" class="block text-sm font-medium text-gray-700 mb-2">Selecionar Médico</label>
                    <select id="doctor-select" class="p-2 border rounded focus:ring-2 focus:ring-blue-500 w-full max-w-md" aria-label="Selecionar médico">
                        <option value="">-- Escolha um médico --</option>
                        <?php foreach ($medicos ?? [] as $medico): ?>
                            <option value="<?php echo $medico['ID_Medico']; ?>" data-name="<?php echo htmlspecialchars($medico['Nome']); ?>" data-specialty="<?php echo htmlspecialchars($medico['Especialidade']); ?>">
                                <?php echo htmlspecialchars($medico['Nome']); ?> (<?php echo htmlspecialchars($medico['Especialidade']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dynamic Content for Selected Doctor -->
                <div id="doctor-content" class="hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
                        <h3 id="doctor-header" class="text-lg font-semibold text-gray-800"></h3>
                        <div class="flex space-x-2">
                            <select id="date-range" class="p-2 border rounded focus:ring-2 focus:ring-blue-500">
                                <option value="next-30">Próximos 30 Dias</option>
                                <option value="2025">2025</option>
                                <option value="all">Todos</option>
                            </select>
                        </div>
                    </div>

                    <!-- Calendar -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Calendário</h4>
                            <div class="flex items-center space-x-2">
                                <button id="prev-month" class="p-2 py-1 rounded-md hover:bg-gray-100" aria-label="Mês anterior">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span id="calendar-month" class="px-4 py-1 font-medium text-gray-700"></span>
                                <button id="next-month" class="p-2 py-1 rounded-md hover:bg-gray-100" aria-label="Próximo mês">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center font-semibold py-2 text-gray-700">Dom</div>
                                <div class="text-center font-semibold text-gray-700">Seg</div>
                                <div class="text-center font-semibold py-2 text-gray-700">Ter</div>
                                <div class="text-center font-semibold py-2 text-gray-700">Qua</div>
                                <div class="text-center font-semibold py-2 text-gray-700">Qui</div>
                                <div class="text-center font-semibold py-2 text-gray-700">Sex</div>
                                <div class="text-center font-semibold py-2 text-gray-700">Sáb</div>
                            </div>
                            <div id="calendar-days" class="grid grid-cols-7 gap-1"></div>
                        </div>
                    </div>

                    <!-- Add Slot Form -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Horário ou Exceção</h4>
                        <form id="add-slot-form" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div>
                                    <label for="slot-date" class="block text-sm font-medium text-gray-700">Data</label>
                                    <input type="text" id="slot-date" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500 date-input" required aria-label="Selecionar data">
                                </div>
                                <div>
                                    <label for="slot-start" class="block text-sm font-medium text-gray-700">Início</label>
                                    <input type="time" id="slot-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de início">
                                </div>
                                <div>
                                    <label for="slot-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input type="time" id="slot-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de fim">
                                </div>
                                <div>
                                    <label for="slot-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select id="slot-type" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Selecionar tipo">
                                        <option value="Available">Disponível</option>
                                        <option value="Unavailable">Indisponível</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="slot-reason" class="block text-sm font-medium text-gray-700">Motivo</label> (opcional)
                                <input type="text" id="slot-reason" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Motivo da exceção">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-2"></i>Adicionar
                            </button>
                        </form>
                    </div>

                    <!-- Standard Working Hours -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Horário Padrão</h4>
                            <button id="save-schedule" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>Salvar
                            </button>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-medium text-gray-800 mb-3">Dias de Trabalho</h5>
                                    <div class="flex flex-wrap gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="1" checked>
                                            <span class="ml-2">Segunda</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="2" checked>
                                            <span class="ml-2">Terça</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="3" checked>
                                            <span class="ml-2">Quarta</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="4" checked>
                                            <span class="ml-2">Quinta</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="5" checked>
                                            <span class="ml-2">Sexta</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" class="rounded text-blue-600 schedule-day" value="6">
                                            <span class="ml-2">Sábado</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800 mb-3">Horário de Atendimento</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="schedule-start" class="block text-sm font-medium text-gray-700">Início</label>
                                            <input type="time" id="schedule-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="08:00" step="900" required aria-label="Horário de início">
                                        </div>
                                        <div>
                                            <label for="schedule-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                            <input type="time" id="schedule-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="16:00" step="900" required aria-label="Horário de fim">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="consultation-duration" class="block text-sm font-medium text-gray-700">Duração da Consulta</label>
                                        <select id="consultation-duration" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Duração da consulta">
                                            <option value="30">30 minutos</option>
                                            <option value="45" selected>45 minutos</option>
                                            <option value="60">60 minutos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Slots Table -->
                    <div class="table-container">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Horários e Exceções</h4>
                        <table class="table-auto w-full text-left">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Data</th>
                                    <th class="px-4 py-2">Horário</th>
                                    <th class="px-4 py-2">Tipo</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Motivo</th>
                                    <th class="px-4 py-2">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="slots-table">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                        <p id="no-slots" class="text-center text-gray-500 mt-4 hidden empty-state">Nenhum horário encontrado para este médico.</p>
                    </div>
                </div>

                <?php if (empty($medicos ?? [])): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-md text-4xl text-gray-400 mb-4"></i>
                        <p>Nenhum médico cadastrado. <a href="<?php echo site_url('admin/cad_medico'); ?>" class="text-blue-600 hover:underline">Cadastre o primeiro!</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Edit Slot Modal -->
        <div id="edit-slot-modal" class="modal">
            <div class="modal-content">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Editar Horário</h4>
                <form id="edit-slot-form" class="space-y-4">
                    <input type="hidden" id="edit-slot-id">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label for="edit-slot-date" class="block text-sm font-medium text-gray-700">Data</label>
                            <input type="text" id="edit-slot-date" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500 date-input" required aria-label="Selecionar data">
                        </div>
                        <div>
                            <label for="edit-slot-start" class="block text-sm font-medium text-gray-700">Início</label>
                            <input type="time" id="edit-slot-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de início">
                        </div>
                        <div>
                            <label for="edit-slot-end" class="block text-sm font-medium text-gray-700">Fim</label>
                            <input type="time" id="edit-slot-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de fim">
                        </div>
                        <div>
                            <label for="edit-slot-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select id="edit-slot-type" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Selecionar tipo">
                                <option value="Available">Disponível</option>
                                <option value="Unavailable">Indisponível</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="edit-slot-reason" class="block text-sm font-medium text-gray-700">Motivo (opcional)</label>
                        <input type="text" id="edit-slot-reason" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Motivo da exceção">
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancel-edit" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
        // Dados iniciais do PHP
        let doctors = <?php echo json_encode(array_map(function($medico) {
            return [
                'id' => $medico['ID_Medico'] ?? '',
                'name' => $medico['Nome'] ?? '',
                'specialty' => $medico['Especialidade'] ?? ''
            ];
        }, $medicos ?? [])); ?>;

        let currentDoctor = null;
        let bookedAppointments = [];  // Carregue via AJAX se necessário
        let availableSlots = [];  // Carregue via AJAX baseado no médico selecionado
        let doctorSchedule = {
            days: [1, 2, 3, 4, 5],
            start: "08:00",
            end: "16:00",
            duration: 45
        };
        let currentMonth = new Date();

        // Função para carregar dados do médico
        async function loadDoctorData(doctorId) {
            if (!doctorId) return;
            try {
                const response = await fetch('<?php echo site_url('admin/get_doctor_data'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ doctor_id: parseInt(doctorId) })
                });
                if (!response.ok) throw new Error('Erro ao carregar dados');
                const data = await response.json();
                if (data.error) {
                    showNotification(data.error, 'error');
                    return;
                }
                currentDoctor = { id: doctorId, name: data.name, specialty: data.specialty };
                availableSlots = data.slots || [];
                bookedAppointments = data.appointments || [];
                doctorSchedule = data.schedule || doctorSchedule;
                document.getElementById('doctor-header').textContent = `${currentDoctor.name} - ${currentDoctor.specialty}`;
                document.getElementById('doctor-content').classList.remove('hidden');
                renderCalendar();
                renderSlotsTable(document.getElementById('date-range')?.value || 'next-30');
                showNotification('Dados do médico carregados.', 'success');
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
                showNotification('Erro ao carregar dados do médico.', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => notification.classList.remove('show'), 5000);
        }

        // Renderizar calendário (adaptado)
        function renderCalendar() {
            const monthYear = currentMonth.toLocaleString('pt-PT', { month: 'long', year: 'numeric' });
            document.getElementById('calendar-month').textContent = monthYear.charAt(0).toUpperCase() + monthYear.slice(1);

            const firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
            const lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);
            const startDay = firstDay.getDay();
            const daysInMonth = lastDay.getDate();
            const today = new Date();

            const daysContainer = document.getElementById('calendar-days');
            daysContainer.innerHTML = '';

            // Dias anteriores
            const prevMonthDays = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 0).getDate();
            for (let i = startDay - 1; i >= 0; i--) {
                const div = document.createElement('div');
                div.className = 'calendar-day calendar-day-past';
                div.textContent = prevMonthDays - i;
                daysContainer.appendChild(div);
            }

            // Dias atuais
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
                const dateStr = date.toISOString().split('T')[0];
                const isToday = dateStr === today.toISOString().split('T')[0];
                const hasAvailable = availableSlots.some(slot => slot.date === dateStr && slot.type === 'Available');
                const hasBooked = bookedAppointments.some(appt => appt.date === dateStr);
                const isPast = date < today && !isToday;

                const div = document.createElement('div');
                div.className = `calendar-day ${isToday ? 'calendar-day-active' : ''} ${isPast ? 'calendar-day-past' : ''} ${hasAvailable ? 'calendar-day-available' : ''} ${hasBooked ? 'calendar-day-booked' : ''}`;
                div.textContent = day;
                div.dataset.date = dateStr;
                if (!isPast) {
                    div.addEventListener('click', () => {
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('calendar-day-active'));
                        div.classList.add('calendar-day-active');
                        const slotDateInput = document.getElementById('slot-date');
                        if (slotDateInput._flatpickr) slotDateInput._flatpickr.setDate(dateStr);
                        renderSlotsTable('all');
                    });
                }
                daysContainer.appendChild(div);
            }

            // Dias próximos
            const remainingDays = 42 - (startDay + daysInMonth);
            for (let i = 1; i <= remainingDays; i++) {
                const div = document.createElement('div');
                div.className = 'calendar-day calendar-day-past';
                div.textContent = i;
                daysContainer.appendChild(div);
            }
        }

        // Funções de validação, status, geração de slots, etc. (adaptadas do código fornecido, mas com AJAX para save)
        function isValidSlot(date, start, end, type, excludeId = null) {
            if (!date || !start || !end) return { valid: false, message: 'Campos obrigatórios ausentes.' };
            const now = new Date();
            const slotStart = new Date(`${date}T${start}`);
            const slotEnd = new Date(`${date}T${end}`);
            if (slotStart <= now) return { valid: false, message: 'Horário no passado.' };
            if (slotStart >= slotEnd) return { valid: false, message: 'Início após fim.' };
            // Adicione mais validações...
            return { valid: true };
        }

        function getSlotStatus(slot) {
            // Lógica similar...
            return 'Disponível';  // Placeholder
        }

        function generateSlotsForDate(date) {
            // Lógica similar...
            return [];
        }

        function renderSlotsTable(dateRange) {
            const tbody = document.getElementById('slots-table');
            const noSlots = document.getElementById('no-slots');
            tbody.innerHTML = '';
            if (availableSlots.length === 0) {
                noSlots.classList.remove('hidden');
                return;
            }
            noSlots.classList.add('hidden');
            availableSlots.forEach(slot => {
                const row = document.createElement('tr');
                row.className = 'border-t';
                row.innerHTML = `
                    <td class="px-4 py-2">${slot.date || '-'}</td>
                    <td class="px-4 py-2">${slot.start} - ${slot.end}</td>
                    <td class="px-4 py-2">${slot.type === 'Available' ? 'Disponível' : 'Indisponível'}</td>
                    <td class="px-4 py-2">${getSlotStatus(slot)}</td>
                    <td class="px-4 py-2">${slot.reason || '-'}</td>
                    <td class="px-4 py-2">
                        <button class="action-btn edit-btn mr-2" data-id="${slot.id}" title="Editar"><i class="fas fa-edit"></i></button>
                        <button class="action-btn delete-btn" data-id="${slot.id}" title="Excluir"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            // Adicione event listeners para edit/delete com AJAX
            document.querySelectorAll('.edit-btn').forEach(btn => btn.addEventListener('click', () => openEditModal(btn.dataset.id)));
            document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', async () => {
                if (confirm('Confirmar exclusão?')) {
                    // AJAX delete
                    try {
                        const response = await fetch('<?php echo site_url('admin/delete_slot'); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: parseInt(btn.dataset.id), doctor_id: currentDoctor.id })
                        });
                        const result = await response.json();
                        if (result.success) {
                            availableSlots = availableSlots.filter(s => s.id != btn.dataset.id);
                            renderSlotsTable(dateRange);
                            showNotification(result.success, 'success');
                        } else {
                            showNotification(result.error, 'error');
                        }
                    } catch (error) {
                        showNotification('Erro ao excluir.', 'error');
                    }
                }
            }));
        }

        function openEditModal(id) {
            const slot = availableSlots.find(s => s.id == id);
            if (!slot) return;
            document.getElementById('edit-slot-id').value = id;
            document.getElementById('edit-slot-date')._flatpickr.setDate(slot.date);
            document.getElementById('edit-slot-start').value = slot.start;
            document.getElementById('edit-slot-end').value = slot.end;
            document.getElementById('edit-slot-type').value = slot.type;
            document.getElementById('edit-slot-reason').value = slot.reason;
            document.getElementById('edit-slot-modal').classList.add('show');
        }

        // Event listeners para forms com AJAX
        document.getElementById('add-slot-form').addEventListener('submit', async e => {
            e.preventDefault();
            const formData = {
                date: document.getElementById('slot-date').value,
                start: document.getElementById('slot-start').value,
                end: document.getElementById('slot-end').value,
                type: document.getElementById('slot-type').value,
                reason: document.getElementById('slot-reason').value,
                doctor_id: currentDoctor.id
            };
            const validation = isValidSlot(formData.date, formData.start, formData.end, formData.type);
            if (!validation.valid) return showNotification(validation.message, 'error');

            try {
                const response = await fetch('<?php echo site_url('admin/add_slot'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const result = await response.json();
                if (result.success) {
                    availableSlots.unshift(result.slot);
                    renderSlotsTable(document.getElementById('date-range').value);
                    renderCalendar();
                    showNotification(result.success, 'success');
                    document.getElementById('add-slot-form').reset();
                    document.getElementById('slot-date')._flatpickr.clear();
                } else {
                    showNotification(result.error, 'error');
                }
            } catch (error) {
                showNotification('Erro ao adicionar.', 'error');
            }
        });

        document.getElementById('edit-slot-form').addEventListener('submit', async e => {
            e.preventDefault();
            const formData = {
                id: parseInt(document.getElementById('edit-slot-id').value),
                date: document.getElementById('edit-slot-date').value,
                start: document.getElementById('edit-slot-start').value,
                end: document.getElementById('edit-slot-end').value,
                type: document.getElementById('edit-slot-type').value,
                reason: document.getElementById('edit-slot-reason').value,
                doctor_id: currentDoctor.id
            };
            const validation = isValidSlot(formData.date, formData.start, formData.end, formData.type, formData.id);
            if (!validation.valid) return showNotification(validation.message, 'error');

            try {
                const response = await fetch('<?php echo site_url('admin/update_slot'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const result = await response.json();
                if (result.success) {
                    const index = availableSlots.findIndex(s => s.id === formData.id);
                    if (index !== -1) availableSlots[index] = result.slot;
                    renderSlotsTable(document.getElementById('date-range').value);
                    renderCalendar();
                    showNotification(result.success, 'success');
                    document.getElementById('edit-slot-modal').classList.remove('show');
                } else {
                    showNotification(result.error, 'error');
                }
            } catch (error) {
                showNotification('Erro ao atualizar.', 'error');
            }
        });

        document.getElementById('save-schedule').addEventListener('click', async () => {
            const scheduleData = {
                days: Array.from(document.querySelectorAll('.schedule-day:checked')).map(cb => parseInt(cb.value)),
                start: document.getElementById('schedule-start').value,
                end: document.getElementById('schedule-end').value,
                duration: parseInt(document.getElementById('consultation-duration').value),
                doctor_id: currentDoctor.id
            };
            // Validações...
            try {
                const response = await fetch('<?php echo site_url('admin/save_schedule'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(scheduleData)
                });
                const result = await response.json();
                if (result.success) {
                    doctorSchedule = scheduleData;
                    // Regenerar slots se necessário
                    showNotification(result.success, 'success');
                } else {
                    showNotification(result.error, 'error');
                }
            } catch (error) {
                showNotification('Erro ao salvar horário.', 'error');
            }
        });

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar e outros handlers (copiados do agendamentos)
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            notificationClose.addEventListener('click', () => document.getElementById('notification').classList.remove('show'));
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
            sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });
            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                if (!isClickInsideSidebar && !isClickOnMenuBtn && sidebarMenu.classList.contains('show') && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada!', 'success');
                    window.location.href = '<?php echo site_url('login'); ?>';
                });
            }

            // Flatpickr
            flatpickr('.date-input', {
                dateFormat: 'Y-m-d',
                locale: 'pt',
                minDate: new Date().toISOString().split('T')[0]
            });

            // Seleção de médico
            document.getElementById('doctor-select').addEventListener('change', (e) => {
                const doctorId = e.target.value;
                if (doctorId) {
                    loadDoctorData(doctorId);
                } else {
                    document.getElementById('doctor-content').classList.add('hidden');
                }
            });

            // Navegação calendário
            document.getElementById('prev-month').addEventListener('click', () => {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                renderCalendar();
            });
            document.getElementById('next-month').addEventListener('click', () => {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                renderCalendar();
            });

            // Filtro de data
            document.getElementById('date-range').addEventListener('change', () => {
                renderSlotsTable(document.getElementById('date-range').value);
            });

            // Cancelar edição
            document.getElementById('cancel-edit').addEventListener('click', () => {
                document.getElementById('edit-slot-modal').classList.remove('show');
            });
        });
    </script>
</body>
</html>