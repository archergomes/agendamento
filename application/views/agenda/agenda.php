<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
            background-color: #f9fafb;
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

        .specialty-item.selected,
        .doctor-item.selected {
            background-color: #3b82f6;
            color: white;
        }

        .specialty-item.selected i,
        .doctor-item.selected i {
            color: white !important;
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

        #time-slot-modal,
        #review-modal,
        #confirmation-modal {
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

        #time-slot-modal.show,
        #review-modal.show,
        #confirmation-modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 80vh;
            overflow-y: auto;
        }

        .loading {
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }

        .doctor-selection-container {
            position: relative;
        }

        #doctor-container {
            max-height: 24rem;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        #doctor-container::-webkit-scrollbar {
            width: 6px;
        }

        #doctor-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #doctor-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        /* Chat Bot Styles - MODIFICADO */
        #chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        #chat-btn:hover {
            transform: scale(1.1);
        }

        /* Chat Modal - Agora no canto inferior */
        #chat-modal {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 500px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        #chat-modal.show {
            display: flex;
            animation: slideInUp 0.3s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        #close-chat-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        #close-chat-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .chat-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            background-color: #f8fafc;
            max-height: 350px;
        }

        .chat-message {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            max-width: 85%;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-message.user {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0.25rem;
        }

        .chat-message.bot {
            background-color: white;
            color: #374151;
            border: 1px solid #e5e7eb;
            margin-right: auto;
            border-bottom-left-radius: 0.25rem;
        }

        .chat-input-container {
            display: flex;
            padding: 1rem;
            gap: 0.5rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .chat-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 1.5rem;
            outline: none;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .chat-send-btn {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 50%;
            cursor: pointer;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .chat-send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }

        .chat-send-btn:active {
            transform: scale(0.95);
        }

        /* Responsividade para mobile */
        @media (max-width: 640px) {
            #chat-modal {
                width: calc(100vw - 40px);
                right: 20px;
                left: 20px;
                height: 70vh;
                bottom: 80px;
            }
            
            #chat-btn {
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
            }
        }

        /* Scrollbar personalizada para as mensagens */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* CORREÇÃO DO LAYOUT PRINCIPAL - NOVA ESTRUTURA */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 80px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
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

        @media (min-width: 768px) {
            #mobile-menu-btn, #sidebar-overlay { display: none; }
        }

        @media (max-width: 767px) {
            .sidebar.desktop { 
                transform: translateX(-100%); 
                width: 250px;
            }
            .sidebar.show { transform: translateX(0); }
            .page-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            .page-wrapper.expanded {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 800;
        }

        #sidebar-overlay.show {
            display: block;
        }

        .sidebar-nav {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1rem;
        }

        .sidebar-nav a,
        .sidebar-nav button {
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

        .sidebar.desktop .sidebar-nav a, 
        .sidebar.desktop .sidebar-nav button {
            justify-content: center;
            padding: 10px;
        }

        .sidebar.desktop.expanded .sidebar-nav a,
        .sidebar.desktop.expanded .sidebar-nav button {
            justify-content: flex-start;
            padding: 10px 16px;
        }

        .sidebar-nav .logout {
            margin-top: auto;
        }

        .appointments-list {
            margin-top: 1rem;
        }

        .appointment-item {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
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

        /* Correção para o modal de horários: lista com scroll */
        #time-slot-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #d1d5db;
            border-radius: 0.25rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        #time-slot-list::-webkit-scrollbar {
            width: 6px;
        }

        #time-slot-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #time-slot-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #time-slot-list::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
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
                <a href="<?= site_url('agenda'); ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Home</span>
                </a>
                <a href="<?= site_url('agenda/agendamentos'); ?>" id="meus-agendamentos-btn" class="block text-gray-700 hover:bg-blue-50 rounded">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Meus Agendamentos</span>
                </a>
                <a href="<?= site_url('agenda/perfil'); ?>" class="block text-gray-700 hover:bg-blue-50 rounded">
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

    <!-- Overlay para fechar sidebar em mobile -->
    <div id="sidebar-overlay"></div>

    <!-- Page Wrapper que contém todo o conteúdo exceto sidebar -->
    <div class="page-wrapper">
        <!-- Header/Navbar - SEM alterações de margem -->
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

        <!-- Main Content - CORRIGIDO -->
        <main class="main-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Agendar Consulta</h2>

                    <!-- Step 1: Selecionar Especialidade -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">1. Selecione a especialidade médica</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="specialty-container">
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Medicina Geral">
                                <i class="fas fa-user-md text-blue-500 text-2xl mb-2" aria-label="Ícone de Medicina Geral"></i>
                                <p class="font-medium">Medicina Geral</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Cardiologia">
                                <i class="fas fa-heartbeat text-blue-500 text-2xl mb-2" aria-label="Ícone de Cardiologia"></i>
                                <p class="font-medium">Cardiologia</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Pediatria">
                                <i class="fas fa-child text-blue-500 text-2xl mb-2" aria-label="Ícone de Pediatria"></i>
                                <p class="font-medium">Pediatria</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Ortopedia">
                                <i class="fas fa-bone text-blue-500 text-2xl mb-2" aria-label="Ícone de Ortopedia"></i>
                                <p class="font-medium">Ortopedia</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Ginecologia">
                                <i class="fas fa-female text-blue-500 text-2xl mb-2" aria-label="Ícone de Ginecologia"></i>
                                <p class="font-medium">Ginecologia</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Neurologia">
                                <i class="fas fa-brain text-blue-500 text-2xl mb-2" aria-label="Ícone de Neurologia"></i>
                                <p class="font-medium">Neurologia</p>
                            </div>
                            <div class="specialty-item border rounded-lg p-4 hover:bg-blue-50 cursor-pointer" data-specialty="Cirurgia Geral">
                                <i class="fas fa-cut text-blue-500 text-2xl mb-2" aria-label="Ícone de Cirurgia Geral"></i>
                                <p class="font-medium">Cirurgia Geral</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Selecionar Médico -->
                    <div class="mb-8 doctor-selection-container">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">2. Escolha o médico</h3>
                        <div id="doctor-header" class="hidden mb-2 text-sm font-medium text-blue-600"></div>
                        <div id="doctor-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto border rounded-lg p-4 bg-gray-50">
                            <p class="text-gray-500 text-center py-8 col-span-full">Selecione uma especialidade para ver os médicos disponíveis.</p>
                        </div>
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
                            <div id="time-slot-list"></div>
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
                            <input type="text" id="name" value="<?= isset($paciente->Nome) ? $paciente->Nome . ' ' . $paciente->Sobrenome : ''; ?>" class="w-full p-2 border rounded" aria-required="true">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Número de telefone</label>
                            <input type="tel" id="phone" value="<?= isset($paciente->Telefone) ? $paciente->Telefone : ''; ?>" class="w-full p-2 border rounded" aria-required="true">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Número do BI</label>
                            <input type="text" id="bi" value="<?= isset($paciente->BI) ? $paciente->BI : ''; ?>" class="w-full p-2 border rounded" aria-required="true">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Motivo da consulta (opcional)</label>
                            <textarea id="motivo" class="w-full p-2 border rounded" rows="3" placeholder="Descreva o motivo da consulta..."></textarea>
                        </div>
                        <button id="confirm-btn" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition" aria-label="Confirmar agendamento">
                            Confirmar Agendamento
                        </button>
                    </div>

                    <!-- Lista de Meus Agendamentos (escondida inicialmente) -->
                    <div id="appointments-section" class="hidden mt-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Meus Agendamentos</h3>
                        <div id="appointments-list" class="appointments-list"></div>
                    </div>
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
    </div> <!-- Fim do page-wrapper -->

    <!-- Chat Bot Button -->
    <button id="chat-btn" title="Falar com Assistente">
        <i class="fas fa-comments"></i>
    </button>

    <!-- Chat Modal - MODIFICADO -->
    <div id="chat-modal">
        <div class="chat-header">
            <h3>Assistente de Agendamento</h3>
            <button id="close-chat-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-messages" id="chat-messages">
            <div class="chat-message bot">
                Olá! Sou o assistente do Hospital Matlhovele. Como posso ajudar? Descreva seus sintomas ou o problema para eu sugerir a especialidade certa.
            </div>
        </div>
        <div class="chat-input-container">
            <input type="text" id="chat-input" class="chat-input" placeholder="Digite sua mensagem...">
            <button id="chat-send-btn" class="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
        // Variáveis globais
        let doctors = [];
        let availableSlots = {};
        let selectedSpecialty = null;
        let selectedDoctor = null;
        let selectedDoctorId = null;
        let selectedDate = null;
        let selectedTime = null;
        let calendar;

        // Função para exibir notificações
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            messageEl.innerHTML = message;
            notification.className = `show ${type}`;
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Close notification
        document.getElementById('notification-close')?.addEventListener('click', () => {
            document.getElementById('notification').classList.remove('show');
        });

        // Chat Bot Logic - VERSÃO MELHORADA
        const chatBtn = document.getElementById('chat-btn');
        const chatModal = document.getElementById('chat-modal');
        const closeChatBtn = document.getElementById('close-chat-btn');
        const chatInput = document.getElementById('chat-input');
        const chatSendBtn = document.getElementById('chat-send-btn');
        const chatMessages = document.getElementById('chat-messages');

        // Especialidades e palavras-chave para sugestões - Expandida
        const specialtySuggestions = {
            'coração': 'Cardiologia',
            'dor no peito': 'Cardiologia',
            'pressão alta': 'Cardiologia',
            'pressão arterial': 'Cardiologia',
            'batimento cardíaco': 'Cardiologia',
            'colesterol': 'Cardiologia',
            'criança': 'Pediatria',
            'bebé': 'Pediatria',
            'bebê': 'Pediatria',
            'crianças': 'Pediatria',
            'infantil': 'Pediatria',
            'vacina': 'Pediatria',
            'dor nas costas': 'Ortopedia',
            'fratura': 'Ortopedia',
            'osso': 'Ortopedia',
            'articulação': 'Ortopedia',
            'joelho': 'Ortopedia',
            'ombro': 'Ortopedia',
            'gravidez': 'Ginecologia',
            'menstruação': 'Ginecologia',
            'menstrual': 'Ginecologia',
            'ginecológica': 'Ginecologia',
            'obstetrícia': 'Ginecologia',
            'cabeça': 'Neurologia',
            'dor de cabeça': 'Neurologia',
            'enxaqueca': 'Neurologia',
            'tontura': 'Neurologia',
            'convulsão': 'Neurologia',
            'memória': 'Neurologia',
            'cirurgia': 'Cirurgia Geral',
            'ferida': 'Cirurgia Geral',
            'operar': 'Cirurgia Geral',
            'geral': 'Medicina Geral',
            'febre': 'Medicina Geral',
            'gripe': 'Medicina Geral',
            'tosse': 'Medicina Geral',
            'dor de garganta': 'Medicina Geral',
            'check-up': 'Medicina Geral'
        };

        // Mensagens de boas-vindas do bot
        const welcomeMessages = [
            "Olá! Sou o assistente virtual do Hospital Matlhovele. Como posso ajudar você hoje?",
            "Bem-vindo! Descreva seus sintomas ou o motivo da consulta para eu sugerir a especialidade adequada.",
            "Oi! Estou aqui para ajudar. Conte-me sobre o que você está sentindo para indicar o melhor especialista."
        ];

        if (chatBtn) {
            chatBtn.addEventListener('click', () => {
                chatModal.classList.add('show');
                chatInput.focus();
                
                // Adiciona mensagem de boas-vindas aleatória se for a primeira vez
                if (chatMessages.children.length <= 1) {
                    const welcomeMsg = welcomeMessages[Math.floor(Math.random() * welcomeMessages.length)];
                    addBotMessage(welcomeMsg);
                }
            });
        }

        if (closeChatBtn) {
            closeChatBtn.addEventListener('click', () => {
                chatModal.classList.remove('show');
                chatInput.value = '';
            });
        }

        if (chatSendBtn) {
            chatSendBtn.addEventListener('click', sendChatMessage);
        }

        if (chatInput) {
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendChatMessage();
                }
            });
        }

        // Função para adicionar mensagem do bot
        function addBotMessage(message) {
            const botMsg = document.createElement('div');
            botMsg.className = 'chat-message bot';
            botMsg.innerHTML = message;
            chatMessages.appendChild(botMsg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Função para adicionar mensagem do usuário
        function addUserMessage(message) {
            const userMsg = document.createElement('div');
            userMsg.className = 'chat-message user';
            userMsg.textContent = message;
            chatMessages.appendChild(userMsg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function sendChatMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            // Adiciona mensagem do usuário
            addUserMessage(message);

            // Limpa input
            chatInput.value = '';

            // Processa a resposta do bot
            setTimeout(() => {
                let response = '';
                let foundSpecialty = null;

                // Procura por palavras-chave
                for (let keyword in specialtySuggestions) {
                    if (message.toLowerCase().includes(keyword)) {
                        foundSpecialty = specialtySuggestions[keyword];
                        break;
                    }
                }

                if (foundSpecialty) {
                    response = `Com base na sua descrição, recomendo a especialidade de <strong>${foundSpecialty}</strong>. `;
                    response += `Clique em "${foundSpecialty}" na lista de especialidades acima para agendar sua consulta. `;
                    response += `Posso ajudar com mais alguma coisa?`;
                } else if (message.toLowerCase().includes('obrigado') || message.toLowerCase().includes('obrigada')) {
                    response = `De nada! Estou aqui para ajudar. Se precisar de mais alguma coisa, é só falar. 😊`;
                } else if (message.toLowerCase().includes('horário') || message.toLowerCase().includes('funcionamento')) {
                    response = `O Hospital Matlhovele funciona:<br>
                               • Segunda a Sexta: 7h30 - 16h30<br>
                               • Sábado: 8h00 - 12h00<br>
                               • Emergências: 24 horas`;
                } else if (message.toLowerCase().includes('telefone') || message.toLowerCase().includes('contacto')) {
                    response = `📞 Telefone: +258 84 123 4567<br>
                               📍 Endereço: Av. 25 de Setembro, Maputo<br>
                               📧 Email: info@mathlovele.gov.mz`;
                } else {
                    response = `Desculpe, não entendi completamente. Pode descrever melhor seus sintomas? `;
                    response += `Por exemplo: "estou com dor de cabeça frequente" ou "minha filha está com febre".`;
                }

                addBotMessage(response);
                chatInput.focus();
            }, 1000);
        }

        // Fechar chat ao clicar fora (opcional)
        document.addEventListener('click', (e) => {
            if (chatModal.classList.contains('show') && 
                !chatModal.contains(e.target) && 
                e.target !== chatBtn) {
                chatModal.classList.remove('show');
            }
        });

        // AJAX para carregar médicos
        async function loadDoctors(query = '', specialty = '', limit = 10, offset = 0) {
            try {
                console.log('Carregando médicos com filtro:', { query, specialty, limit, offset });
                const params = new URLSearchParams({ q: query, specialty: specialty, limit: limit, offset: offset });
                const response = await fetch('<?= site_url("agenda/get_doctors"); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('Resposta AJAX médicos:', result);
                if (result.status === 'success') {
                    if (offset === 0) {
                        doctors = result.data;
                    } else {
                        doctors = doctors.concat(result.data);
                    }
                    renderDoctors(specialty, result.total);
                    if (result.data.length === 0 && offset === 0) {
                        showNotification('Nenhum médico encontrado para esta especialidade.', 'info');
                    }
                    if (result.total > (offset + limit)) {
                        renderLoadMoreButton(result.total - (offset + limit));
                    }
                } else {
                    showNotification(result.message || 'Erro ao carregar médicos.', 'error');
                    console.error('Erro no AJAX:', result);
                }
            } catch (err) {
                showNotification('Erro de conexão ao carregar médicos.', 'error');
                console.error('Erro AJAX:', err);
            }
        }

        // Renderizar lista de médicos
        function renderDoctors(specialty = null, total = 0) {
            const container = document.getElementById('doctor-container');
            const header = document.getElementById('doctor-header');
            const filteredDoctors = doctors;  // Server já filtra

            if (specialty && filteredDoctors.length > 0) {
                header.classList.remove('hidden');
                header.textContent = `Médicos em ${specialty} (${total})`;
            } else {
                header.classList.add('hidden');
            }

            container.innerHTML = filteredDoctors.length > 0 
                ? filteredDoctors.map(doctor => `
                    <div class="doctor-item flex items-center p-4 border rounded-lg hover:bg-blue-50 cursor-pointer" data-id="${doctor.id}" data-name="${doctor.name}">
                        <img src="${doctor.image || 'https://picsum.photos/100?random=' + doctor.id}" alt="${doctor.name}" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <p class="font-medium">${doctor.name}</p>
                            <p class="text-sm text-gray-600">${doctor.specialty} - Experiência: ${doctor.experience || 'N/A'}</p>
                        </div>
                    </div>
                `).join('')
                : '<p class="text-gray-500 text-center py-8 col-span-full">Nenhum médico disponível para esta especialidade.</p>';

            document.querySelectorAll('.doctor-item').forEach(item => {
                item.addEventListener('click', function() {
                    document.querySelectorAll('.doctor-item').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedDoctor = this.dataset.name;
                    selectedDoctorId = this.dataset.id;
                    selectedDate = null;
                    selectedTime = null;
                    updateAvailableDays();
                    showNotification(`Médico selecionado: ${selectedDoctor}`, 'info');
                });
            });
        }

        // Função para renderizar botão "Carregar Mais"
        function renderLoadMoreButton(remaining) {
            const container = document.getElementById('doctor-container');
            if (container.querySelector('.load-more-btn')) return;  // Evita duplicatas
            const loadMoreBtn = document.createElement('button');
            loadMoreBtn.innerHTML = `<i class="fas fa-plus mr-2"></i>Carregar Mais Médicos (${remaining} restantes)`;
            loadMoreBtn.className = 'load-more-btn w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition mt-4';
            loadMoreBtn.addEventListener('click', () => {
                loadDoctors('', selectedSpecialty, 10, doctors.length);
            });
            container.appendChild(loadMoreBtn);
        }

        // Eventos para especialidades
        document.querySelectorAll('.specialty-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.specialty-item').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                selectedSpecialty = this.dataset.specialty;
                selectedDoctor = null;
                selectedDoctorId = null;
                selectedDate = null;
                selectedTime = null;

                const container = document.getElementById('doctor-container');
                const header = document.getElementById('doctor-header');
                container.innerHTML = '<div class="loading">Carregando médicos para ' + selectedSpecialty + '...</div>';
                header.classList.add('hidden');

                loadDoctors('', selectedSpecialty, 6, 0);
            });
        });

        // AJAX para horários disponíveis
        async function loadAvailableSlots(dateStr, medicoId) {
            try {
                const params = new URLSearchParams({ data: dateStr, medico_id: medicoId });
                const response = await fetch('<?= site_url("agenda/get_available_slots"); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.status === 'success') {
                    const slots = result.data;
                    if (!availableSlots[dateStr]) availableSlots[dateStr] = {};
                    availableSlots[dateStr][selectedDoctor || 'default'] = slots;
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (err) {
                showNotification('Erro ao carregar horários.', 'error');
                console.error('Erro AJAX slots:', err);
            }
        }

        // Função para destacar dias disponíveis
        async function updateAvailableDays() {
            if (!selectedDoctorId) return;

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const visibleStart = calendar.view.activeStart;
            const visibleEnd = calendar.view.activeEnd;

            document.querySelectorAll('.fc-daygrid-day').forEach(el => {
                el.classList.remove('available', 'selected', 'unavailable');
            });

            const loadPromises = [];
            for (let date = new Date(Math.max(visibleStart, today)); date < visibleEnd && ((date - today) / (1000 * 60 * 60 * 24)) < 14; date.setDate(date.getDate() + 1)) {
                const dateStr = date.toISOString().split('T')[0];
                if (date >= today) {
                    loadPromises.push(loadAvailableSlots(dateStr, selectedDoctorId));
                }
            }

            await Promise.all(loadPromises);

            Object.keys(availableSlots).forEach(dateStr => {
                const dayEl = document.querySelector(`.fc-daygrid-day[data-date="${dateStr}"]`);
                if (dayEl) {
                    const slots = availableSlots[dateStr][selectedDoctor || 'default'] || [];
                    if (slots.length > 0) {
                        dayEl.classList.add('available');
                    } else {
                        dayEl.classList.add('unavailable');
                    }
                }
            });
        }

        // Show time slots
        async function showTimeSlots(dateStr, doctorName) {
            const medicoId = doctors.find(d => d.name === doctorName)?.id;
            if (!medicoId) {
                showNotification('Médico inválido.', 'error');
                return;
            }

            await loadAvailableSlots(dateStr, medicoId);

            const slots = availableSlots[dateStr] ? availableSlots[dateStr][doctorName] || [] : [];
            const modal = document.getElementById('time-slot-modal');
            const slotList = document.getElementById('time-slot-list');
            slotList.innerHTML = slots.length > 0 
                ? slots.map(slot => `<button class="slot-button available" data-time="${slot}">${slot} (Disponível)</button>`).join('')
                : '<p class="text-gray-500">Nenhum horário disponível para esta data.</p>';

            modal.classList.add('show');

            document.querySelectorAll('.slot-button.available').forEach(button => {
                button.addEventListener('click', function() {
                    selectedTime = this.dataset.time;
                    showNotification(`Horário selecionado: ${selectedTime}`, 'success');
                    modal.classList.remove('show');
                });
            });
        }

        // AJAX para salvar agendamento
        async function saveAppointment(formData) {
            try {
                const params = new URLSearchParams(formData);
                const response = await fetch('<?= site_url("agenda/save_appointment"); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: params
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.status === 'success') {
                    showNotification(result.message, 'success');
                    return true;
                } else {
                    showNotification(result.message, 'error');
                    return false;
                }
            } catch (err) {
                showNotification('Erro de conexão. Tente novamente.', 'error');
                console.error('Erro AJAX:', err);
                return false;
            }
        }

        // Evento para confirmar
        document.getElementById('review-confirm-btn').addEventListener('click', async () => {
            const formData = {
                especialidade: selectedSpecialty,
                medico: selectedDoctorId,
                data_consulta: selectedDate,
                horario: selectedTime,
                nome: document.getElementById('name').value.trim(),
                telefone: document.getElementById('phone').value.trim(),
                bi: document.getElementById('bi').value.trim(),
                motivo: document.getElementById('motivo').value.trim()
            };

            const success = await saveAppointment(formData);
            if (success) {
                const confirmationMessage = `Agendamento confirmado!<br>Nome: ${formData.nome}<br>Especialidade: ${selectedSpecialty}<br>Médico: ${selectedDoctor}<br>Data: ${selectedDate}<br>Horário: ${selectedTime}`;
                document.getElementById('confirmation-message').innerHTML = confirmationMessage;
                document.getElementById('confirmation-modal').classList.add('show');
                document.getElementById('review-modal').classList.remove('show');

                selectedSpecialty = null;
                selectedDoctor = null;
                selectedDoctorId = null;
                selectedDate = null;
                selectedTime = null;
                document.querySelectorAll('.specialty-item').forEach(el => el.classList.remove('selected'));
                document.getElementById('doctor-container').innerHTML = '<p class="text-gray-500 text-center py-8 col-span-full">Selecione uma especialidade para ver os médicos disponíveis.</p>';
                document.getElementById('doctor-header').classList.add('hidden');
            }
        });

        // AJAX para listar agendamentos
        async function loadPatientAppointments() {
            try {
                const response = await fetch('<?= site_url("agenda/get_patient_appointments"); ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.status === 'success') {
                    renderAppointments(result.data);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (err) {
                showNotification('Erro ao carregar agendamentos.', 'error');
                console.error('Erro AJAX:', err);
            }
        }

        // Renderizar agendamentos
        function renderAppointments(appointments) {
            const container = document.getElementById('appointments-list');
            if (appointments.length === 0) {
                container.innerHTML = '<p class="text-gray-500">Nenhum agendamento encontrado.</p>';
                return;
            }

            container.innerHTML = appointments.map(appointment => `
                <div class="appointment-item">
                    <h4 class="font-medium">${appointment.especialidade || 'N/A'} - ${appointment.medico || 'N/A'}</h4>
                    <p class="text-sm text-gray-600">Data: ${appointment.date} às ${appointment.time}</p>
                    <p class="text-sm text-gray-600">Status: <span class="font-semibold ${appointment.status === 'Pendente' ? 'text-yellow-600' : appointment.status === 'Confirmado' ? 'text-green-600' : 'text-red-600'}">${appointment.status}</span></p>
                    <p class="text-sm text-gray-600">Motivo: ${appointment.motivo || 'N/A'}</p>
                </div>
            `).join('');
        }

        // Evento para Meus Agendamentos
        document.getElementById('meus-agendamentos-btn').addEventListener('click', () => {
            document.getElementById('appointments-section').classList.toggle('hidden');
            if (!document.getElementById('appointments-section').classList.contains('hidden')) {
                loadPatientAppointments();
            }
        });

        // Logout
        document.getElementById('logout-btn').addEventListener('click', () => {
            window.location.href = '<?= site_url("auth/logout"); ?>';
        });

        // DOMContentLoaded - CORRIGIDO
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado - inicializando...');
            
            // Sidebar Handlers
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const sidebarMenu = document.getElementById('sidebar-menu');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const pageWrapper = document.querySelector('.page-wrapper');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebarMenu.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    pageWrapper.classList.add('expanded');
                });
            }

            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', () => {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', () => {
                    sidebarMenu.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    pageWrapper.classList.remove('expanded');
                });
            }

            if (toggleSidebarBtn) {
                toggleSidebarBtn.addEventListener('click', () => {
                    sidebarMenu.classList.toggle('expanded');
                    pageWrapper.classList.toggle('expanded');
                });
            }

            // FullCalendar
            const calendarEl = document.getElementById('calendar');
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt',
                initialDate: tomorrowStr,
                validRange: {
                    start: tomorrowStr
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: [],
                dateClick: (info) => {
                    if (!selectedDoctorId) {
                        showNotification('Selecione um médico antes de escolher a data.', 'error');
                        return;
                    }
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
                    if (!selectedDoctorId) {
                        showNotification('Selecione um médico antes de escolher a data.', 'error');
                        return;
                    }
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

            // Modal handlers
            document.getElementById('modal-cancel-btn').addEventListener('click', () => {
                document.getElementById('time-slot-modal').classList.remove('show');
            });

            document.getElementById('review-cancel-btn').addEventListener('click', () => {
                document.getElementById('review-modal').classList.remove('show');
            });

            document.getElementById('confirmation-close-btn').addEventListener('click', () => {
                document.getElementById('confirmation-modal').classList.remove('show');
            });

            document.getElementById('confirm-btn').addEventListener('click', () => {
                const nome = document.getElementById('name').value.trim();
                const telefone = document.getElementById('phone').value.trim();
                const bi = document.getElementById('bi').value.trim();

                if (!nome || !telefone || !bi) {
                    showNotification('Por favor, preencha todos os campos do formulário.', 'error');
                    return;
                }
                if (!selectedSpecialty || !selectedDoctor || !selectedDate || !selectedTime) {
                    showNotification('Selecione especialidade, médico, data e horário antes de continuar.', 'error');
                    return;
                }

                document.getElementById('review-specialty').textContent = selectedSpecialty;
                document.getElementById('review-doctor').textContent = selectedDoctor;
                document.getElementById('review-date').textContent = selectedDate;
                document.getElementById('review-time').textContent = selectedTime;
                document.getElementById('review-name').textContent = nome;
                document.getElementById('review-phone').textContent = telefone;
                document.getElementById('review-bi').textContent = bi;

                document.getElementById('review-modal').classList.add('show');
            });
        });
    </script>
</body>
</html>