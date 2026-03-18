<x-mail::message>
# Olá!

Você está recebendo este e-mail porque solicitou a recuperação de senha da sua conta no **{{ config('app.name') }}**.

<x-mail::button :url="$url">
Redefinir Minha Senha
</x-mail::button>

Este link de redefinição de senha expirará em 60 minutos.

Se você não solicitou isso, ignore este e-mail.

Obrigado,<br>
Equipe {{ config('app.name') }}
</x-mail::message>