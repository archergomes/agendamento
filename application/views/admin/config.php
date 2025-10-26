<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Configurações do sistema no Hospital Público de Matlhovele">
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

        .config-section {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .config-form {
            display: grid;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-input, .form-select, .form-textarea {
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .save-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            align-self: flex-start;
        }

        .save-btn:hover {
            background-color: #059669;
        }

        .save-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            background: none;
            color: #6b7280;
            cursor: pointer;
            transition: color 0.2s;
        }

        .tab-btn.active {
            color: #3b82f6;
            border-bottom: 2px solid #3b82f6;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
            font-style: italic;
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
                    <a href="<?php echo site_url('admin/cad_secretario'); ?>" class="block text-gray-700">
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
                    <a href="<?php echo site_url('admin/configuracoes'); ?>" class="block text-gray-700 active">
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
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Configurações do Sistema</h2>

                <!-- Tabs -->
                <div class="tabs mb-6">
                    <button class="tab-btn active" data-tab="hospital">Informações do Hospital</button>
                    <button class="tab-btn" data-tab="users">Gestão de Usuários</button>
                    <button class="tab-btn" data-tab="system">Configurações do Sistema</button>
                </div>

                <!-- Hospital Info Tab -->
                <div id="hospital" class="tab-content active">
                    <div class="config-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Hospital</h3>
                        <form id="hospital-form" class="config-form">
                            <div class="form-group">
                                <label for="hospital-name" class="form-label">Nome do Hospital</label>
                                <input type="text" id="hospital-name" class="form-input" value="<?php echo htmlspecialchars($config['hospital_name'] ?? 'Hospital Público de Matlhovele'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hospital-address" class="form-label">Endereço</label>
                                <input type="text" id="hospital-address" class="form-input" value="<?php echo htmlspecialchars($config['hospital_address'] ?? 'Av. 25 de Setembro, Maputo'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hospital-phone" class="form-label">Telefone</label>
                                <input type="tel" id="hospital-phone" class="form-input" value="<?php echo htmlspecialchars($config['hospital_phone'] ?? '+258 84 123 4567'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hospital-email" class="form-label">E-mail</label>
                                <input type="email" id="hospital-email" class="form-input" value="<?php echo htmlspecialchars($config['hospital_email'] ?? 'contato@hospitalmatlhovele.mz'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="hospital-description" class="form-label">Descrição</label>
                                <textarea id="hospital-description" class="form-textarea"><?php echo htmlspecialchars($config['hospital_description'] ?? 'Hospital público dedicado ao cuidado de qualidade em Maputo.'); ?></textarea>
                            </div>
                            <button type="submit" class="save-btn">
                                <i class="fas fa-save mr-2"></i> Salvar Configurações
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Users Management Tab -->
                <div id="users" class="tab-content">
                    <div class="config-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestão de Usuários</h3>
                        <div class="flex justify-between items-center mb-4">
                            <input type="text" id="user-search" class="form-input" placeholder="Pesquisar usuários..." style="max-width: 300px;">
                            <button id="add-user-btn" class="save-btn">
                                <i class="fas fa-plus mr-2"></i> Adicionar Usuário
                            </button>
                        </div>
                        <div class="table-container">
                            <table class="table-auto w-full text-left">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Nome</th>
                                        <th class="px-4 py-2">E-mail</th>
                                        <th class="px-4 py-2">Tipo</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table">
                                    <?php if (empty($users ?? [])): ?>
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 empty-state">
                                                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                                <p>Nenhum usuário encontrado. <button class="text-blue-600 hover:underline" id="add-user-btn-inline">Adicione o primeiro!</button></p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users ?? [] as $user): ?>
                                            <tr class="border-t">
                                                <td class="px-4 py-2"><?php echo htmlspecialchars($user['nome'] ?? ''); ?></td>
                                                <td class="px-4 py-2"><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 rounded-full text-xs <?php echo $user['tipo'] === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                        <?php echo ucfirst($user['tipo'] ?? ''); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 rounded-full text-xs <?php echo $user['status'] === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                        <?php echo ucfirst($user['status'] ?? ''); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <button class="action-btn edit-btn mr-2" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="action-btn delete-btn" title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- System Settings Tab -->
                <div id="system" class="tab-content">
                    <div class="config-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Configurações do Sistema</h3>
                        <form id="system-form" class="config-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="default-duration" class="form-label">Duração Padrão da Consulta (minutos)</label>
                                    <input type="number" id="default-duration" class="form-input" value="<?php echo htmlspecialchars($config['default_duration'] ?? '45'); ?>" min="15" max="120" required>
                                </div>
                                <div class="form-group">
                                    <label for="max-appointments" class="form-label">Máximo de Agendamentos por Dia por Médico</label>
                                    <input type="number" id="max-appointments" class="form-input" value="<?php echo htmlspecialchars($config['max_appointments'] ?? '20'); ?>" min="1" max="50" required>
                                </div>
                                <div class="form-group">
                                    <label for="email-notifications" class="form-label">Notificações por E-mail</label>
                                    <select id="email-notifications" class="form-select">
                                        <option value="1" <?php echo ($config['email_notifications'] ?? 1) ? 'selected' : ''; ?>>Ativadas</option>
                                        <option value="0" <?php echo !($config['email_notifications'] ?? 1) ? 'selected' : ''; ?>>Desativadas</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sms-notifications" class="form-label">Notificações por SMS</label>
                                    <select id="sms-notifications" class="form-select">
                                        <option value="1" <?php echo ($config['sms_notifications'] ?? 0) ? 'selected' : ''; ?>>Ativadas</option>
                                        <option value="0" <?php echo !($config['sms_notifications'] ?? 0) ? 'selected' : ''; ?>>Desativadas</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="save-btn">
                                <i class="fas fa-save mr-2"></i> Salvar Configurações
                            </button>
                        </form>
                    </div>
                </div>

                <?php if (empty($config ?? [])): ?>
                    <div class="empty-state">
                        <i class="fas fa-cog text-4xl text-gray-400 mb-4"></i>
                        <p>Nenhuma configuração encontrada. Comece configurando o hospital!</p>
                    </div>
                <?php endif; ?>
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
                            <li><a href="<?php echo site_url('sobre'); ?>" class="text-gray-300 hover:text-white">Sobre Nós</a></li>
                            <li><a href="<?php echo site_url('servicos'); ?>" class="text-gray-300 hover:text-white">Serviços</a></li>
                            <li><a href="<?php echo site_url('contactos'); ?>" class="text-gray-300 hover:text-white">Contactos</a></li>
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
        // Config data from PHP
        let config = <?php echo json_encode($config ?? []); ?>;
        let users = <?php echo json_encode($users ?? []); ?>;

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            if (!notification || !messageEl) return;
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => notification.classList.remove('show'), 5000);
        }

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });

        // Hospital form submit
        document.getElementById('hospital-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                hospital_name: document.getElementById('hospital-name').value,
                hospital_address: document.getElementById('hospital-address').value,
                hospital_phone: document.getElementById('hospital-phone').value,
                hospital_email: document.getElementById('hospital-email').value,
                hospital_description: document.getElementById('hospital-description').value
            };
            try {
                const response = await fetch('<?php echo site_url('admin/save_hospital_config'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification(result.success, 'success');
                } else {
                    showNotification(result.error, 'error');
                }
            } catch (error) {
                showNotification('Erro ao salvar configurações.', 'error');
            }
        });

        // System form submit
        document.getElementById('system-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = {
                default_duration: document.getElementById('default-duration').value,
                max_appointments: document.getElementById('max-appointments').value,
                email_notifications: document.getElementById('email-notifications').value,
                sms_notifications: document.getElementById('sms-notifications').value
            };
            try {
                const response = await fetch('<?php echo site_url('admin/save_system_config'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });
                const result = await response.json();
                if (result.success) {
                    showNotification(result.success, 'success');
                } else {
                    showNotification(result.error, 'error');
                }
            } catch (error) {
                showNotification('Erro ao salvar configurações.', 'error');
            }
        });

        // User search
        document.getElementById('user-search').addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#users-table tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // Add user button (modal or redirect)
        document.querySelectorAll('#add-user-btn, #add-user-btn-inline').forEach(btn => {
            btn.addEventListener('click', () => {
                // Redirect or open modal; here, redirect to add user
                window.location.href = '<?php echo site_url('admin/add_user'); ?>';
            });
        });

        // Edit/Delete users (AJAX or redirect)
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.target.closest('tr').dataset.id || 1; // Assume data-id
                window.location.href = `<?php echo site_url('admin/edit_user'); ?>?id=${userId}`;
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                if (confirm('Confirmar exclusão do usuário?')) {
                    const userId = e.target.closest('tr').dataset.id || 1;
                    try {
                        const response = await fetch('<?php echo site_url('admin/delete_user'); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: userId })
                        });
                        const result = await response.json();
                        if (result.success) {
                            e.target.closest('tr').remove();
                            showNotification(result.success, 'success');
                        } else {
                            showNotification(result.error, 'error');
                        }
                    } catch (error) {
                        showNotification('Erro ao excluir usuário.', 'error');
                    }
                }
            });
        });

        // Sidebar handlers (same as previous)
        document.addEventListener('DOMContentLoaded', function () {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (notificationClose) notificationClose.addEventListener('click', () => document.getElementById('notification').classList.remove('show'));

            if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.add('show');
                sidebarOverlay.classList.add('show');
                pageWrapper.classList.add('expanded');
            });

            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });

            if (toggleSidebarBtn) toggleSidebarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebarMenu.classList.toggle('expanded');
                pageWrapper.classList.toggle('expanded');
            });

            if (sidebarOverlay) sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                pageWrapper.classList.remove('expanded');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                if (!isClickInsideSidebar && !isClickOnMenuBtn && sidebarMenu.classList.contains('show') && window.innerWidth < 768) {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                }
            });

            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', () => {
                    showNotification('Sessão encerrada com sucesso!', 'success');
                    window.location.href = '<?php echo site_url('login'); ?>';
                });
            }
        });
    </script>
</body>
</html>