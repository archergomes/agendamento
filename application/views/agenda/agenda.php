<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Consultas - Hospital Matlhovele</title>
    <meta name="description" content="Sistema inteligente de agendamento de consultas para o Hospital Público de Matlhovele">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .calendar-day.selected {
            background-color: #3b82f6;
            color: white;
        }
        .fc-button {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
        }
        .fc-button:hover {
            background-color: #2563eb !important;
        }
        .specialty-item.selected, .doctor-item.selected {
            background-color: #3b82f6;
            color: white;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .fc-daygrid-day.selected {
            background-color: #e0f2fe !important;
        }
        .fc-daygrid-day.available {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        .fc-daygrid-day.available .fc-daygrid-day-number {
            color: white !important;
        }
        .fc-day-past {
            background-color: #f3f4f6 !important;
            cursor: not-allowed !important;
        }
        #time-slot-modal, #review-modal, #confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 950;
            justify-content: center;
            align-items: center;
        }
        #time-slot-modal.show, #review-modal.show, #confirmation-modal.show {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .slot-button {
            display: block;
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            text-align: center;
            cursor: pointer;
        }
        .slot-button.available:hover {
            background-color: #e0f2fe;
        }
        .slot-button.booked {
            background-color: #ef4444;
            color: white;
            cursor: not-allowed;
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
            height: 100%;
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%);
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
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 1rem;
        }
        .sidebar.collapsed .close-sidebar-btn {
            display: none;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 800;
            background-color: #2563eb;
        }
        .main-content {
            margin-left: 60px;
            margin-top: 80px;
            padding: 1rem;
            transition: margin-left 0.3s ease-in-out;
            min-height: calc(100vh - 80px - 128px);
        }
        .main-content.expanded {
            margin-left: 250px;
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
            }
            .main-content {
                margin-left: 0;
                margin-top: 64px;
            }
            .sidebar.collapsed {
                width: 250px;
            }
            .sidebar.collapsed .sidebar-text {
                display: inline;
            }
            .sidebar.collapsed .sidebar-header {
                justify-content: space-between;
                padding: 1rem;
            }
            .sidebar.collapsed .close-sidebar-btn {
                display: block;
            }
        }
        .sidebar-nav {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
        }
        .sidebar-nav a, .sidebar-nav button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 0.25rem;
            color: #4b5563;
        }
        .sidebar-nav i {
            font-size: 1.5rem;
            width: 28px;
            text-align: center;
        }
        .sidebar.collapsed .sidebar-nav a, .sidebar.collapsed .sidebar-nav button {
            justify-content: center;
            padding: 10px;
        }
        .sidebar-nav .logout {
            margin-top: auto;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Notification -->
    <div id="notification" role="alert">
        <span id="notification-message"></span>
        <button id="notification-close" class="ml-2 text-white hover:text-gray-200">×</button>
    </div>

    <!-- Left Sidebar -->
    <div id="sidebar-menu" class="sidebar bg-white shadow-lg desktop">
        <div class="sidebar-header flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-700 sidebar-text">Menu do Paciente</h2>
            <button id="toggle-sidebar-btn" class="text-gray-700 hover:text-gray-900" aria-label="Alternar menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <button id="close-sidebar-btn" class="text-gray-700 hover:text-gray-900 md:hidden close-sidebar-btn" aria-label="Fechar menu">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <div class="main-menu">
                <a href="<?= base_url('agenda/') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Home</span>
                </a>
                <a href="<?= base_url('agenda/agendamentos') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Meus Agendamentos</span>
                </a>
                <a href="<?= base_url('agenda/perfil') ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-user"></i>
                    <span class="sidebar-text">Perfil</span>
                </a>
            </div>
            <button id="logout-btn" class="block w-full text-left text-gray-700 hover:bg-blue-50 rounded logout">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text">Sair</span>
            </button>
        </nav>
    </div>

    <!-- Header/Navbar -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-hospital-alt text-2xl" aria-label="Ícone do Hospital Matlhovele"></i>
                <h1 class="text-xl font-bold">Hospital Matlhovele</h1>
            </div>
            <button id="mobile-menu-btn" class="md:hidden text-white hover:text-gray-200" aria-label="Abrir menu">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 main-content">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Agendar Consulta</h2>

            <!-- Step 1: Selecionar Especialidade -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-700 mb-4">1. Selecione a especialidade médica</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="specialty-container">
                    <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Cardiologia">
                        <i class="fas fa-heartbeat text-blue-500 text-2xl mb-2" aria-label="Ícone de Cardiologia"></i>
                        <p class="font-medium">Cardiologia</p>
                    </div>
                    <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Pneumologia">
                        <i class="fas fa-lungs text-blue-500 text-2xl mb-2" aria-label="Ícone de Pneumologia"></i>
                        <p class="font-medium">Pneumologia</p>
                    </div>
                    <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Neurologia">
                        <i class="fas fa-brain text-blue-500 text-2xl mb-2" aria-label="Ícone de Neurologia"></i>
                        <p class="font-medium">Neurologia</p>
                    </div>
                    <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Ortopedia">
                        <i class="fas fa-bone text-blue-500 text-2xl mb-2" aria-label="Ícone de Ortopedia"></i>
                        <p class="font-medium">Ortopedia</p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Selecionar Médico -->
            <div class="mb-8 doctor-selection-container">
                <h3 class="text-lg font-medium text-gray-700 mb-4">2. Escolha o médico</h3>
                <div class="space-y-4" id="doctor-container"></div>
            </div>

            <!-- Step 3: Selecionar Data e Horário -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-700 mb-4">3. Escolha a data e horário</h3>
                <div id="calendar" class="bg-white p-4 rounded-lg shadow-inner"></div>
            </div>

            <!-- Modal para Seleção de Horários -->
            <div id="time-slot-modal">
                <div class="modal-content">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Selecione um horário</h3>
                    <div id="time-slot-list" class="mb-4"></div>
                    <button id="modal-cancel-btn" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition w-full">
                        Cancelar
                    </button>
                </div>
            </div>

            <!-- Modal para Revisão do Agendamento -->
            <div id="review-modal">
                <div class="modal-content">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Revisar Agendamento</h3>
                    <dl id="review-details" class="mb-4 grid grid-cols-1 gap-2">
                        <div>
                            <dt class="font-medium">Especialidade:</dt>
                            <dd id="review-specialty"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">Médico:</dt>
                            <dd id="review-doctor"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">Data:</dt>
                            <dd id="review-date"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">Horário:</dt>
                            <dd id="review-time"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">Nome:</dt>
                            <dd id="review-name"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">Telefone:</dt>
                            <dd id="review-phone"></dd>
                        </div>
                        <div>
                            <dt class="font-medium">BI:</dt>
                            <dd id="review-bi"></dd>
                        </div>
                    </dl>
                    <div class="flex space-x-2">
                        <button id="review-confirm-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition flex-1">
                            Confirmar
                        </button>
                        <button id="review-cancel-btn" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition flex-1">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal para Confirmação Final -->
            <div id="confirmation-modal">
                <div class="modal-content">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Agendamento Confirmado</h3>
                    <p id="confirmation-message" class="mb-4"></p>
                    <button id="confirmation-close-btn" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition w-full">
                        Fechar
                    </button>
                </div>
            </div>

            <!-- Step 4: Confirmar Agendamento -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-700 mb-4">4. Confirmar agendamento</h3>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nome completo</label>
                    <input type="text" id="name" class="w-full p-2 border rounded" aria-required="true">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Número de telefone</label>
                    <input type="tel" id="phone" class="w-full p-2 border rounded" aria-required="true" pattern="\+258\s?[8][0-49][0-9]{7}">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Número do BI</label>
                    <input type="text" id="bi" class="w-full p-2 border rounded" aria-required="true">
                </div>
                <button id="confirm-btn" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition" aria-label="Confirmar agendamento">
                    Confirmar Agendamento
                </button>
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

    <script>
        // Dados iniciais
        const doctors = [
            { id: 1, name: "Dr. João Matlhovele", specialty: "Cardiologia", experience: "10 anos", image: "https://picsum.photos/100?random=1" },
            { id: 2, name: "Dra. Maria Nhacumbe", specialty: "Pneumologia", experience: "8 anos", image: "https://picsum.photos/100?random=2" },
            { id: 3, name: "Dr. Pedro Silva", specialty: "Neurologia", experience: "12 anos", image: "https://picsum.photos/100?random=3" },
            { id: 4, name: "Dra. Ana Mbeve", specialty: "Ortopedia", experience: "7 anos", image: "https://picsum.photos/100?random=4" }
        ];

        const availableSlots = {
            "2025-06-23": { "Dr. João Matlhovele": ["14:00", "15:00", "16:00"], "Dra. Maria Nhacumbe": ["13:00", "14:00"], "Dr. Pedro Silva": ["12:00", "13:30"], "Dra. Ana Mbeve": ["14:30", "15:30"] },
            "2025-06-24": { "Dr. João Matlhovele": ["09:00", "10:00"], "Dra. Maria Nhacumbe": ["14:00", "15:00"], "Dr. Pedro Silva": ["10:30", "11:30"], "Dra. Ana Mbeve": ["08:00", "09:30"] },
            "2025-06-25": { "Dr. João Matlhovele": ["11:00", "12:00"], "Dra. Maria Nhacumbe": ["13:00", "14:00"], "Dr. Pedro Silva": ["15:00", "16:00"], "Dra. Ana Mbeve": ["10:00", "11:00"] }
        };

        let bookedSlots = JSON.parse(localStorage.getItem('bookedSlots')) || {};
        let bookedAppointments = JSON.parse(localStorage.getItem('bookedAppointments')) || [];
        let userProfiles = JSON.parse(localStorage.getItem('userProfiles')) || {};
        let selectedSpecialty = null;
        let selectedDoctor = null;
        let selectedDate = null;
        let selectedTime = null;
        let calendar;

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

        // Renderizar lista de médicos
        function renderDoctors(specialty = null) {
            const container = document.getElementById('doctor-container');
            const filteredDoctors = specialty ? doctors.filter(d => d.specialty === specialty) : doctors;
            container.innerHTML = filteredDoctors.map(doctor => `
                <div class="doctor-item flex items-center p-4 border rounded-lg hover:bg-blue-50 cursor-pointer" data-id="${doctor.id}" data-name="${doctor.name}">
                    <img src="${doctor.image}" alt="${doctor.name}" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <p class="font-medium">${doctor.name}</p>
                        <p class="text-sm text-gray-600">${doctor.specialty} - ${doctor.experience} de experiência</p>
                    </div>
                </div>
            `).join('');
            
            document.querySelectorAll('.doctor-item').forEach(item => {
                item.addEventListener('click', function () {
                    document.querySelectorAll('.doctor-item').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedDoctor = this.dataset.name;
                    selectedDate = null;
                    selectedTime = null;
                    updateAvailableDays();
                });
            });
        }

        // Função para destacar dias com horários disponíveis
        function updateAvailableDays() {
            document.querySelectorAll('.fc-daygrid-day').forEach(el => {
                el.classList.remove('available', 'selected');
            });

            if (!selectedDoctor) return;

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            Object.keys(availableSlots).forEach(date => {
                const slotDate = new Date(date);
                if (slotDate >= today && availableSlots[date][selectedDoctor] && availableSlots[date][selectedDoctor].length > 0) {
                    const booked = bookedSlots[date] || [];
                    let available = availableSlots[date][selectedDoctor];
                    if (date === today.toISOString().split('T')[0]) {
                        available = available.filter(slot => {
                            const [hours, minutes] = slot.split(':').map(Number);
                            const slotTime = new Date(date);
                            slotTime.setHours(hours, minutes, 0, 0);
                            return slotTime > new Date();
                        });
                    }
                    available = available.filter(slot => !booked.includes(slot));
                    if (available.length > 0) {
                        const dayEl = document.querySelector(`.fc-daygrid-day[data-date="${date}"]`);
                        if (dayEl) {
                            dayEl.classList.add('available');
                        }
                    }
                }
            });
        }

        // Renderizar horários no modal
        function showTimeSlots(date, doctorName) {
            const today = new Date();
            const selected = new Date(date);

            if (selectedDate < today.setHours(0, 0, 0, 0)) {
                showNotification('Não é possível agendar em datas passadas.', 'error');
                return;
            }

            if (!doctorName || !availableSlots[date]) {
                showNotification('Não há horários disponíveis para esta data ou médico.', 'error');
                return;
            }

            const slots = availableSlots[date][doctorName] || [];
            const booked = bookedSlots[date] || [];

            let availableSlotsFiltered = slots;
            if (date === today.toISOString().split('T')[0]) {
                availableSlotsFiltered = slots.filter(slot => {
                    const [hours, minutes] = slot.split(':').map(Number);
                    const slotTime = new Date(date);
                    slotTime.setHours(hours, minutes, 0, 0);
                    return slotTime > today;
                });
            }
            availableSlotsFiltered = availableSlotsFiltered.filter(slot => !booked.includes(slot));

            if (availableSlotsFiltered.length === 0) {
                showNotification(`Não há horários disponíveis para ${doctorName} em ${date}.`, 'error');
                return;
            }

            const modal = document.getElementById('time-slot-modal');
            const slotList = document.getElementById('time-slot-list');
            slotList.innerHTML = availableSlotsFiltered.map(slot => `
                <button class="slot-button available" data-time="${slot}">
                    ${slot} (Disponível)
                </button>
            `).join('');

            modal.classList.add('show');

            document.querySelectorAll('.slot-button.available').forEach(button => {
                button.addEventListener('click', function () {
                    selectedTime = this.dataset.time;
                    showNotification(`Horário selecionado: ${selectedTime} em ${date}`, 'success');
                    modal.classList.remove('show');
                });
            });
        }

        // Função de inicialização
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) {
                console.error('Elemento #calendar não encontrado!');
                return;
            }

            // Inicializar notificação
            document.getElementById('notification-close').addEventListener('click', function () {
                document.getElementById('notification').classList.remove('show');
            });

            // Inicializar menu lateral
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const mainContent = document.querySelector('.main-content');

            sidebarMenu.classList.add('collapsed');
            mainContent.classList.remove('expanded');

            mobileMenuBtn.addEventListener('click', () => {
                sidebarMenu.classList.add('show');
                sidebarMenu.classList.remove('collapsed');
                mainContent.classList.add('expanded');
            });

            closeSidebarBtn.addEventListener('click', () => {
                sidebarMenu.classList.remove('show');
                mainContent.classList.remove('expanded');
            });

            toggleSidebarBtn.addEventListener('click', () => {
                sidebarMenu.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });

            document.addEventListener('click', (e) => {
                const isClickInsideSidebar = sidebarMenu.contains(e.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(e.target);
                const isSidebarOpen = sidebarMenu.classList.contains('show');
                const isModalOpen = document.querySelectorAll('.show').length > 0;
                if (!isClickInsideSidebar && !isClickOnMenuBtn && isSidebarOpen && !isModalOpen) {
                    sidebarMenu.classList.remove('show');
                    mainContent.classList.remove('expanded');
                }
            });

            // Inicializar FullCalendar
            try {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'pt',
                    initialDate: '2025-06-23',
                    validRange: { start: '2025-06-23' },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    events: [],
                    dateClick: (info) => {
                        if (!selectedDoctor) {
                            showNotification('Selecione um médico antes de escolher a data.', 'error');
                            return;
                        }
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const clickedDate = new Date(info.dateStr);
                        if (clickedDate < today) {
                            showNotification('Não é possível selecionar datas passadas.', 'error');
                            return;
                        }
                        document.querySelectorAll('.fc-daygrid-day').forEach(el => el.classList.remove('selected'));
                        info.dayEl.classList.add('selected');
                        selectedDate = info.dateStr;
                        showTimeSlots(selectedDate, selectedDoctor);
                    },
                    datesSet: () => {
                        updateAvailableDays();
                    },
                    selectable: true,
                    select: (info) => {
                        if (!selectedDoctor) {
                            showNotification('Selecione um médico antes de escolher a data.', 'error');
                            return;
                        }
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const selected = new Date(info.startStr);
                        if (selected < today) {
                            showNotification('Não é possível selecionar datas passadas.', 'error');
                            return;
                        }
                        selectedDate = info.startStr;
                        document.querySelectorAll('.fc-daygrid-day').forEach(el => el.classList.remove('selected'));
                        const dayEl = document.querySelector(`.fc-daygrid-day[data-date="${selectedDate}"]`);
                        if (dayEl) dayEl.classList.add('selected');
                        showTimeSlots(selectedDate, selectedDoctor);
                    }
                });
                calendar.render();
            } catch (error) {
                console.error('Erro ao inicializar o FullCalendar:', error);
            }

            // Eventos para especialidades
            document.querySelectorAll('.specialty-item').forEach(item => {
                item.addEventListener('click', function () {
                    document.querySelectorAll('.specialty-item').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedSpecialty = this.dataset.specialty;
                    renderDoctors(selectedSpecialty);
                    selectedDoctor = null;
                    selectedDate = null;
                    selectedTime = null;
                });
            });

            // Inicializar lista de médicos
            renderDoctors();

            // Evento para fechar o modal de horários
            document.getElementById('modal-cancel-btn').addEventListener('click', () => {
                document.getElementById('time-slot-modal').classList.remove('show');
            });

            // Evento para fechar o modal de revisão
            document.getElementById('review-cancel-btn').addEventListener('click', () => {
                document.getElementById('review-modal').classList.remove('show');
            });

            // Evento para confirmar no modal de revisão
            document.getElementById('review-confirm-btn').addEventListener('click', () => {
                if (!bookedSlots[selectedDate]) bookedSlots[selectedDate] = [];
                bookedSlots[selectedDate].push(selectedTime);

                bookedAppointments.push({
                    specialty: selectedSpecialty,
                    doctor: selectedDoctor,
                    date: selectedDate,
                    time: selectedTime,
                    name: formData.name,
                    phone: formData.phone,
                    bi: formData.bi,
                    status: 'Pending',
                    cancellationReason: ''
                });

                // Salvar perfil do usuário
                userProfiles[formData.bi] = {
                    name: formData.name,
                    phone: formData.phone,
                    bi: formData.bi,
                    email: userProfiles[formData.bi]?.email || ''
                };
                localStorage.setItem('userProfiles', JSON.stringify(userProfiles));

                // Salvar agendamentos
                localStorage.setItem('bookedAppointments', JSON.stringify(bookedAppointments));
                localStorage.setItem('bookedSlots', JSON.stringify(bookedSlots));

                const confirmationMessage = `Agendamento confirmado!<br>Nome: ${formData.name}<br>Especialidade: ${selectedSpecialty}<br>Médico: ${selectedDoctor}<br>Data: ${selectedDate}<br>Horário: ${selectedTime}<br>Telefone: ${formData.phone}<br>BI: ${formData.bi}`;
                document.getElementById('confirmation-message').innerHTML = confirmationMessage;
                document.getElementById('confirmation-modal').classList.add('show');

                document.getElementById('review-modal').classList.remove('show');
                showNotification('Agendamento enviado para aprovação!', 'success');

                // Limpar formulário
                document.getElementById('name').value = '';
                document.getElementById('phone').value = '';
                document.getElementById('bi').value = '';
                updateAvailableDays();
                selectedTime = null;
            });

            // Evento para fechar o modal de confirmação
            document.getElementById('confirmation-close-btn').addEventListener('click', () => {
                document.getElementById('confirmation-modal').classList.remove('show');
            });

            // Evento para confirmar agendamento (form inicial)
            let formData = {};
            document.getElementById('confirm-btn').addEventListener('click', () => {
                formData.name = document.getElementById('name').value.trim();
                formData.phone = document.getElementById('phone').value.trim();
                formData.bi = document.getElementById('bi').value.trim();

                if (!formData.name || !formData.phone || !formData.bi) {
                    showNotification('Por favor, preencha todos os campos do formulário.', 'error');
                    return;
                }
                if (!formData.phone.match(/\+258\s*[8][0-49][0-9]{7}/)) {
                    showNotification('Número de telefone inválido. Use o formato +258 8X XXXXXXX.', 'error');
                    return;
                }
                if (!selectedSpecialty || !selectedDoctor || !selectedDate || !selectedTime) {
                    showNotification('Selecione especialidade, médico, data e horário antes de continuar.', 'error');
                    return;
                }

                // Preencher modal de revisão
                document.getElementById('review-specialty').textContent = selectedSpecialty;
                document.getElementById('review-doctor').textContent = selectedDoctor;
                document.getElementById('review-date').textContent = selectedDate;
                document.getElementById('review-time').textContent = selectedTime;
                document.getElementById('review-name').textContent = formData.name;
                document.getElementById('review-phone').textContent = formData.phone;
                document.getElementById('review-bi').textContent = formData.bi;

                document.getElementById('review-modal').classList.add('show');
            });

            // Evento para logout
            document.getElementById('logout-btn').addEventListener('click', () => {
                showNotification('Sessão encerrada com sucesso!', 'success');
                sidebarMenu.classList.remove('show');
                mainContent.classList.remove('expanded');
                localStorage.removeItem('bookedAppointments');
                localStorage.removeItem('bookedSlots');
                localStorage.removeItem('userProfiles');
                selectedSpecialty = null;
                selectedDoctor = null;
                selectedDate = null;
                selectedTime = null;
                document.getElementById('name').value = '';
                document.getElementById('phone').value = '';
                document.getElementById('bi').value = '';
                renderDoctors();
                updateAvailableDays();
                // Em um sistema real, redirecionar para /login
                // window.location.href = '/login';
            });
        });
    </script>
</body>
</html>