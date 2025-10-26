<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hospital Matlhovele</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glow {
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.2);
            transition: box-shadow 0.3s ease;
        }

        .glow:hover {
            box-shadow: 0 0 30px rgba(37, 99, 235, 0.35);
        }

        .error-msg,
        .success-msg {
            display: none;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }

        .error-msg {
            background-color: #f87171;
            color: white;
        }

        .success-msg {
            background-color: #10b981;
            color: white;
        }

        .error-validation {
            color: #f87171;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 fade-in mx-4 glow">
        <div class="text-center mb-6">
            <i class="fas fa-hospital-user text-4xl text-blue-600 mb-3"></i>
            <h1 class="text-2xl font-semibold text-gray-800">Hospital Matlhovele</h1>
            <p class="text-gray-500 text-sm mt-1">Sistema de Agendamento de Consultas</p>
        </div>

        <!-- Mensagens Flash do CI (fallback para não-AJAX) -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="error-msg"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="success-msg"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <!-- Erros de Validação (se fallback não-AJAX) -->
        <?php if (validation_errors()): ?>
            <div class="error-msg"><?= validation_errors(); ?></div>
        <?php endif; ?>

        <!-- Mensagens Dinâmicas do JS -->
        <div id="login-success" class="success-msg">Login efetuado com sucesso!</div>
        <div id="login-error" class="error-msg">Credenciais inválidas. Tente novamente.</div>

        <!-- Formulário (com fallback para POST normal) -->
        <?= form_open('auth/login', ['id' => 'login-form', 'class' => 'space-y-5']); ?>
        <div>
            <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
            <div class="flex items-center border border-gray-300 rounded-lg px-3">
                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                <?= form_input(['type' => 'email', 'id' => 'email', 'name' => 'email', 'value' => set_value('email'), 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'exemplo@dominio.com', 'class' => 'w-full py-2 outline-none text-gray-700 placeholder-gray-400']); ?>
            </div>
            <?php echo form_error('email', '<div class="error-validation">', '</div>'); ?>
        </div>
        <div>
            <label for="senha" class="block text-gray-700 font-medium mb-1">Senha</label>
            <div class="flex items-center border border-gray-300 rounded-lg px-3">
                <i class="fas fa-lock text-gray-400 mr-2"></i>
                <?= form_input(['type' => 'password', 'id' => 'senha', 'name' => 'senha', 'value' => '', 'required' => 'required', 'placeholder' => '••••••••', 'class' => 'w-full py-2 outline-none text-gray-700 placeholder-gray-400']); ?>
            </div>
            <?php echo form_error('senha', '<div class="error-validation">', '</div>'); ?>
        </div>

        <?= form_submit(['type' => 'submit', 'class' => 'w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition transform hover:scale-[1.02] duration-150', 'value' => 'Entrar']); ?>

        <p class="text-center text-sm text-gray-600 mt-3">
            Esqueceu a senha?
            <a href="#" class="text-blue-600 hover:underline">Recuperar</a>
        </p>
        <?= form_close(); ?>

        <div class="mt-8 text-center text-gray-400 text-xs">
            © <?= date('Y') ?> Hospital Matlhovele. Todos os direitos reservados.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const errorMsg = document.getElementById('login-error');
            const successMsg = document.getElementById('login-success');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    const email = formData.get('email').trim();
                    const senha = formData.get('senha').trim();

                    errorMsg.style.display = 'none';
                    successMsg.style.display = 'none';

                    try {
                        console.log('Enviando login para:', '<?= site_url('auth/login'); ?>');
                        const response = await fetch("<?= site_url('auth/login'); ?>", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        console.log('Status:', response.status);

                        const text = await response.text();
                        console.log('Response text preview:', text.substring(0, 200) + '...');

                        let result;
                        try {
                            result = JSON.parse(text);
                            console.log('JSON sucesso:', result);
                        } catch (parseErr) {
                            console.error('Parse erro:', parseErr);
                            throw new Error('Resposta inválida');
                        }

                        if (response.ok && result.status === 'success') {
                            successMsg.style.display = 'block';
                            console.log('Login OK! Iniciando redirect para agenda...');
                            console.log('URL de redirect:', '<?= site_url('agenda'); ?>');

                            // Redirect imediato para teste (sem delay)
                            window.location.href = "<?= site_url('agenda'); ?>";

                            // Fallback com delay se necessário
                            setTimeout(() => {
                                console.log('Fallback: Confirmando redirect...');
                                if (window.location.pathname.indexOf('agenda') === -1) {
                                    window.location.href = "<?= site_url('agenda'); ?>";
                                }
                            }, 500);

                        } else {
                            errorMsg.textContent = result.message || 'Erro desconhecido.';
                            errorMsg.style.display = 'block';
                            console.log('Login falhou:', result.message);
                        }
                    } catch (err) {
                        console.error('Erro geral:', err);
                        errorMsg.textContent = `Erro: ${err.message}`;
                        errorMsg.style.display = 'block';
                    }
                });
            }
        });
    </script>
</body>

</html>