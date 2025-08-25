<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Página de gerenciamento de agendamentos para secretários do Hospital Público de Matlhovele">
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
            padding: 10px 16px;
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
            transition: margin-left 0.3s ease-in-out;
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
            .sidebar.mobile .sidebar-text {
                display: inline !important;
            }
            .sidebar.mobile .sidebar-header {
                justify-content: space-between !important;
                padding: 1rem !important;
            }
            .sidebar.mobile .sidebar-nav a, .sidebar.mobile .sidebar-nav button {
                justify-content: flex-start !important;
                padding: 10px 16px !important;
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
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
        }
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 0.375rem;
            color: #374151;
            transition: background-color 0.2s, color 0.2s;
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
            font-size: 1.75rem;
            width: 32px;
            text-align: center;
        }
        .sidebar.desktop.collapsed .sidebar-nav a, .sidebar.desktop.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 12px;
        }
        .sidebar-nav .logout {
            margin-top: auto;
        }
        #confirmation-modal {
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
        #confirmation-modal.show {
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
        footer {
            width: 100%;
            position: relative;
            margin-left: 0;
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
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg">
            <div class="sidebar-header flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Secretário</h2>
                <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="/secretario/dashboard" class="block text-gray-700">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="/secretario/agendamentos" class="block text-gray-700 active">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="/secretario/pacientes" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="/secretario/medicos" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                </div>
                <button id="logout-btn" class="block w-full text-left text-gray-700">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Sair</span>
                </button>
            </nav>
        </div>

        <!-- Header/Navbar -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex header-content">
                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="flex items-center gap-2">
                    <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
                    <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Gerenciar Agendamentos</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border rounded-lg">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 text-left text-gray-700">Paciente</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Médico</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Especialidade</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Data</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Horário</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Estado</th>
                                    <th class="py-2 px-4 text-left text-gray-700">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="appointment-list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal de Confirmação para Cancelamento -->
        <div id="confirmation-modal">
            <div class="modal-content">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Confirmar Cancelamento</h3>
                <p class="mb-4">Deseja realmente cancelar este agendamento? Por favor, forneça um motivo para o cancelamento.</p>
                <textarea id="cancellation-reason" class="w-full p-2 border rounded mb-4" placeholder="Motivo do cancelamento" aria-required="true"></textarea>
                <div class="flex space-x-2">
                    <button id="confirm-cancel-btn" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition flex-1">
                        Confirmar
                    </button>
                    <button id="cancel-cancel-btn" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                        Cancelar
                    </button>
                </div>
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
        // Dados iniciais
        let bookedAppointments = JSON.parse(localStorage.getItem('bookedAppointments')) || [];
        let bookedSlots = JSON.parse(localStorage.getItem('bookedSlots')) || {};
        let userProfiles = JSON.parse(localStorage.getItem('userProfiles')) || {};

        // Função para exibir notificações
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

        // Renderizar lista de agendamentos
        function renderAppointments() {
            const list = document.getElementById('appointment-list');
            if (!list) {
                console.error('Appointment list element not found');
                return;
            }
            if (bookedAppointments.length === 0) {
                list.innerHTML = '<tr><td colspan="7" class="py-2 px-4 text-gray-600 text-center">Nenhum agendamento encontrado.</td></tr>';
                return;
            }
            list.innerHTML = bookedAppointments.map((appt, index) => `
                <tr class="border-t">
                    <td class="py-2 px-4">${appt.name}</td>
                    <td class="py-2 px-4">${appt.doctor}</td>
                    <td class="py-2 px-4">${appt.specialty}</td>
                    <td class="py-2 px-4">${appt.date}</td>
                    <td class="py-2 px-4">${appt.time}</td>
                    <td class="py-2 px-4">
                        <span class="inline-block px-2 py-1 rounded text-sm ${appt.status === 'Confirmed' ? 'bg-green-100 text-green-700' : appt.status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'}">
                            ${appt.status}
                        </span>
                    </td>
                    <td class="py-2 px-4 flex space-x-2">
                        ${appt.status !== 'Confirmed' && appt.status !== 'Cancelled' ? `
                            <button class="confirm-btn bg-blue-600 text-white py-1 px-2 rounded-lg hover:bg-blue-700" data-index="${index}">
                                Confirmar
                            </button>
                        ` : ''}
                        ${appt.status !== 'Cancelled' ? `
                            <button class="cancel-btn bg-red-600 text-white py-1 px-2 rounded-lg hover:bg-red-700" data-index="${index}">
                                Cancelar
                            </button>
                        ` : ''}
                    </td>
                </tr>
            `).join('');

            document.querySelectorAll('.confirm-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.dataset.index;
                    bookedAppointments[index].status = 'Confirmed';
                    localStorage.setItem('bookedAppointments', JSON.stringify(bookedAppointments));
                    renderAppointments();
                    showNotification('Agendamento confirmado com sucesso!', 'success');
                });
            });

            document.querySelectorAll('.cancel-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.dataset.index;
                    showConfirmationModal(index);
                });
            });
        }

        // Exibir modal de confirmação para cancelamento
        function showConfirmationModal(index) {
            const modal = document.getElementById('confirmation-modal');
            const reasonInput = document.getElementById('cancellation-reason');
            if (!modal || !reasonInput) {
                console.error('Confirmation modal elements not found:', { modal, reasonInput });
                return;
            }
            reasonInput.value = '';
            modal.classList.add('show');

            const confirmBtn = document.getElementById('confirm-cancel-btn');
            const cancelBtn = document.getElementById('cancel-cancel-btn');

            const confirmHandler = () => {
                const reason = reasonInput.value.trim();
                if (!reason) {
                    showNotification('Por favor, forneça um motivo para o cancelamento.', 'error');
                    return;
                }
                const appt = bookedAppointments[index];
                appt.status = 'Cancelled';
                appt.cancellationReason = reason;
                if (bookedSlots[appt.date]) {
                    bookedSlots[appt.date] = bookedSlots[appt.date].filter(slot => slot !== appt.time);
                    if (bookedSlots[appt.date].length === 0) {
                        delete bookedSlots[appt.date];
                    }
                }
                localStorage.setItem('bookedAppointments', JSON.stringify(bookedAppointments));
                localStorage.setItem('bookedSlots', JSON.stringify(bookedSlots));
                renderAppointments();
                modal.classList.remove('show');
                showNotification('Agendamento cancelado com sucesso!', 'success');
                confirmBtn.removeEventListener('click', confirmHandler);
            };

            confirmBtn.addEventListener('click', confirmHandler);
            cancelBtn.addEventListener('click', () => {
                modal.classList.remove('show');
                confirmBtn.removeEventListener('click', confirmHandler);
            });
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            const notificationClose = document.getElementById('notification-close');
            if (notificationClose) {
                notificationClose.addEventListener('click', function () {
                    document.getElementById('notification').classList.remove('show');
                });
            }

            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (!mobileMenuBtn || !sidebarMenu || !closeSidebarBtn || !toggleSidebarBtn || !mainContent || !sidebarOverlay) {
                console.error('Um ou mais elementos do DOM não foram encontrados:', {
                    mobileMenuBtn, sidebarMenu, closeSidebarBtn, toggleSidebarBtn, mainContent, sidebarOverlay
                });
                return;
            }

            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('desktop', 'collapsed');
                sidebarMenu.classList.remove('mobile', 'show');
                mainContent.classList.remove('expanded');
            } else {
                sidebarMenu.classList.add('mobile');
                sidebarMenu.classList.remove('desktop', 'collapsed', 'show');
                mainContent.classList.remove('expanded');
            }

            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.add('show');
                sidebarMenu.classList.remove('collapsed');
                sidebarOverlay.classList.add('show');
            });

            closeSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            toggleSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (window.innerWidth >= 768) {
                    sidebarMenu.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });

            sidebarOverlay.addEventListener('click', (e) => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                const isModalOpen = document.querySelectorAll('#confirmation-modal.show').length > 0;
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && !isModalOpen && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
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
                        sidebarMenu.classList.add('show');
                        sidebarOverlay.classList.add('show');
                    }
                    if (isTouchingSidebar && sidebarMenu.classList.contains('show') && deltaX < -100) {
                        sidebarMenu.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebarMenu.classList.add('desktop');
                    sidebarMenu.classList.remove('mobile', 'show');
                    sidebarOverlay.classList.remove('show');
                    if (!sidebarMenu.classList.contains('collapsed')) {
                        mainContent.classList.add('expanded');
                    }
                } else {
                    sidebarMenu.classList.add('mobile');
                    sidebarMenu.classList.remove('desktop', 'collapsed');
                    mainContent.classList.remove('expanded');
                }
            });

            renderAppointments();

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    sidebarMenu.classList.remove('show', 'desktop', 'collapsed', 'mobile');
                    mainContent.classList.remove('expanded');
                    sidebarOverlay.classList.remove('show');
                    // window.location.href = '/login';
                });
            }
        });
    </script>
</body>
</html>