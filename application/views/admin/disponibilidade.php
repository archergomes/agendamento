<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidade - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de disponibilidade para médicos do Hospital Público de Matlhovele">
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
            background-color: #1e40af;
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
            scrollbar-color: #3b82f6 #1e40af;
        }

        .main-menu::-webkit-scrollbar {
            width: 6px;
        }

        .main-menu::-webkit-scrollbar-track {
            background: #1e40af;
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
            color: white;
            transition: background-color 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover,
        .sidebar-nav button:hover {
            background-color: #3730a3;
        }

        .sidebar-nav a.active {
            background-color: #3730a3;
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
            border-top: 1px solid #3730a3;
            padding-top: 0.5rem;
        }

        footer {
            width: 100%;
            position: relative;
            margin-left: 0;
        }

        /* Calendar Styles */
        .calendar-grid {
            min-height: 200px;
        }

        .calendar-day {
            text-align: center;
            padding: 8px;
            border-radius: 0.25rem;
            min-height: 32px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .calendar-day:hover:not(.calendar-day-past):not(.calendar-day-booked) {
            background-color: #e0f2fe;
        }

        .calendar-day-active {
            background-color: #3b82f6;
            color: white;
        }

        .calendar-day-past {
            color: #d1d5db;
            cursor: not-allowed;
        }

        .calendar-day-available {
            background-color: #dcfce7;
        }

        .calendar-day-booked {
            background-color: #fef9c3;
        }

        .calendar-day-unavailable {
            background-color: #fee2e2;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Form Styles */
        .form-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
            transition: all 0.2s;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .btn-outline {
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-outline:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-available {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-unavailable {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-booked {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* User Menu */
        .user-menu {
            position: relative;
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

        .doctor-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem;
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="page-wrapper">
        <!-- Notification -->
        <div id="notification" role="alert">
            <span id="notification-message"></span>
            <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
        </div>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        <!-- Left Sidebar -->
        <div id="sidebar-menu" class="sidebar shadow-lg desktop">
            <div class="sidebar-header flex justify-between items-center p-4 border-b border-blue-700">
                <h2 class="text-lg font-semibold text-white sidebar-text">Menu do Médico</h2>
                <button id="toggle-sidebar-btn" class="text-white hover:text-blue-200" aria-label="Alternar menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-white hover:text-blue-200 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="<?php echo site_url('medico'); ?>" class="block">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('medico/pacientes'); ?>" class="block">
                        <i class="fas fa-user-injured"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('medico/estatisticas'); ?>" class="block">
                        <i class="fas fa-chart-line"></i>
                        <span class="sidebar-text">Estatísticas</span>
                    </a>
                    <a href="<?php echo site_url('medico/disponibilidade'); ?>" class="block active">
                        <i class="fas fa-calendar-plus"></i>
                        <span class="sidebar-text">Disponibilidade</span>
                    </a>
                    <a href="<?php echo site_url('medico/configuracoes'); ?>" class="block">
                        <i class="fas fa-cog"></i>
                        <span class="sidebar-text">Configurações</span>
                    </a>
                </div>
                <button id="logout-btn" class="block w-full text-left logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Sair</span>
                </button>
            </nav>
        </div>

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="bg-white container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <button id="mobile-menu-btn" class="text-gray-600 md:hidden">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <i class="fas fa-hospital-alt text-2xl text-blue-600" aria-label="Ícone do Hospital Matlhovele"></i>
                    <h1 class="text-xl font-bold text-blue-600">Disponibilidade</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="user-menu">
                        <button id="user-menu-btn" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                            <div class="doctor-avatar">
                                <?php
                                $iniciais = '';
                                if (isset($medico) && !empty($medico['Nome_Completo'])) {
                                    $nomes = explode(' ', $medico['Nome_Completo']);
                                    $iniciais = substr($nomes[0], 0, 1) . (isset($nomes[1]) ? substr($nomes[1], 0, 1) : '');
                                } else {
                                    $iniciais = 'MD';
                                }
                                echo $iniciais;
                                ?>
                            </div>
                            <span><?php echo isset($medico) ? $medico['Nome_Completo'] : 'Médico'; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="dropdown-menu" class="dropdown-menu">
                            <div class="doctor-info border-b border-gray-200">
                                <div class="doctor-avatar">
                                    <?php echo $iniciais; ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo isset($medico) ? $medico['Nome_Completo'] : 'Médico'; ?></p>
                                    <p class="text-sm text-gray-600"><?php echo isset($medico) ? $medico['Especialidade'] : 'Especialidade'; ?></p>
                                </div>
                            </div>
                            <a href="<?php echo site_url('medico/perfil'); ?>" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-user mr-2"></i>Perfil
                            </a>
                            <button id="logout-btn-header" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Sair
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Minha Disponibilidade</h2>
                        <p class="text-gray-600 mt-2">Gerencie seus horários de atendimento e disponibilidade</p>
                    </div>
                    <div class="flex space-x-2">
                        <select id="date-range" class="form-select w-48">
                            <option value="next-30">Próximos 30 Dias</option>
                            <option value="2025">2025</option>
                            <option value="all">Todos</option>
                        </select>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="form-container">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Calendário</h3>
                        <div class="flex items-center space-x-2">
                            <button id="prev-month" class="btn btn-outline p-2" aria-label="Mês anterior">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span id="calendar-month" class="px-4 py-1 font-medium text-gray-700"></span>
                            <button id="next-month" class="btn btn-outline p-2" aria-label="Próximo mês">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center font-semibold py-2 text-gray-700">Dom</div>
                            <div class="text-center font-semibold py-2 text-gray-700">Seg</div>
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
                <div class="form-container">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Horário ou Exceção</h3>
                    <form id="add-slot-form" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="slot-date" class="form-label">Data</label>
                                <input type="text" id="slot-date" class="form-input date-input" required>
                            </div>
                            <div>
                                <label for="slot-start" class="form-label">Início</label>
                                <input type="time" id="slot-start" class="form-input" step="900" required>
                            </div>
                            <div>
                                <label for="slot-end" class="form-label">Fim</label>
                                <input type="time" id="slot-end" class="form-input" step="900" required>
                            </div>
                            <div>
                                <label for="slot-type" class="form-label">Tipo</label>
                                <select id="slot-type" class="form-select">
                                    <option value="Available">Disponível</option>
                                    <option value="Unavailable">Indisponível</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="slot-reason" class="form-label">Motivo (opcional)</label>
                            <input type="text" id="slot-reason" class="form-input" placeholder="Ex: Reunião, Feriado, etc.">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus mr-2"></i>Adicionar Horário
                        </button>
                    </form>
                </div>

                <!-- Standard Working Hours -->
                <div class="form-container">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Horário Padrão</h3>
                        <button id="save-schedule" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Salvar Horário
                        </button>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-800 mb-3">Dias de Trabalho</h4>
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
                                <h4 class="font-medium text-gray-800 mb-3">Horário de Atendimento</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="schedule-start" class="form-label">Início</label>
                                        <input type="time" id="schedule-start" class="form-input" value="08:00" step="900" required>
                                    </div>
                                    <div>
                                        <label for="schedule-end" class="form-label">Fim</label>
                                        <input type="time" id="schedule-end" class="form-input" value="16:00" step="900" required>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="consultation-duration" class="form-label">Duração da Consulta</label>
                                    <select id="consultation-duration" class="form-select">
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
                <div class="form-container">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Meus Horários e Exceções</h3>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="slots-table" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
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

    <!-- Edit Slot Modal -->
    <div id="edit-slot-modal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Editar Horário</h3>
            <form id="edit-slot-form" class="space-y-4">
                <input type="hidden" id="edit-slot-id">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="edit-slot-date" class="form-label">Data</label>
                        <input type="text" id="edit-slot-date" class="form-input date-input" required>
                    </div>
                    <div>
                        <label for="edit-slot-start" class="form-label">Início</label>
                        <input type="time" id="edit-slot-start" class="form-input" step="900" required>
                    </div>
                    <div>
                        <label for="edit-slot-end" class="form-label">Fim</label>
                        <input type="time" id="edit-slot-end" class="form-input" step="900" required>
                    </div>
                    <div>
                        <label for="edit-slot-type" class="form-label">Tipo</label>
                        <select id="edit-slot-type" class="form-select">
                            <option value="Available">Disponível</option>
                            <option value="Unavailable">Indisponível</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="edit-slot-reason" class="form-label">Motivo (opcional)</label>
                    <input type="text" id="edit-slot-reason" class="form-input" placeholder="Ex: Reunião, Feriado, etc.">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancel-edit" class="btn btn-outline">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Dados iniciais
        let currentMonth = new Date();
        let availableSlots = [];
        let doctorSchedule = {
            days: [1, 2, 3, 4, 5],
            start: "08:00",
            end: "16:00",
            duration: 45
        };

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) {
                console.error('Notification elements not found');
                return;
            }
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Render calendar
        function renderCalendar() {
            const monthYear = currentMonth.toLocaleString('pt-PT', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('calendar-month').textContent = monthYear.charAt(0).toUpperCase() + monthYear.slice(1);

            const firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
            const lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);
            const startDay = firstDay.getDay();
            const daysInMonth = lastDay.getDate();
            const today = new Date();

            const daysContainer = document.getElementById('calendar-days');
            daysContainer.innerHTML = '';

            // Previous month days
            const prevMonthDays = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 0).getDate();
            for (let i = startDay - 1; i >= 0; i--) {
                const div = document.createElement('div');
                div.className = 'calendar-day calendar-day-past';
                div.textContent = prevMonthDays - i;
                daysContainer.appendChild(div);
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
                const dateStr = date.toISOString().split('T')[0];
                const isToday = dateStr === today.toISOString().split('T')[0];
                const hasAvailable = availableSlots.some(slot => slot.date === dateStr && slot.type === 'Available');
                const hasUnavailable = availableSlots.some(slot => slot.date === dateStr && slot.type === 'Unavailable');
                const isPast = date < today && !isToday;

                const div = document.createElement('div');
                div.className = `calendar-day ${isToday ? 'calendar-day-active' : ''} ${isPast ? 'calendar-day-past' : ''} ${hasAvailable ? 'calendar-day-available' : ''} ${hasUnavailable ? 'calendar-day-unavailable' : ''}`;
                div.textContent = day;
                div.dataset.date = dateStr;

                if (!isPast) {
                    div.addEventListener('click', () => {
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('calendar-day-active'));
                        div.classList.add('calendar-day-active');
                        const slotDateInput = document.getElementById('slot-date');
                        if (slotDateInput._flatpickr) {
                            slotDateInput._flatpickr.setDate(dateStr);
                        }
                    });
                }
                daysContainer.appendChild(div);
            }

            // Next month days
            const remainingDays = 42 - (startDay + daysInMonth);
            for (let i = 1; i <= remainingDays; i++) {
                const div = document.createElement('div');
                div.className = 'calendar-day calendar-day-past';
                div.textContent = i;
                daysContainer.appendChild(div);
            }
        }

        // Render slots table
        function renderSlotsTable() {
            const tbody = document.getElementById('slots-table');
            tbody.innerHTML = '';

            availableSlots.forEach(slot => {
                const status = slot.type === 'Available' ? 'Disponível' : 'Indisponível';
                const statusClass = slot.type === 'Available' ? 'status-available' : 'status-unavailable';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.start} - ${slot.end}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="status-badge ${statusClass}">${status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="status-badge status-available">Ativo</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${slot.reason || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="edit-slot text-blue-600 hover:text-blue-800 mr-2" data-id="${slot.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="delete-slot text-red-600 hover:text-red-800" data-id="${slot.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Add event listeners
            document.querySelectorAll('.edit-slot').forEach(btn => {
                btn.addEventListener('click', () => openEditModal(btn.dataset.id));
            });

            document.querySelectorAll('.delete-slot').forEach(btn => {
                btn.addEventListener('click', () => deleteSlot(btn.dataset.id));
            });
        }

        // Open edit modal
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

        // Delete slot
        function deleteSlot(id) {
            if (!confirm('Tem certeza que deseja excluir este horário?')) return;

            availableSlots = availableSlots.filter(s => s.id != id);
            renderSlotsTable();
            renderCalendar();
            showNotification('Horário excluído com sucesso!', 'success');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize flatpickr
            flatpickr('.date-input', {
                dateFormat: 'Y-m-d',
                locale: 'pt',
                minDate: 'today'
            });

            // Notification close
            document.getElementById('notification-close').addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Sidebar handlers
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

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

            // Calendar navigation
            document.getElementById('prev-month').addEventListener('click', () => {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                renderCalendar();
            });

            document.getElementById('next-month').addEventListener('click', () => {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                renderCalendar();
            });

            // Add slot form
            document.getElementById('add-slot-form').addEventListener('submit', (e) => {
                e.preventDefault();

                const date = document.getElementById('slot-date').value;
                const start = document.getElementById('slot-start').value;
                const end = document.getElementById('slot-end').value;
                const type = document.getElementById('slot-type').value;
                const reason = document.getElementById('slot-reason').value;

                if (!date || !start || !end) {
                    showNotification('Preencha todos os campos obrigatórios.', 'error');
                    return;
                }

                const newSlot = {
                    id: availableSlots.length ? Math.max(...availableSlots.map(s => s.id)) + 1 : 1,
                    date,
                    start,
                    end,
                    type,
                    reason
                };

                availableSlots.unshift(newSlot);
                renderSlotsTable();
                renderCalendar();
                showNotification('Horário adicionado com sucesso!', 'success');
                document.getElementById('add-slot-form').reset();
            });

            // Edit slot form
            document.getElementById('edit-slot-form').addEventListener('submit', (e) => {
                e.preventDefault();

                const id = parseInt(document.getElementById('edit-slot-id').value);
                const date = document.getElementById('edit-slot-date').value;
                const start = document.getElementById('edit-slot-start').value;
                const end = document.getElementById('edit-slot-end').value;
                const type = document.getElementById('edit-slot-type').value;
                const reason = document.getElementById('edit-slot-reason').value;

                const index = availableSlots.findIndex(s => s.id === id);
                if (index !== -1) {
                    availableSlots[index] = {
                        id,
                        date,
                        start,
                        end,
                        type,
                        reason
                    };
                    renderSlotsTable();
                    renderCalendar();
                    showNotification('Horário atualizado com sucesso!', 'success');
                    document.getElementById('edit-slot-modal').classList.remove('show');
                }
            });

            // Cancel edit
            document.getElementById('cancel-edit').addEventListener('click', () => {
                document.getElementById('edit-slot-modal').classList.remove('show');
            });

            // Save schedule
            document.getElementById('save-schedule').addEventListener('click', () => {
                const days = Array.from(document.querySelectorAll('.schedule-day:checked')).map(cb => parseInt(cb.value));
                const start = document.getElementById('schedule-start').value;
                const end = document.getElementById('schedule-end').value;
                const duration = parseInt(document.getElementById('consultation-duration').value);

                if (!days.length) {
                    showNotification('Selecione pelo menos um dia de trabalho.', 'error');
                    return;
                }

                doctorSchedule = {
                    days,
                    start,
                    end,
                    duration
                };
                showNotification('Horário padrão salvo com sucesso!', 'success');
            });

            // Logout
            document.getElementById('logout-btn').addEventListener('click', () => {
                showNotification('Sessão encerrada com sucesso!', 'success');
                setTimeout(() => {
                    window.location.href = '<?php echo site_url('login'); ?>';
                }, 1000);
            });

            // Initial render
            renderCalendar();
            renderSlotsTable();
        });
    </script>
</body>

</html>