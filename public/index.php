<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/../config/database.php';

$beaches = $pdo->query(
    'SELECT id, nome, localidade, bandeira, estado, condicao_mar, nadadores_ativos, cobertura, updated_at FROM praias WHERE ativa = 1 ORDER BY nome'
)->fetchAll();

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


function badgeClass(string $bandeira): string
{
    return match ($bandeira) {
        'verde' => 'bg-emerald-100 text-emerald-800',
        'amarela' => 'bg-amber-100 text-amber-800',
        'vermelha' => 'bg-red-100 text-red-800',
    };
}

$featured = $beaches[0] ?? [
    'nome' => 'Sem praias disponíveis',
    'estado' => 'Indisponível',
    'bandeira' => 'amarela',
    'condicao_mar' => 'Dados ainda não disponíveis',
    'nadadores_ativos' => 0,
    'cobertura' => 0,
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project FCT 2.0</title>
    <link rel="stylesheet" href="assets/app.css">
</head>

<body class="bg-slate-50 text-slate-900">
    <header class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
        <a href="/" class="flex items-center gap-3 font-semibold">
            <span class="grid h-10 w-10 place-items-center rounded-full bg-sky-600 text-xl text-white">≈</span>
            Maré Segura
        </a>

        <nav class="hidden gap-7 text-sm text-slate-600 md:flex">
            <a href="#praias" class="hover:text-sky-700">Praias</a>
            <a href="#seguranca" class="hover:text-sky-700">Segurança</a>
            <a href="#municipios" class="hover:text-sky-700">Para municípios</a>
        </nav>

        <a href="/login.php" class="rounded-full bg-sky-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-sky-700">
            Entrar
        </a>
    </header>

    <main>
        <section class="mx-auto grid max-w-6xl gap-12 px-6 py-14 lg:grid-cols-2 lg:items-center">
            <div>
                <p class="mb-4 font-medium text-sky-700">✦ Informação que protege</p>
                <h1 class="max-w-xl text-5xl font-semibold tracking-tight sm:text-6xl">
                    Dias de praia mais seguros começam aqui.
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                    Acompanhe as condições das praias, coordene nadadores-salvadores
                    e informe banhistas em tempo real.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#praias" class="rounded-full bg-sky-600 px-6 py-3 font-medium text-white hover:bg-sky-700">
                        Explorar praias
                    </a>
                    <a href="#seguranca" class="rounded-full border border-slate-300 px-6 py-3 font-medium hover:bg-white">
                        Ver estado operacional
                    </a>
                </div>
            </div>

            <aside class="relative min-h-96 overflow-hidden rounded-[2rem] bg-gradient-to-br from-sky-200 via-cyan-100 to-amber-100 p-6">
                <div class="absolute right-14 top-12 h-20 w-20 rounded-full bg-amber-300"></div>
                <div class="absolute inset-x-0 top-1/2 h-px bg-sky-600/30"></div>
                <div class="absolute -bottom-12 left-[-10%] h-44 w-[120%] rounded-t-[50%] bg-amber-200"></div>

                <div class="absolute inset-x-6 bottom-6 rounded-2xl border border-white/60 bg-white/85 p-5 shadow-lg backdrop-blur">
                    <div class="flex items-center justify-between gap-3">
                        <strong id="beach-name"><?= e($featured['nome']) ?></strong>

                        <span id="beach-status"
                            class="rounded-full px-3 py-1 text-xs font-semibold <?= badgeClass($featured['bandeira']) ?>">
                            <?= e($featured['estado']) ?>
                        </span>
                    </div>

                    <p id="beach-detail" class="mt-2 text-sm text-slate-600">
                        <?= e($featured['condicao_mar']) ?>
                        · <?= (int) $featured['nadadores_ativos'] ?> nadadores-salvadores em serviço
                    </p>

                    <div class="mt-4 flex items-center gap-3 text-sm">
                        <span class="text-slate-500">Cobertura</span>

                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-200">
                            <div id="coverage-bar"
                                class="h-full bg-sky-600"
                                style="width: <?= (int) $featured['cobertura'] ?>%">
                            </div>
                        </div>

                        <strong id="coverage-value"><?= (int) $featured['cobertura'] ?>%</strong>
                    </div>
                </div>
            </aside>
        </section>

        <section class="border-y border-slate-200 bg-white">
            <div class="mx-auto grid max-w-6xl gap-5 px-6 py-5 text-center text-sm text-slate-600 md:grid-cols-3">
                <p><strong class="text-slate-900">82</strong> praias monitorizadas</p>
                <p><strong class="text-slate-900">246</strong> profissionais ligados</p>
                <p>Atualizações a cada <strong class="text-slate-900">15 min</strong></p>
            </div>
        </section>

        <section id="praias" class="mx-auto max-w-6xl px-6 py-20">

            <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-3xl font-semibold">Antes de sair de casa</h2>
                    <p class="mt-2 text-slate-600">Escolha uma praia para consultar o estado.</p>
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <?php foreach ($beaches as $beach): ?>
                    <button
                        type="button"
                        class="beach-card rounded-2xl border border-slate-200 bg-white p-5 text-left shadow-sm"
                        data-name="<?= e($beach['nome']) ?>"
                        data-status="<?= e($beach['estado']) ?>"
                        data-detail="<?= e($beach['condicao_mar']) ?> · <?= $beach['nadadores_ativos'] ?> nadadores-salvadores em serviço"
                        data-coverage="<?= $beach['cobertura'] ?>">
                        <div class="mb-5 h-28 rounded-xl bg-gradient-to-br from-sky-200 to-cyan-100"></div>

                        <h3 class="font-semibold"><?= e($beach['nome']) ?></h3>

                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="text-slate-500"><?= e($beach['localidade']) ?></span>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold <?= badgeClass($beach['bandeira']) ?>">
                                <?= ucfirst(e($beach['bandeira'])) ?>
                            </span>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="mt-5 mb-5 text-center">
            <a href="praias.php"
                class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-6 py-3 font-medium text-white hover:bg-sky-700">
                Ver todas as praias <span aria-hidden="true">→</span>
            </a>
        </div>

        <section id="seguranca" class="bg-sky-950 px-6 py-16 text-white">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-semibold">Uma equipa coordenada. Um areal mais seguro.</h2>
                    <p class="mt-3 text-sky-100">Turnos, alertas e ocorrências num só lugar.</p>
                </div>
                <span class="rounded-full bg-white/10 px-4 py-2 text-sm">● Sistema operacional</span>
            </div>
        </section>
    </main>

    <script>
        const cards = document.querySelectorAll('.beach-card');

        cards.forEach((card) => {
            card.addEventListener('click', () => {
                cards.forEach((item) => item.classList.remove('ring-2', 'ring-sky-600'));
                card.classList.add('ring-2', 'ring-sky-600');

                document.querySelector('#beach-name').textContent = card.dataset.name;
                document.querySelector('#beach-status').textContent = card.dataset.status;
                document.querySelector('#beach-detail').textContent = card.dataset.detail;
                document.querySelector('#coverage-value').textContent = `${card.dataset.coverage}%`;
                document.querySelector('#coverage-bar').style.width = `${card.dataset.coverage}%`;
            });
        });
    </script>
</body>

</html>