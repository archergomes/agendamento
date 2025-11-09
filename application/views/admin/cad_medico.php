<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Médico - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Cadastrar ou editar médicos no Hospital Público de Matlhovele">
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
            background-color: #f8fafc;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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

        .sidebar.mobile .sidebar-nav a,
        .sidebar.mobile .sidebar-nav button {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            .sidebar.mobile .sidebar-nav a,
            .sidebar.mobile .sidebar-nav button {
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

        .sidebar.desktop.collapsed .sidebar-nav a,
        .sidebar.desktop.collapsed .sidebar-nav button {
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

        /* Estilos aprimorados para o formulário - mais compacto */
        .form-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .form-subtitle {
            color: #6b7280;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
        }

        .form-label.required::after {
            content: " *";
            color: #ef4444;
        }

        .form-input {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .form-input.success {
            border-color: #10b981;
        }

        .form-input:read-only {
            background-color: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .form-error {
            font-size: 0.75rem;
            color: #ef4444;
            margin-top: 0.25rem;
            display: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .save-btn {
            background-color: #3b82f6;
            color: white;
        }

        .save-btn:hover {
            background-color: #2563eb;
        }

        .cancel-btn {
            background-color: #f3f4f6;
            color: #374151;
        }

        .cancel-btn:hover {
            background-color: #e5e7eb;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .form-actions .form-btn {
            flex: 1;
        }

        .card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }

        .card-body {
            padding: 0;
        }

        .name-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .name-fields {
                grid-template-columns: 1fr;
            }
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
        }

        .password-container {
            position: relative;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="page-wrapper">
        <!-- Notification -->
        <div id="notification" role="alert">
            <span id="notification-message"></span>
            <button id="notification-close" class="ml-4 text-white hover:text-gray-200 text-xl font-bold">×</button>
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
                    <a href="<?php echo site_url('admin/cad_medico'); ?>" class="block text-gray-700 active">
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

        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-3 flex items-center justify-between">
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
            <div class="container mx-auto px-4 py-6">
                <div class="mb-6 text-center">
                    <h2 class="form-title" id="form-title">Cadastrar Novo Médico</h2>
                    <p class="form-subtitle">Preencha os dados abaixo para cadastrar ou editar um médico.</p>
                </div>

                <!-- Doctor Form -->
                <div class="form-container">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-gray-800">Dados Pessoais</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <!-- Nome e Sobrenome separados -->
                            <div class="form-group">
                                <label for="doctor-firstname" class="form-label required">Nome</label>
                                <input type="text" id="doctor-firstname" class="form-input" required>
                                <div class="form-error" id="firstname-error">Por favor, insira o nome</div>
                            </div>
                            <div class="form-group">
                                <label for="doctor-lastname" class="form-label required">Sobrenome</label>
                                <input type="text" id="doctor-lastname" class="form-input" required>
                                <div class="form-error" id="lastname-error">Por favor, insira o sobrenome</div>
                            </div>

                            <!-- Telefone -->
                            <div class="form-group">
                                <label for="doctor-phone" class="form-label required">Telefone</label>
                                <input type="tel" id="doctor-phone" class="form-input" required>
                                <div class="form-error" id="phone-error">Por favor, insira um telefone válido</div>
                            </div>

                            <!-- BI -->
                            <div class="form-group">
                                <label for="doctor-bi" class="form-label required">BI</label>
                                <input type="text" id="doctor-bi" class="form-input" required>
                                <div class="form-error" id="bi-error">Por favor, insira um BI válido</div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="doctor-email" class="form-label required">Email</label>
                                <input type="email" id="doctor-email" class="form-input" required>
                                <div class="form-error" id="email-error">Por favor, insira um email válido</div>
                            </div>

                            <!-- Especialidade -->
                            <div class="form-group">
                                <label for="doctor-specialty" class="form-label required">Especialidade</label>
                                <select id="doctor-specialty" class="form-input" required>
                                    <option value="">Selecione uma especialidade</option>
                                    <option value="Medicina Geral">Medicina Geral</option>
                                    <option value="Cardiologia">Cardiologia</option>
                                    <option value="Pediatria">Pediatria</option>
                                    <option value="Ortopedia">Ortopedia</option>
                                    <option value="Ginecologia">Ginecologia</option>
                                    <option value="Neurologia">Neurologia</option>
                                    <option value="Cirurgia Geral">Cirurgia Geral</option>
                                </select>
                                <div class="form-error" id="specialty-error">Por favor, selecione a especialidade</div>
                            </div>

                            <!-- Número da Licença -->
                            <div class="form-group">
                                <label for="doctor-license" class="form-label required">Número da Licença</label>
                                <input type="text" id="doctor-license" class="form-input" required>
                                <div class="form-error" id="license-error">Por favor, insira o número da licença</div>
                            </div>
                        </div>

                        <!-- Campo Senha -->
                        <div class="form-group">
                            <label for="doctor-password" class="form-label required">Senha</label>
                            <div class="password-container">
                                <input type="password" id="doctor-password" class="form-input" required minlength="6">
                                <button type="button" class="password-toggle" id="password-toggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-error" id="password-error">A senha deve ter pelo menos 6 caracteres</div>
                            <div class="form-hint">Mínimo 6 caracteres</div>
                        </div>

                        <div class="form-actions">
                            <button id="save-btn" class="form-btn save-btn">Salvar</button>
                            <button id="cancel-btn" class="form-btn cancel-btn">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-3">Hospital Matlhovele</h3>
                        <p class="text-gray-300 text-sm">Av. 25 de Setembro, Maputo</p>
                        <p class="text-gray-300 text-sm">Telefone: +258 84 123 4567</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-3">Horário de Funcionamento</h3>
                        <p class="text-gray-300 text-sm">Segunda a Sexta: 7h30 - 16h30</p>
                        <p class="text-gray-300 text-sm">Sábado: 8h00 - 12h00</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-3">Links Rápidos</h3>
                        <ul class="space-y-1">
                            <li><a href="/sobre" class="text-gray-300 hover:text-white text-sm">Sobre Nós</a></li>
                            <li><a href="/servicos" class="text-gray-300 hover:text-white text-sm">Serviços</a></li>
                            <li><a href="/contactos" class="text-gray-300 hover:text-white text-sm">Contactos</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-6 pt-4 text-center text-gray-400 text-sm">
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
                console.error('Notification elements not found:', {
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

        // Get URL parameter
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Fetch doctor by BI
        async function fetchDoctor(bi) {
            try {
                // Simulação de dados - substituir pela chamada real à API
                const doctors = {
                    '123456789LA123': {
                        nome: 'João',
                        sobrenome: 'Silva',
                        telefone: '+258 84 123 4567',
                        bi: '123456789LA123',
                        email: 'joao.silva@hospital.mz',
                        especialidade: 'Cardiologia',
                        numero_licenca: 'CRM-MZ-12345',
                        senha: 'senha123'
                    }
                };

                return doctors[bi] || null;
            } catch (error) {
                showNotification('Erro ao carregar médico.', 'error');
                console.error(error);
                return null;
            }
        }

        // Form validation
        function validateForm() {
            let isValid = true;

            // First name validation
            const firstnameInput = document.getElementById('doctor-firstname');
            const firstnameError = document.getElementById('firstname-error');
            if (!firstnameInput.value.trim()) {
                firstnameInput.classList.add('error');
                firstnameError.style.display = 'block';
                isValid = false;
            } else {
                firstnameInput.classList.remove('error');
                firstnameError.style.display = 'none';
            }

            // Last name validation
            const lastnameInput = document.getElementById('doctor-lastname');
            const lastnameError = document.getElementById('lastname-error');
            if (!lastnameInput.value.trim()) {
                lastnameInput.classList.add('error');
                lastnameError.style.display = 'block';
                isValid = false;
            } else {
                lastnameInput.classList.remove('error');
                lastnameError.style.display = 'none';
            }

            // Phone validation
            const phoneInput = document.getElementById('doctor-phone');
            const phoneError = document.getElementById('phone-error');
            const phoneRegex = /^[0-9+\-\s()]{8,}$/;
            if (!phoneInput.value.trim() || !phoneRegex.test(phoneInput.value)) {
                phoneInput.classList.add('error');
                phoneError.style.display = 'block';
                isValid = false;
            } else {
                phoneInput.classList.remove('error');
                phoneError.style.display = 'none';
            }

            // BI validation
            const biInput = document.getElementById('doctor-bi');
            const biError = document.getElementById('bi-error');
            if (!biInput.value.trim()) {
                biInput.classList.add('error');
                biError.style.display = 'block';
                isValid = false;
            } else {
                biInput.classList.remove('error');
                biError.style.display = 'none';
            }

            // Email validation
            const emailInput = document.getElementById('doctor-email');
            const emailError = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value)) {
                emailInput.classList.add('error');
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailInput.classList.remove('error');
                emailError.style.display = 'none';
            }

            // Specialty validation
            const specialtyInput = document.getElementById('doctor-specialty');
            const specialtyError = document.getElementById('specialty-error');
            if (!specialtyInput.value) {
                specialtyInput.classList.add('error');
                specialtyError.style.display = 'block';
                isValid = false;
            } else {
                specialtyInput.classList.remove('error');
                specialtyError.style.display = 'none';
            }

            // License validation
            const licenseInput = document.getElementById('doctor-license');
            const licenseError = document.getElementById('license-error');
            if (!licenseInput.value.trim()) {
                licenseInput.classList.add('error');
                licenseError.style.display = 'block';
                isValid = false;
            } else {
                licenseInput.classList.remove('error');
                licenseError.style.display = 'none';
            }

            // Password validation
            const passwordInput = document.getElementById('doctor-password');
            const passwordError = document.getElementById('password-error');
            if (!passwordInput.value || passwordInput.value.length < 6) {
                passwordInput.classList.add('error');
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordInput.classList.remove('error');
                passwordError.style.display = 'none';
            }

            return isValid;
        }

        // Save or update doctor
        async function saveDoctor() {
            if (!validateForm()) {
                showNotification('Corrija os erros no formulário.', 'error');
                return;
            }

            const doctor = {
                nome: document.getElementById('doctor-firstname').value.trim(),
                sobrenome: document.getElementById('doctor-lastname').value.trim(),
                telefone: document.getElementById('doctor-phone').value.trim(),
                bi: document.getElementById('doctor-bi').value.trim(),
                email: document.getElementById('doctor-email').value.trim(),
                especialidade: document.getElementById('doctor-specialty').value,
                numero_licenca: document.getElementById('doctor-license').value.trim(),
                senha: document.getElementById('doctor-password').value
            };

            const isEditMode = document.getElementById('doctor-bi').hasAttribute('readonly');
            const endpoint = isEditMode ?
                '<?php echo site_url('Api/update_doctor'); ?>' :
                '<?php echo site_url('Api/create_doctor'); ?>';

            try {
                // Simulação de salvamento - substituir pela chamada real à API
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Sucesso
                const action = isEditMode ? 'atualizado' : 'cadastrado';
                showNotification(`Médico ${action} com sucesso!`, 'success');

                setTimeout(() => {
                    window.location.href = '<?php echo site_url('admin/medicos'); ?>';
                }, 1500);

            } catch (error) {
                showNotification('Erro de conexão.', 'error');
                console.error(error);
            }
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', async function() {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const saveBtn = document.getElementById('save-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            const passwordToggle = document.getElementById('password-toggle');
            const passwordInput = document.getElementById('doctor-password');
            const formTitle = document.getElementById('form-title');

            // Check for edit mode
            const bi = getQueryParam('bi');
            if (bi) {
                formTitle.textContent = 'Editar Médico';
                const doctor = await fetchDoctor(bi);
                if (doctor) {
                    document.getElementById('doctor-firstname').value = doctor.nome || '';
                    document.getElementById('doctor-lastname').value = doctor.sobrenome || '';
                    document.getElementById('doctor-phone').value = doctor.telefone || '';
                    document.getElementById('doctor-bi').value = doctor.bi || '';
                    document.getElementById('doctor-bi').setAttribute('readonly', 'true');
                    document.getElementById('doctor-email').value = doctor.email || '';
                    document.getElementById('doctor-specialty').value = doctor.especialidade || '';
                    document.getElementById('doctor-license').value = doctor.numero_licenca || '';
                    // Não preenchemos a senha por segurança
                }
            } else {
                formTitle.textContent = 'Cadastrar Novo Médico';
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

            // Password toggle functionality
            passwordToggle.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                passwordToggle.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // Form events
            saveBtn.addEventListener('click', saveDoctor);
            cancelBtn.addEventListener('click', () => {
                window.location.href = '<?php echo site_url('admin/medicos'); ?>';
            });

            // Real-time validation
            const inputs = [
                document.getElementById('doctor-firstname'),
                document.getElementById('doctor-lastname'),
                document.getElementById('doctor-phone'),
                document.getElementById('doctor-bi'),
                document.getElementById('doctor-email'),
                document.getElementById('doctor-specialty'),
                document.getElementById('doctor-license'),
                document.getElementById('doctor-password')
            ];

            inputs.forEach(input => {
                input.addEventListener('blur', validateForm);
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                    const errorElement = document.getElementById(`${this.id}-error`);
                    if (errorElement) errorElement.style.display = 'none';
                });
            });

            // Logout
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('auth/logout'); ?>';
                    }, 1000);
                });
            }
        });
    </script>
</body>
</html>