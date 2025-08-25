<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidade - Hospital Matlhovele</title>
    <meta name="description" content="Gerenciamento de disponibilidade para médicos do Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }
        #notification {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
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
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 50;
            min-width: 200px;
        }
        .dropdown-menu.show {
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
            z-index: 60;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
        }
        .table-container {
            overflow-x: auto;
        }
        .calendar-grid {
            min-height: 200px; /* Ensure visibility */
        }
        .calendar-day {
            text-align: center;
            padding: 8px;
            border-radius: 0.25rem;
            min-height: 32px;
        }
        .calendar-day:hover:not(.calendar-day-past):not(.calendar-day-booked) {
            background-color: #e0f2fe;
            cursor: pointer;
        }
        .calendar-day-active {
            background-color: #3b82f6;
            color: white;
        }
        .calendar-day-past {
            color: #d1d5db;
        }
        .calendar-day-available {
            background-color: #dcfce7;
        }
        .calendar-day-booked {
            background-color: #fef9c3;
        }
        @media (max-width: 640px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            #mobile-menu-btn {
                display: inline-block;
            }
            .calendar-day {
                padding: 4px;
                font-size: 0.75rem;
            }
        }
        @media (min-width: 641px) {
            #mobile-menu-btn {
                display: none;
            }
            .sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Notification -->
    <div id="notification" role="alert" aria-live="polite">
        <span id="notification-message"></span>
        <button id="notification-close" class="ml-2 text-white hover:text-gray-200" aria-label="Fechar notificação">×</button>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 md:static md:translate-x-0">
            <div class="flex items-center space-x-2 px-4">
                <img src="https://picsum.photos/100?random=3" alt="Foto do Médico" class="w-10 h-10 rounded-full">
                <span class="text-lg font-medium">Dr. João Matlhovele</span>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="/medico.html#agendamentos" class="nav-link flex items-center space-x-2 px-4 py-3 hover:bg-blue-700 rounded">
                            <i class="fas fa-calendar-check"></i>
                            <span>Agendamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="/pacientes.html" class="nav-link flex items-center space-x-2 px-4 py-3 hover:bg-blue-700 rounded">
                            <i class="fas fa-user-injured"></i>
                            <span>Pacientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/estatisticas.html" class="nav-link flex items-center space-x-2 px-4 py-3 hover:bg-blue-700 rounded">
                            <i class="fas fa-chart-line"></i>
                            <span>Estatísticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#disponibilidade" class="nav-link flex items-center space-x-2 px-4 py-3 text-white bg-blue-700 rounded" data-section="disponibilidade">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Disponibilidade</span>
                        </a>
                    </li>
                    <li>
                        <a href="/medico.html#configurações" class="nav-link flex items-center space-x-2 px-4 py-3 hover:bg-blue-700 rounded">
                            <i class="fas fa-cog"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-blue-600 shadow-lg">
                <div class="bg-white container mx-auto px-4 py-3 flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <button id="mobile-menu-btn" class="text-gray-600">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <i class="fas fa-hospital-alt text-2xl text-blue-600" aria-label="Ícone do Hospital Matlhovele"></i>
                        <h1 class="text-xl font-bold text-blue-600">Disponibilidade</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="user-menu-btn" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span>Dr. João Matlhovele</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="dropdown-menu" class="dropdown-menu">
                                <a href="/medico.html" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Home</a>
                                <a href="/perfil.html" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Perfil</a>
                                <button id="logout-btn" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50">Sair</button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 bg-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                    <section id="disponibilidade" class="py-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 space-y-4 sm:space-y-0">
                            <h2 class="text-lg font-semibold text-gray-800">Gerenciar Disponibilidade</h2>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                                <select id="date-range" class="p-2 border rounded focus:ring-2 focus:ring-blue-500 w-full sm:w-auto" aria-label="Selecionar período">
                                    <option value="next-30">Próximos 30 Dias</option>
                                    <option value="2025">2025</option>
                                    <option value="all">Todos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Calendar -->
                        <div class="bg-white rounded-lg shadow p-6 mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Calendário</h3>
                                <div class="flex items-center space-x-2">
                                    <button id="prev-month" class="p-2 py-1 rounded-md hover:bg-gray-100" aria-label="Mês anterior">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span id="calendar-month" class="px-4 py-1 font-medium text-gray-700"></span>
                                    <button id="next-month" class="p-2 py-1 rounded-md hover:bg-gray-100" aria-label="Próximo mês">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="calendar-grid">
                                <div class="grid grid-cols-7 gap-1 mb-2">
                                    <div class="text-center font-semibold py-2 text-gray-700">Dom</div>
                                    <div class="text-center font-semibold text-gray-700">Seg</div>
                                    <div class="text-center font-semibold py-2 text-gray-700">Ter</div>
                                    <div class="text-center font-semibold py-2 text-gray-700">Qua</div>
                                    <div class="text-center font-semibold py-2 text-gray-700">Qui</div>
                                    <div class="text-center font-semibold py-2 text-gray-700">Sex</div>
                                    <div class="text-center font-semibold py-2 text-gray-700">Sáb</div>
                                </div>
                                <div id="calendar-days" class="grid grid-cols-7 gap-1"></div>
                            </div>
                        </div>

                        <!-- Add Slot Form -->
                        <div class="bg-white rounded-lg shadow p-6 mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Horário ou Exceção</h3>
                            <form id="add-slot-form" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    <div>
                                        <label for="slot-date" class="block text-sm font-medium text-gray-700">Data</label>
                                        <input type="slot-date" id="text" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500 date-input" required aria-label="Selecionar data">
                                    </div>
                                    <div>
                                        <label for="slot-start" class="block text-sm font-medium text-gray-700">Início</label>
                                        <input type="time" id="slot-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de início">
                                    </div>
                                    <div>
                                        <label for="slot-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                        <input type="time" id="slot-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de fim">
                                    </div>
                                    <div>
                                        <label for="slot-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                        <select id="slot-type" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Selecionar tipo">
                                            <option value="Available">Disponível</option>
                                            <option value="Unavailable">Indisponível</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label for="slot-reason" class="block text-sm font-medium text-gray-700">Motivo</label> (opcional)
                                    <input type="text" id="slot-reason" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Motivo da exceção">
                                </div>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i>Adicionar
                                </button>
                            </form>
                        </div>

                        <!-- Standard Working Hours -->
                        <div class="bg-white rounded-lg shadow p-6 mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Horário Padrão</h3>
                                <button id="save-schedule" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>Salvar
                                </button>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Dias de Trabalho</h4>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="1" checked>
                                                <span class="ml-2">Segunda</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="2" checked>
                                                <span class="ml-2">Terça</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="3" checked>
                                                <span class="ml-2">Quarta</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="4" checked>
                                                <span class="ml-2">Quinta</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="5" checked>
                                                <span class="ml-2">Sexta</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" class="rounded text-blue-600 schedule-day" value="6">
                                                <span class="ml-2">Sábado</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 mb-3">Horário de Atendimento</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="schedule-start" class="block text-sm font-medium text-gray-700">Início</label>
                                                <input type="time" id="schedule-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="08:00" step="900" required aria-label="Horário de início">
                                            </div>
                                            <div>
                                                <label for="schedule-end" class="block text-sm font-medium text-gray-700">Fim</label>
                                                <input type="time" id="schedule-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" value="16:00" step="900" required aria-label="Horário de fim">
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="consultation-duration" class="block text-sm font-medium text-gray-700">Duração da Consulta</label>
                                            <select id="consultation-duration" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Duração da consulta">
                                                <option value="30">30 minutos</option>
                                                <option value="45" selected>45 minutos</option>
                                                <option value="60">60 minutos</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slots Table -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Horários e Exceções</h3>
                            <div class="table-container">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horário</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="slots-table" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit Slot Modal -->
    <div id="edit-slot-modal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Editar Horário</h3>
            <form id="edit-slot-form" class="space-y-4">
                <input type="hidden" id="edit-slot-id">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="edit-slot-date" class="block text-sm font-medium text-gray-700">Data</label>
                        <input type="text" id="edit-slot-date" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500 date-input" required aria-label="Selecionar data">
                    </div>
                    <div>
                        <label for="edit-slot-start" class="block text-sm font-medium text-gray-700">Início</label>
                        <input type="time" id="edit-slot-start" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de início">
                    </div>
                    <div>
                        <label for="edit-slot-end" class="block text-sm font-medium text-gray-700">Fim</label>
                        <input type="time" id="edit-slot-end" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" step="900" required aria-label="Selecionar horário de fim">
                    </div>
                    <div>
                        <label for="edit-slot-type" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select id="edit-slot-type" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Selecionar tipo">
                            <option value="Available">Disponível</option>
                            <option value="Unavailable">Indisponível</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="edit-slot-reason" class="block text-sm font-medium text-gray-700">Motivo (opcional)</label>
                    <input type="text" id="edit-slot-reason" class="mt-1 p-2 w-full border rounded focus:ring-2 focus:ring-blue-500" aria-label="Motivo da exceção">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancel-edit" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-medium mb-4">Hospital Matlhovele</h3>
                    <p class="text-gray-400">Av. 25 de Setembro, Maputo</p>
                    <p class="text-gray-400">Telefone: +258 84 123 4567</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">Horário de Funcionamento</h3>
                    <p class="text-gray-400">Segunda a Sexta: 7h30 - 16h30</p>
                    <p class="text-gray-400">Sábado: 8h00 - 12h00</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="/sobre" class="text-gray-400 hover:text-white">Sobre Nós</a></li>
                        <li><a href="/servicos" class="text-gray-400 hover:text-white">Serviços</a></li>
                        <li><a href="/contactos" class="text-gray-400 hover:text-white">Contactos</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>© 2025 Hospital Público de Matlhovele. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Dados iniciais
        const doctor = { id: 1, name: "Dr. João Matlhovele", specialty: "Cardiologia" };
        let bookedAppointments = [
            { specialty: "Cardiologia", doctor: "Dr. João Matlhovele", date: "2025-06-12", time: "14:00", name: "Carlos Nhantumbo", phone: "+258 841234567", bi: "1234567890A", status: "scheduled", notes: "" },
            { specialty: "Cardiologia", doctor: "Dr. João Matlhovele", date: "2025-06-12", time: "15:00", name: "Ana Macuácua", phone: "+258 842345678", bi: "0987654321B", status: "scheduled", notes: "" },
            { specialty: "Cardiologia", doctor: "Dr. João Matlhovele", date: "2025-06-12", time: "13:30", name: "José Matavel", phone: "+258 843456789", bi: "1122334455C", status: "completed", notes: "Consulta inicial realizada." },
            { specialty: "Cardiologia", doctor: "Dr. João Matlhovele", date: "2025-06-13", time: "09:00", name: "Maria Sitoe", phone: "+258 844567890", bi: "5566778899D", status: "scheduled", notes: "" }
        ];
        let availableSlots = [
            { id: 1, date: "2025-06-12", start: "13:00", end: "14:00", type: "Available", reason: "", doctor: "Dr. João Matlhovele" },
            { id: 2, date: "2025-06-12", start: "14:00", end: "15:00", type: "Available", reason: "", doctor: "Dr. João Matlhovele" },
            { id: 3, date: "2025-06-12", start: "15:00", end: "16:00", type: "Available", reason: "", doctor: "Dr. João Matlhovele" },
            { id: 4, date: "2025-06-13", start: "09:00", end: "10:00", type: "Available", reason: "", doctor: "Dr. João Matlhovele" },
            { id: 5, date: "2025-06-13", start: "10:00", end: "11:00", type: "Available", reason: "", doctor: "Dr. João Matlhovele" },
            { id: 6, date: "2025-06-15", start: "08:00", end: "16:00", type: "Unavailable", reason: "Feriado municipal", doctor: "Dr. João Matlhovele" }
        ];
        let doctorSchedule = {
            days: [1, 2, 3, 4, 5], // Segunda a Sexta
            start: "08:00",
            end: "16:00",
            duration: 45 // minutos
        };

        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.textContent = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Renderizar calendário
        let currentMonth = new Date('2025-06-01');
        function renderCalendar() {
            try {
                console.log('Rendering calendar for:', currentMonth);
                if (!document.getElementById('calendar-days')) {
                    console.error('Calendar days container not found');
                    return;
                }

                const monthYear = currentMonth.toLocaleString('pt-PT', { month: 'long', year: 'numeric' });
                document.getElementById('calendar-month').textContent = monthYear.charAt(0).toUpperCase() + monthYear.slice(1);

                const firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
                const lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);
                const startDay = firstDay.getDay();
                const daysInMonth = lastDay.getDate();
                const today = new Date('2025-06-12T13:22:00');

                const daysContainer = document.getElementById('calendar-days');
                daysContainer.innerHTML = '';
                console.log('Calendar cleared, daysInMonth:', daysInMonth, 'startDay:', startDay);

                // Dias do mês anterior
                const prevMonthDays = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 0).getDate();
                for (let i = startDay - 1; i >= 0; i--) {
                    const div = document.createElement('div');
                    div.className = 'calendar-day calendar-day-past';
                    div.textContent = prevMonthDays - i;
                    daysContainer.appendChild(div);
                }

                // Dias do mês atual
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
                    const dateStr = date.toISOString().split('T')[0];
                    const isToday = dateStr === today.toISOString().split('T')[0];
                    const hasAvailable = availableSlots.some(slot => slot.date === dateStr && slot.type === 'Available');
                    const hasBooked = bookedAppointments.some(appt => appt.date === dateStr);
                    const isPast = date < today && !isToday;

                    const div = document.createElement('div');
                    div.className = `calendar-day ${isToday ? 'calendar-day-active' : ''} ${isPast ? 'calendar-day-past' : ''} ${hasAvailable ? 'calendar-day-available' : ''} ${hasBooked ? 'calendar-day-booked' : ''}`;
                    div.textContent = day;
                    div.dataset.date = dateStr;
                    if (!isPast) {
                        div.addEventListener('click', () => {
                            console.log('Clicked day:', dateStr);
                            document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('calendar-day-active'));
                            div.classList.add('calendar-day-active');
                            const slotDateInput = document.getElementById('slot-date');
                            if (slotDateInput._flatpickr) {
                                slotDateInput._flatpickr.setDate(dateStr);
                            }
                            renderSlotsTable('all');
                        });
                    }
                    daysContainer.appendChild(div);
                }

                // Dias do próximo mês
                const remainingDays = 42 - (startDay + daysInMonth);
                for (let i = 1; i <= remainingDays; i++) {
                    const div = document.createElement('div');
                    div.className = 'calendar-day calendar-day-past';
                    div.textContent = i;
                    daysContainer.appendChild(div);
                }
                console.log('Calendar rendered successfully');
            } catch (error) {
                console.error('Error rendering calendar:', error);
                showNotification('Erro ao carregar o calendário. Verifique o console.', 'error');
            }
        }

        // Validar horário
        function isValidSlot(date, start, end, type, excludeId = null) {
            console.log('Validating slot:', { date, start, end, type, excludeId });
            if (!date || !start || !end) {
                return { valid: false, message: 'Todos os campos obrigatórios devem ser preenchidos.' };
            }

            const now = new Date('2025-06-12T13:22:00');
            const slotStart = new Date(`${date}T${start}`);
            const slotEnd = new Date(`${date}T${end}`);

            if (isNaN(slotStart.getTime()) || isNaN(slotEnd.getTime())) {
                return { valid: false, message: 'Data ou horário inválido.' };
            }

            if (type === 'Unavailable') {
                return { valid: true };
            }

            if (slotStart <= now) {
                return { valid: false, message: 'O horário deve estar no futuro.' };
            }

            if (slotStart >= slotEnd) {
                return { valid: false, message: 'O horário de início deve ser antes do fim.' };
            }

            const startMinutes = slotStart.getMinutes();
            if (startMinutes % 15 !== 0) {
                return { valid: false, message: 'Os horários devem ser em incrementos de 15 minutos.' };
            }

            for (const slot of availableSlots) {
                if (slot.id === excludeId) continue;
                const otherStart = new Date(`${slot.date}T${slot.start}`);
                const otherEnd = new Date(`${slot.date}T${slot.end}`);
                if (date === slot.date && slot.type === 'Available' && (
                    (slotStart >= otherStart && slotStart < otherEnd) ||
                    (slotEnd > otherStart && slotEnd <= otherEnd) ||
                    (slotStart <= otherStart && slotEnd >= otherEnd)
                )) {
                    return { valid: false, message: 'Conflito com outro horário disponível.' };
                }
            }

            for (const appt of bookedAppointments) {
                const apptTime = new Date(`${appt.date}T${appt.time}`);
                if (date === appt.date && slotStart <= apptTime && apptTime < slotEnd) {
                    return { valid: false, message: 'Conflito com um agendamento existente.' };
                }
            }

            return { valid: true };
        }

        // Verificar status do slot
        function getSlotStatus(slot) {
            if (slot.type === 'Unavailable') return 'Indisponível';
            for (const appt of bookedAppointments) {
                const apptTime = new Date(`${appt.date}T${appt.time}`);
                const slotStart = new Date(`${slot.date}T${slot.start}`);
                const slotEnd = new Date(`${slot.date}T${slot.end}`);
                if (appt.date === slot.date && apptTime >= slotStart && apptTime < slotEnd) {
                    return appt.status === 'completed' ? 'Concluído' : 'Agendado';
                }
            }
            return 'Disponível';
        }

        // Gerar slots com base no horário padrão
        function generateSlotsForDate(date) {
            const dayOfWeek = new Date(date).getDay();
            const scheduleDay = dayOfWeek === 0 ? 7 : dayOfWeek;
            if (!doctorSchedule.days.includes(scheduleDay)) return [];

            const startTime = new Date(`${date}T${doctorSchedule.start}`);
            const endTime = new Date(`${date}T${doctorSchedule.end}`);
            const duration = doctorSchedule.duration * 60 * 1000;
            const slots = [];

            let currentTime = startTime;
            let id = availableSlots.length ? Math.max(...availableSlots.map(s => s.id)) + 1 : 1;
            while (currentTime < endTime) {
                const slotStart = currentTime.toTimeString().slice(0, 5);
                currentTime = new Date(currentTime.getTime() + duration);
                const slotEnd = currentTime.toTimeString().slice(0, 5);
                if (currentTime <= endTime) {
                    const validation = isValidSlot(date, slotStart, slotEnd, 'Available');
                    if (validation.valid) {
                        slots.push({
                            id: id++,
                            date,
                            start: slotStart,
                            end: slotEnd,
                            type: 'Available',
                            reason: '',
                            doctor: doctor.name
                        });
                    }
                }
            }
            return slots;
        }

        // Renderizar tabela
        function renderSlotsTable(dateRange) {
            console.log('Rendering slots table with range:', dateRange);
            const now = new Date('2025-06-12T13:22:00');
            let startDate, endDate;
            if (dateRange === '2025') {
                startDate = new Date('2025-01-01');
                endDate = new Date('2025-12-31');
            } else if (dateRange === 'next-30') {
                startDate = new Date(now);
                endDate = new Date(now);
                endDate.setDate(now.getDate() + 30);
            } else {
                startDate = new Date(0);
                endDate = new Date('2100-01-01');
            }

            const selectedDate = document.querySelector('.calendar-day-active')?.dataset.date;
            let filteredSlots = availableSlots.filter(slot => {
                const slotDate = new Date(slot.date);
                return slot.doctor === doctor.name && slotDate >= startDate && slotDate <= endDate &&
                       (!selectedDate || slot.date === selectedDate);
            });

            const tbody = document.getElementById('slots-table');
            tbody.innerHTML = '';
            filteredSlots.forEach(slot => {
                const status = getSlotStatus(slot);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${slot.start} - ${slot.end}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs ${slot.type === 'Available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">${slot.type === 'Available' ? 'Disponível' : 'Indisponível'}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs ${
                            status === 'Disponível' ? 'bg-green-100 text-green-800' :
                            status === 'Agendado' ? 'bg-yellow-100 text-yellow-800' :
                            status === 'Concluído' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'}">${status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${slot.reason || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="edit-slot text-blue-600 hover:text-blue-800 mr-2" data-id="${slot.id}" aria-label="Editar horário"><i class="fas fa-edit"></i></button>
                        <button class="delete-slot text-red-600 hover:text-red-800" data-id="${slot.id}" aria-label="Excluir horário"><i class="fas fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            document.querySelectorAll('.edit-slot').forEach(btn => {
                btn.removeEventListener('click', openEditModal);
                btn.addEventListener('click', () => openEditModal(btn.dataset.id));
            });
            document.querySelectorAll('.delete-slot').forEach(btn => {
                btn.removeEventListener('click', deleteSlot);
                btn.addEventListener('click', () => deleteSlot(btn.dataset.id));
            });
        }

        // Abrir modal de edição
        function openEditModal(id) {
            console.log('Opening edit modal for id:', id);
            const slot = availableSlots.find(s => s.id == id);
            if (!slot) {
                console.error('Slot not found:', id);
                return;
            }
            document.getElementById('edit-slot-id').value = id;
            document.getElementById('edit-slot-date')._flatpickr.setDate(slot.date);
            document.getElementById('edit-slot-start').value = slot.start;
            document.getElementById('edit-slot-end').value = slot.end;
            document.getElementById('edit-slot-type').value = slot.type;
            document.getElementById('edit-slot-reason').value = slot.reason;
            document.getElementById('edit-slot-modal').classList.add('show');
        }

        // Adicionar horário
        document.getElementById('add-slot-form').addEventListener('submit', e => {
            e.preventDefault();
            console.log('Add slot form submitted');
            const date = document.getElementById('slot-date').value;
            const start = document.getElementById('slot-start').value;
            const end = document.getElementById('slot-end').value;
            const type = document.getElementById('slot-type').value;
            const reason = document.getElementById('slot-reason').value;

            console.log('Form values:', { date, start, end, type, reason });

            const validation = isValidSlot(date, start, end, type);
            if (!validation.valid) {
                console.error('Validation failed:', validation.message);
                showNotification(validation.message, 'error');
                return;
            }

            const newSlot = {
                id: availableSlots.length ? Math.max(...availableSlots.map(s => s.id)) + 1 : 1,
                date,
                start,
                end,
                type,
                reason,
                doctor: doctor.name
            };

            console.log('Adding new slot:', newSlot);
            availableSlots.unshift(newSlot);
            renderSlotsTable(document.getElementById('date-range').value);
            renderCalendar();
            showNotification('Horário adicionado com sucesso!', 'success');
            document.getElementById('add-slot-form').reset();
            document.getElementById('slot-date')._flatpickr.clear();
        });

        // Editar horário
        document.getElementById('edit-slot-form').addEventListener('submit', e => {
            e.preventDefault();
            console.log('Edit slot form submitted');
            const id = parseInt(document.getElementById('edit-slot-id').value);
            const date = document.getElementById('edit-slot-date').value;
            const start = document.getElementById('edit-slot-start').value;
            const end = document.getElementById('edit-slot-end').value;
            const type = document.getElementById('edit-slot-type').value;
            const reason = document.getElementById('edit-slot-reason').value;

            console.log('Edit values:', { id, date, start, end, type, reason });

            const validation = isValidSlot(date, start, end, type, id);
            if (!validation.valid) {
                console.error('Validation failed:', validation.message);
                showNotification(validation.message, 'error');
                return;
            }

            const index = availableSlots.findIndex(s => s.id === id);
            if (index !== -1) {
                availableSlots[index] = { id, date, start, end, type, reason, doctor: doctor.name };
                console.log('Slot updated:', availableSlots[index]);
                renderSlotsTable(document.getElementById('date-range').value);
                renderCalendar();
                showNotification('Horário atualizado com sucesso!', 'success');
                document.getElementById('edit-slot-modal').classList.remove('show');
            }
        });

        // Cancelar edição
        document.getElementById('cancel-edit').addEventListener('click', () => {
            console.log('Cancel edit');
            document.getElementById('edit-slot-modal').classList.remove('show');
        });

        // Excluir horário
        function deleteSlot(id) {
            console.log('Delete slot id:', id);
            if (!confirm('Tem certeza que deseja excluir este horário?')) return;
            const slot = availableSlots.find(s => s.id == id);
            if (getSlotStatus(slot) === 'Agendado' || getSlotStatus(slot) === 'Concluído') {
                console.error('Cannot delete booked slot');
                showNotification('Não é possível excluir um horário agendado ou concluído.', 'error');
                return;
            }
            availableSlots = availableSlots.filter(s => s.id != id);
            console.log('Slot deleted, remaining slots:', availableSlots.length);
            renderSlotsTable(document.getElementById('date-range').value);
            renderCalendar();
            showNotification('Horário excluído com sucesso!', 'success');
        }

        // Salvar horário padrão
        document.getElementById('save-schedule').addEventListener('click', () => {
            console.log('Saving schedule');
            const days = Array.from(document.querySelectorAll('.schedule-day:checked')).map(cb => parseInt(cb.value));
            const start = document.getElementById('schedule-start').value;
            const end = document.getElementById('schedule-end').value;
            const duration = parseInt(document.getElementById('consultation-duration').value);

            if (!days.length) {
                console.error('No days selected');
                showNotification('Selecione pelo menos um dia de trabalho.', 'error');
                return;
            }
            if (!start || !end) {
                console.error('Missing start or end time');
                showNotification('Defina o horário de atendimento.', 'error');
                return;
            }
            const startTime = new Date(`2025-06-12T${start}`);
            const endTime = new Date(`2025-06-12T${end}`);
            if (startTime >= endTime) {
                console.error('Invalid time range');
                showNotification('O horário de início deve ser antes do fim.', 'error');
                return;
            }

            doctorSchedule = { days, start, end, duration };
            console.log('Schedule saved:', doctorSchedule);

            const now = new Date('2025-06-12T13:22:00');
            const endDate = new Date(now);
            endDate.setDate(now.getDate() + 30);
            for (let d = new Date(now); d <= endDate; d.setDate(d.getDate() + 1)) {
                const dateStr = d.toISOString().split('T')[0];
                if (!availableSlots.some(slot => slot.date === dateStr)) {
                    const newSlots = generateSlotsForDate(dateStr);
                    availableSlots.push(...newSlots);
                    console.log('Generated slots for', dateStr, ':', newSlots.length);
                }
            }
            renderSlotsTable(document.getElementById('date-range').value);
            renderCalendar();
            showNotification('Horário padrão salvo com sucesso!', 'success');
        });

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Initializing page');

            flatpickr('.date-input', {
                dateFormat: 'Y-m-d',
                locale: 'pt',
                minDate: '2025-06-12',
                onReady: () => console.log('Flatpickr initialized')
            });

            document.getElementById('notification-close').addEventListener('click', () => {
                console.log('Closing notification');
                document.getElementById('notification').classList.remove('show');
            });

            const userMenuBtn = document.getElementById('user-menu-btn');
            const dropdownMenu = document.getElementById('dropdown-menu');
            userMenuBtn.addEventListener('click', () => {
                console.log('Toggling user menu');
                dropdownMenu.classList.toggle('show');
            });
            document.addEventListener('click', e => {
                if (!userMenuBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    console.log('Closing user menu');
                    dropdownMenu.classList.remove('show');
                }
            });

            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebar = document.querySelector('.sidebar');
            mobileMenuBtn.addEventListener('click', () => {
                console.log('Toggling mobile menu');
                sidebar.classList.toggle('show');
            });

            document.getElementById('date-range').addEventListener('change', () => {
                console.log('Date range changed:', document.getElementById('date-range').value);
                renderSlotsTable(document.getElementById('date-range').value);
            });

            document.getElementById('logout-btn').addEventListener('click', () => {
                console.log('Logging out');
                showNotification('Sessão encerrada com sucesso!', 'success');
                dropdownMenu.classList.remove('show');
            });

            document.getElementById('prev-month').addEventListener('click', () => {
                console.log('Previous month');
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                renderCalendar();
            });
            document.getElementById('next-month').addEventListener('click', () => {
                console.log('Next month');
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                renderCalendar();
            });

            renderCalendar();
            renderSlotsTable('next-30');
        });
    </script>
</body>
</html>