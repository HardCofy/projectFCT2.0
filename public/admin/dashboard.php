<?php

declare(strict_types=1);

$pdo = require  '../../config/database.php';

$beaches = $pdo->query(
    'SELECT id, nome, localidade, bandeira, estado, condicao_mar, nadadores_ativos, cobertura, updated_at FROM praias WHERE ativa = 1 ORDER BY nome'
)->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$totalBeaches = count($beaches);
$totalProfessionals = $pdo->query('SELECT COUNT(*) FROM utilizadores')->fetchColumn();
$activeProfessionals = $pdo->query('SELECT COUNT(*) FROM utilizadores WHERE ativo = 1')->fetchColumn();

function badgeClass(string $bandeira): string
{
    return match ($bandeira) {
        'verde' => 'bg-emerald-100 text-emerald-800',
        'amarela' => 'bg-amber-100 text-amber-800',
        'vermelha' => 'bg-red-100 text-red-800',
    };
}


?>
<!doctype html>
<html lang="pt-PT">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel — Maré Segura</title>
    <link rel="stylesheet" href="../assets/app.css?v=<?= filemtime(__DIR__ . '/../assets/app.css') ?>">
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-900/50 lg:hidden" aria-hidden="true"></div>

    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0">
        <div class="flex items-center gap-3 border-b border-slate-200 px-6 py-5">
            <span class="grid h-11 w-11 place-items-center rounded-full bg-sky-600 text-2xl text-white">≈</span>
            <div>
                <p class="font-semibold leading-tight">Maré Segura</p>
                <p class="text-xs text-slate-500">Painel de administração</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5" aria-label="Navegação principal">
            <button type="button" data-section="overview" class="nav-link w-full rounded-xl bg-sky-50 px-4 py-3 text-left text-sm font-medium text-sky-700 transition hover:bg-sky-100">
                Visão geral
            </button>
            <button type="button" data-section="beaches" class="nav-link w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                Praias
            </button>
            <button type="button" data-section="teams" class="nav-link w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                Equipas
            </button>
            <button type="button" data-section="alerts" class="nav-link w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                Alertas
            </button>
            <button type="button" data-section="settings" class="nav-link w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                Configurações
            </button>
        </nav>

        <div class="border-t border-slate-200 p-4">
            <a href="index.php" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                Terminar sessão
            </a>
        </div>
    </aside>

    <div class="lg:pl-72">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        id="sidebar-toggle"
                        class="rounded-xl border border-slate-200 p-2.5 text-slate-600 transition hover:bg-slate-50 lg:hidden"
                        aria-label="Abrir menu">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-sky-700">Área reservada</p>
                        <h1 id="page-title" class="text-xl font-semibold tracking-tight sm:text-2xl">Visão geral</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <label class="relative hidden sm:block">
                        <span class="sr-only">Pesquisar</span>
                        <input
                            type="search"
                            id="global-search"
                            placeholder="Pesquisar..."
                            class="w-56 rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </label>

                    <button
                        type="button"
                        id="notifications-btn"
                        class="relative rounded-xl border border-slate-200 p-2.5 text-slate-600 transition hover:bg-slate-50"
                        aria-label="Notificações">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>

                    <div class="relative">
                        <button
                            type="button"
                            id="user-menu-btn"
                            class="flex items-center gap-3 rounded-xl border border-slate-200 px-2 py-1.5 transition hover:bg-slate-50 sm:px-3"
                            aria-expanded="false"
                            aria-haspopup="true">
                            <span class="grid h-9 w-9 place-items-center rounded-full bg-sky-100 text-sm font-semibold text-sky-700">JS</span>
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-medium">João Silva</span>
                                <span class="block text-xs text-slate-500">Administrador</span>
                            </span>
                        </button>

                        <div id="user-menu" class="absolute right-0 z-10 mt-2 hidden w-52 rounded-2xl border border-slate-200 bg-white p-2 shadow-lg">
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Perfil</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Preferências</a>
                            <a href="index.php" class="block rounded-xl px-3 py-2 text-sm text-red-700 hover:bg-red-50">Terminar sessão</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-6 sm:px-6 sm:py-8">
            <section id="section-overview" class="dashboard-section space-y-6">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Praias monitorizadas</p>
                        <p class="mt-2 text-3xl font-semibold"><?= $totalBeaches ?></p>
                        <p class="mt-2 text-xs font-medium text-emerald-700">+3 este mês</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Total de profissionais</p>
                        <p class="mt-2 text-3xl font-semibold"><?= $totalProfessionals ?></p>
                        <p class="mt-2 text-xs font-medium text-sky-700"><?= $activeProfessionals ?> em turno agora</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Alertas abertos</p>
                        <p class="mt-2 text-3xl font-semibold">7</p>
                        <p class="mt-2 text-xs font-medium text-amber-700">2 prioritários</p>
                    </article>
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Cobertura média</p>
                        <p class="mt-2 text-3xl font-semibold">91%</p>
                        <p class="mt-2 text-xs font-medium text-slate-500">Atualizado há 12 min</p>
                    </article>
                </div>

                <div class="grid gap-6 xl:grid-cols-3">
                    <div class="space-y-6 xl:col-span-2">
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-semibold">Estado das praias</h2>
                                    <p class="text-sm text-slate-500">Distribuição por bandeira hoje</p>
                                </div>
                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800">Tempo real</span>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="rounded-xl bg-emerald-50 p-4">
                                    <p class="text-sm font-medium text-emerald-800">Verde</p>
                                    <p class="mt-2 text-2xl font-semibold text-emerald-900">54</p>
                                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-emerald-100">
                                        <div class="h-full w-[66%] rounded-full bg-emerald-500"></div>
                                    </div>
                                </div>
                                <div class="rounded-xl bg-amber-50 p-4">
                                    <p class="text-sm font-medium text-amber-800">Amarela</p>
                                    <p class="mt-2 text-2xl font-semibold text-amber-900">21</p>
                                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-amber-100">
                                        <div class="h-full w-[26%] rounded-full bg-amber-500"></div>
                                    </div>
                                </div>
                                <div class="rounded-xl bg-red-50 p-4">
                                    <p class="text-sm font-medium text-red-800">Vermelha</p>
                                    <p class="mt-2 text-2xl font-semibold text-red-900">7</p>
                                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-red-100">
                                        <div class="h-full w-[8%] rounded-full bg-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-center justify-between gap-3">
                                <h2 class="text-lg font-semibold">Atividade recente</h2>
                                <button type="button" class="text-sm font-medium text-sky-700 hover:underline">Ver tudo</button>
                            </div>

                            <ul class="space-y-4">
                                <li class="flex gap-4 rounded-xl border border-slate-100 p-4">
                                    <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>
                                    <div>
                                        <p class="text-sm font-medium">Praia da Figueirinha atualizada para bandeira verde</p>
                                        <p class="mt-1 text-xs text-slate-500">Há 8 minutos · Ana Costa</p>
                                    </div>
                                </li>
                                <li class="flex gap-4 rounded-xl border border-slate-100 p-4">
                                    <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>
                                    <div>
                                        <p class="text-sm font-medium">Alerta de corrente registado em São Pedro de Moel</p>
                                        <p class="mt-1 text-xs text-slate-500">Há 22 minutos · Turno Norte</p>
                                    </div>
                                </li>
                                <li class="flex gap-4 rounded-xl border border-slate-100 p-4">
                                    <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-sky-500"></span>
                                    <div>
                                        <p class="text-sm font-medium">Nova equipa adicionada ao concelho de Óbidos</p>
                                        <p class="mt-1 text-xs text-slate-500">Há 1 hora · João Silva</p>
                                    </div>
                                </li>
                            </ul>
                        </article>
                    </div>

                    <aside class="space-y-6">
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold">Ações rápidas</h2>
                            <div class="mt-5 space-y-3">
                                <button type="button" data-go-section="beaches" class="quick-action w-full rounded-xl bg-sky-600 px-4 py-3 text-left text-sm font-semibold text-white transition hover:bg-sky-700">
                                    Atualizar estado de praia
                                </button>
                                <button type="button" data-go-section="alerts" class="quick-action w-full rounded-xl border border-slate-200 px-4 py-3 text-left text-sm font-medium transition hover:bg-slate-50">
                                    Registar novo alerta
                                </button>
                                <button type="button" data-go-section="teams" class="quick-action w-full rounded-xl border border-slate-200 px-4 py-3 text-left text-sm font-medium transition hover:bg-slate-50">
                                    Gerir turnos
                                </button>
                            </div>
                        </article>

                        <article class="rounded-2xl bg-gradient-to-br from-sky-950 to-sky-800 p-6 text-white shadow-sm">
                            <p class="text-sm text-sky-100">Próxima atualização automática</p>
                            <p class="mt-2 text-3xl font-semibold">03:42</p>
                            <p class="mt-3 text-sm leading-6 text-sky-100">
                                Os dados públicos são sincronizados a cada 15 minutos com o site principal.
                            </p>
                        </article>
                    </aside>
                </div>
            </section>

            <section id="section-beaches" class="dashboard-section hidden space-y-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Gestão de praias</h2>
                        <p class="mt-1 text-slate-600">Consulta, filtra e edita o estado operacional.</p>
                    </div>
                    <button
                        type="button"
                        id="add-beach-btn"
                        class="rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                        Adicionar praia
                    </button>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                        <label class="relative flex-1">
                            <span class="sr-only">Pesquisar praias</span>
                            <input
                                type="search"
                                id="beach-search"
                                placeholder="Pesquisar por nome ou localidade..."
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                            </svg>
                        </label>

                        <select id="beach-filter" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                            <option value="all">Todas as bandeiras</option>
                            <option value="verde">Verde</option>
                            <option value="amarela">Amarela</option>
                            <option value="vermelha">Vermelha</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-500">
                                <tr>
                                    <th class="px-5 py-4 font-medium">Praia</th>
                                    <th class="px-5 py-4 font-medium">Localidade</th>
                                    <th class="px-5 py-4 font-medium">Bandeira</th>
                                    <th class="px-5 py-4 font-medium">Nadadores</th>
                                    <th class="px-5 py-4 font-medium">Cobertura</th>
                                    <th class="px-5 py-4 font-medium">Atualizado</th>
                                    <th class="px-5 py-4 font-medium"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody id="beaches-table-body" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                    <p id="beaches-empty" class="hidden px-5 py-10 text-center text-sm text-slate-500">
                        Nenhuma praia encontrada com os filtros atuais.
                    </p>
                </div>
            </section>

            <section id="section-teams" class="dashboard-section hidden space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold">Equipas e turnos</h2>
                    <p class="mt-1 text-slate-600">Profissionais em serviço e disponibilidade por zona.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" id="teams-grid"></div>
            </section>

            <section id="section-alerts" class="dashboard-section hidden space-y-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Alertas operacionais</h2>
                        <p class="mt-1 text-slate-600">Ocorrências que requerem atenção imediata.</p>
                    </div>
                    <button type="button" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-medium transition hover:bg-white">
                        Exportar relatório
                    </button>
                </div>

                <div class="space-y-4" id="alerts-list"></div>
            </section>

            <section id="section-settings" class="dashboard-section hidden space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold">Configurações</h2>
                    <p class="mt-1 text-slate-600">Preferências do painel e sincronização pública.</p>
                </div>

                <form id="settings-form" class="grid gap-6 xl:grid-cols-2">
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold">Conta</h3>
                        <div class="mt-5 space-y-4">
                            <div>
                                <label for="settings-name" class="mb-2 block text-sm font-medium text-slate-700">Nome</label>
                                <input id="settings-name" type="text" value="João Silva" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                            </div>
                            <div>
                                <label for="settings-email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                                <input id="settings-email" type="email" value="joao.silva@municipio.pt" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold">Sistema</h3>
                        <div class="mt-5 space-y-4">
                            <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3">
                                <span class="text-sm">
                                    <span class="block font-medium">Notificações por email</span>
                                    <span class="block text-slate-500">Receber alertas prioritários</span>
                                </span>
                                <input type="checkbox" checked class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            </label>
                            <label class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 px-4 py-3">
                                <span class="text-sm">
                                    <span class="block font-medium">Sincronização automática</span>
                                    <span class="block text-slate-500">Publicar alterações no site</span>
                                </span>
                                <input type="checkbox" checked class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            </label>
                            <div>
                                <label for="settings-interval" class="mb-2 block text-sm font-medium text-slate-700">Intervalo de atualização</label>
                                <select id="settings-interval" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                                    <option>15 minutos</option>
                                    <option>30 minutos</option>
                                    <option>1 hora</option>
                                </select>
                            </div>
                        </div>
                    </article>

                    <div class="xl:col-span-2">
                        <button type="submit" class="rounded-xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                            Guardar alterações
                        </button>
                        <p id="settings-feedback" class="mt-3 hidden text-sm font-medium text-emerald-700">
                            Preferências guardadas (simulação UI).
                        </p>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <div id="beach-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="beach-modal-title">
        <div id="beach-modal-backdrop" class="absolute inset-0 bg-slate-900/50"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h2 id="beach-modal-title" class="text-xl font-semibold">Editar praia</h2>
                    <p id="beach-modal-subtitle" class="mt-1 text-sm text-slate-500"></p>
                </div>
                <button type="button" id="beach-modal-close" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100" aria-label="Fechar">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="beach-form" class="space-y-4">
                <input type="hidden" id="beach-id">
                <div>
                    <label for="beach-estado" class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <input id="beach-estado" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                </div>
                <div>
                    <label for="beach-bandeira" class="mb-2 block text-sm font-medium text-slate-700">Bandeira</label>
                    <select id="beach-bandeira" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                        <option value="verde">Verde</option>
                        <option value="amarela">Amarela</option>
                        <option value="vermelha">Vermelha</option>
                    </select>
                </div>
                <div>
                    <label for="beach-condicao" class="mb-2 block text-sm font-medium text-slate-700">Condições do mar</label>
                    <textarea id="beach-condicao" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100"></textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="beach-nadadores" class="mb-2 block text-sm font-medium text-slate-700">Nadadores ativos</label>
                        <input id="beach-nadadores" type="number" min="0" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                    </div>
                    <div>
                        <label for="beach-cobertura" class="mb-2 block text-sm font-medium text-slate-700">Cobertura (%)</label>
                        <input id="beach-cobertura" type="number" min="0" max="100" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-sky-600 focus:ring-4 focus:ring-sky-100">
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                        Guardar alterações
                    </button>
                    <button type="button" id="beach-modal-cancel" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-medium transition hover:bg-slate-50">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden translate-y-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-medium text-white opacity-0 shadow-lg transition duration-200"></div>

    <script>
        const sectionTitles = {
            overview: 'Visão geral',
            beaches: 'Praias',
            teams: 'Equipas',
            alerts: 'Alertas',
            settings: 'Configurações',
        };

        const badgeClasses = {
            verde: 'bg-emerald-100 text-emerald-800',
            amarela: 'bg-amber-100 text-amber-800',
            vermelha: 'bg-red-100 text-red-800',
        };

        let beaches = [{
                id: 1,
                nome: 'Praia da Figueirinha',
                localidade: 'Setúbal',
                bandeira: 'verde',
                estado: 'Segura',
                nadadores: 4,
                cobertura: 95,
                updated: '08:42'
            },
            {
                id: 2,
                nome: 'Praia de São Pedro de Moel',
                localidade: 'Leiria',
                bandeira: 'amarela',
                estado: 'Precaução',
                nadadores: 3,
                cobertura: 88,
                updated: '08:35'
            },
            {
                id: 3,
                nome: 'Praia do Carvoeiro',
                localidade: 'Lagoa',
                bandeira: 'verde',
                estado: 'Segura',
                nadadores: 5,
                cobertura: 92,
                updated: '08:30'
            },
            {
                id: 4,
                nome: 'Praia da Nazaré',
                localidade: 'Nazaré',
                bandeira: 'vermelha',
                estado: 'Perigosa',
                nadadores: 6,
                cobertura: 100,
                updated: '08:28'
            },
            {
                id: 5,
                nome: 'Praia de Matosinhos',
                localidade: 'Porto',
                bandeira: 'verde',
                estado: 'Segura',
                nadadores: 7,
                cobertura: 90,
                updated: '08:20'
            },
            {
                id: 6,
                nome: 'Praia da Comporta',
                localidade: 'Alcácer do Sal',
                bandeira: 'amarela',
                estado: 'Precaução',
                nadadores: 2,
                cobertura: 75,
                updated: '08:15'
            },
        ];

        const teams = [{
                nome: 'Turno Norte',
                zona: 'Leiria · Nazaré',
                membros: 12,
                ativos: 8,
                estado: 'Operacional'
            },
            {
                nome: 'Turno Centro',
                zona: 'Setúbal · Almada',
                membros: 18,
                ativos: 14,
                estado: 'Operacional'
            },
            {
                nome: 'Turno Sul',
                zona: 'Algarve',
                membros: 22,
                ativos: 16,
                estado: 'Reforço'
            },
        ];

        const alerts = [{
                titulo: 'Corrente forte detetada',
                praia: 'São Pedro de Moel',
                prioridade: 'alta',
                tempo: 'Há 22 min',
                descricao: 'Aumento súbito da intensidade da corrente na zona central.'
            },
            {
                titulo: 'Equipa incompleta',
                praia: 'Comporta',
                prioridade: 'media',
                tempo: 'Há 45 min',
                descricao: 'Apenas 2 nadadores-salvadores confirmados para o turno da tarde.'
            },
            {
                titulo: 'Bandeira vermelha ativa',
                praia: 'Nazaré',
                prioridade: 'alta',
                tempo: 'Há 1 h',
                descricao: 'Banho temporariamente proibido devido às condições do mar.'
            },
            {
                titulo: 'Atualização pendente',
                praia: 'Matosinhos',
                prioridade: 'baixa',
                tempo: 'Há 2 h',
                descricao: 'Estado operacional não atualizado desde o turno anterior.'
            },
        ];

        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const pageTitle = document.getElementById('page-title');
        const toast = document.getElementById('toast');

        function showToast(message) {
            toast.textContent = message;
            toast.classList.remove('hidden', 'opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');

            window.clearTimeout(showToast.timeoutId);
            showToast.timeoutId = window.setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                window.setTimeout(() => toast.classList.add('hidden'), 200);
            }, 2600);
        }

        function setSidebarOpen(isOpen) {
            sidebar.classList.toggle('-translate-x-full', !isOpen);
            sidebarBackdrop.classList.toggle('hidden', !isOpen);
        }

        function showSection(sectionId) {
            document.querySelectorAll('.dashboard-section').forEach((section) => {
                section.classList.toggle('hidden', section.id !== `section-${sectionId}`);
            });

            document.querySelectorAll('.nav-link').forEach((link) => {
                const isActive = link.dataset.section === sectionId;
                link.classList.toggle('bg-sky-50', isActive);
                link.classList.toggle('text-sky-700', isActive);
                link.classList.toggle('hover:bg-sky-100', isActive);
                link.classList.toggle('text-slate-600', !isActive);
                link.classList.toggle('hover:bg-slate-100', !isActive);
            });

            pageTitle.textContent = sectionTitles[sectionId] || 'Painel';
            setSidebarOpen(false);
        }

        function renderBeachesTable() {
            const search = document.getElementById('beach-search').value.trim().toLowerCase();
            const filter = document.getElementById('beach-filter').value;
            const tbody = document.getElementById('beaches-table-body');
            const emptyState = document.getElementById('beaches-empty');

            const filtered = beaches.filter((beach) => {
                const matchesSearch = `${beach.nome} ${beach.localidade}`.toLowerCase().includes(search);
                const matchesFilter = filter === 'all' || beach.bandeira === filter;
                return matchesSearch && matchesFilter;
            });

            tbody.innerHTML = filtered.map((beach) => `
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4">
                        <p class="font-medium">${beach.nome}</p>
                        <p class="text-xs text-slate-500">${beach.estado}</p>
                    </td>
                    <td class="px-5 py-4 text-slate-600">${beach.localidade}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-3 py-1 text-xs font-semibold ${badgeClasses[beach.bandeira]}">
                            ${beach.bandeira.charAt(0).toUpperCase() + beach.bandeira.slice(1)}
                        </span>
                    </td>
                    <td class="px-5 py-4">${beach.nadadores}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-20 overflow-hidden rounded-full bg-slate-200">
                                <div class="h-full bg-sky-600" style="width: ${beach.cobertura}%"></div>
                            </div>
                            <span>${beach.cobertura}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-500">${beach.updated}</td>
                    <td class="px-5 py-4 text-right">
                        <button type="button" class="edit-beach rounded-lg px-3 py-2 text-sm font-medium text-sky-700 transition hover:bg-sky-50" data-id="${beach.id}">
                            Editar
                        </button>
                    </td>
                </tr>
            `).join('');

            emptyState.classList.toggle('hidden', filtered.length > 0);

            tbody.querySelectorAll('.edit-beach').forEach((button) => {
                button.addEventListener('click', () => openBeachModal(Number(button.dataset.id)));
            });
        }

        function renderTeams() {
            const priorityStyles = {
                alta: 'bg-red-100 text-red-800',
                media: 'bg-amber-100 text-amber-800',
                baixa: 'bg-slate-100 text-slate-700',
            };

            document.getElementById('teams-grid').innerHTML = teams.map((team) => `
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold">${team.nome}</h3>
                            <p class="mt-1 text-sm text-slate-500">${team.zona}</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">${team.estado}</span>
                    </div>
                    <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <dt class="text-slate-500">Membros</dt>
                            <dd class="mt-1 text-2xl font-semibold">${team.membros}</dd>
                        </div>
                        <div class="rounded-xl bg-sky-50 p-4">
                            <dt class="text-sky-700">Em serviço</dt>
                            <dd class="mt-1 text-2xl font-semibold text-sky-900">${team.ativos}</dd>
                        </div>
                    </dl>
                    <button type="button" class="mt-5 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium transition hover:bg-slate-50">
                        Ver turno
                    </button>
                </article>
            `).join('');

            document.getElementById('alerts-list').innerHTML = alerts.map((alert) => `
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold">${alert.titulo}</h3>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold ${priorityStyles[alert.prioridade]}">
                                    ${alert.prioridade.charAt(0).toUpperCase() + alert.prioridade.slice(1)}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">${alert.praia} · ${alert.tempo}</p>
                        </div>
                        <button type="button" class="rounded-lg px-3 py-2 text-sm font-medium text-sky-700 transition hover:bg-sky-50">
                            Resolver
                        </button>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600">${alert.descricao}</p>
                </article>
            `).join('');
        }

        const beachModal = document.getElementById('beach-modal');

        function openBeachModal(beachId) {
            const beach = beaches.find((item) => item.id === beachId);
            if (!beach) {
                return;
            }

            document.getElementById('beach-id').value = beach.id;
            document.getElementById('beach-modal-subtitle').textContent = `${beach.nome} · ${beach.localidade}`;
            document.getElementById('beach-estado').value = beach.estado;
            document.getElementById('beach-bandeira').value = beach.bandeira;
            document.getElementById('beach-condicao').value = beach.condicao || 'Mar calmo, visibilidade boa.';
            document.getElementById('beach-nadadores').value = beach.nadadores;
            document.getElementById('beach-cobertura').value = beach.cobertura;

            beachModal.classList.remove('hidden');
            beachModal.classList.add('flex');
        }

        function closeBeachModal() {
            beachModal.classList.add('hidden');
            beachModal.classList.remove('flex');
        }

        document.getElementById('sidebar-toggle').addEventListener('click', () => {
            setSidebarOpen(sidebar.classList.contains('-translate-x-full'));
        });

        sidebarBackdrop.addEventListener('click', () => setSidebarOpen(false));

        document.querySelectorAll('.nav-link, .quick-action').forEach((element) => {
            element.addEventListener('click', () => {
                const sectionId = element.dataset.section || element.dataset.goSection;
                if (sectionId) {
                    showSection(sectionId);
                }
            });
        });

        document.getElementById('beach-search').addEventListener('input', renderBeachesTable);
        document.getElementById('beach-filter').addEventListener('change', renderBeachesTable);

        document.getElementById('add-beach-btn').addEventListener('click', () => {
            showToast('Funcionalidade de adicionar praia — apenas UI.');
        });

        document.getElementById('beach-modal-close').addEventListener('click', closeBeachModal);
        document.getElementById('beach-modal-cancel').addEventListener('click', closeBeachModal);
        document.getElementById('beach-modal-backdrop').addEventListener('click', closeBeachModal);

        document.getElementById('beach-form').addEventListener('submit', (event) => {
            event.preventDefault();

            const beachId = Number(document.getElementById('beach-id').value);
            const beach = beaches.find((item) => item.id === beachId);

            if (beach) {
                beach.estado = document.getElementById('beach-estado').value.trim();
                beach.bandeira = document.getElementById('beach-bandeira').value;
                beach.condicao = document.getElementById('beach-condicao').value.trim();
                beach.nadadores = Number(document.getElementById('beach-nadadores').value);
                beach.cobertura = Number(document.getElementById('beach-cobertura').value);
                beach.updated = new Date().toLocaleTimeString('pt-PT', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            renderBeachesTable();
            closeBeachModal();
            showToast('Alterações guardadas localmente (simulação UI).');
        });

        document.getElementById('settings-form').addEventListener('submit', (event) => {
            event.preventDefault();
            const feedback = document.getElementById('settings-feedback');
            feedback.classList.remove('hidden');
            showToast('Configurações guardadas (simulação UI).');
        });

        const userMenuBtn = document.getElementById('user-menu-btn');
        const userMenu = document.getElementById('user-menu');

        userMenuBtn.addEventListener('click', () => {
            const isOpen = !userMenu.classList.contains('hidden');
            userMenu.classList.toggle('hidden', isOpen);
            userMenuBtn.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('click', (event) => {
            if (!userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });

        document.getElementById('notifications-btn').addEventListener('click', () => {
            showSection('alerts');
            showToast('7 alertas por resolver.');
        });

        document.getElementById('global-search').addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                showSection('beaches');
                document.getElementById('beach-search').value = event.target.value;
                renderBeachesTable();
            }
        });

        renderBeachesTable();
        renderTeams();
        showSection('overview');
    </script>
</body>

</html>