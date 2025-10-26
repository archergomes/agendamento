<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretários - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciar secretários no Hospital Público de Matlhovele">
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

        /* Header com largura total e centralizado */
        header {
            position: relative;
            z-index: 800;
            background-color: #2563eb;
            width: 100%;
            margin-left: 0;
        }

        /* NOVA ESTRUTURA: PAGE WRAPPER */
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

        /* Main Content - CORRIGIDO */
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

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            overflow-x: auto;
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 0.25rem;
            color: white;
            transition: background-color 0.2s;
        }

        .edit-btn { background-color: #3b82f6; }
        .edit-btn:hover { background-color: #2563eb; }
        .delete-btn { background-color: #ef4444; }
        .delete-btn:hover { background-color: #dc2626; }
        
        .create-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        
        .create-btn:hover { background-color: #059669; }
        
        .search-input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            flex-grow: 1;
            max-width: 300px;
        }
        
        .search-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        
        .search-btn:hover { background-color: #2563eb; }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
            font-style: italic;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .pagination button {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            background-color: white;
            color: #374151;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .pagination button:hover {
            background-color: #eff6ff;
        }

        .pagination button.active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
                    <a href="<?php echo site_url('admin/secretarios'); ?>" class="block text-gray-700 active">
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Lista de Secretários</h2>
                    <a href="<?php echo site_url('admin/cad_secretario'); ?>" class="create-btn">
                        <i class="fas fa-user-plus mr-2"></i> Cadastrar Novo Secretário
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4 flex space-x-2">
                    <input type="text" id="search-input" class="search-input" placeholder="Pesquisar por nome ou BI...">
                    <button id="search-btn" class="search-btn"><i class="fas fa-search"></i></button>
                </div>

                <!-- Secretaries Table -->
                <div class="table-container">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Telefone</th>
                                <th class="px-4 py-2">BI</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Cargo</th>
                                <th class="px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="secretaries-table">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                    <p id="no-results" class="text-center text-gray-500 mt-4 hidden empty-state">Nenhum secretário encontrado para a pesquisa.</p>
                    <div id="pagination-container" class="pagination"></div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initial secretaries from PHP
        let initialSecretaries = <?php echo json_encode(array_map(function($secretario) {
            return [
                'bi' => $secretario['ID_Secretario'] ?? '',
                'name' => trim(($secretario['Nome'] ?? '') . ' ' . ($secretario['Sobrenome'] ?? '')),
                'phone' => $secretario['Telefone'] ?? '',
                'email' => $secretario['Email'] ?? null,
                'cargo' => $secretario['Cargo'] ?? null
            ];
        }, $secretarios ?? [])); ?>;

        let allSecretaries = initialSecretaries;
        let currentPage = 1;
        const secretariesPerPage = 10;

        console.log('Dados iniciais de secretários (PHP):', initialSecretaries);

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

        // Render pagination controls
        function renderPagination(totalPages) {
            const container = document.getElementById('pagination-container');
            if (!container) return;

            container.innerHTML = '';

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.textContent = 'Anterior';
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable(allSecretaries);
                }
            });
            container.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.classList.toggle('active', i === currentPage);
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable(allSecretaries);
                });
                container.appendChild(btn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Próxima';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable(allSecretaries);
                }
            });
            container.appendChild(nextBtn);
        }

        // Render secretaries table
        function renderTable(secretariesList) {
            const tableBody = document.getElementById('secretaries-table');
            const noResults = document.getElementById('no-results');
            const paginationContainer = document.getElementById('pagination-container');
            if (!tableBody || !noResults || !paginationContainer) {
                console.error('Table elements not found');
                return;
            }

            const startIndex = (currentPage - 1) * secretariesPerPage;
            const endIndex = startIndex + secretariesPerPage;
            const paginatedSecretaries = secretariesList.slice(startIndex, endIndex);

            tableBody.innerHTML = '';
            if (secretariesList.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500 empty-state">
                            <i class="fas fa-user-tie text-4xl text-gray-400 mb-4"></i>
                            <p>Nenhum secretário encontrado. <a href="<?php echo site_url('admin/cad_secretario'); ?>" class="text-blue-600 hover:underline">Cadastre o primeiro!</a></p>
                        </td>
                    </tr>
                `;
                noResults.classList.remove('hidden');
                paginationContainer.innerHTML = '';
                return;
            }

            noResults.classList.add('hidden');
            paginatedSecretaries.forEach(secretary => {
                const row = document.createElement('tr');
                row.className = 'border-t';
                row.innerHTML = `
                    <td class="px-4 py-2">${secretary.name || 'N/A'}</td>
                    <td class="px-4 py-2">${secretary.phone || '-'}</td>
                    <td class="px-4 py-2">${secretary.bi || '-'}</td>
                    <td class="px-4 py-2">${secretary.email || '-'}</td>
                    <td class="px-4 py-2">${secretary.cargo || '-'}</td>
                    <td class="px-4 py-2">
                        <a href="<?php echo site_url('admin/cad_secretario?bi='); ?>${secretary.bi}" class="action-btn edit-btn mr-2" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="action-btn delete-btn" data-bi="${secretary.bi}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            const totalPages = Math.ceil(secretariesList.length / secretariesPerPage);
            renderPagination(totalPages);

            // Attach delete event listeners
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const bi = btn.getAttribute('data-bi');
                    console.log('Tentando deletar BI:', bi);
                    if (confirm(`Tem certeza que deseja excluir o secretário com BI ${bi}?`)) {
                        try {
                            const response = await fetch('<?php echo site_url('admin/delete_secretary'); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ bi })
                            });
                            const result = await response.json();
                            console.log('Resultado delete:', result);
                            if (result.error) {
                                showNotification(result.error, 'error');
                            } else {
                                showNotification(result.success, 'success');
                                allSecretaries = allSecretaries.filter(secretary => secretary.bi !== bi);
                                if (allSecretaries.length === 0) {
                                    currentPage = 1;
                                } else if (endIndex > allSecretaries.length) {
                                    currentPage = Math.ceil(allSecretaries.length / secretariesPerPage);
                                }
                                renderTable(allSecretaries);
                            }
                        } catch (error) {
                            console.error('Erro delete:', error);
                            showNotification('Erro ao excluir secretário: ' + error.message, 'error');
                        }
                    }
                });
            });
        }

        // Handle search - client-side filtering
        function handleSearch(query) {
            console.log('Buscando com query:', query);
            if (query === '') {
                currentPage = 1;
                renderTable(allSecretaries);
            } else {
                const lowerQuery = query.toLowerCase();
                const filtered = allSecretaries.filter(secretary => 
                    (secretary.name && secretary.name.toLowerCase().includes(lowerQuery)) ||
                    (secretary.bi && secretary.bi.toLowerCase().includes(lowerQuery))
                );
                currentPage = 1;
                renderTable(filtered);
            }
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', function () {
            const notificationClose = document.getElementById('notification-close');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const pageWrapper = document.querySelector('.page-wrapper');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.getElementById('search-btn');

            if (!notificationClose || !mobileMenuBtn || !sidebarMenu || !closeSidebarBtn || !toggleSidebarBtn ||
                !pageWrapper || !sidebarOverlay || !searchInput || !searchBtn) {
                console.error('DOM elements not found');
                return;
            }

            // Initial render with PHP data
            renderTable(allSecretaries);

            // Search functionality
            searchBtn.addEventListener('click', () => {
                const query = searchInput.value.trim();
                handleSearch(query);
            });

            // Search on Enter key
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const query = searchInput.value.trim();
                    handleSearch(query);
                }
            });

            // Notification close
            notificationClose.addEventListener('click', () => {
                document.getElementById('notification').classList.remove('show');
            });

            // Sidebar handlers - ATUALIZADO
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
        });
    </script>
</body>
</html>