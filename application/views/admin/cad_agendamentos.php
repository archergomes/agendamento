<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Agendamento - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Cadastrar ou editar agendamentos no Hospital Público de Matlhovele">
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
            display: none;
        }
        .sidebar.desktop {
            left: 0;
            display: flex;
            transform: translateX(0);
        }
        .sidebar.desktop.collapsed {
            width: 60px;
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
            margin-top: 64px;
            padding: 2rem;
            min-height: calc(100vh - 64px - 128px);
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
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
            .sidebar.mobile .sidebar-text {
                display: inline !important;
            }
            .sidebar.mobile .sidebar-header {
                justify-content: space-between !important;
                padding: 1rem !important;
            }
            .sidebar.mobile .sidebar-nav a, .sidebar.mobile .sidebar-nav button {
                justify-content: flex-start !important;
                padding: 8px 16px !important;
            }
            .main-content {
                margin-top: 64px;
                width: 100%;
                max-width: 100%;
                padding: 1rem;
            }
            #toggle-sidebar-btn {
                display: none !important;
            }
            #mobile-menu-btn {
                display: block;
                margin-right: 1rem;
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
            #mobile-menu-btn {
                display: none;
            }
            #close-sidebar-btn {
                display: none;
            }
            .sidebar.desktop {
                display: flex;
            }
            .sidebar.mobile {
                display: none;
            }
            .header-content {
                justify-content: flex-start;
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
        .sidebar.desktop.collapsed .sidebar-nav a, .sidebar.desktop.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 8px;
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
        .form-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }
        .form-input, .form-select {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            width: 100%;
            margin-bottom: 1rem;
        }
        .form-input[readonly] {
            background-color: #f3f4f6;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
        }
        .submit-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .submit-btn:hover {
            background-color: #059669;
        }
        .cancel-btn {
            background-color: #ef4444;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .cancel-btn:hover {
            background-color: #dc2626;
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
            border-radius: 0.5rem;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 1.5rem;
            position: relative;
        }
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #374151;
            cursor: pointer;
        }
        .modal-search {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            width: 100%;
            margin-bottom: 1rem;
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
        }
        .modal-table tr:hover {
            background-color: #eff6ff;
            cursor: pointer;
        }
        .content-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .form-label {
            text-align: left;
        }
        @media (max-width: 767px) {
            .modal-content {
                width: 95%;
                max-height: 90vh;
            }
            .content-container {
                max-width: 100%;
                padding: 0 1rem;
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
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg desktop collapsed">
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
                    <a href="<?php echo site_url('admin/agendamentos'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Paciente</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_secretarios'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Secretário</span>
                    </a>
                    <a href="<?php echo site_url('admin/cad_medicos'); ?>" class="block text-gray-700">
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

        <!-- Patient Selection Modal -->
        <div id="patient-modal" class="modal">
            <div class="modal-content">
                <span class="modal-close" id="patient-modal-close">&times;</span>
                <h3 class="text-lg font-semibold mb-4 text-center">Selecionar Paciente</h3>
                <input type="text" id="patient-search" class="modal-search" placeholder="Pesquisar por nome ou BI...">
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
                <span class="modal-close" id="doctor-modal-close">&times;</span>
                <h3 class="text-lg font-semibold mb-4 text-center">Selecionar Médico</h3>
                <input type="text" id="doctor-search" class="modal-search" placeholder="Pesquisar por nome, BI ou especialidade...">
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

        <!-- Slot Selection Modal -->
        <div id="slot-modal" class="modal">
            <div class="modal-content">
                <span class="modal-close" id="slot-modal-close">&times;</span>
                <h3 class="text-lg font-semibold mb-4 text-center">Selecionar Horário</h3>
                <input type="date" id="slot-date-filter" class="modal-search" min="<?php echo date('Y-m-d'); ?>">
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora Início</th>
                            <th>Hora Fim</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody id="slot-table">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
                <p id="slot-no-results" class="text-center text-gray-500 mt-4 hidden">Nenhum horário disponível.</p>
            </div>
        </div>

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <i class="fas fa-hospital-alt text-2xl" aria-hidden="true"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-container">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center" id="form-title">Cadastrar Novo Agendamento</h2>
                <div class="form-container">
                    <form id="appointment-form">
                        <input type="hidden" id="appointment-id">
                        <input type="hidden" id="patient-id">
                        <input type="hidden" id="doctor-id">
                        <input type="hidden" id="horario-id">
                        <div class="mb-4">
                            <label for="patient-input" class="block text-gray-700 font-medium mb-2 form-label">Paciente</label>
                            <input type="text" id="patient-input" class="form-input" readonly placeholder="Selecione um paciente" required>
                        </div>
                        <div class="mb-4">
                            <label for="doctor-input" class="block text-gray-700 font-medium mb-2 form-label">Médico</label>
                            <input type="text" id="doctor-input" class="form-input" readonly placeholder="Selecione um médico" required>
                        </div>
                        <div class="mb-4">
                            <label for="slot-input" class="block text-gray-700 font-medium mb-2 form-label">Horário</label>
                            <input type="text" id="slot-input" class="form-input" readonly placeholder="Selecione um horário" required>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 font-medium mb-2 form-label">Estado</label>
                            <select id="status" class="form-select" required>
                                <option value="Agendado">Agendado</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="motivo" class="block text-gray-700 font-medium mb-2 form-label">Motivo</label>
                            <textarea id="motivo" class="form-input" rows="4" placeholder="Descreva o motivo do agendamento" required></textarea>
                        </div>
                        <div class="flex space-x-4 justify-center">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-save mr-2"></i> Salvar
                            </button>
                            <a href="<?php echo site_url('admin/agendamentos'); ?>" class="cancel-btn">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
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
                            <li><a href="<?php echo site_url('sobre'); ?>" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                            <li><a href="<?php echo site_url('servicos'); ?>" class="text-gray-300 hover:text-white">Serviços</a></li>
                            <li><a href="<?php echo site_url('contactos'); ?>" class="text-gray-300 hover:text-white">Contactos</a></li>
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

        // Validate date
        function isValidDate(dateStr) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const inputDate = new Date(dateStr);
            return inputDate >= today;
        }

        // Validate time
        function isValidTime(timeStr) {
            const regex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
            return regex.test(timeStr);
        }

        // Fetch patients from backend
        async function fetchPatients(query = '') {
            try {
                const url = `<?php echo site_url('api/get_patients'); ?>${query ? `?query=${encodeURIComponent(query)}` : ''}`;
                console.log('Fetching patients from:', url);
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                const data = await response.json();
                console.log('Patients received:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                return data;
            } catch (error) {
                showNotification('Erro ao carregar pacientes: ' + error.message, 'error');
                console.error('Erro ao carregar pacientes:', {
                    message: error.message,
                    stack: error.stack,
                    url
                });
                return [];
            }
        }

        // Fetch doctors from backend
        async function fetchDoctors(query = '') {
            try {
                const url = `<?php echo site_url('api/get_doctors'); ?>${query ? `?query=${encodeURIComponent(query)}` : ''}`;
                console.log('Fetching doctors from:', url);
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                const data = await response.json();
                console.log('Doctors received:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                return data;
            } catch (error) {
                showNotification('Erro ao carregar médicos: ' + error.message, 'error');
                console.error('Erro ao carregar médicos:', {
                    message: error.message,
                    stack: error.stack,
                    url
                });
                return [];
            }
        }

        // Fetch available slots from backend
        async function fetchSlots(medico_id, date = '') {
            try {
                let url = `<?php echo site_url('api/slots'); ?>?medico_id=${encodeURIComponent(medico_id)}`;
                if (date) {
                    url += `&data_horario=${encodeURIComponent(date)}`;
                }
                console.log('Fetching slots from:', url);
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                const data = await response.json();
                console.log('Slots received:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                return data;
            } catch (error) {
                showNotification('Erro ao carregar horários: ' + error.message, 'error');
                console.error('Erro ao carregar horários:', {
                    message: error.message,
                    stack: error.stack,
                    url
                });
                return [];
            }
        }

        // Fetch appointment for editing
        async function fetchAppointment(id) {
            try {
                const url = `<?php echo site_url('api/get_appointment'); ?>?id=${id}`;
                console.log('Fetching appointment from:', url);
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                const data = await response.json();
                console.log('Appointment received:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                return data;
            } catch (error) {
                showNotification('Erro ao carregar agendamento: ' + error.message, 'error');
                console.error('Erro ao carregar agendamento:', {
                    message: error.message,
                    stack: error.stack,
                    url
                });
                return null;
            }
        }

        // Populate patient table
        async function populatePatientTable(filter = '') {
            const tableBody = document.getElementById('patient-table');
            const noResults = document.getElementById('patient-no-results');
            if (!tableBody || !noResults) {
                console.error('Patient table elements not found:', { tableBody, noResults });
                return;
            }

            tableBody.innerHTML = '';
            const patients = await fetchPatients(filter);
            if (patients.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }

            noResults.classList.add('hidden');
            patients.forEach(patient => {
                const row = document.createElement('tr');
                row.dataset.id = patient.id;
                row.innerHTML = `
                    <td>${patient.name}</td>
                    <td>${patient.bi || 'N/A'}</td>
                `;
                row.addEventListener('click', () => {
                    document.getElementById('patient-input').value = patient.name;
                    document.getElementById('patient-id').value = patient.id;
                    document.getElementById('patient-modal').classList.remove('show');
                });
                tableBody.appendChild(row);
            });
        }

        // Populate doctor table
        async function populateDoctorTable(filter = '') {
            const tableBody = document.getElementById('doctor-table');
            const noResults = document.getElementById('doctor-no-results');
            if (!tableBody || !noResults) {
                console.error('Doctor table elements not found:', { tableBody, noResults });
                return;
            }

            tableBody.innerHTML = '';
            const doctors = await fetchDoctors(filter);
            if (doctors.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }

            noResults.classList.add('hidden');
            doctors.forEach(doctor => {
                const row = document.createElement('tr');
                row.dataset.id = doctor.id;
                row.innerHTML = `
                    <td>${doctor.name}</td>
                    <td>${doctor.bi || 'N/A'}</td>
                    <td>${doctor.specialty}</td>
                `;
                row.addEventListener('click', () => {
                    document.getElementById('doctor-input').value = doctor.name;
                    document.getElementById('doctor-id').value = doctor.id;
                    document.getElementById('slot-input').value = '';
                    document.getElementById('horario-id').value = '';
                    document.getElementById('doctor-modal').classList.remove('show');
                });
                tableBody.appendChild(row);
            });
        }

        // Populate slot table
        async function populateSlotTable(medico_id, date = '') {
            const tableBody = document.getElementById('slot-table');
            const noResults = document.getElementById('slot-no-results');
            if (!tableBody || !noResults) {
                console.error('Slot table elements not found:', { tableBody, noResults });
                return;
            }

            tableBody.innerHTML = '';
            const slots = await fetchSlots(medico_id, date);
            if (slots.length === 0) {
                noResults.classList.remove('hidden');
                return;
            }

            noResults.classList.add('hidden');
            slots.forEach(slot => {
                const row = document.createElement('tr');
                row.dataset.id = slot.id;
                row.innerHTML = `
                    <td>${slot.date}</td>
                    <td>${slot.start_time}</td>
                    <td>${slot.end_time}</td>
                    <td>${slot.type}</td>
                `;
                row.addEventListener('click', () => {
                    document.getElementById('slot-input').value = `${slot.date} ${slot.start_time} - ${slot.end_time} (${slot.type})`;
                    document.getElementById('horario-id').value = slot.id;
                    document.getElementById('slot-modal').classList.remove('show');
                });
                tableBody.appendChild(row);
            });
        }

        // Load appointment for editing
        async function loadAppointment() {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            if (!id) {
                document.getElementById('form-title').textContent = 'Cadastrar Novo Agendamento';
                return;
            }

            document.getElementById('form-title').textContent = 'Editar Agendamento';
            const appointment = await fetchAppointment(id);
            if (!appointment) {
                showNotification('Agendamento não encontrado.', 'error');
                setTimeout(() => {
                    window.location.href = '<?php echo site_url('admin/agendamentos'); ?>';
                }, 2000);
                return;
            }

            document.getElementById('appointment-id').value = appointment.id;
            document.getElementById('patient-id').value = appointment.patient_id;
            document.getElementById('patient-input').value = appointment.patient_name;
            document.getElementById('doctor-id').value = appointment.doctor_id;
            document.getElementById('doctor-input').value = appointment.doctor_name;
            document.getElementById('horario-id').value = appointment.horario_id || '';
            document.getElementById('slot-input').value = appointment.horario_id ? `${appointment.date} ${appointment.time}` : '';
            document.getElementById('status').value = appointment.status;
            document.getElementById('motivo').value = appointment.motivo || '';
        }

        // Submit form
        async function submitForm(event) {
            event.preventDefault();
            const id = document.getElementById('appointment-id').value;
            const patient_id = document.getElementById('patient-id').value;
            const doctor_id = document.getElementById('doctor-id').value;
            const horario_id = document.getElementById('horario-id').value;
            const status = document.getElementById('status').value;
            const motivo = document.getElementById('motivo').value;

            if (!patient_id || !doctor_id || !horario_id || !status || !motivo) {
                showNotification('Por favor, preencha todos os campos obrigatórios.', 'error');
                return;
            }

            const data = {
                id,
                ID_Paciente: patient_id,
                ID_Medico: doctor_id,
                ID_Horario: horario_id,
                Status: status,
                Motivo: motivo
            };

            const url = id ? '<?php echo site_url('api/update_agendamento'); ?>' : '<?php echo site_url('api/create_agendamento'); ?>';
            try {
                console.log('Submitting form to:', url, 'with data:', data);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    body: JSON.stringify(data)
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}, StatusText: ${response.statusText}`);
                }
                const result = await response.json();
                console.log('Form submission result:', result);
                if (result.success) {
                    showNotification(result.success, 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('admin/agendamentos'); ?>';
                    }, 2000);
                } else {
                    showNotification(result.error || 'Erro ao salvar agendamento.', 'error');
                }
            } catch (error) {
                showNotification('Erro ao salvar agendamento: ' + error.message, 'error');
                console.error('Erro ao salvar agendamento:', {
                    message: error.message,
                    stack: error.stack,
                    url,
                    data
                });
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', async function () {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const appointmentForm = document.getElementById('appointment-form');
            const patientInput = document.getElementById('patient-input');
            const patientModal = document.getElementById('patient-modal');
            const patientModalClose = document.getElementById('patient-modal-close');
            const patientSearch = document.getElementById('patient-search');
            const doctorInput = document.getElementById('doctor-input');
            const doctorModal = document.getElementById('doctor-modal');
            const doctorModalClose = document.getElementById('doctor-modal-close');
            const doctorSearch = document.getElementById('doctor-search');
            const slotInput = document.getElementById('slot-input');
            const slotModal = document.getElementById('slot-modal');
            const slotModalClose = document.getElementById('slot-modal-close');
            const slotDateFilter = document.getElementById('slot-date-filter');

            // Sidebar functionality
            let isSidebarCollapsed = true; // Initialize as collapsed

            toggleSidebarBtn.addEventListener('click', () => {
                isSidebarCollapsed = !isSidebarCollapsed;
                sidebarMenu.classList.toggle('collapsed', isSidebarCollapsed);
            });

            mobileMenuBtn.addEventListener('click', () => {
                sidebarMenu.classList.add('mobile', 'show');
                sidebarOverlay.classList.add('show');
            });

            closeSidebarBtn.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            // Notification close
            notificationClose.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Modal functionality
            patientInput.addEventListener('click', () => {
                patientModal.classList.add('show');
                populatePatientTable();
                patientSearch.focus();
            });

            patientModalClose.addEventListener('click', () => {
                patientModal.classList.remove('show');
            });

            patientSearch.addEventListener('input', () => {
                populatePatientTable(patientSearch.value);
            });

            doctorInput.addEventListener('click', () => {
                doctorModal.classList.add('show');
                populateDoctorTable();
                doctorSearch.focus();
            });

            doctorModalClose.addEventListener('click', () => {
                doctorModal.classList.remove('show');
            });

            doctorSearch.addEventListener('input', () => {
                populateDoctorTable(doctorSearch.value);
            });

            slotInput.addEventListener('click', () => {
                const doctorId = document.getElementById('doctor-id').value;
                if (!doctorId) {
                    showNotification('Selecione um médico primeiro.', 'error');
                    return;
                }
                slotModal.classList.add('show');
                populateSlotTable(doctorId);
                slotDateFilter.focus();
            });

            slotModalClose.addEventListener('click', () => {
                slotModal.classList.remove('show');
            });

            slotDateFilter.addEventListener('change', () => {
                const doctorId = document.getElementById('doctor-id').value;
                if (doctorId) {
                    populateSlotTable(doctorId, slotDateFilter.value);
                }
            });

            // Form submission
            appointmentForm.addEventListener('submit', submitForm);

            // Load appointment if editing
            loadAppointment();
        });
    </script>
</body>
</html>
