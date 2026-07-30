    <?php
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
        return match (strtolower(trim($bandeira))) {
            'verde' => 'bg-emerald-100 text-emerald-800',
            'amarela' => 'bg-amber-100 text-amber-800',
            'vermelha' => 'bg-red-100 text-red-800',
            default => 'bg-slate-100 text-slate-800',
        };
    }
    ?>

    <!doctype html>
    <html lang="pt-PT">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Praias — Maré Segura</title>
        <link rel="stylesheet" href="assets/app.css">
    </head>

    <body class="bg-slate-50 text-slate-900">
        <main class="mx-auto max-w-6xl px-6 py-14">
            <a href="index.php" class="text-sm font-medium text-sky-700 hover:underline">
                ← Voltar à página inicial
            </a>

            <div class="mt-8">
                <h1 class="text-4xl font-semibold">Todas as praias</h1>
                <p class="mt-3 text-slate-600">
                    Estado atualizado das praias monitorizadas.
                </p>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($beaches as $beach): ?>
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold"><?= e($beach['nome']) ?></h2>
                                <p class="mt-1 text-sm text-slate-500"><?= e($beach['localidade']) ?></p>
                            </div>

                            <span class="rounded-full px-3 py-1 text-xs font-semibold <?= badgeClass($beach['bandeira']) ?>">
                                <?= ucfirst(e($beach['bandeira'])) ?>
                            </span>
                        </div>

                        <dl class="mt-6 space-y-3 text-sm">
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Estado</dt>
                                <dd class="font-medium"><?= e($beach['estado']) ?></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Condições</dt>
                                <dd class="text-right"><?= e($beach['condicao_mar']) ?></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Nadadores-salvadores</dt>
                                <dd><?= $beach['nadadores_ativos'] ?></dd>
                            </div>
                        </dl>
                    </article>
                <?php endforeach; ?>
            </div>
        </main>
    </body>

    </html>