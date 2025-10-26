<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciar relatórios no Hospital Público de Matlhovele">
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

        .export-btn { background-color: #8b5cf6; }
        .export-btn:hover { background-color: #7c3aed; }
        
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

        .report-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .report-card h3 {
            color: #374151;
            margin-bottom: 1rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.375rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #3b82f6;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .filter-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
                    <a href="<?php echo site_url('admin/disponibilidade'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="sidebar-text">Disponibilidade</span>
                    </a>
                    <a href="<?php echo site_url('admin/relatorios'); ?>" class="block text-gray-700 active">
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
                    <h2 class="text-2xl font-semibold text-gray-800">Relatórios</h2>
                </div>

                <!-- Report Type Selection -->
                <div class="filter-section">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tipo de Relatório</h3>
                    <div class="flex flex-wrap gap-4">
                        <button class="report-type-btn px-4 py-2 border rounded-md hover:bg-gray-100 active:bg-blue-100 active:border-blue-500" data-type="overview">Visão Geral</button>
                        <button class="report-type-btn px-4 py-2 border rounded-md hover:bg-gray-100 active:bg-blue-100 active:border-blue-500" data-type="appointments">Agendamentos</button>
                        <button class="report-type-btn px-4 py-2 border rounded-md hover:bg-gray-100 active:bg-blue-100 active:border-blue-500" data-type="doctors">Médicos</button>
                        <button class="report-type-btn px-4 py-2 border rounded-md hover:bg-gray-100 active:bg-blue-100 active:border-blue-500" data-type="patients">Pacientes</button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-section" id="filters-section" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtros</h3>
                    <div class="filter-grid">
                        <div>
                            <label for="date-from" class="block text-sm font-medium text-gray-700 mb-1">De</label>
                            <input type="date" id="date-from" class="p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="<?php echo date('Y-m-01'); ?>">
                        </div>
                        <div>
                            <label for="date-to" class="block text-sm font-medium text-gray-700 mb-1">Até</label>
                            <input type="date" id="date-to" class="p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label for="doctor-filter" class="block text-sm font-medium text-gray-700 mb-1">Médico</label>
                            <select id="doctor-filter" class="p-2 w-full border rounded focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos os Médicos</option>
                                <?php foreach ($medicos ?? [] as $medico): ?>
                                    <option value="<?php echo $medico['ID_Medico']; ?>"><?php echo htmlspecialchars($medico['Nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status-filter" class="p-2 w-full border rounded focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="Pendente">Pendente</option>
                                <option value="Confirmado">Confirmado</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Concluído">Concluído</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <button id="generate-report" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-chart-bar mr-2"></i>Gerar Relatório
                        </button>
                        <button id="export-pdf" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                            <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                        </button>
                        <button id="export-csv" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-file-csv mr-2"></i>Exportar CSV
                        </button>
                    </div>
                </div>

                <!-- Report Content -->
                <div id="report-content">
                    <!-- Overview Stats -->
                    <div id="overview-section" class="hidden">
                        <div class="report-card">
                            <h3 class="text-xl font-semibold text-gray-800">Visão Geral do Período</h3>
                            <div class="stat-grid">
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_agendamentos ?? 0; ?></div>
                                    <div class="stat-label">Total de Agendamentos</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_pacientes ?? 0; ?></div>
                                    <div class="stat-label">Total de Pacientes</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_medicos ?? 0; ?></div>
                                    <div class="stat-label">Total de Médicos</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $taxa_ocupacao ?? '0%'; ?></div>
                                    <div class="stat-label">Taxa de Ocupação</div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="report-card">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Agendamentos por Médico</h3>
                                <div id="appointments-chart-container">
                                    <!-- Chart will be rendered here -->
                                </div>
                            </div>
                            <div class="report-card">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Agendamentos por Status</h3>
                                <div id="status-chart-container">
                                    <!-- Chart will be rendered here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments Report Table -->
                    <div id="appointments-section" class="hidden">
                        <div class="report-card">
                            <h3 class="text-xl font-semibold text-gray-800">Relatório de Agendamentos</h3>
                            <div class="table-container mt-4">
                                <table class="table-auto w-full text-left">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2">Paciente</th>
                                            <th class="px-4 py-2">Médico</th>
                                            <th class="px-4 py-2">Data</th>
                                            <th class="px-4 py-2">Hora</th>
                                            <th class="px-4 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointments-report-table">
                                        <!-- Populated by JS/AJAX -->
                                    </tbody>
                                </table>
                                <p id="no-report-data" class="text-center text-gray-500 mt-4 hidden empty-state">Nenhum dado encontrado para os filtros selecionados.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Doctors Report -->
                    <div id="doctors-section" class="hidden">
                        <div class="report-card">
                            <h3 class="text-xl font-semibold text-gray-800">Relatório de Médicos</h3>
                            <div class="stat-grid">
                                <!-- Dynamic stats -->
                            </div>
                            <div class="table-container mt-4">
                                <table class="table-auto w-full text-left">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2">Médico</th>
                                            <th class="px-4 py-2">Especialidade</th>
                                            <th class="px-4 py-2">Agendamentos</th>
                                            <th class="px-4 py-2">Taxa de Ocupação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="doctors-report-table">
                                        <!-- Populated by JS/AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Patients Report -->
                    <div id="patients-section" class="hidden">
                        <div class="report-card">
                            <h3 class="text-xl font-semibold text-gray-800">Relatório de Pacientes</h3>
                            <div class="table-container mt-4">
                                <table class="table-auto w-full text-left">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2">Paciente</th>
                                            <th class="px-4 py-2">Número de Visitas</th>
                                            <th class="px-4 py-2">Última Visita</th>
                                            <th class="px-4 py-2">Status Médio</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patients-report-table">
                                        <!-- Populated by JS/AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

    <script>
        // Dados iniciais do PHP
        let initialStats = <?php echo json_encode($stats ?? []); ?>;
        let medicos = <?php echo json_encode(array_map(function($medico) {
            return [
                'id' => $medico['ID_Medico'] ?? '',
                'name' => $medico['Nome'] ?? '',
                'specialty' => $medico['Especialidade'] ?? ''
            ];
        }, $medicos ?? [])); ?>;
        let currentReportType = 'overview';

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => notification.classList.remove('show'), 5000);
        }

        // Render report based on type
        async function renderReport(type = 'overview') {
            currentReportType = type;
            document.querySelectorAll('[id$="-section"]').forEach(el => el.classList.add('hidden'));
            document.getElementById(type + '-section')?.classList.remove('hidden');
            document.getElementById('filters-section').style.display = type === 'overview' ? 'none' : 'block';

            if (type === 'overview') {
                // Use initial stats
                updateOverviewStats(initialStats);
                renderOverviewCharts(initialStats);
            } else {
                // AJAX to fetch filtered data
                const filters = getFilters();
                try {
                    const response = await fetch('<?php echo site_url('admin/get_report_data'); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ type, ...filters })
                    });
                    const data = await response.json();
                    if (data.error) {
                        showNotification(data.error, 'error');
                        return;
                    }
                    updateReportTable(type, data);
                } catch (error) {
                    console.error('Erro ao carregar relatório:', error);
                    showNotification('Erro ao gerar relatório.', 'error');
                }
            }
        }

        function getFilters() {
            return {
                date_from: document.getElementById('date-from').value,
                date_to: document.getElementById('date-to').value,
                doctor_id: document.getElementById('doctor-filter').value,
                status: document.getElementById('status-filter').value
            };
        }

        function updateOverviewStats(stats) {
            // Update stat items
            document.querySelectorAll('.stat-item .stat-number').forEach((el, i) => {
                el.textContent = stats[i]?.value || 0;
            });
        }

        function renderOverviewCharts(stats) {
            // Appointments by Doctor Chart
            const appointmentsChartCtx = document.getElementById('appointments-chart-container');
            if (appointmentsChartCtx && stats.appointments_by_doctor) {
                appointmentsChartCtx.innerHTML = ''; // Clear
                // Use Chart.js if loaded, or placeholder
                // For now, placeholder div
                appointmentsChartCtx.innerHTML = '<canvas id="appointments-chart" width="400" height="200"></canvas>';
                // Assume Chart.js is loaded via CDN if needed; here, use code block for static
            }

            // Status Chart
            const statusChartCtx = document.getElementById('status-chart-container');
            if (statusChartCtx && stats.status_distribution) {
                statusChartCtx.innerHTML = '<canvas id="status-chart" width="400" height="200"></canvas>';
            }
        }

        function updateReportTable(type, data) {
            const tableId = type + '-report-table';
            const tbody = document.getElementById(tableId);
            const noData = document.getElementById('no-report-data');
            tbody.innerHTML = '';
            if (!data || data.length === 0) {
                noData.classList.remove('hidden');
                return;
            }
            noData.classList.add('hidden');
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.className = 'border-t';
                // Dynamic columns based on type
                let cells = '';
                if (type === 'appointments') {
                    cells = `
                        <td class="px-4 py-2">${row.paciente_nome || '-'}</td>
                        <td class="px-4 py-2">${row.medico_nome || '-'}</td>
                        <td class="px-4 py-2">${row.date || '-'}</td>
                        <td class="px-4 py-2">${row.time || '-'}</td>
                        <td class="px-4 py-2"><span class="status-${row.status?.toLowerCase()}">${row.status || '-'}</span></td>
                    `;
                } else if (type === 'doctors') {
                    cells = `
                        <td class="px-4 py-2">${row.name || '-'}</td>
                        <td class="px-4 py-2">${row.specialty || '-'}</td>
                        <td class="px-4 py-2">${row.appointments || 0}</td>
                        <td class="px-4 py-2">${row.occupancy || '0%'}</td>
                    `;
                } else if (type === 'patients') {
                    cells = `
                        <td class="px-4 py-2">${row.name || '-'}</td>
                        <td class="px-4 py-2">${row.visits || 0}</td>
                        <td class="px-4 py-2">${row.last_visit || '-'}</td>
                        <td class="px-4 py-2">${row.avg_status || '-'}</td>
                    `;
                }
                tr.innerHTML = cells;
                tbody.appendChild(tr);
            });
        }

        // Export functions (AJAX to server)
        async function exportReport(format) {
            const filters = getFilters();
            try {
                const url = `<?php echo site_url('admin/export_report'); ?>?format=${format}&` + new URLSearchParams(filters);
                window.open(url, '_blank');
                showNotification(`Relatório exportado como ${format.toUpperCase()}!`, 'success');
            } catch (error) {
                showNotification('Erro ao exportar.', 'error');
            }
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar handlers (same as previous views)
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (notificationClose) notificationClose.addEventListener('click', () => document.getElementById('notification').classList.remove('show'));
            if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.add('show');
                sidebarOverlay.classList.add('show');
                pageWrapper.classList.add('expanded');
            });
            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });
            if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.toggle('expanded');
                pageWrapper.classList.toggle('expanded');
            });
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', () => {
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
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    window.location.href = '<?php echo site_url('login'); ?>';
                });
            }

            // Report type buttons
            document.querySelectorAll('.report-type-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.report-type-btn').forEach(b => b.classList.remove('active', 'bg-blue-100', 'border-blue-500'));
                    btn.classList.add('active', 'bg-blue-100', 'border-blue-500');
                    renderReport(btn.dataset.type);
                });
            });

            // Generate report
            document.getElementById('generate-report').addEventListener('click', () => {
                const activeBtn = document.querySelector('.report-type-btn.active');
                if (activeBtn) renderReport(activeBtn.dataset.type);
            });

            // Exports
            document.getElementById('export-pdf').addEventListener('click', () => exportReport('pdf'));
            document.getElementById('export-csv').addEventListener('click', () => exportReport('csv'));

            // Initial render
            renderReport('overview');
        });
    </script>
</body>
</html>