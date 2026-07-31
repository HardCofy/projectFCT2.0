<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/../config/database.php';

$users = $pdo->query(
    'SELECT * FROM utilizadores'
)->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html lang="pt-PT">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — Maré Segura</title>
    <link rel="stylesheet" href="../assets/app.css?v=<?= filemtime(__DIR__ . '/../assets/app.css') ?>">
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="grid min-h-screen md:grid-cols-2">
        <section class="relative hidden min-h-screen overflow-hidden bg-sky-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-sky-500/30 blur-3xl"></div>
            <div class="absolute -bottom-28 -left-20 h-96 w-96 rounded-full bg-cyan-400/20 blur-3xl"></div>

            <a href="../index.php" class="relative flex items-center gap-3 self-start text-lg font-semibold ">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-white text-2xl text-sky-700">
                    ≈
                </span>
                Maré Segura
            </a>

            <div class="relative max-w-lg">
                <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-sky-100">
                    Área reservada
                </span>

                <h1 class="mt-6 text-5xl font-semibold tracking-tight">
                    Gestão de praias num só lugar.
                </h1>

                <p class="mt-6 text-lg leading-8 text-sky-100">
                    Coordena equipas, atualiza condições do mar e mantém a informação
                    disponível para todos os banhistas.
                </p>

                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <strong class="block text-2xl">82</strong>
                        <span class="mt-1 block text-sm text-sky-100">Praias monitorizadas</span>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <strong class="block text-2xl">246</strong>
                        <span class="mt-1 block text-sm text-sky-100">Profissionais ligados</span>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <strong class="block text-2xl">15 min</strong>
                        <span class="mt-1 block text-sm text-sky-100">Entre atualizações</span>
                    </div>
                </div>
            </div>

            <p class="relative text-sm text-sky-200">
                © <?= date('Y') ?> Maré Segura
            </p>
        </section>

        <section class="flex min-h-screen items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                <a href="../index.php" class="mb-12 inline-flex items-center gap-2 text-sm font-medium text-sky-700 hover:underline md:hidden">
                    ← Voltar ao site
                </a>

                <div class="mb-8">
                    <p class="font-medium text-sky-700">Área de administração</p>

                    <h2 class="mt-3 text-4xl font-semibold tracking-tight">
                        Bem-vindo de volta
                    </h2>

                    <p class="mt-3 text-slate-600">
                        Introduz os teus dados para acederes ao painel de gestão.
                    </p>
                </div>

                <form class="space-y-5" action="#" method="post">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                            Email profissional
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            placeholder="nome@municipio.pt"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="text-sm font-medium text-slate-700">
                                Palavra-passe
                            </label>

                            <a href="#" class="text-sm font-medium text-sky-700 hover:underline">
                                Esqueceste-te?
                            </a>
                        </div>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                    </div>

                    <label class="flex cursor-pointer items-center gap-3 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        Manter sessão iniciada
                    </label>

                    <button
                        type="button"
                        class="w-full rounded-xl bg-sky-600 px-5 py-3.5 font-semibold text-white transition hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-200">
                        Entrar no painel
                    </button>
                </form>

                <div class="my-8 flex items-center gap-4 text-sm text-slate-400">
                    <span class="h-px flex-1 bg-slate-200"></span>
                    <span>Acesso exclusivo a equipas autorizadas</span>
                    <span class="h-px flex-1 bg-slate-200"></span>
                </div>

                <p class="text-center text-sm text-slate-500">
                    Precisas de acesso?
                    <a href="mailto:suporte@maresegura.pt" class="font-medium text-sky-700 hover:underline">
                        Contacta o administrador
                    </a>
                </p>
            </div>
        </section>
    </main>
</body>

</html>