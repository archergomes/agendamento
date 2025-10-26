<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar - Hospital Matlhovele</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2d9d6f5f5.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-gradient {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            transition: all 0.3s ease-in-out;
        }
        .btn-gradient:hover {
            transform: scale(1.03);
            background: linear-gradient(90deg, #1d4ed8, #1e40af);
        }
        .input-focus:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .input-group input, .input-group select {
            padding-left: 2.5rem;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 fade-in">
        <div class="text-center mb-6">
            <i class="fas fa-user-plus text-4xl text-blue-600 mb-2"></i>
            <h2 class="text-2xl font-semibold text-gray-800">Criar Conta</h2>
            <p class="text-gray-500 text-sm">Preencha os dados abaixo para se registar</p>
        </div>

        <?= form_open('auth/register', ['class' => 'space-y-4']); ?>

            <!-- Erros de validação gerais -->
            <?php if (validation_errors()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= validation_errors('<div class="error-message">', '</div>'); ?>
                </div>
            <?php endif; ?>

            <!-- Mensagem de erro flash -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Mensagem de sucesso flash (opcional, caso queira mostrar antes de redirect) -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <?= form_input(['name' => 'nome', 'placeholder' => 'Nome', 'value' => set_value('nome'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                    <?php echo form_error('nome', '<div class="error-message">', '</div>'); ?>
                </div>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <?= form_input(['name' => 'sobrenome', 'placeholder' => 'Sobrenome', 'value' => set_value('sobrenome'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                    <?php echo form_error('sobrenome', '<div class="error-message">', '</div>'); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <?= form_input(['name' => 'data_nascimento', 'type' => 'date', 'value' => set_value('data_nascimento'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                    <?php echo form_error('data_nascimento', '<div class="error-message">', '</div>'); ?>
                </div>
                <div class="input-group">
                    <i class="fas fa-venus-mars"></i>
                    <?= form_dropdown('genero', ['Masculino' => 'Masculino', 'Feminino' => 'Feminino'], set_value('genero'), 'class="w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none" required'); ?>
                    <?php echo form_error('genero', '<div class="error-message">', '</div>'); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <?= form_input(['name' => 'telefone', 'placeholder' => 'Número de telefone', 'value' => set_value('telefone'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                    <?php echo form_error('telefone', '<div class="error-message">', '</div>'); ?>
                </div>
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <?= form_input(['name' => 'bi', 'placeholder' => 'Número do BI', 'value' => set_value('bi'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                    <?php echo form_error('bi', '<div class="error-message">', '</div>'); ?>
                </div>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <?= form_input(['name' => 'email', 'type' => 'email', 'placeholder' => 'E-mail', 'value' => set_value('email'), 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none', 'required' => 'required']); ?>
                <?php echo form_error('email', '<div class="error-message">', '</div>'); ?>
            </div>

            <div class="input-group relative">
                <i class="fas fa-lock"></i>
                <?= form_input(['id' => 'password', 'name' => 'senha', 'type' => 'password', 'placeholder' => 'Senha', 'value' => '', 'class' => 'w-full border border-gray-300 rounded-lg px-4 py-2 input-focus focus:outline-none pr-10', 'required' => 'required', 'minlength' => '6']); ?>
                <i class="fas fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" id="toggle-password"></i>
                <?php echo form_error('senha', '<div class="error-message">', '</div>'); ?>
            </div>

            <button type="submit"
                class="w-full py-3 text-white font-semibold rounded-lg btn-gradient focus:outline-none">
                Registar
            </button>
        <?= form_close(); ?>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Já tem uma conta? <a href="<?= site_url('auth/login'); ?>" class="text-blue-600 hover:underline">Entre aqui</a></p>
        </div>

        <footer class="mt-8 text-center text-xs text-gray-400">
            &copy; <?= date('Y'); ?> Hospital Matlhovele — Todos os direitos reservados
        </footer>
    </div>

    <script>
        const togglePassword = document.querySelector('#toggle-password');
        const passwordInput = document.querySelector('#password');
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>