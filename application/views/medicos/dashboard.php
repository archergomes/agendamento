<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Dashboard médico do Hospital Público de Matlhovele">
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
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .appointment-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }

        .appointment-card.urgent {
            border-left-color: #ef4444;
        }

        .appointment-card.completed {
            border-left-color: #10b981;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-scheduled {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-in-progress {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .action-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
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

        .action-btn.danger {
            background-color: #ef4444;
        }

        .action-btn.danger:hover {
            background-color: #dc2626;
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
                    <a href="<?php echo site_url('medico'); ?>" class="block text-gray-700 active">
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
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Bem-vindo, Dr. <?php echo $medico_nome ?? 'Médico'; ?></h2>
                    <p class="text-gray-600">Aqui está o resumo das suas atividades médicas hoje.</p>
                </div>

                <!-- Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="metric-card cursor-pointer" onclick="showConsultas()">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">Consultas Hoje</h3>
                                <p id="consultas-hoje" class="text-3xl font-bold text-blue-600">0</p>
                            </div>
                            <i class="fas fa-calendar-day text-2xl text-blue-600"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Agendadas para hoje</p>
                    </div>

                    <div class="metric-card cursor-pointer" onclick="showPacientes()">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">Pacientes Ativos</h3>
                                <p id="pacientes-ativos" class="text-3xl font-bold text-green-600">0</p>
                            </div>
                            <i class="fas fa-user-injured text-2xl text-green-600"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Em acompanhamento</p>
                    </div>

                    <div class="metric-card cursor-pointer" onclick="showPrescricoes()">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">Prescrições</h3>
                                <p id="prescricoes-pendentes" class="text-3xl font-bold text-orange-600">0</p>
                            </div>
                            <i class="fas fa-prescription-bottle-alt text-2xl text-orange-600"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Para elaborar</p>
                    </div>

                    <div class="metric-card cursor-pointer" onclick="showLaudos()">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">Laudos</h3>
                                <p id="laudos-pendentes" class="text-3xl font-bold text-purple-600">0</p>
                            </div>
                            <i class="fas fa-file-medical-alt text-2xl text-purple-600"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Aguardando emissão</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Próximas Consultas -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-700">Próximas Consultas</h3>
                            <a href="<?php echo site_url('medico/consultas'); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                                Ver todas
                            </a>
                        </div>
                        <div id="proximas-consultas">
                            <!-- Consultas serão carregadas via JavaScript -->
                        </div>
                    </div>

                    <!-- Ações Rápidas -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Ações Rápidas</h3>
                        <div class="grid grid-cols-1 gap-3">
                            <button onclick="novaConsulta()" class="action-btn w-full text-left flex items-center gap-3">
                                <i class="fas fa-plus-circle"></i>
                                Nova Consulta
                            </button>
                            <button onclick="novaPrescricao()" class="action-btn w-full text-left flex items-center gap-3">
                                <i class="fas fa-prescription"></i>
                                Nova Prescrição
                            </button>
                            <button onclick="novoLaudo()" class="action-btn w-full text-left flex items-center gap-3">
                                <i class="fas fa-file-medical"></i>
                                Novo Laudo
                            </button>
                            <button onclick="verProntuarios()" class="action-btn w-full text-left flex items-center gap-3">
                                <i class="fas fa-file-medical-alt"></i>
                                Ver Prontuários
                            </button>
                        </div>

                        <!-- Agenda do Dia -->
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Agenda de Hoje</h4>
                            <div id="agenda-hoje" class="space-y-2">
                                <!-- Agenda será carregada via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas e Lembretes -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Alertas e Lembretes</h3>
                    <div id="alertas-lembretes">
                        <!-- Alertas serão carregados via JavaScript -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
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

        // Carregar métricas do médico
        async function loadMetrics() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/metrics'); ?>');
                const data = await response.json();
                
                if (data.error) {
                    showNotification(data.error, 'error');
                    return;
                }

                // Atualizar métricas
                document.getElementById('consultas-hoje').textContent = data.consultas_hoje || 0;
                document.getElementById('pacientes-ativos').textContent = data.pacientes_ativos || 0;
                document.getElementById('prescricoes-pendentes').textContent = data.prescricoes_pendentes || 0;
                document.getElementById('laudos-pendentes').textContent = data.laudos_pendentes || 0;

            } catch (error) {
                showNotification('Erro ao carregar métricas.', 'error');
                console.error('Erro ao carregar métricas:', error);
            }
        }

        // Carregar próximas consultas
        async function loadProximasConsultas() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/proximas_consultas'); ?>');
                const consultas = await response.json();
                
                const container = document.getElementById('proximas-consultas');
                if (!container) return;

                if (consultas.error) {
                    container.innerHTML = '<p class="text-gray-600 text-center">Erro ao carregar consultas.</p>';
                    return;
                }

                if (consultas.length === 0) {
                    container.innerHTML = '<p class="text-gray-600 text-center">Nenhuma consulta agendada.</p>';
                    return;
                }

                container.innerHTML = consultas.map(consulta => `
                    <div class="appointment-card ${consulta.urgente ? 'urgent' : ''}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-medium text-gray-800">${consulta.paciente_nome}</h4>
                                <p class="text-sm text-gray-600">${consulta.motivo}</p>
                            </div>
                            <span class="status-badge status-${consulta.status}">${consulta.status}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-700">
                                <i class="fas fa-clock mr-1"></i>${consulta.hora}
                            </span>
                            <div class="flex gap-2">
                                <button onclick="iniciarConsulta(${consulta.id})" class="action-btn success text-xs">
                                    Iniciar
                                </button>
                                <button onclick="detalhesConsulta(${consulta.id})" class="action-btn text-xs">
                                    Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Erro ao carregar consultas:', error);
                showNotification('Erro ao carregar consultas.', 'error');
            }
        }

        // Carregar agenda do dia
        async function loadAgendaHoje() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/agenda_hoje'); ?>');
                const agenda = await response.json();
                
                const container = document.getElementById('agenda-hoje');
                if (!container) return;

                if (agenda.error || agenda.length === 0) {
                    container.innerHTML = '<p class="text-gray-600 text-center">Nenhum compromisso hoje.</p>';
                    return;
                }

                container.innerHTML = agenda.map(item => `
                    <div class="flex justify-between items-center p-2 border rounded">
                        <div>
                            <span class="font-medium text-gray-700">${item.hora}</span>
                            <span class="text-sm text-gray-600 ml-2">${item.descricao}</span>
                        </div>
                        <span class="status-badge status-${item.tipo}">${item.tipo}</span>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Erro ao carregar agenda:', error);
            }
        }

        // Carregar alertas e lembretes
        async function loadAlertas() {
            try {
                const response = await fetch('<?php echo site_url('api/medico/alertas'); ?>');
                const alertas = await response.json();
                
                const container = document.getElementById('alertas-lembretes');
                if (!container) return;

                if (alertas.error || alertas.length === 0) {
                    container.innerHTML = '<p class="text-gray-600 text-center">Nenhum alerta no momento.</p>';
                    return;
                }

                container.innerHTML = alertas.map(alerta => `
                    <div class="flex items-start gap-3 p-3 border rounded mb-2 ${alerta.prioridade === 'alta' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200'}">
                        <i class="fas ${alerta.prioridade === 'alta' ? 'fa-exclamation-triangle text-red-600' : 'fa-info-circle text-yellow-600'} mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">${alerta.titulo}</p>
                            <p class="text-sm text-gray-600">${alerta.descricao}</p>
                            <p class="text-xs text-gray-500 mt-1">${alerta.data}</p>
                        </div>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Erro ao carregar alertas:', error);
            }
        }

        // Funções de ação
        function showConsultas() {
            window.location.href = '<?php echo site_url('medico/consultas'); ?>';
        }

        function showPacientes() {
            window.location.href = '<?php echo site_url('medico/pacientes'); ?>';
        }

        function showPrescricoes() {
            window.location.href = '<?php echo site_url('medico/prescricoes'); ?>';
        }

        function showLaudos() {
            window.location.href = '<?php echo site_url('medico/laudos'); ?>';
        }

        function novaConsulta() {
            showNotification('Redirecionando para nova consulta...', 'info');
            setTimeout(() => {
                window.location.href = '<?php echo site_url('medico/nova_consulta'); ?>';
            }, 1000);
        }

        function novaPrescricao() {
            showNotification('Redirecionando para nova prescrição...', 'info');
            setTimeout(() => {
                window.location.href = '<?php echo site_url('medico/nova_prescricao'); ?>';
            }, 1000);
        }

        function novoLaudo() {
            showNotification('Redirecionando para novo laudo...', 'info');
            setTimeout(() => {
                window.location.href = '<?php echo site_url('medico/novo_laudo'); ?>';
            }, 1000);
        }

        function verProntuarios() {
            window.location.href = '<?php echo site_url('medico/prontuarios'); ?>';
        }

        function iniciarConsulta(consultaId) {
            showNotification('Iniciando consulta...', 'info');
            window.location.href = `<?php echo site_url('medico/consulta/'); ?>${consultaId}`;
        }

        function detalhesConsulta(consultaId) {
            window.location.href = `<?php echo site_url('medico/consulta/detalhes/'); ?>${consultaId}`;
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
            loadMetrics();
            loadProximasConsultas();
            loadAgendaHoje();
            loadAlertas();

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