<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Secretário - Hospital Matlhovele</title>
    <meta name="description" content="Página de configurações para secretários do Hospital Público de Matlhovele">
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

        /* ESTILOS ESPECÍFICOS PARA CONFIGURAÇÕES DO SECRETÁRIO */
        .settings-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .settings-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
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

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            min-height: 100px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox {
            width: 1rem;
            height: 1rem;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
        }

        .action-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
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

        .action-btn.warning {
            background-color: #f59e0b;
        }

        .action-btn.warning:hover {
            background-color: #d97706;
        }

        .action-btn.danger {
            background-color: #ef4444;
        }

        .action-btn.danger:hover {
            background-color: #dc2626;
        }

        .action-btn.secondary {
            background-color: #6b7280;
        }

        .action-btn.secondary:hover {
            background-color: #4b5563;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .permission-card {
            background-color: #f8fafc;
            border-radius: 0.5rem;
            padding: 1.5rem;
            border-left: 4px solid #3b82f6;
            margin-bottom: 1rem;
        }

        .permission-card h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .permission-card p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #10b981;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        /* CORREÇÃO DO FOOTER/SISTEMA INFO */
        .system-info {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-top: 2rem;
            border-top: 3px solid #e5e7eb;
        }

        .system-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 0.375rem;
            border-left: 3px solid #3b82f6;
        }

        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .support-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 1.5rem;
            text-align: center;
        }

        .support-section p {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }

            .system-info-grid {
                grid-template-columns: 1fr;
            }
        }

        .tab-container {
            margin-bottom: 2rem;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-button:hover {
            color: #374151;
        }

        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .permission-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .permission-badge.active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .permission-badge.inactive {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .session-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 1rem;
            background-color: #f8fafc;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .session-info {
            flex: 1;
        }

        .session-device {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .session-details {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .session-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-current {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-other {
            background-color: #fef3c7;
            color: #92400e;
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
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Secretário</h2>
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
                    <a href="<?php echo site_url('secretario'); ?>" class="block text-gray-700">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="<?php echo site_url('secretario/agendamentos'); ?>" class="block text-gray-700">
                        <i class="fas fa-calendar-check"></i>
                        <span class="sidebar-text">Agendamentos</span>
                    </a>
                    <a href="<?php echo site_url('secretario/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('secretario/medicos'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-md"></i>
                        <span class="sidebar-text">Médicos</span>
                    </a>
                    <a href="<?php echo site_url('secretario/relatorios'); ?>" class="block text-gray-700">
                        <i class="fas fa-chart-bar"></i>
                        <span class="sidebar-text">Relatórios</span>
                    </a>
                    <a href="<?php echo site_url('secretario/configuracoes'); ?>" class="block text-gray-700 active">
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
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm">Secretário</p>
                        <p class="text-xs text-blue-200"><?php echo $nome_secretario ?? 'Nome do Secretário'; ?></p>
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
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Minhas Configurações</h2>
                            <p class="text-gray-600">Gerencie suas preferências pessoais e configurações de trabalho</p>
                        </div>
                        <button onclick="salvarTodasConfiguracoes()" class="action-btn success flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Salvar Configurações
                        </button>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="tab-container">
                    <div class="tab-buttons">
                        <button class="tab-button active" data-tab="perfil">Meu Perfil</button>
                        <button class="tab-button" data-tab="preferencias">Preferências</button>
                        <button class="tab-button" data-tab="notificacoes">Notificações</button>
                        <button class="tab-button" data-tab="seguranca">Segurança</button>
                    </div>

                    <!-- Tab: Meu Perfil -->
                    <div class="tab-content active" id="tab-perfil">
                        <div class="settings-grid">
                            <!-- Informações Pessoais -->
                            <div class="settings-section">
                                <h3>Informações Pessoais</h3>
                                
                                <div class="flex flex-col items-center mb-6">
                                    <img src="/api/placeholder/120/120" alt="Foto de perfil" class="profile-picture">
                                    <div class="flex gap-2">
                                        <button class="action-btn secondary text-sm">
                                            <i class="fas fa-camera mr-1"></i>Alterar Foto
                                        </button>
                                        <button class="action-btn danger text-sm">
                                            <i class="fas fa-trash mr-1"></i>Remover
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-input" id="user-name" value="<?php echo $nome_secretario ?? 'Nome do Secretário'; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" id="user-email" value="secretario@hospitalmatlhovele.mz">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Telefone</label>
                                    <input type="tel" class="form-input" id="user-phone" value="+258 84 123 4567">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Departamento</label>
                                    <input type="text" class="form-input" id="user-department" value="Secretaria" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Cargo</label>
                                    <input type="text" class="form-input" id="user-role" value="Secretário" readonly>
                                </div>
                            </div>

                            <!-- Minhas Permissões -->
                            <div class="settings-section">
                                <h3>Minhas Permissões</h3>
                                <p class="text-gray-600 mb-4">Permissões atribuídas ao seu perfil de secretário</p>
                                
                                <div class="permission-card">
                                    <h4>Gestão de Agendamentos</h4>
                                    <p>Agendar, alterar e cancelar consultas</p>
                                    <span class="permission-badge active">Ativo</span>
                                </div>
                                
                                <div class="permission-card">
                                    <h4>Gestão de Pacientes</h4>
                                    <p>Cadastrar, editar e visualizar pacientes</p>
                                    <span class="permission-badge active">Ativo</span>
                                </div>
                                
                                <div class="permission-card">
                                    <h4>Visualização de Médicos</h4>
                                    <p>Consultar informações dos médicos</p>
                                    <span class="permission-badge active">Ativo</span>
                                </div>
                                
                                <div class="permission-card">
                                    <h4>Relatórios Básicos</h4>
                                    <p>Gerar relatórios de agendamentos</p>
                                    <span class="permission-badge active">Ativo</span>
                                </div>
                                
                                <div class="permission-card">
                                    <h4>Configurações do Sistema</h4>
                                    <p>Acesso limitado às configurações</p>
                                    <span class="permission-badge active">Ativo</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Preferências -->
                    <div class="tab-content" id="tab-preferencias">
                        <div class="settings-grid">
                            <!-- Preferências de Trabalho -->
                            <div class="settings-section">
                                <h3>Preferências de Trabalho</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">Página Inicial Padrão</label>
                                    <select class="form-select" id="default-homepage">
                                        <option value="dashboard">Dashboard</option>
                                        <option value="agendamentos" selected>Agendamentos</option>
                                        <option value="pacientes">Pacientes</option>
                                        <option value="medicos">Médicos</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Itens por Página</label>
                                    <select class="form-select" id="items-per-page">
                                        <option value="10">10 itens</option>
                                        <option value="25" selected>25 itens</option>
                                        <option value="50">50 itens</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Formato de Data Preferido</label>
                                    <select class="form-select" id="date-format">
                                        <option value="dd/mm/yyyy" selected>DD/MM/AAAA</option>
                                        <option value="mm/dd/yyyy">MM/DD/AAAA</option>
                                        <option value="yyyy-mm-dd">AAAA-MM-DD</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Formato de Hora</label>
                                    <select class="form-select" id="time-format">
                                        <option value="24h" selected>24 horas (14:30)</option>
                                        <option value="12h">12 horas (2:30 PM)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Preferências de Visualização -->
                            <div class="settings-section">
                                <h3>Preferências de Visualização</h3>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="compact-view" checked>
                                        Modo de visualização compacta
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="show-avatars" checked>
                                        Mostrar fotos de perfil
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="high-contrast">
                                        Alto contraste
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="large-font">
                                        Texto grande
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Tema de Cores</label>
                                    <select class="form-select" id="color-theme">
                                        <option value="light" selected>Claro</option>
                                        <option value="dark">Escuro</option>
                                        <option value="auto">Automático</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Notificações -->
                    <div class="tab-content" id="tab-notificacoes">
                        <div class="settings-grid">
                            <!-- Notificações do Sistema -->
                            <div class="settings-section">
                                <h3>Notificações do Sistema</h3>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="notify-new-appointments" checked>
                                        Novos agendamentos
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="notify-cancellations" checked>
                                        Cancelamentos
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="notify-patient-updates">
                                        Atualizações de pacientes
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="notify-schedule-changes" checked>
                                        Alterações de horário
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="notify-system-maintenance">
                                        Manutenção do sistema
                                    </label>
                                </div>
                            </div>

                            <!-- Preferências de Notificação -->
                            <div class="settings-section">
                                <h3>Preferências de Notificação</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">Método de Notificação</label>
                                    <select class="form-select" id="notification-method">
                                        <option value="both" selected>Email e Notificação do Sistema</option>
                                        <option value="email">Apenas Email</option>
                                        <option value="system">Apenas Sistema</option>
                                        <option value="none">Desativar Todas</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Frequência de Resumo</label>
                                    <select class="form-select" id="summary-frequency">
                                        <option value="never">Nunca</option>
                                        <option value="daily" selected>Diariamente</option>
                                        <option value="weekly">Semanalmente</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Horário do Resumo Diário</label>
                                    <input type="time" class="form-input" id="daily-summary-time" value="08:00">
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="quiet-hours">
                                        Ativar horas silenciosas
                                    </label>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 mt-2" id="quiet-hours-times" style="display: none;">
                                    <input type="time" class="form-input" id="quiet-start" value="22:00">
                                    <input type="time" class="form-input" id="quiet-end" value="07:00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Segurança -->
                    <div class="tab-content" id="tab-seguranca">
                        <div class="settings-grid">
                            <!-- Segurança da Conta -->
                            <div class="settings-section">
                                <h3>Segurança da Conta</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">Senha Atual</label>
                                    <input type="password" class="form-input" id="current-password">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Nova Senha</label>
                                    <input type="password" class="form-input" id="new-password">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Confirmar Nova Senha</label>
                                    <input type="password" class="form-input" id="confirm-password">
                                </div>
                                
                                <button onclick="alterarSenha()" class="action-btn">Alterar Senha</button>
                                
                                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <h4 class="font-semibold text-yellow-800 mb-2">Dicas de Segurança</h4>
                                    <ul class="text-sm text-yellow-700 list-disc list-inside space-y-1">
                                        <li>Use uma senha com pelo menos 8 caracteres</li>
                                        <li>Inclua letras maiúsculas, minúsculas, números e símbolos</li>
                                        <li>Não use informações pessoais na senha</li>
                                        <li>Altere sua senha regularmente</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Sessão e Acesso -->
                            <div class="settings-section">
                                <h3>Sessão e Acesso</h3>
                                
                                <div class="form-group">
                                    <label class="form-label">Tempo de Inatividade para Logout</label>
                                    <select class="form-select" id="session-timeout">
                                        <option value="15">15 minutos</option>
                                        <option value="30" selected>30 minutos</option>
                                        <option value="60">1 hora</option>
                                        <option value="120">2 horas</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="login-notifications" checked>
                                        Notificar logins suspeitos
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-group">
                                        <input type="checkbox" class="checkbox" id="logout-other-sessions">
                                        Terminar outras sessões ativas
                                    </label>
                                </div>
                                
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-700 mb-3">Sessões Ativas</h4>
                                    <div class="space-y-2">
                                        <div class="session-item">
                                            <div class="session-info">
                                                <div class="session-device">Chrome - Windows</div>
                                                <div class="session-details">Ativo agora • Maputo, MZ</div>
                                            </div>
                                            <span class="session-status status-current">Atual</span>
                                        </div>
                                        <div class="session-item">
                                            <div class="session-info">
                                                <div class="session-device">Firefox - Windows</div>
                                                <div class="session-details">2 horas atrás • Maputo, MZ</div>
                                            </div>
                                            <button class="action-btn danger text-sm">Terminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sistema Info Section - CORRIGIDA -->
                <div class="system-info">
                    <h3>Informações do Sistema</h3>
                    <div class="system-info-grid">
                        <div class="info-item">
                            <span class="info-label">Versão do Sistema</span>
                            <span class="info-value">v2.1.4</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Último Login</span>
                            <span class="info-value">15/01/2024 08:30</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">IP de Acesso</span>
                            <span class="info-value">192.168.1.100</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Navegador</span>
                            <span class="info-value">Chrome 120</span>
                        </div>
                    </div>
                    
                    <div class="support-section">
                        <p class="text-gray-600 mb-4">
                            Precisa de ajuda? Contacte o suporte técnico: 
                            <a href="mailto:suporte@hospitalmatlhovele.mz" class="text-blue-600 hover:text-blue-800">
                                suporte@hospitalmatlhovele.mz
                            </a>
                        </p>
                        <div class="flex justify-center gap-4">
                            <button class="action-btn secondary text-sm">
                                <i class="fas fa-question-circle mr-1"></i>Ajuda
                            </button>
                            <button class="action-btn secondary text-sm">
                                <i class="fas fa-book mr-1"></i>Documentação
                            </button>
                            <button class="action-btn secondary text-sm">
                                <i class="fas fa-bug mr-1"></i>Reportar Problema
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-700" id="confirmation-title">Confirmar Ação</h3>
                <button onclick="fecharModalConfirmacao()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-6" id="confirmation-message">Tem certeza que deseja prosseguir com esta ação?</p>
            <div class="flex gap-2 justify-end">
                <button onclick="fecharModalConfirmacao()" class="action-btn secondary">Cancelar</button>
                <button onclick="executarAcaoConfirmada()" class="action-btn danger" id="confirmation-action-btn">Confirmar</button>
            </div>
        </div>
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

        // Sistema de Tabs
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    button.classList.add('active');
                    const tabId = button.getAttribute('data-tab');
                    document.getElementById(`tab-${tabId}`).classList.add('active');
                });
            });
        }

        // Funções de Configuração
        function salvarTodasConfiguracoes() {
            // Simulação de salvamento
            showNotification('Todas as configurações foram salvas com sucesso!', 'success');
        }

        function alterarSenha() {
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                showNotification('Por favor, preencha todos os campos da senha.', 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                showNotification('As senhas não coincidem.', 'error');
                return;
            }

            if (newPassword.length < 8) {
                showNotification('A senha deve ter pelo menos 8 caracteres.', 'error');
                return;
            }

            // Simulação de alteração de senha
            showNotification('Senha alterada com sucesso!', 'success');
            
            // Limpar campos
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        }

        // Configurar eventos
        function setupEventListeners() {
            // Configurar tabs
            setupTabs();

            // Mostrar/ocultar horas silenciosas
            const quietHoursCheckbox = document.getElementById('quiet-hours');
            const quietHoursTimes = document.getElementById('quiet-hours-times');
            
            quietHoursCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    quietHoursTimes.style.display = 'grid';
                } else {
                    quietHoursTimes.style.display = 'none';
                }
            });

            // Inicializar estado das horas silenciosas
            if (!quietHoursCheckbox.checked) {
                quietHoursTimes.style.display = 'none';
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

            // Configurar funcionalidades
            setupEventListeners();

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