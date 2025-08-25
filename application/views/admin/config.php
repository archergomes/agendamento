<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciar configurações do sistema no Hospital Público de Matlhovele">
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
            background-color: #f3f4f6;
            color: #000000;
            transition: background-color 0.3s, color 0.3s;
        }
        .dark body {
            background-color: #1f2937;
            color: #f9fafb;
        }
        .dark .sidebar, .dark .settings-container, .dark .form-input {
            background-color: #2d3748;
            border-color: #4b5563;
            color: #f9fafb;
        }
        .dark .sidebar-nav a, .dark .sidebar-nav button {
            color: #d1d5db;
        }
        .dark .sidebar-nav a:hover, .dark .sidebar-nav button:hover {
            background-color: #374151;
            color: #60a5fa;
        }
        .dark .sidebar-nav a.active {
            background-color: #2563eb;
            color: white;
        }
        .dark header {
            background-color: #1e40af;
        }
        .dark footer {
            background-color: #111827;
        }
        .dark .text-black {
            color: #d1d5db;
        }
        .dark .footer-text {
            color: #9ca3af;
        }
        .dark .border-gray-700 {
            border-color: #4b5563;
        }
        #notification {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        #notification.error { background-color: #ef4444; }
        #notification.success { background-color: #10b981; }
        #notification.info { background-color: #3b82f6; }
        #notification.show { display: block; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            z-index: 900;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar.mobile:not(.show) {
            transform: translateX(-100%);
            display: none;
        }
        .sidebar.desktop.collapsed {
            width: 60px;
        }
        .sidebar.desktop:not(.collapsed) {
            width: 250px;
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
            transition: background-color 0.3s;
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
            transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
            min-height: calc(100vh - 64px - 128px);
            width: calc(100% - 60px);
        }
        .main-content.expanded {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        @media (max-width: 767px) {
            .sidebar {
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
                display: flex;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
            #toggle-sidebar-btn {
                display: none;
            }
            #mobile-menu-btn {
                display: block;
                margin-right: 1rem;
                z-index: 901;
            }
            #close-sidebar-btn {
                display: block;
            }
        }
        @media (min-width: 768px) {
            #mobile-menu-btn { display: none; }
            #close-sidebar-btn { display: none; }
            .sidebar.desktop { display: flex; }
            .sidebar.mobile { display: none; }
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
        .dark .main-menu::-webkit-scrollbar-track {
            background: #4b5563;
        }
        .dark .main-menu::-webkit-scrollbar-thumb {
            background: #60a5fa;
        }
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 0.375rem;
            color: #000000;
            transition: background-color 0.2s, color 0.2s, padding 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .sidebar-nav a:hover, .sidebar-nav button:hover {
            background-color: #eff6ff;
            color: #1e40af;
            padding-left: 20px;
        }
        .sidebar-nav a.active {
            background-color: #2563eb;
            color: white;
            font-weight: 600;
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
        .dark .sidebar-nav .logout {
            border-top-color: #4b5563;
        }
        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        footer {
            width: 100%;
            position: relative;
            margin-left: 0;
            background-color: #1f2937;
            transition: background-color 0.3s;
        }
        .settings-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }
        .form-input {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            width: 100%;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            background-color: white;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        .form-input.error {
            border-color: #ef4444;
        }
        .save-btn, .reset-btn, .theme-toggle-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .save-btn:hover, .reset-btn:hover, .theme-toggle-btn:hover {
            background-color: #059669;
        }
        .reset-btn, .theme-toggle-btn {
            background-color: #6b7280;
        }
        .reset-btn:hover, .theme-toggle-btn:hover {
            background-color: #4b5563;
        }
        .error-tooltip {
            display: none;
            position: absolute;
            bottom: -1.5rem;
            left: 0;
            color: #ef4444;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        .form-input:invalid[required] + .error-tooltip, .form-input.error + .error-tooltip {
            display: block;
        }
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        .loading::after {
            content: '';
            position: absolute;
            width: 1.5rem;
            height: 1.5rem;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        @media (max-width: 767px) {
            .settings-container {
                padding: 1.5rem;
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
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg" data-testid="sidebar-menu" data-state="collapsed">
            <div class="sidebar-header flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-black sidebar-text">Menu do Administrador</h2>
                <button id="toggle-sidebar-btn" class="text-black hover:text-gray-900" aria-label="Alternar menu" data-testid="toggle-sidebar-btn">
                    <i id="toggle-sidebar-icon" class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-black hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="/admin/dashboard" class="block text-black" data-testid="nav-dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="/admin/pacientes" class="block text-black" data-testid="nav-pacientes">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="/admin/medicos" class="block text-black" data-testid="nav-medicos">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="/admin/secretarios" class="block text-black" data-testid="nav-secretarios">
                        <i class="fas fa-user-tie"></i>
                        <span class="sidebar-text">Secretários</span>
                    </a>
                    <a href="/admin/agendamentos" class="block text-black" data-testid="nav-agendamentos">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="/admin/cadastrar-paciente" class="block text-black" data-testid="nav-cadastrar-paciente">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Paciente</span>
                    </a>
                    <a href="/admin/cadastrar-secretario" class="block text-black" data-testid="nav-cadastrar-secretario">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Secretário</span>
                    </a>
                    <a href="/admin/cadastrar-medico" class="block text-black" data-testid="nav-cadastrar-medico">
                        <i class="fas fa-user-plus"></i>
                        <span class="sidebar-text">Cadastrar Médico</span>
                    </a>
                    <a href="/admin/relatorios" class="block text-black" data-testid="nav-relatorios">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="/admin/configuracoes" class="block text-black active" data-testid="nav-configuracoes">
                        <i class="fas fa-cog"></i>
                        <span class="sidebar-text">Configurações</span>
                    </a>
                </div>
                <button id="logout-btn" class="block w-full text-left text-black logout" data-testid="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="sidebar-text">Sair</span>
                </button>
            </nav>
        </div>

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button id="mobile-menu-btn" class="md:hidden text-white hover:text-blue-200" aria-label="Abrir menu" data-testid="mobile-menu-btn">
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
                <h2 class="text-2xl font-bold text-black mb-6 dark:text-black-100">Configurações do Sistema</h2>

                <!-- Settings Form -->
                <div class="settings-container">
                    <h3 class="text-lg font-semibold mb-4 text-black dark:text-black-100">Informações do Hospital</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="relative">
                            <label for="hospital-address" class="block text-black font-medium mb-2 dark:text-black-300">Endereço</label>
                            <input type="text" id="hospital-address" class="form-input" placeholder="Ex.: Av. 25 de Setembro, Maputo" required aria-describedby="address-error">
                            <span id="address-error" class="error-tooltip">Endereço é obrigatório.</span>
                        </div>
                        <div class="relative">
                            <label for="hospital-phone" class="block text-black font-medium mb-2 dark:text-black-300">Telefone</label>
                            <input type="text" id="hospital-phone" class="form-input" placeholder="Ex.: +258 84 1234567" required aria-describedby="phone-error">
                            <span id="phone-error" class="error-tooltip">Use formato: +258 XX XXXXXXX</span>
                        </div>
                        <div class="relative">
                            <label for="hospital-email" class="block text-black font-medium mb-2 dark:text-black-300">Email</label>
                            <input type="email" id="hospital-email" class="form-input" placeholder="Ex.: contacto@matlhovele.co.mz" aria-describedby="email-error">
                            <span id="email-error" class="error-tooltip">Email inválido.</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold mb-4 text-black dark:text-black-100">Horário de Funcionamento</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="relative">
                            <label for="weekday-hours" class="block text-black font-medium mb-2 dark:text-black-300">Segunda a Sexta</label>
                            <input type="text" id="weekday-hours" class="form-input" placeholder="Ex.: 7:30 - 16:30" aria-describedby="weekday-error">
                            <span id="weekday-error" class="error-tooltip">Use formato: HH:MM - HH:MM</span>
                        </div>
                        <div class="relative">
                            <label for="saturday-hours" class="block text-black font-medium mb-2 dark:text-black-300">Sábado</label>
                            <input type="text" id="saturday-hours" class="form-input" placeholder="Ex.: 8:00 - 12:00" aria-describedby="saturday-error">
                            <span id="saturday-error" class="error-tooltip">Use formato: HH:MM - HH:MM</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <button id="save-btn" class="save-btn" data-testid="save-btn">
                            <i class="fas fa-save mr-2"></i> Salvar
                        </button>
                        <button id="reset-btn" class="reset-btn" data-testid="reset-btn">
                            <i class="fas fa-undo mr-2"></i> Redefinir
                        </button>
                        <button id="theme-toggle-btn" class="theme-toggle-btn" data-testid="theme-toggle-btn">
                            <i id="theme-icon" class="fas fa-moon mr-2"></i> Modo Escuro
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-medium mb-4 text-white">Hospital Matlhovele</h3>
                        <p id="footer-address" class="footer-text text-black dark:text-gray-300">Av. 25 de Setembro, Maputo</p>
                        <p id="footer-phone" class="footer-text text-black dark:text-gray-300">Telefone: +258 84 1234567</p>
                        <p id="footer-email" class="footer-text text-black dark:text-gray-300">Email: contacto@matlhovele.co.mz</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4 text-white">Horário de Funcionamento</h3>
                        <p id="footer-weekday-hours" class="footer-text text-black dark:text-gray-300">Segunda a Sexta: 7h30 - 16h30</p>
                        <p id="footer-saturday-hours" class="footer-text text-black dark:text-gray-300">Sábado: 8h00 - 12h00</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-4 text-white">Links Rápidos</h3>
                        <ul class="space-y-2">
                            <li><a href="/sobre" class="footer-text text-black hover:text-white dark:text-gray-300 dark:hover:text-white">Sobre Nós</a></li>
                            <li><a href="/servicos" class="footer-text text-black hover:text-white dark:text-gray-300 dark:hover:text-white">Serviços</a></li>
                            <li><a href="/contactos" class="footer-text text-black hover:text-white dark:text-gray-300 dark:hover:text-white">Contactos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-6 text-center footer-text text-black dark:text-gray-400">
                    <p>© 2025 Hospital Público de Matlhovele. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initial data
        const defaultSettings = {
            hospitalAddress: "Av. 25 de Setembro, Maputo",
            hospitalPhone: "+258 84 1234567",
            hospitalEmail: "contacto@matlhovele.co.mz",
            weekdayHours: "7:30 - 16:30",
            saturdayHours: "8:00 - 12:00"
        };
        let settings = JSON.parse(localStorage.getItem('settings')) || { ...defaultSettings };

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

        // Update footer with settings
        function updateFooter() {
            const footerAddress = document.getElementById('footer-address');
            const footerPhone = document.getElementById('footer-phone');
            const footerEmail = document.getElementById('footer-email');
            const footerWeekdayHours = document.getElementById('footer-weekday-hours');
            const footerSaturdayHours = document.getElementById('footer-saturday-hours');
            if (!footerAddress || !footerPhone || !footerEmail || !footerWeekdayHours || !footerSaturdayHours) {
                console.error('Footer elements not found:', { footerAddress, footerPhone, footerEmail, footerWeekdayHours, footerSaturdayHours });
                return;
            }
            footerAddress.textContent = settings.hospitalAddress;
            footerPhone.textContent = `Telefone: ${settings.hospitalPhone}`;
            footerEmail.textContent = `Email: ${settings.hospitalEmail || 'Não definido'}`;
            footerWeekdayHours.textContent = `Segunda a Sexta: ${settings.weekdayHours || 'Não definido'}`;
            footerSaturdayHours.textContent = `Sábado: ${settings.saturdayHours || 'Não definido'}`;
        }

        // Validate phone number
        function validatePhone(phone) {
            const phoneRegex = /^\+258\s\d{2}\s\d{7}$/;
            return phoneRegex.test(phone);
        }

        // Validate email
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return email && emailRegex.test(email);
        }

        // Validate hours
        function validateHours(hours) {
            if (!hours) return true; // Optional field
            const hoursRegex = /^\d{1,2}:\d{2}\s-\s\d{1,2}:\d{2}$/;
            return hoursRegex.test(hours);
        }

        // Toggle theme
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            const themeBtn = document.getElementById('theme-toggle-btn');
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            themeIcon.className = `fas fa-${isDark ? 'sun' : 'moon'} mr-2`;
            themeBtn.textContent = isDark ? 'Modo Claro' : 'Modo Escuro';
            themeIcon.nextSibling.textContent = isDark ? 'Modo Claro' : 'Modo Escuro';
        }

        // Sidebar initialization
        let isSidebarExpanded = false;
        function initializeSidebar() {
            const sidebarMenu = document.getElementById('sidebar-menu');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const toggleSidebarIcon = document.getElementById('toggle-sidebar-icon');

            if (!sidebarMenu || !mainContent || !sidebarOverlay || !toggleSidebarIcon) {
                console.error('Sidebar initialization failed: Missing elements', { sidebarMenu, mainContent, sidebarOverlay, toggleSidebarIcon });
                showNotification('Erro ao inicializar o menu.', 'error');
                return false;
            }

            sidebarMenu.classList.remove('desktop', 'mobile', 'show', 'collapsed');
            mainContent.classList.remove('expanded');
            sidebarOverlay.classList.remove('show');
            toggleSidebarIcon.classList.remove('fa-times', 'fa-bars');

            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('desktop', 'collapsed');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                mainContent.classList.remove('expanded');
                toggleSidebarIcon.classList.add('fa-bars');
                isSidebarExpanded = false;
            } else {
                sidebarMenu.classList.add('mobile');
                sidebarMenu.setAttribute('data-state', 'collapsed');
                mainContent.classList.remove('expanded');
                toggleSidebarIcon.classList.add('fa-bars');
                isSidebarExpanded = false;
            }
            console.log('Sidebar initialized:', { isSidebarExpanded, width: window.innerWidth, sidebarClasses: sidebarMenu.classList.toString(), dataState: sidebarMenu.getAttribute('data-state') });
            return true;
        }

        // DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function () {
            // DOM elements
            const elements = {
                notificationClose: document.getElementById('notification-close'),
                mobileMenuBtn: document.getElementById('mobile-menu-btn'),
                sidebarMenu: document.getElementById('sidebar-menu'),
                closeSidebarBtn: document.getElementById('close-sidebar-btn'),
                toggleSidebarBtn: document.getElementById('toggle-sidebar-btn'),
                toggleSidebarIcon: document.getElementById('toggle-sidebar-icon'),
                mainContent: document.querySelector('.main-content'),
                sidebarOverlay: document.getElementById('sidebar-overlay'),
                hospitalAddress: document.getElementById('hospital-address'),
                hospitalPhone: document.getElementById('hospital-phone'),
                hospitalEmail: document.getElementById('hospital-email'),
                weekdayHours: document.getElementById('weekday-hours'),
                saturdayHours: document.getElementById('saturday-hours'),
                saveBtn: document.getElementById('save-btn'),
                resetBtn: document.getElementById('reset-btn'),
                themeToggleBtn: document.getElementById('theme-toggle-btn'),
                logoutBtn: document.getElementById('logout-btn')
            };

            // Check DOM elements
            let missingElements = false;
            for (const [key, value] of Object.entries(elements)) {
                if (!value) {
                    console.error(`DOM element not found: ${key}`);
                    missingElements = true;
                }
            }
            if (missingElements) {
                showNotification('Erro ao carregar alguns elementos da página.', 'error');
            }

            // Initialize sidebar
            if (!initializeSidebar()) return;

            // Load theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                elements.themeToggleBtn.textContent = 'Modo Claro';
                elements.themeToggleBtn.querySelector('i').className = 'fas fa-sun mr-2';
            }

            // Load settings
            elements.hospitalAddress.value = settings.hospitalAddress;
            elements.hospitalPhone.value = settings.hospitalPhone;
            elements.hospitalEmail.value = settings.hospitalEmail;
            elements.weekdayHours.value = settings.weekdayHours;
            elements.saturdayHours.value = settings.saturdayHours;
            updateFooter();

            // Toggleesian
            elements.toggleSidebarBtn?.addEventListener('click', () => {
                if (window.innerWidth >= 768) {
                    isSidebarExpanded = !isSidebarExpanded;
                    elements.sidebarMenu.classList.toggle('collapsed', !isSidebarExpanded);
                    elements.mainContent.classList.toggle('expanded', isSidebarExpanded);
                    elements.toggleSidebarIcon.classList.toggle('fa-bars', !isSidebarExpanded);
                    elements.toggleSidebarIcon.classList.toggle('fa-times', isSidebarExpanded);
                    elements.sidebarMenu.setAttribute('data-state', isSidebarExpanded ? 'expanded' : 'collapsed');
                    console.log('Desktop sidebar toggled:', { isSidebarExpanded, sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
                }
            });

            elements.mobileMenuBtn?.addEventListener('click', () => {
                elements.sidebarMenu.classList.add('show', 'mobile');
                elements.sidebarOverlay.classList.add('show');
                elements.sidebarMenu.setAttribute('data-state', 'expanded');
                console.log('Mobile menu opened:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
            });

            elements.closeSidebarBtn?.addEventListener('click', () => {
                elements.sidebarMenu.classList.remove('show');
                elements.sidebarOverlay.classList.remove('show');
                elements.sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Mobile menu closed:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
            });

            elements.sidebarOverlay?.addEventListener('click', () => {
                elements.sidebarMenu.classList.remove('show');
                elements.sidebarOverlay.classList.remove('show');
                elements.sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Mobile menu closed via overlay:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
            });

            document.querySelectorAll('.sidebar-nav a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const href = link.getAttribute('href') || 'unknown';
                    showNotification(`Navegando para ${href}`, 'info');
                    console.log('Navigation link clicked:', { href });
                    // window.location.href = href;
                });
            });

            elements.saveBtn?.addEventListener('click', () => {
                elements.saveBtn.classList.add('loading');
                setTimeout(() => {
                    const newSettings = {
                        hospitalAddress: elements.hospitalAddress.value.trim(),
                        hospitalPhone: elements.hospitalPhone.value.trim(),
                        hospitalEmail: elements.hospitalEmail.value.trim(),
                        weekdayHours: elements.weekdayHours.value.trim(),
                        saturdayHours: elements.saturdayHours.value.trim()
                    };

                    if (!newSettings.hospitalAddress || !newSettings.hospitalPhone) {
                        elements.hospitalAddress.classList.toggle('error', !newSettings.hospitalAddress);
                        elements.hospitalPhone.classList.toggle('error', !newSettings.hospitalPhone);
                        showNotification('Por favor, preencha todos os campos obrigatórios.', 'error');
                        elements.saveBtn.classList.remove('loading');
                        return;
                    }

                    if (!validatePhone(newSettings.hospitalPhone)) {
                        elements.hospitalPhone.classList.add('error');
                        showNotification('Telefone inválido. Use o formato: +258 XX XXXXXXX', 'error');
                        elements.saveBtn.classList.remove('loading');
                        return;
                    }

                    if (newSettings.hospitalEmail && !validateEmail(newSettings.hospitalEmail)) {
                        elements.hospitalEmail.classList.add('error');
                        showNotification('Email inválido.', 'error');
                        elements.saveBtn.classList.remove('loading');
                        return;
                    }

                    if (newSettings.weekdayHours && !validateHours(newSettings.weekdayHours)) {
                        elements.weekdayHours.classList.add('error');
                        showNotification('Horário de Segunda a Sexta inválido. Use formato: HH:MM - HH:MM', 'error');
                        elements.saveBtn.classList.remove('loading');
                        return;
                    }

                    if (newSettings.saturdayHours && !validateHours(newSettings.saturdayHours)) {
                        elements.saturdayHours.classList.add('error');
                        showNotification('Horário de Sábado inválido. Use formato: HH:MM - HH:MM', 'error');
                        elements.saveBtn.classList.remove('loading');
                        return;
                    }

                    elements.hospitalAddress.classList.remove('error');
                    elements.hospitalPhone.classList.remove('error');
                    elements.hospitalEmail.classList.remove('error');
                    elements.weekdayHours.classList.remove('error');
                    elements.saturdayHours.classList.remove('error');

                    settings = { ...newSettings };
                    localStorage.setItem('settings', JSON.stringify(settings));
                    updateFooter();
                    showNotification('Configurações salvas com sucesso!', 'success');
                    elements.saveBtn.classList.remove('loading');
                }, 500);
            });

            elements.resetBtn?.addEventListener('click', () => {
                elements.resetBtn.classList.add('loading');
                setTimeout(() => {
                    settings = { ...defaultSettings };
                    elements.hospitalAddress.value = settings.hospitalAddress;
                    elements.hospitalPhone.value = settings.hospitalPhone;
                    elements.hospitalEmail.value = settings.hospitalEmail;
                    elements.weekdayHours.value = settings.weekdayHours;
                    elements.saturdayHours.value = settings.saturdayHours;
                    elements.hospitalAddress.classList.remove('error');
                    elements.hospitalPhone.classList.remove('error');
                    elements.hospitalEmail.classList.remove('error');
                    elements.weekdayHours.classList.remove('error');
                    elements.saturdayHours.classList.remove('error');
                    updateFooter();
                    showNotification('Configurações redefinidas com sucesso!', 'success');
                    elements.resetBtn.classList.remove('loading');
                }, 500);
            });

            elements.themeToggleBtn?.addEventListener('click', toggleTheme);

            elements.notificationClose?.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = elements.sidebarMenu?.contains(e.target);
                const isClickOnMenuBtn = elements.mobileMenuBtn?.contains(e.target);
                const isSidebarOpen = elements.sidebarMenu?.classList.contains('show');
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && window.innerWidth < 768) {
                    elements.sidebarMenu.classList.remove('show');
                    elements.sidebarOverlay.classList.remove('show');
                    elements.sidebarMenu.setAttribute('data-state', 'collapsed');
                    console.log('Mobile menu closed via outside click:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
                }
            });

            let touchStartX = 0;
            let touchEndX = 0;
            let isTouchingSidebar = false;

            document.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                isTouchingSidebar = elements.sidebarMenu?.contains(e.target);
            });

            document.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (window.innerWidth < 768 && elements.sidebarMenu) {
                    const deltaX = touchEndX - touchStartX;
                    if (!elements.sidebarMenu.classList.contains('show') && touchStartX < 50 && deltaX > 100) {
                        elements.sidebarMenu.classList.add('show', 'mobile');
                        elements.sidebarOverlay.classList.add('show');
                        elements.sidebarMenu.setAttribute('data-state', 'expanded');
                        console.log('Mobile menu opened via swipe:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
                    }
                    if (isTouchingSidebar && elements.sidebarMenu.classList.contains('show') && deltaX < -100) {
                        elements.sidebarMenu.classList.remove('show');
                        elements.sidebarOverlay.classList.remove('show');
                        elements.sidebarMenu.setAttribute('data-state', 'collapsed');
                        console.log('Mobile menu closed via swipe:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
                    }
                }
            });

            window.addEventListener('resize', () => {
                initializeSidebar();
                console.log('Window resized:', { width: window.innerWidth, isSidebarExpanded, sidebarClasses: elements.sidebarMenu?.classList.toString(), dataState: elements.sidebarMenu?.getAttribute('data-state') });
            });

            elements.logoutBtn?.addEventListener('click', () => {
                showNotification('Sessão encerrada com sucesso!', 'success');
                elements.sidebarMenu.classList.remove('show', 'desktop', 'collapsed', 'mobile');
                elements.mainContent.classList.remove('expanded');
                elements.sidebarOverlay.classList.remove('show');
                elements.sidebarMenu.setAttribute('data-state', 'collapsed');
                console.log('Logout clicked:', { sidebarClasses: elements.sidebarMenu.classList.toString(), dataState: elements.sidebarMenu.getAttribute('data-state') });
                // window.location.href = '/login';
            });
        });
    </script>
</body>
</html>