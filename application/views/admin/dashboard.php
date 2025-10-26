<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Dashboard de administração do Hospital Público de Matlhovele">
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

        /* Header com largura total e centralizado */
        header {
            position: relative;
            z-index: 800;
            background-color: #2563eb;
            width: 100%;
            margin-left: 0;
        }

        /* NOVA ESTRUTURA: PAGE WRAPPER */
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

        /* Main Content - CORRIGIDO */
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

        #edit-patient-modal,
        #edit-doctor-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 950;
            justify-content: center;
            align-items: center;
        }

        #edit-patient-modal.show,
        #edit-doctor-modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .metric-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: center;
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
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn"
                    aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="<?php echo site_url('admin'); ?>" class="block text-gray-700 active">
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

        <!-- Header/Navbar -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
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
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Dashboard do Administrador</h2>
                    <p class="text-gray-600">Visão geral do sistema do Hospital Matlhovele.</p>
                </div>
                <!-- Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="metric-card">
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Total de Pacientes</h3>
                        <p id="total-patients" class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <div class="metric-card">
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Total de Médicos</h3>
                        <p id="total-doctors" class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <div class="metric-card">
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Agendamentos Futuros</h3>
                        <p id="upcoming-appointments" class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                </div>
                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search-input" class="w-full p-2 pl-10 border rounded-lg"
                            placeholder="Pesquisar por nome ou BI (pacientes ou médicos)..." aria-label="Pesquisar">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
                    </div>
                </div>
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Atividade Recente</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border rounded-lg">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 text-left text-gray-700">Tipo</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Detalhes</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Data</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="activity-list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal de Edição de Paciente -->
        <div id="edit-patient-modal">
            <div class="modal-content">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Editar Paciente</h3>
                <div class="mb-4">
                    <label for="edit-patient-name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" id="edit-patient-name" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit-patient-phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="tel" id="edit-patient-phone" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit-patient-bi" class="block text-sm font-medium text-gray-700">BI</label>
                    <input type="text" id="edit-patient-bi" class="w-full p-2 border rounded" readonly>
                </div>
                <div class="mb-4">
                    <label for="edit-patient-email" class="block text-sm font-medium text-gray-700">Email
                        (opcional)</label>
                    <input type="email" id="edit-patient-email" class="w-full p-2 border rounded">
                </div>
                <div class="flex space-x-2">
                    <button id="save-patient-btn"
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex-1">
                        Salvar
                    </button>
                    <button id="cancel-patient-btn"
                        class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Edição de Médico -->
        <div id="edit-doctor-modal">
            <div class="modal-content">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Editar Médico</h3>
                <div class="mb-4">
                    <label for="edit-doctor-name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" id="edit-doctor-name" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit-doctor-specialty"
                        class="block text-sm font-medium text-gray-700">Especialidade</label>
                    <input type="text" id="edit-doctor-specialty" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit-doctor-phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="tel" id="edit-doctor-phone" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="edit-doctor-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="edit-doctor-email" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="edit-doctor-bi" class="block text-sm font-medium text-gray-700">BI</label>
                    <input type="text" id="edit-doctor-bi" class="w-full p-2 border rounded" readonly>
                </div>
                <div class="flex space-x-2">
                    <button id="save-doctor-btn"
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex-1">
                        Salvar
                    </button>
                    <button id="cancel-doctor-btn"
                        class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) {
                console.error('Elementos de notificação não encontrados:', {
                    notification,
                    messageEl
                });
                return;
            }
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Renderizar métricas
        async function renderMetrics() {
            const totalPatientsEl = document.getElementById('total-patients');
            const totalDoctorsEl = document.getElementById('total-doctors');
            const upcomingAppointmentsEl = document.getElementById('upcoming-appointments');
            if (!totalPatientsEl || !totalDoctorsEl || !upcomingAppointmentsEl) {
                console.error('Elementos de métricas não encontrados:', {
                    totalPatientsEl,
                    totalDoctorsEl,
                    upcomingAppointmentsEl
                });
                return;
            }

            try {
                const response = await fetch('<?php echo site_url('api/metrics'); ?>');
                const data = await response.json();
                if (data.error) {
                    showNotification(data.error, 'error');
                    return;
                }
                totalPatientsEl.textContent = data.total_patients || 0;
                totalDoctorsEl.textContent = data.total_doctors || 0;
                upcomingAppointmentsEl.textContent = data.upcoming_appointments || 0;
            } catch (error) {
                showNotification('Erro ao carregar métricas.', 'error');
                console.error('Erro ao buscar métricas:', error);
            }
        }

        // Renderizar atividade recente
        async function renderActivity(searchQuery = '') {
            const list = document.getElementById('activity-list');
            if (!list) {
                console.error('Elemento de lista de atividades não encontrado');
                return;
            }

            try {
                const response = await fetch(`<?php echo site_url('api/activity'); ?>${searchQuery ? `?query=${encodeURIComponent(searchQuery)}` : ''}`);
                const activities = await response.json();
                if (activities.error) {
                    showNotification(activities.error, 'error');
                    list.innerHTML = '<tr><td colspan="4" class="py-2 px-4 text-gray-600 text-center">Erro ao carregar atividades.</td></tr>';
                    return;
                }

                if (activities.length === 0) {
                    list.innerHTML = '<tr><td colspan="4" class="py-2 px-4 text-gray-600 text-center">Nenhuma atividade encontrada.</td></tr>';
                    showNotification('Nenhuma atividade corresponde à pesquisa.', 'info');
                    return;
                }

                list.innerHTML = activities.map(activity => `
                    <tr class="border-t">
                        <td class="py-2 px-4">${activity.type}</td>
                        <td class="py-2 px-4">${activity.details}</td>
                        <td class="py-2 px-4">${new Date(activity.date).toLocaleString('pt-PT')}</td>
                        <td class="py-2 px-4">
                            ${activity.action_type !== 'appointment' ? `
                                <button class="edit-btn bg-blue-600 text-white py-1 px-2 rounded-lg hover:bg-blue-700" data-bi="${activity.bi}" data-type="${activity.action_type}">
                                    Editar
                                </button>
                            ` : '-'}
                        </td>
                    </tr>
                `).join('');

                // Adicionar eventos aos botões de edição
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const bi = this.dataset.bi;
                        const type = this.dataset.type;
                        if (type === 'patient') {
                            showEditPatientModal(bi);
                        } else if (type === 'doctor') {
                            showEditDoctorModal(bi);
                        }
                    });
                });
            } catch (error) {
                showNotification('Erro ao carregar atividades.', 'error');
                console.error('Erro ao buscar atividades:', error);
            }
        }

        // Exibir modal de edição de paciente
        async function showEditPatientModal(bi) {
            const modal = document.getElementById('edit-patient-modal');
            const nameInput = document.getElementById('edit-patient-name');
            const phoneInput = document.getElementById('edit-patient-phone');
            const biInput = document.getElementById('edit-patient-bi');
            const emailInput = document.getElementById('edit-patient-email');
            if (!modal || !nameInput || !phoneInput || !biInput || !emailInput) {
                console.error('Elementos do modal de paciente não encontrados:', {
                    modal,
                    nameInput,
                    phoneInput,
                    biInput,
                    emailInput
                });
                return;
            }

            try {
                const response = await fetch(`<?php echo site_url('api/activity'); ?>?query=${bi}`);
                const data = await response.json();
                const patient = data.find(item => item.action_type === 'patient' && item.bi === bi);
                if (!patient) {
                    showNotification('Paciente não encontrado.', 'error');
                    return;
                }
                nameInput.value = patient.details.match(/Nome: (.*?)(, BI:|$)/)?.[1] || '';
                phoneInput.value = patient.details.match(/Telefone: (\+258 \d{9})/)?.[1] || '';
                biInput.value = bi;
                emailInput.value = patient.details.match(/Email: (.*)/)?.[1] || '';
                modal.classList.add('show');

                const saveBtn = document.getElementById('save-patient-btn');
                const cancelBtn = document.getElementById('cancel-patient-btn');

                const saveHandler = async () => {
                    const name = nameInput.value.trim();
                    const phone = phoneInput.value.trim();
                    const email = emailInput.value.trim();
                    if (!name || !phone) {
                        showNotification('Nome e telefone são obrigatórios.', 'error');
                        return;
                    }
                    if (!phone.match(/\+258\s*[8][0-49][0-9]{7}/)) {
                        showNotification('Número de telefone inválido. Use o formato +258 8X XXXXXXX.', 'error');
                        return;
                    }

                    try {
                        const response = await fetch('<?php echo site_url('api/update_patient'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                bi,
                                name,
                                phone,
                                email
                            })
                        });
                        const result = await response.json();
                        if (result.error) {
                            showNotification(result.error, 'error');
                            return;
                        }
                        renderActivity(document.getElementById('search-input')?.value || '');
                        modal.classList.remove('show');
                        showNotification(result.success, 'success');
                    } catch (error) {
                        showNotification('Erro ao atualizar paciente.', 'error');
                        console.error('Erro ao atualizar paciente:', error);
                    }
                    saveBtn.removeEventListener('click', saveHandler);
                };

                saveBtn.addEventListener('click', saveHandler);
                cancelBtn.addEventListener('click', () => {
                    modal.classList.remove('show');
                    saveBtn.removeEventListener('click', saveHandler);
                });
            } catch (error) {
                showNotification('Erro ao carregar dados do paciente.', 'error');
                console.error('Erro ao buscar paciente:', error);
            }
        }

        // Exibir modal de edição de médico
        async function showEditDoctorModal(bi) {
            const modal = document.getElementById('edit-doctor-modal');
            const nameInput = document.getElementById('edit-doctor-name');
            const specialtyInput = document.getElementById('edit-doctor-specialty');
            const phoneInput = document.getElementById('edit-doctor-phone');
            const emailInput = document.getElementById('edit-doctor-email');
            const biInput = document.getElementById('edit-doctor-bi');
            if (!modal || !nameInput || !specialtyInput || !phoneInput || !emailInput || !biInput) {
                console.error('Elementos do modal de médico não encontrados:', {
                    modal,
                    nameInput,
                    specialtyInput,
                    phoneInput,
                    emailInput,
                    biInput
                });
                return;
            }

            try {
                const response = await fetch(`<?php echo site_url('api/activity'); ?>?query=${bi}`);
                const data = await response.json();
                const doctor = data.find(item => item.action_type === 'doctor' && item.bi === bi);
                if (!doctor) {
                    showNotification('Médico não encontrado.', 'error');
                    return;
                }
                nameInput.value = doctor.details.match(/Nome: (.*?)(, Especialidade:|$)/)?.[1] || '';
                specialtyInput.value = doctor.details.match(/Especialidade: (.*?)(, BI:|$)/)?.[1] || '';
                phoneInput.value = doctor.details.match(/Telefone: (\+258 \d{9})/)?.[1] || '';
                emailInput.value = doctor.details.match(/Email: (.*)/)?.[1] || '';
                biInput.value = bi;
                modal.classList.add('show');

                const saveBtn = document.getElementById('save-doctor-btn');
                const cancelBtn = document.getElementById('cancel-doctor-btn');

                const saveHandler = async () => {
                    const name = nameInput.value.trim();
                    const specialty = specialtyInput.value.trim();
                    const phone = phoneInput.value.trim();
                    const email = emailInput.value.trim();
                    if (!name || !specialty || !phone) {
                        showNotification('Nome, especialidade e telefone são obrigatórios.', 'error');
                        return;
                    }
                    if (!phone.match(/\+258\s*[8][0-49][0-9]{7}/)) {
                        showNotification('Número de telefone inválido. Use o formato +258 8X XXXXXXX.', 'error');
                        return;
                    }

                    try {
                        const response = await fetch('<?php echo site_url('api/update_doctor'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                bi,
                                name,
                                specialty,
                                phone,
                                email
                            })
                        });
                        const result = await response.json();
                        if (result.error) {
                            showNotification(result.error, 'error');
                            return;
                        }
                        renderActivity(document.getElementById('search-input')?.value || '');
                        modal.classList.remove('show');
                        showNotification(result.success, 'success');
                    } catch (error) {
                        showNotification('Erro ao atualizar médico.', 'error');
                        console.error('Erro ao atualizar médico:', error);
                    }
                    saveBtn.removeEventListener('click', saveHandler);
                };

                saveBtn.addEventListener('click', saveHandler);
                cancelBtn.addEventListener('click', () => {
                    modal.classList.remove('show');
                    saveBtn.removeEventListener('click', saveHandler);
                });
            } catch (error) {
                showNotification('Erro ao carregar dados do médico.', 'error');
                console.error('Erro ao buscar médico:', error);
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
            const searchInput = document.getElementById('search-input');

            if (!mobileMenuBtn || !sidebarMenu || !closeSidebarBtn || !toggleSidebarBtn || !pageWrapper || !sidebarOverlay || !searchInput) {
                console.error('Um ou mais elementos do DOM não foram encontrados:', {
                    mobileMenuBtn,
                    sidebarMenu,
                    closeSidebarBtn,
                    toggleSidebarBtn,
                    pageWrapper,
                    sidebarOverlay,
                    searchInput
                });
                return;
            }

            // Sidebar handlers - ATUALIZADO
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.add('show');
                sidebarMenu.classList.remove('collapsed');
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
                const isModalOpen = document.querySelectorAll('#edit-patient-modal.show, #edit-doctor-modal.show').length > 0;
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && !isModalOpen && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });

            // Evento de busca
            searchInput.addEventListener('input', () => {
                renderActivity(searchInput.value);
            });

            // Carregar dados iniciais
            renderMetrics();
            renderActivity();

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    sidebarMenu.classList.remove('show', 'expanded');
                    pageWrapper.classList.remove('expanded');
                    sidebarOverlay.classList.remove('show');
                    window.location.href = '<?php echo site_url('login'); ?>';
                });
            }
        });
    </script>
</body>

</html>