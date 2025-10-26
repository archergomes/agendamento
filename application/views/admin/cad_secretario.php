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

        .form-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
            .form-full-width {
                grid-column: 1 / -1;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input:disabled {
            background-color: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
            transition: all 0.2s;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* CORREÇÃO: Botões iguais aos da view de pacientes */
        .form-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-start;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .create-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .create-btn:hover {
            background-color: #059669;
        }

        .create-btn:disabled {
            background-color: #d1d5db;
            cursor: not-allowed;
        }

        .cancel-btn {
            background-color: #d1d5db;
            color: #374151;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .cancel-btn:hover {
            background-color: #9ca3af;
        }

        .required-field::after {
            content: " *";
            color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .loading {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* CORREÇÃO: Header centralizado igual ao de pacientes */
        .page-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .page-title {
            text-align: center;
        }

        .back-button {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
            }
            .back-button {
                position: static;
                transform: none;
                align-self: flex-end;
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
                    <a href="<?php echo site_url('admin/cad_secretario'); ?>" class="block text-gray-700 active">
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
                <!-- Header centralizado -->
                <div class="page-header">
                    <div class="page-title">
                        <h2 class="text-2xl font-semibold text-gray-800" id="form-title">Cadastrar Novo Secretário</h2>
                        <p class="text-gray-600 mt-2">Preencha os dados abaixo para cadastrar ou editar um secretário.</p>
                    </div>
                    <div class="back-button">
                        <a href="<?php echo site_url('admin/secretarios'); ?>" class="cancel-btn">
                            <i class="fas fa-arrow-left"></i>
                            Voltar para Lista
                        </a>
                    </div>
                </div>

                <!-- Secretary Form -->
                <div class="form-container">
                    <form id="secretary-form">
                        <div class="form-grid">
                            <!-- Nome Completo -->
                            <div class="form-group form-full-width">
                                <label for="secretary-name" class="form-label required-field">Nome Completo</label>
                                <input type="text" id="secretary-name" class="form-input" required>
                            </div>

                            <!-- Telefone -->
                            <div class="form-group">
                                <label for="secretary-phone" class="form-label required-field">Telefone</label>
                                <input type="tel" id="secretary-phone" class="form-input" required>
                            </div>

                            <!-- BI -->
                            <div class="form-group">
                                <label for="secretary-bi" class="form-label required-field">Número do BI</label>
                                <input type="text" id="secretary-bi" class="form-input" required>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="secretary-email" class="form-label">Email</label>
                                <input type="email" id="secretary-email" class="form-input">
                            </div>

                            <!-- Senha -->
                            <div class="form-group form-full-width" id="password-field">
                                <label for="secretary-password" class="form-label required-field">Senha</label>
                                <input type="password" id="secretary-password" class="form-input" required>
                            </div>
                        </div>

                        <!-- CORREÇÃO: Botões iguais aos da view de pacientes -->
                        <div class="form-actions">
                            <button type="submit" id="save-btn" class="create-btn">
                                <i class="fas fa-save"></i>
                                <span id="save-btn-text">Salvar Secretário</span>
                            </button>
                            <button type="button" id="cancel-btn" class="cancel-btn">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>
                        </div>
                    </form>
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
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) {
                console.error('Notification elements not found');
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

        // Fetch secretary by BI from database
        async function fetchSecretary(bi) {
            try {
                const response = await fetch(`<?php echo site_url('admin/get_secretary'); ?>?bi=${encodeURIComponent(bi)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Erro na requisição');
                
                const result = await response.json();
                console.log('Dados do secretário:', result);
                
                if (result.error) {
                    showNotification(result.error, 'error');
                    return null;
                }
                
                return result.data || null;
            } catch (error) {
                console.error('Erro ao carregar secretário:', error);
                showNotification('Erro ao carregar dados do secretário.', 'error');
                return null;
            }
        }

        // Save or update secretary
        async function saveSecretary(formData) {
            const isEditMode = formData.get('bi_disabled') === 'true';
            const saveBtn = document.getElementById('save-btn');
            const saveBtnText = document.getElementById('save-btn-text');
            const originalText = saveBtnText.textContent;

            try {
                // Show loading state
                saveBtn.disabled = true;
                saveBtnText.innerHTML = '<div class="loading"></div> Salvando...';

                const endpoint = isEditMode ? 
                    '<?php echo site_url('admin/update_secretary'); ?>' : 
                    '<?php echo site_url('admin/create_secretary'); ?>';
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const result = await response.json();
                console.log('Resposta do servidor:', result);

                if (result.error) {
                    showNotification(result.error, 'error');
                } else {
                    showNotification(result.success, 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('admin/secretarios'); ?>';
                    }, 1500);
                }
            } catch (error) {
                console.error('Erro ao salvar secretário:', error);
                showNotification(
                    isEditMode ? 'Erro ao atualizar secretário.' : 'Erro ao cadastrar secretário.', 
                    'error'
                );
            } finally {
                // Restore button state
                saveBtn.disabled = false;
                saveBtnText.textContent = originalText;
            }
        }

        // Validate form
        function validateForm(formData) {
            const requiredFields = ['name', 'phone', 'bi'];
            
            for (let field of requiredFields) {
                if (!formData.get(field)?.trim()) {
                    showNotification(`O campo ${field} é obrigatório.`, 'error');
                    return false;
                }
            }

            // Password is only required for new secretaries
            if (!formData.get('bi_disabled') && !formData.get('password')) {
                showNotification('A senha é obrigatória para novos secretários.', 'error');
                return false;
            }

            const email = formData.get('email');
            if (email && !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                showNotification('Por favor, insira um email válido.', 'error');
                return false;
            }

            return true;
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', async function () {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const secretaryForm = document.getElementById('secretary-form');
            const cancelBtn = document.getElementById('cancel-btn');
            const formTitle = document.getElementById('form-title');
            const passwordField = document.getElementById('password-field');

            // Check for edit mode
            const bi = getQueryParam('bi');
            let isEditMode = false;

            if (bi) {
                isEditMode = true;
                formTitle.textContent = 'Editar Secretário';
                document.getElementById('save-btn-text').textContent = 'Atualizar Secretário';
                
                console.log('Modo edição - BI:', bi);
                
                const secretary = await fetchSecretary(bi);
                if (secretary) {
                    console.log('Secretário carregado:', secretary);
                    document.getElementById('secretary-name').value = secretary.Nome_Completo || secretary.nome || '';
                    document.getElementById('secretary-phone').value = secretary.Telefone || secretary.phone || '';
                    document.getElementById('secretary-bi').value = secretary.ID_Secretario || secretary.bi || '';
                    document.getElementById('secretary-bi').setAttribute('readonly', 'true');
                    document.getElementById('secretary-email').value = secretary.Email || secretary.email || '';
                    
                    // Hide password field in edit mode
                    if (passwordField) {
                        passwordField.style.display = 'none';
                    }
                } else {
                    showNotification('Secretário não encontrado. Redirecionando...', 'error');
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('admin/secretarios'); ?>';
                    }, 2000);
                }
            } else {
                formTitle.textContent = 'Cadastrar Novo Secretário';
                document.getElementById('secretary-bi').removeAttribute('readonly');
                
                // Show password field for new secretaries
                if (passwordField) {
                    passwordField.style.display = 'block';
                }
            }

            // Notification close
            notificationClose.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Sidebar handlers
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
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });

            // Form submission
            secretaryForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData();
                formData.append('name', document.getElementById('secretary-name').value.trim());
                formData.append('phone', document.getElementById('secretary-phone').value.trim());
                formData.append('bi', document.getElementById('secretary-bi').value.trim());
                formData.append('email', document.getElementById('secretary-email').value.trim());
                formData.append('password', document.getElementById('secretary-password').value.trim());
                formData.append('bi_disabled', isEditMode.toString());

                if (validateForm(formData)) {
                    await saveSecretary(formData);
                }
            });

            // Cancel button
            cancelBtn.addEventListener('click', () => {
                window.location.href = '<?php echo site_url('admin/secretarios'); ?>';
            });

            // Logout
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

            // Responsive sidebar on resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>