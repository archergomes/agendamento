<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Hospital Matlhovele</title>
    <meta name="description" content="Gerencie suas informações de perfil no Hospital Público de Matlhovele">
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
        .profile-container[aria-hidden="true"] {
            display: none;
        }
        .profile-container[aria-hidden="false"] {
            display: block;
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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Meu Perfil</h2>
                <button id="edit-profile-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition" aria-label="Editar perfil">
                    Editar
                </button>
            </div>

            <!-- View Profile -->
            <div id="view-profile" class="profile-container" aria-hidden="false">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="font-medium text-gray-700">Nome Completo</dt>
                        <dd id="view-name" class="text-gray-600"></dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700">Número de Telefone</dt>
                        <dd id="view-phone" class="text-gray-600"></dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700">Número do BI</dt>
                        <dd id="view-bi" class="text-gray-600"></dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700">Email</dt>
                        <dd id="view-email" class="text-gray-600"></dd>
                    </div>
                </dl>
            </div>

            <!-- Edit Profile -->
            <div id="edit-profile" class="profile-container" aria-hidden="true">
                <div class="space-y-4">
                    <div>
                        <label for="edit-name" class="block text-gray-700 mb-2">Nome Completo</label>
                        <input type="text" id="edit-name" class="w-full p-2 border rounded" aria-required="true">
                    </div>
                    <div>
                        <label for="edit-phone" class="block text-gray-700 mb-2">Número de Telefone</label>
                        <input type="tel" id="edit-phone" class="w-full p-2 border rounded" aria-required="true" pattern="\+258\s?[8][0-49][0-9]{7}">
                    </div>
                    <div>
                        <label for="edit-bi" class="block text-gray-700 mb-2">Número do BI</label>
                        <input type="text" id="edit-bi" class="w-full p-2 border rounded" aria-required="true">
                    </div>
                    <div>
                        <label for="edit-email" class="block text-gray-700 mb-2">Email</label>
                        <input type="email" id="edit-email" class="w-full p-2 border rounded">
                    </div>
                    <div class="flex space-x-2">
                        <button id="save-profile-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex-1">
                            Salvar
                        </button>
                        <button id="cancel-edit-btn" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                            Cancelar
                        </button>
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

    <script>
        // Simulated profile data (stored in localStorage)
        let userProfile = JSON.parse(localStorage.getItem('userProfile')) || {
            name: '',
            phone: '',
            bi: '',
            email: ''
        };

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

        // Renderizar perfil
        function renderProfile() {
            document.getElementById('view-name').textContent = userProfile.name || 'Não definido';
            document.getElementById('view-phone').textContent = userProfile.phone || 'Não definido';
            document.getElementById('view-bi').textContent = userProfile.bi || 'Não definido';
            document.getElementById('view-email').textContent = userProfile.email || 'Não definido';
        }

        // Preencher formulário de edição
        function populateEditForm() {
            document.getElementById('edit-name').value = userProfile.name;
            document.getElementById('edit-phone').value = userProfile.phone;
            document.getElementById('edit-bi').value = userProfile.bi;
            document.getElementById('edit-email').value = userProfile.email;
        }

        // Alternar entre visualização e edição
        function toggleEditMode(isEditing) {
            document.getElementById('view-profile').setAttribute('aria-hidden', isEditing ? 'true' : 'false');
            document.getElementById('edit-profile').setAttribute('aria-hidden', isEditing ? 'false' : 'true');
            document.getElementById('edit-profile-btn').textContent = isEditing ? 'Cancelar' : 'Editar';
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
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen) {
                    sidebarMenu.classList.remove('show');
                    mainContent.classList.remove('expanded');
                }
            });

            // Renderizar perfil inicial
            renderProfile();

            // Evento para botão de edição
            document.getElementById('edit-profile-btn').addEventListener('click', function () {
                const isEditing = document.getElementById('edit-profile').getAttribute('aria-hidden') === 'false';
                if (!isEditing) {
                    populateEditForm();
                }
                toggleEditMode(!isEditing);
            });

            // Evento para salvar perfil
            document.getElementById('save-profile-btn').addEventListener('click', function () {
                const name = document.getElementById('edit-name').value.trim();
                const phone = document.getElementById('edit-phone').value.trim();
                const bi = document.getElementById('edit-bi').value.trim();
                const email = document.getElementById('edit-email').value.trim();

                if (!name || !phone || !bi) {
                    showNotification('Por favor, preencha todos os campos obrigatórios.', 'error');
                    return;
                }
                if (!phone.match(/\+258\s?[8][0-49][0-9]{7}/)) {
                    showNotification('Número de telefone inválido. Use o formato +258 8X XXXXXXX.', 'error');
                    return;
                }
                if (email && !email.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
                    showNotification('Email inválido.', 'error');
                    return;
                }

                userProfile = { name, phone, bi, email };
                localStorage.setItem('userProfile', JSON.stringify(userProfile));
                renderProfile();
                toggleEditMode(false);
                showNotification('Perfil atualizado com sucesso!', 'success');
            });

            // Evento para cancelar edição
            document.getElementById('cancel-edit-btn').addEventListener('click', function () {
                toggleEditMode(false);
            });

            // Evento para logout
            document.getElementById('logout-btn').addEventListener('click', function () {
                showNotification('Sessão encerrada com sucesso!', 'success');
                sidebarMenu.classList.remove('show');
                mainContent.classList.remove('expanded');
                // Limpar dados locais
                localStorage.removeItem('userProfile');
                localStorage.removeItem('bookedAppointments');
                localStorage.removeItem('bookedSlots');
                renderProfile();
                // Em um sistema real, redirecionar para /login
                // window.location.href = '/login';
            });
        });
    </script>
</body>
</html>