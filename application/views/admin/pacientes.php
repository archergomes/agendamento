<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - Administrador - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciar pacientes no Hospital Público de Matlhovele">
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

        .edit-btn {
            background-color: #3b82f6;
        }

        .edit-btn:hover {
            background-color: #2563eb;
        }

        .delete-btn {
            background-color: #ef4444;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        .create-btn {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }

        .create-btn:hover {
            background-color: #059669;
        }

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

        .search-btn:hover {
            background-color: #2563eb;
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
        <div id="notification" role="alert" class="hidden">
            <span id="notification-message"></span>
            <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
        </div>

        <!-- Sidebar, Header, etc. (mantidos) -->
        <!-- ... (seu código de sidebar e header) ... -->

        <main class="main-content">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Lista de Pacientes</h2>
                    <a href="<?php echo site_url('admin/cad_paciente'); ?>" class="create-btn">
                        <i class="fas fa-user-plus mr-2"></i> Cadastrar Novo Paciente
                    </a>
                </div>

                <div class="mb-4 flex space-x-2">
                    <input type="text" id="search-input" class="search-input" placeholder="Pesquisar por nome ou BI...">
                    <button id="search-btn" class="search-btn"><i class="fas fa-search"></i></button>
                </div>

                <div class="table-container">
                    <table class="table-auto w-full text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Nome</th>
                                <th class="px-4 py-2">Telefone</th>
                                <th class="px-4 py-2">BI</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Nascimento</th>
                                <th class="px-4 py-2">Género</th>
                                <th class="px-4 py-2">Endereço</th>
                                <th class="px-4 py-2">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="patients-table">
                            <!-- Preenchido via JS -->
                        </tbody>
                    </table>
                    <p id="no-results" class="text-center text-gray-500 mt-4 hidden empty-state">Nenhum paciente encontrado.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        let patients = <?php echo json_encode(array_map(function ($p) {
                            return [
                                'bi' => $p['BI'] ?? '',
                                'name' => $p['Nome_Completo'] ?? '',
                                'phone' => $p['Telefone'] ?? '',
                                'email' => $p['Email'] ?? '',
                                'birthday' => $p['Data_Nascimento'] ?? '',
                                'gender' => $p['Genero'] ?? '',
                                'address' => $p['Endereco'] ?? ''
                            ];
                        }, $pacientes ?? [])); ?>;

        function showNotification(message, type = 'info') {
            const n = document.getElementById('notification');
            const m = document.getElementById('notification-message');
            m.textContent = message;
            n.className = `fixed top-4 right-4 px-4 py-2 rounded text-white z-50 ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'}`;
            n.classList.remove('hidden');
            setTimeout(() => n.classList.add('hidden'), 5000);
        }

        function renderTable(list) {
            const tbody = document.getElementById('patients-table');
            const noResults = document.getElementById('no-results');
            tbody.innerHTML = '';

            if (list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-gray-500">Nenhum paciente encontrado.</td></tr>`;
                noResults.classList.remove('hidden');
                return;
            }
            noResults.classList.add('hidden');

            list.forEach(p => {
                const row = document.createElement('tr');
                row.className = 'border-t';
                row.innerHTML = `
                    <td class="px-4 py-2">${p.name || 'N/A'}</td>
                    <td class="px-4 py-2">${p.phone || '-'}</td>
                    <td class="px-4 py-2">${p.bi || '-'}</td>
                    <td class="px-4 py-2">${p.email || '-'}</td>
                    <td class="px-4 py-2">${p.birthday || '-'}</td>
                    <td class="px-4 py-2">${p.gender || '-'}</td>
                    <td class="px-4 py-2">${p.address || '-'}</td>
                    <td class="px-4 py-2">
                        <a href="<?php echo site_url('admin/cad_paciente?bi='); ?>${encodeURIComponent(p.bi)}" class="action-btn edit-btn mr-2" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="action-btn delete-btn" data-bi="${p.bi}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Delete
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.onclick = async () => {
                    const bi = btn.dataset.bi;
                    if (confirm(`Excluir paciente com BI ${bi}?`)) {
                        try {
                            const res = await fetch('<?php echo site_url('Api/delete_patient'); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    bi
                                })
                            });
                            const json = await res.json();
                            showNotification(json.success || json.error, json.success ? 'success' : 'error');
                            if (json.success) {
                                patients = patients.filter(x => x.bi !== bi);
                                renderTable(patients);
                            }
                        } catch (e) {
                            showNotification('Erro de conexão', 'error');
                        }
                    }
                };
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderTable(patients);

            const searchBtn = document.getElementById('search-btn');
            const searchInput = document.getElementById('search-input');

            const search = async () => {
                const q = searchInput.value.trim();
                try {
                    const url = `<?php echo site_url('admin/get_patients'); ?>${q ? '?query=' + encodeURIComponent(q) : ''}`;
                    const res = await fetch(url);
                    const data = await res.json();
                    patients = data;
                    renderTable(patients);
                } catch (e) {
                    showNotification('Erro na busca', 'error');
                }
            };

            searchBtn.onclick = search;
            searchInput.addEventListener('keypress', e => e.key === 'Enter' && search());
        });
    </script>
</body>

</html>