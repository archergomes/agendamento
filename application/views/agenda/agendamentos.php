<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - Hospital Matlhovele</title>
    <meta name="description" content="Visualize e gerencie seus agendamentos no Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        #notification {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
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
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            width: 250px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar.desktop {
            transform: translateX(0);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }
        .sidebar.collapsed .close-sidebar-btn {
            display: none;
        }
        .main-content {
            margin-left: 60px;
            transition: margin-left 0.3s ease-in-out;
        }
        .main-content.expanded {
            margin-left: 250px;
        }
        @media (min-width: 768px) {
            #mobile-menu-btn {
                display: none;
            }
            .sidebar.desktop {
                display: block;
            }
        }
        @media (max-width: 767px) {
            .sidebar.desktop {
                display: none;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.collapsed {
                width: 250px;
            }
            .sidebar.collapsed .sidebar-text {
                display: inline;
            }
            .sidebar.collapsed .sidebar-header {
                justify-content: space-between;
                padding: 1rem;
            }
            .sidebar.collapsed .close-sidebar-btn {
                display: block;
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
            padding: 10px 16px;
            border-radius: 0.25rem;
        }
        .sidebar-nav i {
            font-size: 1.5rem;
            width: 28px;
            text-align: center;
        }
        .sidebar.collapsed .sidebar-nav a, .sidebar.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 10px;
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
            z-index: 50;
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Notification -->
    <div id="notification" role="alert">
        <span id="notification-message"></span>
        <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
    </div>

    <!-- Left Sidebar -->
    <div id="sidebar-menu" class="sidebar bg-white shadow-lg z-50 desktop">
        <div class="sidebar-header flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Paciente</h2>
            <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <div class="main-menu">
                <a href="<?= base_url('agenda/') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Home</span>
                </a>
                <a href="<?= base_url('agenda/agendamentos') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Meus Agendamentos</span>
                </a>
                <a href="<?= base_url('agenda/perfil') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-user"></i>
                    <span class="sidebar-text">Perfil</span>
                </a>
            </div>
            <button id="logout-btn" class="block w-full text-left text-gray-700 hover:bg-blue-50 rounded logout">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text">Sair</span>
            </button>
        </nav>
    </div>

    <!-- Header/Navbar -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
                <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
            </div>
            <button id="mobile-menu-btn" class="md:hidden text-white hover:text-gray-200" aria-label="Abrir menu">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 main-content">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Meus Agendamentos</h2>
            <div id="appointments-list" class="space-y-4">
                <!-- Appointments will be rendered here -->
            </div>
        </div>
    </main>

    <!-- Confirmation Modal for Cancellation -->
    <div id="confirmation-modal">
        <div class="modal-content">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Confirmar Cancelamento</h3>
            <p class="mb-4">Deseja realmente cancelar este agendamento?</p>
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

    <script>
        // Simulated appointment data (shared across pages via localStorage)
        let bookedAppointments = JSON.parse(localStorage.getItem('bookedAppointments')) || [];
        let bookedSlots = JSON.parse(localStorage.getItem('bookedSlots')) || {};

        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Renderizar lista de agendamentos
        function renderAppointments() {
            const list = document.getElementById('appointments-list');
            if (bookedAppointments.length === 0) {
                list.innerHTML = '<p class="text-gray-600">Nenhum agendamento encontrado.</p>';
                return;
            }
            list.innerHTML = bookedAppointments.map((appt, index) => `
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="font-medium text-gray-800 mb-2">Agendamento #${index + 1}</h3>
                    <p><strong>Especialidade:</strong> ${appt.specialty}</p>
                    <p><strong>Médico:</strong> ${appt.doctor}</p>
                    <p><strong>Data:</strong> ${appt.date}</p>
                    <p><strong>Horário:</strong> ${appt.time}</p>
                    <p><strong>Nome:</strong> ${appt.name}</p>
                    <p><strong>Telefone:</strong> ${appt.phone}</p>
                    <p><strong>BI:</strong> ${appt.bi}</p>
                    <button class="cancel-btn mt-2 bg-red-600 text-white py-1 px-3 rounded-lg hover:bg-red-700 transition" data-index="${index}">
                        Cancelar
                    </button>
                </div>
            `).join('');

            // Adicionar eventos aos botões de cancelamento
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
            modal.classList.add('show');

            const confirmBtn = document.getElementById('confirm-cancel-btn');
            const cancelBtn = document.getElementById('cancel-cancel-btn');

            // Configurar evento de confirmação
            const confirmHandler = () => {
                const appt = bookedAppointments[index];
                // Remover o horário reservado
                if (bookedSlots[appt.date]) {
                    bookedSlots[appt.date] = bookedSlots[appt.date].filter(slot => slot !== appt.time);
                    if (bookedSlots[appt.date].length === 0) {
                        delete bookedSlots[appt.date];
                    }
                }
                // Remover o agendamento
                bookedAppointments.splice(index, 1);
                // Atualizar localStorage
                localStorage.setItem('bookedAppointments', JSON.stringify(bookedAppointments));
                localStorage.setItem('bookedSlots', JSON.stringify(bookedSlots));
                // Re-renderizar a lista
                renderAppointments();
                modal.classList.remove('show');
                showNotification('Agendamento cancelado com sucesso!', 'success');
                confirmBtn.removeEventListener('click', confirmHandler);
            };

            confirmBtn.addEventListener('click', confirmHandler);

            // Configurar evento de cancelamento
            cancelBtn.addEventListener('click', () => {
                modal.classList.remove('show');
                confirmBtn.removeEventListener('click', confirmHandler);
            });
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar notificação
            document.getElementById('notification-close').addEventListener('click', function () {
                document.getElementById('notification').classList.remove('show');
            });

            // Inicializar menu lateral
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');

            // Iniciar com sidebar colapsada no desktop
            sidebarMenu.classList.add('collapsed');
            mainContent.classList.remove('expanded');

            mobileMenuBtn.addEventListener('click', function () {
                sidebarMenu.classList.add('show');
                sidebarMenu.classList.remove('collapsed');
                mainContent.classList.add('expanded');
            });

            closeSidebarBtn.addEventListener('click', function () {
                sidebarMenu.classList.remove('show');
                mainContent.classList.remove('expanded');
            });

            toggleSidebarBtn.addEventListener('click', function () {
                sidebarMenu.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });

            // Fechar sidebar ao clicar fora (mobile)
            document.addEventListener('click', function (e) {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                const isModalOpen = document.querySelectorAll('.show').length > 0;

                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && !isModalOpen) {
                    sidebarMenu.classList.remove('show');
                    mainContent.classList.remove('expanded');
                }
            });

            // Renderizar agendamentos
            renderAppointments();

            // Evento para logout
            document.getElementById('logout-btn').addEventListener('click', function () {
                showNotification('Sessão encerrada com sucesso!', 'success');
                sidebarMenu.classList.remove('show');
                mainContent.classList.remove('expanded');
                // Limpar dados locais
                localStorage.removeItem('bookedAppointments');
                localStorage.removeItem('bookedSlots');
                bookedAppointments = [];
                bookedSlots = {};
                renderAppointments();
                // Em um sistema real, redirecionar para /login
                // window.location.href = '/login';
            });
        });
    </script>
</body>
</html>