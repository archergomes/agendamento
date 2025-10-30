<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Horários - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de horários e agenda do médico no Hospital Público de Matlhovele">
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

        .schedule-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .schedule-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .schedule-card.available {
            border-left-color: #10b981;
            background-color: #f0fdf4;
        }

        .schedule-card.unavailable {
            border-left-color: #6b7280;
            background-color: #f9fafb;
        }

        .schedule-card.emergency {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }

        .schedule-card.break {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-disponivel {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-indisponivel {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-emergencia {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .status-almoco {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-reuniao {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .day-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #e5e7eb;
        }

        .day-badge.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .day-badge:hover:not(.active) {
            background-color: #eff6ff;
            border-color: #3b82f6;
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
            max-width: 600px;
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

        .calendar {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .calendar-header {
            background-color: #3b82f6;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #e5e7eb;
        }

        .calendar-day {
            background-color: white;
            padding: 0.75rem;
            min-height: 100px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .calendar-day:hover {
            background-color: #f3f4f6;
        }

        .calendar-day.other-month {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .calendar-day.today {
            background-color: #eff6ff;
            border: 2px solid #3b82f6;
        }

        .calendar-day.selected {
            background-color: #dbeafe;
        }

        .calendar-day.has-appointments {
            background-color: #f0fdf4;
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .appointment-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            margin-right: 2px;
            display: inline-block;
        }

        .appointment-indicator.emergency {
            background-color: #ef4444;
        }

        .appointment-indicator.follow-up {
            background-color: #f59e0b;
        }

        .time-slot {
            display: flex;
            align-items: center;
            justify-content: between;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .time-slot.available {
            background-color: #f0fdf4;
            border-color: #a7f3d0;
        }

        .time-slot.booked {
            background-color: #fef2f2;
            border-color: #fecaca;
        }

        .time-slot.break {
            background-color: #fffbeb;
            border-color: #fed7aa;
        }

        .time-slot.unavailable {
            background-color: #f3f4f6;
            border-color: #d1d5db;
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
            min-height: 80px;
            resize: vertical;
        }

        .time-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .time-option {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .time-option:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .time-option.selected {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .recurring-options {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .week-day-selector {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .week-day {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .week-day:hover {
            border-color: #3b82f6;
        }

        .week-day.selected {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
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
                    <a href="<?php echo site_url('medico/horarios'); ?>" class="block text-gray-700 active">
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
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Meus Horários</h2>
                            <p class="text-gray-600">Gerencie sua agenda e horários de atendimento.</p>
                        </div>
                        <button onclick="novoHorario()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Novo Horário
                        </button>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="metric-card" onclick="filterByStatus('hoje')">
                            <div class="text-2xl font-bold text-blue-600" id="total-hoje">0</div>
                            <div class="text-sm text-gray-600">Consultas Hoje</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('semana')">
                            <div class="text-2xl font-bold text-green-600" id="total-semana">0</div>
                            <div class="text-sm text-gray-600">Esta Semana</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('disponiveis')">
                            <div class="text-2xl font-bold text-orange-600" id="horarios-disponiveis">0</div>
                            <div class="text-sm text-gray-600">Horários Livres</div>
                        </div>
                        <div class="metric-card" onclick="filterByStatus('ocupados')">
                            <div class="text-2xl font-bold text-purple-600" id="horarios-ocupados">0</div>
                            <div class="text-sm text-gray-600">Horários Ocupados</div>
                        </div>
                    </div>
                </div>

                <!-- Calendar and Schedule View -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Calendar -->
                    <div class="lg:col-span-2">
                        <div class="calendar">
                            <div class="calendar-header">
                                <div class="flex justify-between items-center">
                                    <button onclick="previousMonth()" class="text-white hover:text-blue-200">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <h3 id="current-month" class="text-lg font-semibold">Fevereiro 2024</h3>
                                    <button onclick="nextMonth()" class="text-white hover:text-blue-200">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="calendar-grid" id="calendar-grid">
                                <!-- Calendar will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-y-4">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Ações Rápidas</h3>
                            <div class="space-y-2">
                                <button onclick="configurarHorarioPadrao()" class="action-btn w-full text-left flex items-center gap-2">
                                    <i class="fas fa-cog"></i>
                                    Configurar Horário Padrão
                                </button>
                                <button onclick="bloquearHorarios()" class="action-btn warning w-full text-left flex items-center gap-2">
                                    <i class="fas fa-lock"></i>
                                    Bloquear Horários
                                </button>
                                <button onclick="ferias()" class="action-btn secondary w-full text-left flex items-center gap-2">
                                    <i class="fas fa-umbrella-beach"></i>
                                    Programar Férias
                                </button>
                                <button onclick="exportarAgenda()" class="action-btn w-full text-left flex items-center gap-2">
                                    <i class="fas fa-download"></i>
                                    Exportar Agenda
                                </button>
                            </div>
                        </div>

                        <!-- Today's Schedule -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-medium text-gray-700 mb-4">Hoje</h3>
                            <div id="today-schedule">
                                <!-- Today's schedule will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Day Selection -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Selecionar Dia</h3>
                    <div class="flex flex-wrap gap-2" id="day-selector">
                        <!-- Days will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Schedule for Selected Day -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700" id="selected-day-title">Agenda do Dia</h3>
                            <div class="text-sm text-gray-600">
                                <span id="schedule-count">0</span> horários configurados
                            </div>
                        </div>
                        
                        <div id="schedule-list">
                            <!-- Schedule will be populated by JavaScript -->
                        </div>

                        <!-- Loading State -->
                        <div id="loading-state" class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                            <p class="text-gray-600">Carregando horários...</p>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="empty-state hidden">
                            <i class="fas fa-calendar-times"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum horário configurado</h3>
                            <p class="text-gray-600 mb-4">Não há horários configurados para este dia.</p>
                            <button onclick="novoHorario()" class="action-btn success">
                                <i class="fas fa-plus mr-2"></i>Configurar Horários
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Novo Horário -->
    <div id="novo-horario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Configurar Novo Horário</h3>
                <button onclick="closeNovoHorarioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="novo-horario-content">
                <!-- Formulário de novo horário será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Edição de Horário -->
    <div id="editar-horario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Editar Horário</h3>
                <button onclick="closeEditarHorarioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="editar-horario-content">
                <!-- Formulário de edição será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Configuração de Horário Padrão -->
    <div id="configurar-horario-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700">Configurar Horário Padrão</h3>
                <button onclick="closeConfigurarHorarioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="configurar-horario-content">
                <!-- Configuração de horário padrão será carregada via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'todos';
        let currentDate = new Date();
        let selectedDate = new Date();

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
                const response = await fetch('<?php echo site_url('api/medico/horarios_stats'); ?>');
                const stats = await response.json();
                
                if (stats.error) {
                    showNotification(stats.error, 'error');
                    return;
                }

                document.getElementById('total-hoje').textContent = stats.hoje || 0;
                document.getElementById('total-semana').textContent = stats.semana || 0;
                document.getElementById('horarios-disponiveis').textContent = stats.disponiveis || 0;
                document.getElementById('horarios-ocupados').textContent = stats.ocupados || 0;

            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        }

        // Inicializar calendário
        function initCalendar() {
            updateCalendar();
            updateDaySelector();
            loadTodaySchedule();
            loadScheduleForDate(selectedDate);
        }

        // Atualizar calendário
        function updateCalendar() {
            const monthNames = [
                'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
            ];

            const currentMonth = currentDate.getMonth();
            const currentYear = currentDate.getFullYear();
            
            document.getElementById('current-month').textContent = 
                `${monthNames[currentMonth]} ${currentYear}`;

            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const startingDay = firstDay.getDay();
            const daysInMonth = lastDay.getDate();

            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';

            // Adicionar cabeçalhos dos dias da semana
            const dayNames = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            dayNames.forEach(dayName => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'bg-gray-100 p-2 text-center text-sm font-medium text-gray-700';
                dayHeader.textContent = dayName;
                calendarGrid.appendChild(dayHeader);
            });

            // Dias do mês anterior
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
            for (let i = startingDay - 1; i >= 0; i--) {
                const dayElement = createDayElement(prevMonthLastDay - i, true);
                calendarGrid.appendChild(dayElement);
            }

            // Dias do mês atual
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = createDayElement(day, false);
                
                // Marcar hoje
                if (day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                    dayElement.classList.add('today');
                }
                
                // Marcar selecionado
                if (day === selectedDate.getDate() && currentMonth === selectedDate.getMonth() && currentYear === selectedDate.getFullYear()) {
                    dayElement.classList.add('selected');
                }

                calendarGrid.appendChild(dayElement);
            }

            // Dias do próximo mês
            const totalCells = 42; // 6 semanas * 7 dias
            const remainingCells = totalCells - (startingDay + daysInMonth);
            for (let day = 1; day <= remainingCells; day++) {
                const dayElement = createDayElement(day, true);
                calendarGrid.appendChild(dayElement);
            }
        }

        // Criar elemento de dia
        function createDayElement(day, isOtherMonth) {
            const dayElement = document.createElement('div');
            dayElement.className = `calendar-day ${isOtherMonth ? 'other-month' : ''}`;
            dayElement.innerHTML = `
                <div class="day-number">${day}</div>
                <div class="appointment-indicators"></div>
            `;

            if (!isOtherMonth) {
                dayElement.addEventListener('click', () => {
                    selectDate(day);
                });
            }

            return dayElement;
        }

        // Selecionar data
        function selectDate(day) {
            selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            updateCalendar();
            updateDaySelector();
            loadScheduleForDate(selectedDate);
        }

        // Mês anterior
        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar();
        }

        // Próximo mês
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendar();
        }

        // Atualizar seletor de dias
        function updateDaySelector() {
            const daySelector = document.getElementById('day-selector');
            const dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            
            daySelector.innerHTML = '';

            // Adicionar próximos 7 dias
            for (let i = 0; i < 7; i++) {
                const date = new Date();
                date.setDate(date.getDate() + i);
                
                const dayElement = document.createElement('div');
                dayElement.className = `day-badge ${isSameDay(date, selectedDate) ? 'active' : ''}`;
                dayElement.innerHTML = `
                    <div class="font-semibold">${dayNames[date.getDay()]}</div>
                    <div class="text-sm">${date.getDate()}/${date.getMonth() + 1}</div>
                `;

                dayElement.addEventListener('click', () => {
                    selectedDate = date;
                    currentDate = new Date(date);
                    updateCalendar();
                    updateDaySelector();
                    loadScheduleForDate(selectedDate);
                });

                daySelector.appendChild(dayElement);
            }
        }

        // Verificar se é o mesmo dia
        function isSameDay(date1, date2) {
            return date1.getDate() === date2.getDate() &&
                   date1.getMonth() === date2.getMonth() &&
                   date1.getFullYear() === date2.getFullYear();
        }

        // Carregar agenda de hoje
        async function loadTodaySchedule() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch(`<?php echo site_url('api/medico/agenda_hoje'); ?>?date=${today}`);
                const schedule = await response.json();

                const container = document.getElementById('today-schedule');
                if (!container) return;

                if (schedule.error || schedule.length === 0) {
                    container.innerHTML = '<p class="text-gray-600 text-center">Nenhum compromisso hoje.</p>';
                    return;
                }

                container.innerHTML = schedule.map(item => `
                    <div class="time-slot ${getTimeSlotClass(item)}">
                        <div class="flex-1">
                            <div class="font-medium">${item.hora}</div>
                            <div class="text-sm text-gray-600">${item.descricao}</div>
                        </div>
                        <span class="status-badge ${getStatusClass(item)}">${item.tipo}</span>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Erro ao carregar agenda de hoje:', error);
            }
        }

        // Carregar horários para data selecionada
        async function loadScheduleForDate(date) {
            const loading = document.getElementById('loading-state');
            const empty = document.getElementById('empty-state');
            const list = document.getElementById('schedule-list');
            const count = document.getElementById('schedule-count');
            const title = document.getElementById('selected-day-title');

            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');
            if (list) list.innerHTML = '';

            // Atualizar título
            const dayNames = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            const monthNames = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            title.textContent = `Agenda - ${dayNames[date.getDay()]}, ${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}`;

            try {
                const dateString = date.toISOString().split('T')[0];
                const response = await fetch(`<?php echo site_url('api/medico/horarios_data'); ?>?date=${dateString}`);
                const horarios = await response.json();

                if (loading) loading.classList.add('hidden');

                if (horarios.error) {
                    showNotification(horarios.error, 'error');
                    return;
                }

                if (count) count.textContent = horarios.length || 0;

                if (horarios.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                renderHorarios(horarios);

            } catch (error) {
                console.error('Erro ao carregar horários:', error);
                showNotification('Erro ao carregar horários.', 'error');
                if (loading) loading.classList.add('hidden');
            }
        }

        // Renderizar horários
        function renderHorarios(horarios) {
            const list = document.getElementById('schedule-list');
            if (!list) return;

            list.innerHTML = horarios.map(horario => {
                const cardClass = getCardClass(horario);
                const statusClass = getStatusClass(horario);

                return `
                    <div class="schedule-card ${cardClass}" data-horario-id="${horario.id}">
                        <div class="flex flex-col lg:flex-row gap-4">
                            <!-- Informações do Horário -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-3">
                                        <h4 class="font-semibold text-gray-800 text-lg">${horario.hora_inicio} - ${horario.hora_fim}</h4>
                                        <span class="status-badge ${statusClass}">${horario.status}</span>
                                        ${horario.tipo ? `<span class="type-badge">${horario.tipo}</span>` : ''}
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-600">Duração:</span>
                                            <p class="font-medium">${horario.duracao || 'N/A'} minutos</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Tipo:</span>
                                            <p class="font-medium">${horario.tipo_atendimento || 'Consulta'}</p>
                                        </div>
                                        ${horario.paciente_nome ? `
                                            <div>
                                                <span class="text-gray-600">Paciente:</span>
                                                <p class="font-medium">${horario.paciente_nome}</p>
                                            </div>
                                        ` : ''}
                                        ${horario.local ? `
                                            <div>
                                                <span class="text-gray-600">Local:</span>
                                                <p class="font-medium">${horario.local}</p>
                                            </div>
                                        ` : ''}
                                    </div>

                                    <!-- Observações -->
                                    ${horario.observacoes ? `
                                        <div class="bg-gray-50 rounded p-3">
                                            <span class="text-gray-600 text-sm">Observações:</span>
                                            <p class="text-sm text-gray-700 mt-1">${horario.observacoes}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-col gap-2 lg:w-48">
                                ${horario.status === 'disponivel' ? `
                                    <button class="action-btn editar-horario" data-id="${horario.id}">
                                        <i class="fas fa-edit mr-1"></i>Editar
                                    </button>
                                    <button class="action-btn danger bloquear-horario" data-id="${horario.id}">
                                        <i class="fas fa-lock mr-1"></i>Bloquear
                                    </button>
                                ` : ''}
                                ${horario.status === 'bloqueado' ? `
                                    <button class="action-btn success liberar-horario" data-id="${horario.id}">
                                        <i class="fas fa-unlock mr-1"></i>Liberar
                                    </button>
                                ` : ''}
                                ${horario.status === 'ocupado' ? `
                                    <button class="action-btn ver-consulta" data-id="${horario.consulta_id}">
                                        <i class="fas fa-eye mr-1"></i>Ver Consulta
                                    </button>
                                ` : ''}
                                <button class="action-btn danger excluir-horario" data-id="${horario.id}">
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

        // Obter classe CSS do card
        function getCardClass(horario) {
            const classMap = {
                'disponivel': 'available',
                'ocupado': 'unavailable',
                'bloqueado': 'unavailable',
                'emergencia': 'emergency',
                'almoco': 'break',
                'reuniao': 'unavailable'
            };
            return classMap[horario.status] || 'available';
        }

        // Obter classe CSS do status
        function getStatusClass(horario) {
            const statusMap = {
                'disponivel': 'status-disponivel',
                'ocupado': 'status-indisponivel',
                'bloqueado': 'status-indisponivel',
                'emergencia': 'status-emergencia',
                'almoco': 'status-almoco',
                'reuniao': 'status-reuniao'
            };
            return statusMap[horario.status] || 'status-disponivel';
        }

        // Obter classe CSS do time slot
        function getTimeSlotClass(item) {
            const classMap = {
                'consulta': 'booked',
                'disponivel': 'available',
                'bloqueado': 'unavailable',
                'almoco': 'break',
                'reuniao': 'unavailable'
            };
            return classMap[item.tipo] || 'available';
        }

        // Adicionar event listeners aos botões
        function addEventListeners() {
            // Editar horário
            document.querySelectorAll('.editar-horario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const horarioId = e.target.closest('button').dataset.id;
                    editarHorario(horarioId);
                });
            });

            // Bloquear horário
            document.querySelectorAll('.bloquear-horario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const horarioId = e.target.closest('button').dataset.id;
                    bloquearHorario(horarioId);
                });
            });

            // Liberar horário
            document.querySelectorAll('.liberar-horario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const horarioId = e.target.closest('button').dataset.id;
                    liberarHorario(horarioId);
                });
            });

            // Ver consulta
            document.querySelectorAll('.ver-consulta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const consultaId = e.target.closest('button').dataset.id;
                    verConsulta(consultaId);
                });
            });

            // Excluir horário
            document.querySelectorAll('.excluir-horario').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const horarioId = e.target.closest('button').dataset.id;
                    excluirHorario(horarioId);
                });
            });
        }

        // Funções de ação
        function novoHorario() {
            const modal = document.getElementById('novo-horario-modal');
            const content = document.getElementById('novo-horario-content');

            const dateString = selectedDate.toISOString().split('T')[0];

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Data</label>
                            <input type="date" class="form-input" value="${dateString}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo de Horário</label>
                            <select class="form-select" id="tipo-horario">
                                <option value="disponivel">Disponível para Consultas</option>
                                <option value="bloqueado">Bloqueado</option>
                                <option value="almoco">Almoço</option>
                                <option value="reuniao">Reunião</option>
                                <option value="emergencia">Plantão/Emergência</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Hora de Início</label>
                            <input type="time" class="form-input" value="08:00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hora de Fim</label>
                            <input type="time" class="form-input" value="17:00" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duração da Consulta (minutos)</label>
                        <select class="form-select">
                            <option value="15">15 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">60 minutos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Local</label>
                        <input type="text" class="form-input" placeholder="Consultório, Sala de Exame...">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Observações</label>
                        <textarea class="form-textarea" placeholder="Observações sobre este horário..."></textarea>
                    </div>

                    <!-- Configuração Recorrente -->
                    <div class="recurring-options">
                        <h4 class="font-medium text-gray-700 mb-3">Configuração Recorrente</h4>
                        <div class="form-group">
                            <label class="form-label">Repetir</label>
                            <select class="form-select" id="repetir-option">
                                <option value="nenhuma">Não repetir</option>
                                <option value="diariamente">Diariamente</option>
                                <option value="semanalmente">Semanalmente</option>
                                <option value="mensalmente">Mensalmente</option>
                            </select>
                        </div>

                        <div id="weekly-options" class="hidden">
                            <label class="form-label">Dias da Semana</label>
                            <div class="week-day-selector">
                                <div class="week-day" data-day="1">Seg</div>
                                <div class="week-day" data-day="2">Ter</div>
                                <div class="week-day" data-day="3">Qua</div>
                                <div class="week-day" data-day="4">Qui</div>
                                <div class="week-day" data-day="5">Sex</div>
                                <div class="week-day" data-day="6">Sáb</div>
                                <div class="week-day" data-day="0">Dom</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Até</label>
                            <input type="date" class="form-input" id="data-fim-recorrencia">
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeNovoHorarioModal()" class="action-btn danger">Cancelar</button>
                        <button onclick="criarHorario()" class="action-btn success">Criar Horário</button>
                    </div>
                </div>
            `;

            // Configurar eventos para a configuração recorrente
            setupRecurringOptions();

            modal.classList.add('show');
        }

        function setupRecurringOptions() {
            const repeatSelect = document.getElementById('repetir-option');
            const weeklyOptions = document.getElementById('weekly-options');

            repeatSelect.addEventListener('change', function() {
                if (this.value === 'semanalmente') {
                    weeklyOptions.classList.remove('hidden');
                } else {
                    weeklyOptions.classList.add('hidden');
                }
            });

            // Selecionar dias da semana
            document.querySelectorAll('.week-day').forEach(day => {
                day.addEventListener('click', function() {
                    this.classList.toggle('selected');
                });
            });
        }

        async function editarHorario(horarioId) {
            try {
                const response = await fetch(`<?php echo site_url('api/medico/horario_editar/'); ?>${horarioId}`);
                const horario = await response.json();

                if (horario.error) {
                    showNotification(horario.error, 'error');
                    return;
                }

                const modal = document.getElementById('editar-horario-modal');
                const content = document.getElementById('editar-horario-content');

                content.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Data</label>
                                <input type="date" class="form-input" value="${horario.data}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tipo de Horário</label>
                                <select class="form-select">
                                    <option value="disponivel" ${horario.status === 'disponivel' ? 'selected' : ''}>Disponível</option>
                                    <option value="bloqueado" ${horario.status === 'bloqueado' ? 'selected' : ''}>Bloqueado</option>
                                    <option value="almoco" ${horario.status === 'almoco' ? 'selected' : ''}>Almoço</option>
                                    <option value="reuniao" ${horario.status === 'reuniao' ? 'selected' : ''}>Reunião</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Hora de Início</label>
                                <input type="time" class="form-input" value="${horario.hora_inicio}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Hora de Fim</label>
                                <input type="time" class="form-input" value="${horario.hora_fim}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Observações</label>
                            <textarea class="form-textarea">${horario.observacoes || ''}</textarea>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <button onclick="closeEditarHorarioModal()" class="action-btn danger">Cancelar</button>
                            <button onclick="salvarHorario(${horarioId})" class="action-btn success">Salvar</button>
                        </div>
                    </div>
                `;

                modal.classList.add('show');

            } catch (error) {
                console.error('Erro ao carregar formulário de edição:', error);
                showNotification('Erro ao carregar formulário de edição.', 'error');
            }
        }

        function criarHorario() {
            showNotification('Horário criado com sucesso!', 'success');
            closeNovoHorarioModal();
            loadScheduleForDate(selectedDate);
        }

        function salvarHorario(horarioId) {
            showNotification('Horário atualizado com sucesso!', 'success');
            closeEditarHorarioModal();
            loadScheduleForDate(selectedDate);
        }

        function bloquearHorario(horarioId) {
            if (confirm('Tem certeza que deseja bloquear este horário?')) {
                showNotification('Horário bloqueado com sucesso!', 'success');
                loadScheduleForDate(selectedDate);
            }
        }

        function liberarHorario(horarioId) {
            showNotification('Horário liberado com sucesso!', 'success');
            loadScheduleForDate(selectedDate);
        }

        function verConsulta(consultaId) {
            window.location.href = `<?php echo site_url('medico/consulta/'); ?>${consultaId}`;
        }

        function excluirHorario(horarioId) {
            if (confirm('Tem certeza que deseja excluir este horário?')) {
                showNotification('Horário excluído com sucesso!', 'success');
                loadScheduleForDate(selectedDate);
            }
        }

        function configurarHorarioPadrao() {
            const modal = document.getElementById('configurar-horario-modal');
            const content = document.getElementById('configurar-horario-content');

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Horário de Trabalho Padrão</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Início</label>
                                <input type="time" class="form-input" value="08:00">
                            </div>
                            <div>
                                <label class="form-label">Fim</label>
                                <input type="time" class="form-input" value="17:00">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duração Padrão da Consulta</label>
                        <select class="form-select">
                            <option value="15">15 minutos</option>
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">60 minutos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dias de Trabalho</label>
                        <div class="week-day-selector">
                            <div class="week-day selected" data-day="1">Seg</div>
                            <div class="week-day selected" data-day="2">Ter</div>
                            <div class="week-day selected" data-day="3">Qua</div>
                            <div class="week-day selected" data-day="4">Qui</div>
                            <div class="week-day selected" data-day="5">Sex</div>
                            <div class="week-day" data-day="6">Sáb</div>
                            <div class="week-day" data-day="0">Dom</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Horário de Almoço</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Início</label>
                                <input type="time" class="form-input" value="12:00">
                            </div>
                            <div>
                                <label class="form-label">Fim</label>
                                <input type="time" class="form-input" value="13:00">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button onclick="closeConfigurarHorarioModal()" class="action-btn danger">Cancelar</button>
                        <button onclick="salvarHorarioPadrao()" class="action-btn success">Salvar Configuração</button>
                    </div>
                </div>
            `;

            modal.classList.add('show');
        }

        function salvarHorarioPadrao() {
            showNotification('Horário padrão configurado com sucesso!', 'success');
            closeConfigurarHorarioModal();
        }

        function bloquearHorarios() {
            showNotification('Funcionalidade de bloquear horários em desenvolvimento', 'info');
        }

        function ferias() {
            showNotification('Funcionalidade de programar férias em desenvolvimento', 'info');
        }

        function exportarAgenda() {
            showNotification('Exportando agenda...', 'info');
        }

        function closeNovoHorarioModal() {
            document.getElementById('novo-horario-modal').classList.remove('show');
        }

        function closeEditarHorarioModal() {
            document.getElementById('editar-horario-modal').classList.remove('show');
        }

        function closeConfigurarHorarioModal() {
            document.getElementById('configurar-horario-modal').classList.remove('show');
        }

        // Filtros
        function filterByStatus(status) {
            currentFilter = status;
            // Implementar filtros específicos
            showNotification(`Filtrando por: ${status}`, 'info');
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

            // Carregar dados iniciais
            loadStats();
            initCalendar();

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