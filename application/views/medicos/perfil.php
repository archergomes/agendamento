<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Médico - Hospital Matlhovele</title>
    <meta name="description" content="Perfil do médico no Hospital Público de Matlhovele">
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

        .profile-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
        }

        .profile-info h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .profile-info p {
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
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
            transition: border-color 0.2s, box-shadow 0.2s;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .tab-container {
            margin-bottom: 2rem;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .tab-button {
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab-button.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .tab-button:hover:not(.active) {
            color: #374151;
            background-color: #f9fafb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .password-strength {
            height: 4px;
            background-color: #e5e7eb;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .password-strength.weak .password-strength-bar {
            width: 33%;
            background-color: #ef4444;
        }

        .password-strength.medium .password-strength-bar {
            width: 66%;
            background-color: #f59e0b;
        }

        .password-strength.strong .password-strength-bar {
            width: 100%;
            background-color: #10b981;
        }

        .avatar-upload {
            position: relative;
            display: inline-block;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .file-input {
            display: none;
        }

        .two-factor-section {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .qrcode-container {
            text-align: center;
            margin: 1rem 0;
        }

        .backup-codes {
            background-color: #f9fafb;
            border-radius: 0.375rem;
            padding: 1rem;
            font-family: monospace;
            text-align: center;
        }

        .loading-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            border-radius: 0.5rem;
            z-index: 10;
        }

        .loading-overlay.show {
            display: flex;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .tab-buttons {
                flex-direction: column;
            }

            .tab-button {
                text-align: left;
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
                    <a href="<?php echo site_url('medico'); ?>" class="block text-gray-700">
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
                    <a href="<?php echo site_url('medico/perfil'); ?>" class="block text-gray-700 active">
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
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Meu Perfil</h2>
                    <p class="text-gray-600">Gerencie suas informações pessoais e preferências da conta.</p>
                </div>

                <!-- Stats Overview -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value" id="total-consultas">0</div>
                        <div class="stat-label">Consultas Realizadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="total-pacientes">0</div>
                        <div class="stat-label">Pacientes Atendidos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="avaliacao-media">0.0</div>
                        <div class="stat-label">Avaliação Média</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="anos-experiencia">0</div>
                        <div class="stat-label">Anos de Experiência</div>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-upload">
                            <img id="profile-avatar" src="<?php echo $avatar_url ?? 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=200&h=200&q=80'; ?>"
                                alt="Avatar do Médico" class="profile-avatar">
                            <button class="avatar-upload-btn" onclick="document.getElementById('avatar-input').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="avatar-input" class="file-input" accept="image/*" onchange="handleAvatarUpload(event)">
                        </div>
                        <div class="profile-info">
                            <h2 id="profile-name">Dr. <?php echo $medico_nome ?? 'Nome do Médico'; ?></h2>
                            <p id="profile-specialty"><?php echo $especialidade ?? 'Especialidade'; ?></p>
                            <p id="profile-email"><?php echo $email ?? 'email@hospital.gov.mz'; ?></p>
                            <p id="profile-phone"><?php echo $telefone ?? '+258 84 000 0000'; ?></p>
                            <span class="badge badge-success" id="profile-status">
                                <i class="fas fa-check-circle mr-1"></i>
                                Ativo
                            </span>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="tab-container">
                        <div class="tab-buttons">
                            <button class="tab-button active" data-tab="informacoes-pessoais">Informações Pessoais</button>
                            <button class="tab-button" data-tab="seguranca">Segurança</button>
                            <button class="tab-button" data-tab="preferencias">Preferências</button>
                            <button class="tab-button" data-tab="notificacoes">Notificações</button>
                        </div>

                        <!-- Informações Pessoais Tab -->
                        <div class="tab-content active" id="informacoes-pessoais">
                            <form id="personal-info-form">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Nome Completo</label>
                                        <input type="text" class="form-input" id="input-nome" value="<?php echo $medico_nome ?? ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">CRM</label>
                                        <input type="text" class="form-input" id="input-crm" value="<?php echo $crm ?? ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Especialidade</label>
                                        <select class="form-select" id="input-especialidade" required>
                                            <option value="">Selecione uma especialidade</option>
                                            <option value="Cardiologia" <?php echo ($especialidade ?? '') == 'Cardiologia' ? 'selected' : ''; ?>>Cardiologia</option>
                                            <option value="Dermatologia" <?php echo ($especialidade ?? '') == 'Dermatologia' ? 'selected' : ''; ?>>Dermatologia</option>
                                            <option value="Pediatria" <?php echo ($especialidade ?? '') == 'Pediatria' ? 'selected' : ''; ?>>Pediatria</option>
                                            <option value="Ortopedia" <?php echo ($especialidade ?? '') == 'Ortopedia' ? 'selected' : ''; ?>>Ortopedia</option>
                                            <option value="Ginecologia" <?php echo ($especialidade ?? '') == 'Ginecologia' ? 'selected' : ''; ?>>Ginecologia</option>
                                            <option value="Neurologia" <?php echo ($especialidade ?? '') == 'Neurologia' ? 'selected' : ''; ?>>Neurologia</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Telefone</label>
                                        <input type="tel" class="form-input" id="input-telefone" value="<?php echo $telefone ?? ''; ?>">
                                    </div>
                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-input" id="input-email" value="<?php echo $email ?? ''; ?>" required>
                                    </div>
                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Endereço</label>
                                        <input type="text" class="form-input" id="input-endereco" value="<?php echo $endereco ?? ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Data de Nascimento</label>
                                        <input type="date" class="form-input" id="input-nascimento" value="<?php echo $data_nascimento ?? ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Gênero</label>
                                        <select class="form-select" id="input-genero">
                                            <option value="">Selecione</option>
                                            <option value="M" <?php echo ($genero ?? '') == 'M' ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="F" <?php echo ($genero ?? '') == 'F' ? 'selected' : ''; ?>>Feminino</option>
                                            <option value="O" <?php echo ($genero ?? '') == 'O' ? 'selected' : ''; ?>>Outro</option>
                                        </select>
                                    </div>
                                    <div class="form-group md:col-span-2">
                                        <label class="form-label">Biografia</label>
                                        <textarea class="form-textarea" id="input-biografia" placeholder="Descreva sua experiência e especializações..."><?php echo $biografia ?? ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end mt-6">
                                    <button type="button" class="action-btn secondary" onclick="resetPersonalInfoForm()">Cancelar</button>
                                    <button type="submit" class="action-btn success">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>

                        <!-- Segurança Tab -->
                        <div class="tab-content" id="seguranca">
                            <form id="security-form">
                                <div class="space-y-4">
                                    <div class="form-group">
                                        <label class="form-label">Senha Atual</label>
                                        <input type="password" class="form-input" id="current-password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Nova Senha</label>
                                        <input type="password" class="form-input" id="new-password" required oninput="checkPasswordStrength(this.value)">
                                        <div class="password-strength" id="password-strength">
                                            <div class="password-strength-bar"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">A senha deve conter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirmar Nova Senha</label>
                                        <input type="password" class="form-input" id="confirm-password" required>
                                    </div>

                                    <!-- Two-Factor Authentication -->
                                    <div class="two-factor-section">
                                        <h4 class="font-semibold text-gray-800 mb-3">Autenticação de Dois Fatores</h4>
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <p class="text-sm text-gray-600">Proteja sua conta com verificação em duas etapas</p>
                                            </div>
                                            <button type="button" class="action-btn warning" onclick="toggleTwoFactor()" id="two-factor-btn">
                                                Ativar 2FA
                                            </button>
                                        </div>
                                        <div id="two-factor-setup" class="hidden">
                                            <div class="qrcode-container">
                                                <p class="text-sm text-gray-600 mb-2">Escaneie este código QR com seu aplicativo autenticador:</p>
                                                <div id="qrcode" class="bg-white p-4 inline-block">
                                                    <!-- QR Code será gerado aqui -->
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Código de Verificação</label>
                                                <input type="text" class="form-input" id="verification-code" placeholder="Digite o código do aplicativo">
                                            </div>
                                            <div class="backup-codes">
                                                <p class="text-sm font-semibold mb-2">Códigos de Backup (Guarde em local seguro):</p>
                                                <div id="backup-codes-list"></div>
                                            </div>
                                            <div class="flex gap-2 mt-4">
                                                <button type="button" class="action-btn secondary" onclick="cancelTwoFactorSetup()">Cancelar</button>
                                                <button type="button" class="action-btn success" onclick="verifyTwoFactor()">Verificar e Ativar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end mt-6">
                                    <button type="button" class="action-btn secondary" onclick="resetSecurityForm()">Cancelar</button>
                                    <button type="submit" class="action-btn success">Alterar Senha</button>
                                </div>
                            </form>
                        </div>

                        <!-- Preferências Tab -->
                        <div class="tab-content" id="preferencias">
                            <form id="preferences-form">
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Idioma e Região</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="form-group">
                                                <label class="form-label">Idioma</label>
                                                <select class="form-select" id="pref-idioma">
                                                    <option value="pt" selected>Português</option>
                                                    <option value="en">English</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Fuso Horário</label>
                                                <select class="form-select" id="pref-fuso-horario">
                                                    <option value="Africa/Maputo" selected>Maputo (UTC+2)</option>
                                                    <option value="UTC">UTC</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Formato de Data e Hora</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="form-group">
                                                <label class="form-label">Formato de Data</label>
                                                <select class="form-select" id="pref-formato-data">
                                                    <option value="dd/mm/yyyy" selected>DD/MM/AAAA</option>
                                                    <option value="mm/dd/yyyy">MM/DD/AAAA</option>
                                                    <option value="yyyy-mm-dd">AAAA-MM-DD</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Formato de Hora</label>
                                                <select class="form-select" id="pref-formato-hora">
                                                    <option value="24" selected>24 horas</option>
                                                    <option value="12">12 horas (AM/PM)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Preferências de Trabalho</h4>
                                        <div class="space-y-3">
                                            <label class="flex items-center">
                                                <input type="checkbox" class="mr-2" id="pref-consulta-online" <?php echo ($pref_consulta_online ?? false) ? 'checked' : ''; ?>>
                                                <span>Permitir consultas online</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" class="mr-2" id="pref-notificacao-email" <?php echo ($pref_notificacao_email ?? true) ? 'checked' : ''; ?>>
                                                <span>Receber notificações por email</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" class="mr-2" id="pref-lembrete-consulta" <?php echo ($pref_lembrete_consulta ?? true) ? 'checked' : ''; ?>>
                                                <span>Lembretes de consultas</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end mt-6">
                                    <button type="button" class="action-btn secondary" onclick="resetPreferencesForm()">Cancelar</button>
                                    <button type="submit" class="action-btn success">Salvar Preferências</button>
                                </div>
                            </form>
                        </div>

                        <!-- Notificações Tab -->
                        <div class="tab-content" id="notificacoes">
                            <form id="notifications-form">
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Notificações por Email</h4>
                                        <div class="space-y-3">
                                            <label class="flex items-center justify-between">
                                                <span>Novos agendamentos</span>
                                                <input type="checkbox" class="mr-2" id="notif-novo-agendamento" <?php echo ($notif_novo_agendamento ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                            <label class="flex items-center justify-between">
                                                <span>Cancelamentos de consultas</span>
                                                <input type="checkbox" class="mr-2" id="notif-cancelamento" <?php echo ($notif_cancelamento ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                            <label class="flex items-center justify-between">
                                                <span>Lembretes de consultas</span>
                                                <input type="checkbox" class="mr-2" id="notif-lembrete" <?php echo ($notif_lembrete ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                            <label class="flex items-center justify-between">
                                                <span>Atualizações do sistema</span>
                                                <input type="checkbox" class="mr-2" id="notif-atualizacao" <?php echo ($notif_atualizacao ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Notificações no Sistema</h4>
                                        <div class="space-y-3">
                                            <label class="flex items-center justify-between">
                                                <span>Novas mensagens</span>
                                                <input type="checkbox" class="mr-2" id="notif-nova-mensagem" <?php echo ($notif_nova_mensagem ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                            <label class="flex items-center justify-between">
                                                <span>Resultados de exames</span>
                                                <input type="checkbox" class="mr-2" id="notif-resultado-exame" <?php echo ($notif_resultado_exame ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                            <label class="flex items-center justify-between">
                                                <span>Alertas importantes</span>
                                                <input type="checkbox" class="mr-2" id="notif-alerta" <?php echo ($notif_alerta ?? true) ? 'checked' : ''; ?>>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Frequência de Notificações</h4>
                                        <div class="form-group">
                                            <select class="form-select" id="notif-frequencia">
                                                <option value="imediato" selected>Imediatamente</option>
                                                <option value="diario">Resumo Diário</option>
                                                <option value="semanal">Resumo Semanal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end mt-6">
                                    <button type="button" class="action-btn secondary" onclick="resetNotificationsForm()">Cancelar</button>
                                    <button type="submit" class="action-btn success">Salvar Configurações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2" id="confirmation-title">Confirmação</h3>
                <p class="text-gray-600 mb-6" id="confirmation-message">Tem certeza que deseja realizar esta ação?</p>
                <div class="flex gap-2 justify-center">
                    <button class="action-btn secondary" onclick="closeConfirmationModal()">Cancelar</button>
                    <button class="action-btn danger" id="confirmation-confirm">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dados do perfil
        let profileData = <?php echo json_encode([
            'nome' => $medico_nome ?? '',
            'crm' => $crm ?? '',
            'especialidade' => $especialidade ?? '',
            'email' => $email ?? '',
            'telefone' => $telefone ?? '',
            'endereco' => $endereco ?? '',
            'data_nascimento' => $data_nascimento ?? '',
            'genero' => $genero ?? '',
            'biografia' => $biografia ?? '',
            'avatar_url' => $avatar_url ?? '',
            'stats' => [
                'consultas' => $total_consultas ?? 0,
                'pacientes' => $total_pacientes ?? 0,
                'avaliacao' => $avaliacao_media ?? 0.0,
                'experiencia' => $anos_experiencia ?? 0
            ]
        ]); ?>;

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

        // Carregar estatísticas
        function loadStats() {
            document.getElementById('total-consultas').textContent = profileData.stats.consultas;
            document.getElementById('total-pacientes').textContent = profileData.stats.pacientes;
            document.getElementById('avaliacao-media').textContent = profileData.stats.avaliacao.toFixed(1);
            document.getElementById('anos-experiencia').textContent = profileData.stats.experiencia;
        }

        // Sistema de abas
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to current button and content
                    button.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        }

        // Upload de avatar
        function handleAvatarUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validar tipo de arquivo
            if (!file.type.startsWith('image/')) {
                showNotification('Por favor, selecione uma imagem válida.', 'error');
                return;
            }

            // Validar tamanho do arquivo (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showNotification('A imagem deve ter no máximo 5MB.', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-avatar').src = e.target.result;
                // Aqui você enviaria a imagem para o servidor
                showNotification('Avatar atualizado com sucesso!', 'success');
            };
            reader.readAsDataURL(file);
        }

        // Verificar força da senha
        function checkPasswordStrength(password) {
            const strengthBar = document.querySelector('.password-strength-bar');
            const strengthContainer = document.getElementById('password-strength');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthContainer.className = 'password-strength';
            if (strength <= 2) {
                strengthContainer.classList.add('weak');
            } else if (strength <= 4) {
                strengthContainer.classList.add('medium');
            } else {
                strengthContainer.classList.add('strong');
            }
        }

        // Two-Factor Authentication
        function toggleTwoFactor() {
            const setupDiv = document.getElementById('two-factor-setup');
            const button = document.getElementById('two-factor-btn');
            
            if (setupDiv.classList.contains('hidden')) {
                setupDiv.classList.remove('hidden');
                button.textContent = 'Cancelar';
                // Aqui você geraria o QR code e códigos de backup
                generateTwoFactorSetup();
            } else {
                setupDiv.classList.add('hidden');
                button.textContent = 'Ativar 2FA';
            }
        }

        function generateTwoFactorSetup() {
            // Simulação de geração de QR code e códigos de backup
            const backupCodes = Array.from({length: 8}, () => 
                Math.random().toString(36).substr(2, 8).toUpperCase()
            );
            
            document.getElementById('backup-codes-list').innerHTML = 
                backupCodes.map(code => `<div>${code}</div>`).join('');
            
            // Em uma implementação real, você geraria um QR code real aqui
            document.getElementById('qrcode').innerHTML = 
                '<div class="text-center p-4 border-2 border-dashed border-gray-300">' +
                '<p class="text-sm text-gray-500">QR Code seria gerado aqui</p>' +
                '</div>';
        }

        function verifyTwoFactor() {
            const code = document.getElementById('verification-code').value;
            if (!code) {
                showNotification('Por favor, digite o código de verificação.', 'error');
                return;
            }
            
            // Simulação de verificação
            showNotification('Autenticação de dois fatores ativada com sucesso!', 'success');
            document.getElementById('two-factor-setup').classList.add('hidden');
            document.getElementById('two-factor-btn').textContent = 'Desativar 2FA';
        }

        function cancelTwoFactorSetup() {
            document.getElementById('two-factor-setup').classList.add('hidden');
            document.getElementById('two-factor-btn').textContent = 'Ativar 2FA';
        }

        // Form handlers
        document.getElementById('personal-info-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Simulação de salvamento
            showNotification('Informações pessoais atualizadas com sucesso!', 'success');
        });

        document.getElementById('security-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (newPassword !== confirmPassword) {
                showNotification('As senhas não coincidem.', 'error');
                return;
            }
            
            // Simulação de alteração de senha
            showNotification('Senha alterada com sucesso!', 'success');
            this.reset();
            document.getElementById('password-strength').className = 'password-strength';
        });

        document.getElementById('preferences-form').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Preferências salvas com sucesso!', 'success');
        });

        document.getElementById('notifications-form').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Configurações de notificação salvas!', 'success');
        });

        // Reset forms
        function resetPersonalInfoForm() {
            document.getElementById('personal-info-form').reset();
        }

        function resetSecurityForm() {
            document.getElementById('security-form').reset();
            document.getElementById('password-strength').className = 'password-strength';
        }

        function resetPreferencesForm() {
            document.getElementById('preferences-form').reset();
        }

        function resetNotificationsForm() {
            document.getElementById('notifications-form').reset();
        }

        // Modal functions
        function showConfirmationModal(title, message, onConfirm) {
            document.getElementById('confirmation-title').textContent = title;
            document.getElementById('confirmation-message').textContent = message;
            document.getElementById('confirmation-confirm').onclick = onConfirm;
            document.getElementById('confirmation-modal').classList.add('show');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmation-modal').classList.remove('show');
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
            loadStats();
            setupTabs();

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showConfirmationModal(
                        'Confirmar Saída',
                        'Tem certeza que deseja sair do sistema?',
                        () => {
                            showNotification('Sessão encerrada com sucesso!', 'success');
                            setTimeout(() => {
                                window.location.href = '<?php echo site_url('login'); ?>';
                            }, 1000);
                        }
                    );
                });
            }
        });
    </script>
</body>

</html>