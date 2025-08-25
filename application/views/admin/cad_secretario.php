<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Secretário - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Cadastrar ou editar secretários no Hospital Público de Matlhovele">
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
                padding: 8px 16px !important;
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
            max-width: 500px;
            margin: 0 auto;
        }
        .form-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }
        .save-btn {
            background-color: #3b82f6;
            color: white;
        }
        .save-btn:hover {
            background-color: #2563eb;
        }
        .cancel-btn {
            background-color: #d1d5db;
            color: #374151;
        }
        .cancel-btn:hover {
            background-color: #9ca3af;
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
                    <a href="/admin/dashboard" class="block text-gray-700">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="/admin/pacientes" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="/admin/medicos" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="/admin/secretarios" class="block text-gray-700">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Secretários</span>
                    </a>
                    <a href="/admin/agendamentos" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="/admin/cadastrar-paciente" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Paciente</span>
                    </a>
                    <a href="/admin/cadastrar-secretario" class="block text-gray-700 active">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Secretário</span>
                    </a>
                    <a href="/admin/cadastrar-medico" class="block text-gray-700">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Médico</span>
                    </a>
                    <a href="/admin/relatorios" class="block text-gray-700">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="/admin/configuracoes" class="block text-gray-700">
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
            <div class="container mx-auto px-4 py-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2" id="form-title">Cadastrar Novo Secretário</h2>
                    <p class="text-gray-600">Preencha os dados abaixo para cadastrar ou editar um secretário.</p>
                </div>

                <!-- Secretary Form -->
                <div class="form-container">
                    <div class="mb-4">
                        <label for="secretary-name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" id="secretary-name" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="secretary-phone" class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="tel" id="secretary-phone" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="secretary-bi" class="block text-sm font-medium text-gray-700">BI</label>
                        <input type="text" id="secretary-bi" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label for="secretary-email" class="block text-sm font-medium text-gray-700">Email (opcional)</label>
                        <input type="email" id="secretary-email" class="w-full p-2 border rounded-lg">
                    </div>
                    <div class="mb-6" id="password-field">
                        <label for="secretary-password" class="block text-sm font-medium text-gray-700">Senha</label>
                        <input type="password" id="secretary-password" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div class="flex space-x-2">
                        <button id="save-btn" class="form-btn save-btn flex-1">Salvar</button>
                        <button id="cancel-btn" class="form-btn cancel-btn flex-1">Cancelar</button>
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
        // Initial data
        let secretaries = JSON.parse(localStorage.getItem('secretaries')) || [];

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

        // Get URL parameter
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Validate and save secretary
        function saveSecretary() {
            const nameInput = document.getElementById('secretary-name');
            const phoneInput = document.getElementById('secretary-phone');
            const biInput = document.getElementById('secretary-bi');
            const emailInput = document.getElementById('secretary-email');
            const passwordInput = document.getElementById('secretary-password');
            if (!nameInput || !phoneInput || !biInput || !emailInput || !passwordInput) {
                console.error('Form elements not found:', { nameInput, phoneInput, biInput, emailInput, passwordInput });
                return;
            }

            const name = nameInput.value.trim();
            const phone = phoneInput.value.trim();
            const bi = biInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            const isEditMode = biInput.hasAttribute('readonly');

            if (!name || !phone || !bi || (!isEditMode && !password)) {
                showNotification('Nome, telefone, BI e senha (para novos secretários) são obrigatórios.', 'error');
                return;
            }

            if (!phone.match(/\+258\s*[8][0-49][0-9]{7}/)) {
                showNotification('Número de telefone inválido. Use o formato +258 8X XXXXXXX.', 'error');
                return;
            }

            if (!isEditMode && secretaries.find(s => s.bi === bi)) {
                showNotification('BI já existe. Escolha outro.', 'error');
                return;
            }

            if (email && !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                showNotification('Email inválido.', 'error');
                return;
            }

            const secretary = { name, phone, bi, email, role: 'Secretário' };
            if (!isEditMode) {
                secretary.password = password;
            } else {
                const existing = secretaries.find(s => s.bi === bi);
                if (existing) {
                    secretary.password = existing.password; // Retain original password
                }
            }

            if (isEditMode) {
                const index = secretaries.findIndex(s => s.bi === bi);
                if (index !== -1) {
                    secretaries[index] = secretary;
                }
            } else {
                secretaries.push(secretary);
            }

            localStorage.setItem('secretaries', JSON.stringify(secretaries));
            showNotification(isEditMode ? 'Secretário atualizado com sucesso!' : 'Secretário cadastrado com sucesso!', 'success');
            setTimeout(() => {
                window.location.href = '/admin/secretarios';
            }, 1000);
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', function () {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const saveBtn = document.getElementById('save-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const nameInput = document.getElementById('secretary-name');
            const phoneInput = document.getElementById('secretary-phone');
            const biInput = document.getElementById('secretary-bi');
            const emailInput = document.getElementById('secretary-email');
            const passwordField = document.getElementById('password-field');
            const passwordInput = document.getElementById('secretary-password');
            const formTitle = document.getElementById('form-title');

            if (!notificationClose || !mobileMenuBtn || !sidebarMenu || !closeSidebarBtn || !toggleSidebarBtn ||
                !mainContent || !sidebarOverlay || !saveBtn || !cancelBtn || !nameInput || !phoneInput || !biInput ||
                !emailInput || !passwordField || !passwordInput || !formTitle) {
                console.error('DOM elements not found:', {
                    notificationClose, mobileMenuBtn, sidebarMenu, closeSidebarBtn, toggleSidebarBtn,
                    mainContent, sidebarOverlay, saveBtn, cancelBtn, nameInput, phoneInput, biInput,
                    emailInput, passwordField, passwordInput, formTitle
                });
                return;
            }

            // Check for edit mode
            const bi = getQueryParam('bi');
            if (bi && secretaries.find(s => s.bi === bi)) {
                formTitle.textContent = 'Editar Secretário';
                const secretary = secretaries.find(s => s.bi === bi);
                nameInput.value = secretary.name;
                phoneInput.value = secretary.phone;
                biInput.value = secretary.bi;
                biInput.setAttribute('readonly', 'true');
                emailInput.value = secretary.email || '';
                passwordField.style.display = 'none';
            } else {
                formTitle.textContent = 'Cadastrar Novo Secretário';
                biInput.removeAttribute('readonly');
                passwordField.style.display = 'block';
            }

            // Notification close
            notificationClose.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Sidebar behavior
            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('desktop', 'collapsed');
                sidebarMenu.classList.remove('mobile', 'show');
                mainContent.classList.remove('expanded');
            } else {
                sidebarMenu.classList.add('mobile');
                sidebarMenu.classList.remove('desktop', 'collapsed');
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

            sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && window.innerWidth < 768) {
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

            // Form events
            saveBtn.addEventListener('click', saveSecretary);
            cancelBtn.addEventListener('click', () => {
                window.location.href = '/admin/secretarios';
            });

            // Logout
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