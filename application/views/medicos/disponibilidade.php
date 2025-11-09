<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Disponibilidade - Hospital Matlhovele</title>
    <meta name="description" content="Definir minha disponibilidade - Hospital Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
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
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
        }

        .dia-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }

        .horario-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .horario-item:hover {
            background-color: #f3f4f6;
        }

        .btn-remove {
            background-color: #ef4444;
            color: white;
            border: none;
            border-radius: 0.375rem;
            padding: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .btn-remove:hover {
            background-color: #dc2626;
        }

        .btn-add {
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .btn-add:hover {
            background-color: #059669;
        }

        .action-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            background-color: #2563eb;
        }

        .action-btn:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        .info-card {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
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

        <!-- Left Sidebar para Médico -->
        <div id="sidebar-menu" class="sidebar bg-white shadow-lg">
            <div class="sidebar-header flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Médico</h2>
                <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="sidebar-nav">
                <div class="main-menu">
                    <a href="<?php echo site_url('medico/dashboard'); ?>" class="block text-gray-700">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                    <a href="<?php echo site_url('medico/consultas'); ?>" class="block text-gray-700">
                        <i class="fas fa-stethoscope"></i>
                        <span class="sidebar-text">Minhas Consultas</span>
                    </a>
                    <a href="<?php echo site_url('medico/pacientes'); ?>" class="block text-gray-700">
                        <i class="fas fa-users"></i>
                        <span class="sidebar-text">Meus Pacientes</span>
                    </a>
                    <a href="<?php echo site_url('medico/disponibilidade'); ?>" class="block text-gray-700 active">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="sidebar-text">Minha Disponibilidade</span>
                    </a>
                    <a href="<?php echo site_url('medico/perfil'); ?>" class="block text-gray-700">
                        <i class="fas fa-user-cog"></i>
                        <span class="sidebar-text">Meu Perfil</span>
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
                <div class="flex items-center gap-4">
                    <span class="text-blue-100">Dr. <?php echo htmlspecialchars($medico['Nome'] . ' ' . $medico['Sobrenome']); ?></span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-4 py-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Minha Disponibilidade</h2>
                    <p class="text-gray-600">Configure seus horários de atendimento para a semana.</p>
                </div>

                <!-- Informações do Médico -->
                <div class="info-card">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-md text-blue-600 text-xl"></i>
                        <div>
                            <h3 class="font-semibold text-blue-800">Dr. <?php echo htmlspecialchars($medico['Nome'] . ' ' . $medico['Sobrenome']); ?></h3>
                            <p class="text-blue-700 text-sm"><?php echo htmlspecialchars($medico['Especialidade']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Formulário de Disponibilidade -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Disponibilidade Semanal</h3>
                    <p class="text-sm text-gray-600 mb-6">Defina seus horários de atendimento para cada dia da semana. Estes horários serão usados para agendamentos de consultas.</p>

                    <div id="dias-semana" class="space-y-4">
                        <!-- Os dias serão gerados dinamicamente via JavaScript -->
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <button id="btn-salvar" class="action-btn">
                            <i class="fas fa-save mr-2"></i>Salvar Disponibilidade
                        </button>
                        <button id="btn-limpar" class="action-btn" style="background-color: #6b7280;">
                            <i class="fas fa-trash-alt mr-2"></i>Limpar Tudo
                        </button>
                        <button id="btn-carregar" class="action-btn" style="background-color: #059669;">
                            <i class="fas fa-sync-alt mr-2"></i>Carregar Minha Disponibilidade
                        </button>
                    </div>
                </div>

                <!-- Instruções -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h4 class="font-medium text-blue-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>Instruções
                    </h4>
                    <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                        <li>Adicione múltiplos horários para o mesmo dia se necessário</li>
                        <li>Os horários definidos aqui serão usados para agendamentos de consultas</li>
                        <li>Certifique-se de que não há sobreposição de horários no mesmo dia</li>
                        <li>Clique em "Salvar Disponibilidade" para aplicar as alterações</li>
                        <li>Use "Carregar Minha Disponibilidade" para ver seus horários atuais</li>
                    </ul>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4">
                <div class="text-center text-gray-400 text-sm">
                    <p>© 2025 Hospital Público de Matlhovele. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Dados dos dias da semana
        const diasSemana = [{
                id: 'segunda',
                nome: 'Segunda-feira'
            },
            {
                id: 'terca',
                nome: 'Terça-feira'
            },
            {
                id: 'quarta',
                nome: 'Quarta-feira'
            },
            {
                id: 'quinta',
                nome: 'Quinta-feira'
            },
            {
                id: 'sexta',
                nome: 'Sexta-feira'
            },
            {
                id: 'sabado',
                nome: 'Sábado'
            },
            {
                id: 'domingo',
                nome: 'Domingo'
            }
        ];

        // Variáveis globais
        let disponibilidadeAtual = {};

        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const n = document.getElementById('notification');
            const m = document.getElementById('notification-message');
            m.textContent = message;
            n.className = `show ${type}`;
            setTimeout(() => n.classList.remove('show'), 5000);
        }

        // Função para criar o HTML de um dia
        function criarDiaHTML(dia) {
            return `
            <div class="dia-card" data-dia="${dia.id}">
                <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                    ${dia.nome}
                </h4>
                <div class="horarios-container" id="horarios-${dia.id}">
                    <!-- Horários serão adicionados aqui -->
                </div>
                <button type="button" class="btn-add add-horario" data-dia="${dia.id}">
                    <i class="fas fa-plus"></i>Adicionar Horário
                </button>
            </div>
        `;
        }

        // Função para criar um item de horário
        function criarHorarioHTML(dia, horario = {
            inicio: '',
            fim: ''
        }) {
            const horarioId = 'horario_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            return `
            <div class="horario-item" data-horario-id="${horarioId}">
                <input type="time" class="form-input horario-inicio" value="${horario.inicio}" placeholder="Início" style="max-width: 120px;">
                <span class="text-gray-500 font-medium">até</span>
                <input type="time" class="form-input horario-fim" value="${horario.fim}" placeholder="Fim" style="max-width: 120px;">
                <button type="button" class="btn-remove remove-horario" title="Remover horário">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        }

        // Função para carregar disponibilidade do médico
        async function carregarDisponibilidade() {
            try {
                const btnCarregar = document.getElementById('btn-carregar');
                const originalText = btnCarregar.innerHTML;
                btnCarregar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Carregando...';
                btnCarregar.disabled = true;

                // URL CORRIGIDA - usando diretamente o controller Admin
                const response = await fetch('<?php echo site_url('admin/get_minha_disponibilidade'); ?>');

                // Verificar se a resposta é JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Resposta não-JSON:', text.substring(0, 500));
                    throw new Error('O servidor retornou uma resposta inválida. Verifique o console para detalhes.');
                }

                const result = await response.json();

                if (result.error) {
                    throw new Error(result.error);
                }

                disponibilidadeAtual = result;
                renderizarDisponibilidade();
                showNotification('Disponibilidade carregada com sucesso!', 'success');

            } catch (error) {
                console.error('Erro detalhado:', error);
                showNotification('Erro ao carregar disponibilidade: ' + error.message, 'error');
            } finally {
                const btnCarregar = document.getElementById('btn-carregar');
                btnCarregar.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Carregar Minha Disponibilidade';
                btnCarregar.disabled = false;
            }
        }

        // Função para renderizar a disponibilidade na interface
        function renderizarDisponibilidade() {
            const container = document.getElementById('dias-semana');
            container.innerHTML = '';

            // Criar cards para cada dia
            diasSemana.forEach(dia => {
                container.innerHTML += criarDiaHTML(dia);

                const horariosContainer = document.getElementById(`horarios-${dia.id}`);
                const horariosDia = disponibilidadeAtual[dia.id] || [];

                if (horariosDia.length === 0) {
                    // Adicionar um horário vazio se não houver horários
                    horariosContainer.innerHTML = criarHorarioHTML(dia);
                } else {
                    // Adicionar os horários existentes
                    horariosDia.forEach(horario => {
                        horariosContainer.innerHTML += criarHorarioHTML(dia, horario);
                    });
                }
            });

            // Adicionar event listeners aos botões
            adicionarEventListeners();
        }

        // Função para adicionar event listeners
        function adicionarEventListeners() {
            // Botões de adicionar horário
            document.querySelectorAll('.add-horario').forEach(btn => {
                btn.addEventListener('click', function() {
                    const dia = this.dataset.dia;
                    const container = document.getElementById(`horarios-${dia}`);
                    container.innerHTML += criarHorarioHTML(dia);
                    adicionarEventListeners();
                });
            });

            // Botões de remover horário
            document.querySelectorAll('.remove-horario').forEach(btn => {
                btn.addEventListener('click', function() {
                    const horarioItem = this.closest('.horario-item');
                    const container = horarioItem.parentElement;

                    if (container.children.length > 1) {
                        horarioItem.remove();
                    } else {
                        showNotification('Cada dia deve ter pelo menos um horário', 'error');
                    }
                });
            });
        }

        // Função para coletar dados da disponibilidade
        function coletarDisponibilidade() {
            const disponibilidade = {};

            diasSemana.forEach(dia => {
                const horariosContainer = document.getElementById(`horarios-${dia.id}`);
                const horariosItems = horariosContainer.querySelectorAll('.horario-item');

                disponibilidade[dia.id] = [];

                horariosItems.forEach(item => {
                    const inicio = item.querySelector('.horario-inicio').value;
                    const fim = item.querySelector('.horario-fim').value;

                    if (inicio && fim) {
                        if (inicio >= fim) {
                            showNotification(`No ${dia.nome}, o horário de fim deve ser depois do início`, 'error');
                            throw new Error('Horário inválido');
                        }

                        disponibilidade[dia.id].push({
                            inicio: inicio,
                            fim: fim
                        });
                    }
                });
            });

            return disponibilidade;
        }

        // Função para salvar disponibilidade
        async function salvarDisponibilidade() {
            let disponibilidade;

            try {
                disponibilidade = coletarDisponibilidade();
            } catch (error) {
                return;
            }

            const temHorarios = Object.values(disponibilidade).some(horarios => horarios.length > 0);
            if (!temHorarios) {
                showNotification('Defina pelo menos um horário de disponibilidade', 'error');
                return;
            }

            try {
                const btnSalvar = document.getElementById('btn-salvar');
                const originalText = btnSalvar.innerHTML;
                btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
                btnSalvar.disabled = true;

                // URL CORRIGIDA - usando diretamente o controller Admin
                const response = await fetch('<?php echo site_url('admin/salvar_minha_disponibilidade'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        disponibilidade: disponibilidade
                    })
                });

                // Verificar se a resposta é JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Resposta não-JJSON:', text.substring(0, 500));
                    throw new Error('O servidor retornou uma resposta inválida ao salvar.');
                }

                const result = await response.json();

                if (result.success) {
                    showNotification(result.success, 'success');
                    disponibilidadeAtual = disponibilidade;
                } else {
                    throw new Error(result.error || 'Erro desconhecido ao salvar');
                }

            } catch (error) {
                console.error('Erro ao salvar:', error);
                showNotification('Erro ao salvar disponibilidade: ' + error.message, 'error');
            } finally {
                const btnSalvar = document.getElementById('btn-salvar');
                btnSalvar.innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Disponibilidade';
                btnSalvar.disabled = false;
            }
        }

        // Função para limpar todos os horários
        function limparDisponibilidade() {
            if (!confirm('Tem certeza que deseja limpar todos os horários? Esta ação não pode ser desfeita.')) {
                return;
            }

            diasSemana.forEach(dia => {
                const container = document.getElementById(`horarios-${dia.id}`);
                container.innerHTML = criarHorarioHTML(dia);
            });

            adicionarEventListeners();
            showNotification('Todos os horários foram limpos', 'info');
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar interface com dias vazios
            renderizarDisponibilidade();

            // Fechar notificação
            document.getElementById('notification-close').addEventListener('click', function() {
                document.getElementById('notification').classList.remove('show');
            });

            // Botões de ação
            document.getElementById('btn-salvar').addEventListener('click', salvarDisponibilidade);
            document.getElementById('btn-limpar').addEventListener('click', limparDisponibilidade);
            document.getElementById('btn-carregar').addEventListener('click', carregarDisponibilidade);

            // Carregar disponibilidade automaticamente ao abrir a página
            carregarDisponibilidade();

            // Sidebar handlers
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mainContent = document.querySelector('.main-content');

            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('desktop', 'collapsed');
            } else {
                sidebarMenu.classList.add('mobile');
            }

            mobileMenuBtn?.addEventListener('click', () => {
                sidebarMenu.classList.add('show');
                sidebarOverlay.classList.add('show');
            });

            closeSidebarBtn?.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            toggleSidebarBtn?.addEventListener('click', () => {
                if (window.innerWidth >= 768) {
                    sidebarMenu.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            // Logout
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