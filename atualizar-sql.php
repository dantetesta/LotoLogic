<?php
/**
 * LotoLab SQL Updater - Atualizacao INCREMENTAL
 * Atualiza os arquivos .sql existentes baixando apenas os concursos novos
 *
 * Protegido por token. Acesso apenas via:
 *   - CLI: php atualizar-sql.php (sem token necessario)
 *   - Browser/Cron URL: atualizar-sql.php?token=SEU_TOKEN
 *
 * Cron cPanel: 0 8 * * * curl -s "https://dantetesta.com.br/lotolab/atualizar-sql.php?token=lotolab-cron-2024"
 * Ou via CLI: 0 8 * * * php /home/USER/public_html/lotolab/atualizar-sql.php
 */

// ── Configuracao ──
$TOKEN = 'lotolab-cron-2024'; // Altere para um token seguro
$API_BASE = 'https://loteriascaixa-api.herokuapp.com/api';
$OUTPUT_DIR = __DIR__ . '/loterias';
$LOG_FILE = __DIR__ . '/update-log.txt';
$DELAY_MS = 200;

// ── Protecao por token (modo browser) ──
if (php_sapi_name() !== 'cli') {
    $provided = $_GET['token'] ?? '';
    if ($provided !== $TOKEN) {
        http_response_code(403);
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>403</title>
        <style>body{font-family:system-ui;background:#0c1518;color:#ffb4ab;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center}
        h1{font-size:60px;margin:0}p{color:#8a9a9e;margin-top:10px}</style></head>
        <body><div><h1>403</h1><p>Acesso negado. Token invalido ou ausente.</p></div></body></html>';
        exit;
    }
}

// ── Loterias ──
$LOTERIAS = [
    ['type' => 'megasena',       'api' => 'megasena',       'name' => 'Mega-Sena'],
    ['type' => 'lotofacil',      'api' => 'lotofacil',      'name' => 'Lotofacil'],
    ['type' => 'quina',          'api' => 'quina',          'name' => 'Quina'],
    ['type' => 'lotomania',      'api' => 'lotomania',      'name' => 'Lotomania'],
    ['type' => 'maismilionaria', 'api' => 'maismilionaria', 'name' => '+Milionaria'],
    ['type' => 'duplasena',      'api' => 'duplasena',      'name' => 'Dupla Sena'],
    ['type' => 'timemania',      'api' => 'timemania',      'name' => 'Timemania'],
    ['type' => 'diadesorte',     'api' => 'diadesorte',     'name' => 'Dia de Sorte'],
    ['type' => 'supersete',      'api' => 'supersete',      'name' => 'Super Sete'],
];

// ── Funcoes ──
$is_cli = (php_sapi_name() === 'cli');

function output($msg) {
    global $is_cli;
    $ts = date('H:i:s');
    $line = "[{$ts}] {$msg}";
    if ($is_cli) {
        echo $line . "\n";
    } else {
        $color = '#dae4e8';
        if (strpos($msg, 'ERRO') !== false) $color = '#ffb4ab';
        elseif (strpos($msg, 'OK') !== false || strpos($msg, 'NOVO') !== false) $color = '#6edba6';
        elseif (strpos($msg, 'ATUALIZADO') !== false) $color = '#e9c349';
        echo "<pre style='margin:0;color:{$color}'>{$line}</pre>";
        ob_flush(); flush();
    }
}

function write_log($msg) {
    global $LOG_FILE;
    $ts = date('Y-m-d H:i:s');
    file_put_contents($LOG_FILE, "[{$ts}] {$msg}\n", FILE_APPEND);
}

function fetch_json($url) {
    $ctx = stream_context_create(['http' => ['timeout' => 30, 'header' => "User-Agent: LotoLab-Updater/1.0\r\n"]]);
    $json = @file_get_contents($url, false, $ctx);
    if ($json === false) return null;
    return json_decode($json, true);
}

function escape_sql($val) {
    if ($val === null) return 'NULL';
    return "'" . str_replace("'", "''", (string)$val) . "'";
}

function contest_to_sql($game_type, $contest) {
    $concurso = (int)$contest['concurso'];
    $data = $contest['data'] ?? '';
    $local = $contest['local'] ?? $contest['localSorteio'] ?? null;
    $dezenas = $contest['dezenas'] ?? [];
    $dezenasOrdem = $contest['dezenasOrdemSorteio'] ?? $contest['dezenas'] ?? [];
    $acumulou = !empty($contest['acumulou']) ? 1 : 0;
    $proximoConcurso = $contest['proximoConcurso'] ?? null;
    $dataProximoConcurso = $contest['dataProximoConcurso'] ?? null;
    $valorEstimado = $contest['valorEstimadoProximoConcurso'] ?? null;
    $valorArrecadado = $contest['valorArrecadado'] ?? null;

    $nums_sorted = array_map('intval', $dezenas);
    sort($nums_sorted);
    $nums_draw = array_map('intval', $dezenasOrdem);
    $nums_text = implode(', ', array_map(function($n) { return str_pad($n, 2, '0', STR_PAD_LEFT); }, $nums_sorted));

    $trevos = $contest['trevosSorteados'] ?? $contest['trevos'] ?? [];
    $trevos_json = !empty($trevos) ? escape_sql(json_encode($trevos)) : 'NULL';
    $time_coracao = $contest['timeCoracao'] ?? $contest['nomeTimeCoracao'] ?? null;
    $time_coracao = ($time_coracao && trim($time_coracao) !== '') ? escape_sql($time_coracao) : 'NULL';
    $mes_sorte = $contest['mesSorte'] ?? $contest['nomesMesDaSorte'] ?? null;
    $mes_sorte = ($mes_sorte && trim($mes_sorte) !== '') ? escape_sql($mes_sorte) : 'NULL';

    $raw_json = escape_sql(json_encode($contest, JSON_UNESCAPED_UNICODE));

    $sql = "INSERT OR IGNORE INTO contests (game_type, contest_number, contest_date, location, numbers_draw_order_json, numbers_sorted_json, numbers_sorted_text, accumulated, next_contest_number, next_contest_date, estimated_next_prize, amount_collected, raw_json, trevos_json, time_coracao, mes_sorte) VALUES (";
    $sql .= escape_sql($game_type) . ", ";
    $sql .= $concurso . ", ";
    $sql .= escape_sql($data) . ", ";
    $sql .= ($local ? escape_sql($local) : 'NULL') . ", ";
    $sql .= escape_sql(json_encode($nums_draw)) . ", ";
    $sql .= escape_sql(json_encode($nums_sorted)) . ", ";
    $sql .= escape_sql($nums_text) . ", ";
    $sql .= $acumulou . ", ";
    $sql .= ($proximoConcurso !== null ? (int)$proximoConcurso : 'NULL') . ", ";
    $sql .= ($dataProximoConcurso ? escape_sql($dataProximoConcurso) : 'NULL') . ", ";
    $sql .= ($valorEstimado !== null ? (float)$valorEstimado : 'NULL') . ", ";
    $sql .= ($valorArrecadado !== null ? (float)$valorArrecadado : 'NULL') . ", ";
    $sql .= $raw_json . ", ";
    $sql .= $trevos_json . ", ";
    $sql .= $time_coracao . ", ";
    $sql .= $mes_sorte;
    $sql .= ");";

    $premiacoes = $contest['premiacoes'] ?? $contest['premiacao'] ?? [];
    $prize_sql = '';
    if (!empty($premiacoes)) {
        foreach ($premiacoes as $p) {
            $desc = $p['descricao'] ?? null;
            $faixa = $p['faixa'] ?? null;
            $ganhadores = $p['ganhadores'] ?? 0;
            $valor = $p['valorPremio'] ?? 0;
            $prize_sql .= "\nINSERT OR IGNORE INTO contest_prizes (contest_id, description, range_number, winners_count, prize_value) SELECT id, " . escape_sql($desc) . ", " . ($faixa !== null ? (int)$faixa : 'NULL') . ", " . (int)$ganhadores . ", " . (float)$valor . " FROM contests WHERE game_type = " . escape_sql($game_type) . " AND contest_number = " . $concurso . ";";
        }
    }

    return $sql . $prize_sql;
}

function get_last_contest_from_sql($filepath) {
    if (!file_exists($filepath)) return 0;

    // Procura o UPDATE sync_state com o ultimo concurso no final do arquivo
    $content = file_get_contents($filepath);
    if (preg_match('/last_imported_contest\s*=\s*(\d+)/', $content, $matches)) {
        return (int)$matches[1];
    }

    // Fallback: procura o maior contest_number nos INSERTs
    preg_match_all('/VALUES\s*\([^,]+,\s*(\d+)\s*,/', $content, $matches);
    if (!empty($matches[1])) {
        return (int)max($matches[1]);
    }

    return 0;
}

// ── Main ──
if (!$is_cli) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>LotoLab SQL Updater</title>
    <style>body{font-family:monospace;background:#0c1518;color:#dae4e8;padding:20px;margin:0;font-size:12px;line-height:1.6}
    h1{color:#6edba6;font-size:20px}h2{color:#e9c349;font-size:14px;margin-top:16px}
    .status{background:#141d20;padding:16px;border-radius:12px;margin:12px 0;border:1px solid #3e4942}
    .footer{margin-top:20px;padding:12px;background:#141d20;border-radius:8px;font-size:11px;color:#8a9a9e}</style></head><body>';
    echo '<h1>LotoLab SQL Updater - Atualizacao Incremental</h1>';
    echo '<div class="status">';
    echo '<p><strong>Modo:</strong> ' . ($is_cli ? 'CLI/Cron' : 'Browser') . '</p>';
    echo '<p><strong>Data:</strong> ' . date('d/m/Y H:i:s') . '</p>';
    echo '<p><strong>Pasta:</strong> ' . $OUTPUT_DIR . '</p>';
    echo '</div>';
}

if (!is_dir($OUTPUT_DIR)) {
    mkdir($OUTPUT_DIR, 0755, true);
}

write_log("=== Atualizacao iniciada ===");
output("Iniciando atualizacao incremental...\n");

$total_new = 0;
$total_loterias = 0;

foreach ($LOTERIAS as $loteria) {
    $type = $loteria['type'];
    $api_path = $loteria['api'];
    $name = $loteria['name'];
    $filepath = "{$OUTPUT_DIR}/{$type}.sql";

    output("--- {$name} ---");

    // Verificar ultimo concurso local
    $local_last = get_last_contest_from_sql($filepath);
    output("  Local: #{$local_last}");

    // Buscar ultimo na API
    $latest = fetch_json("{$API_BASE}/{$api_path}/latest");
    if (!$latest || !isset($latest['concurso'])) {
        output("  [ERRO] API indisponivel para {$name}");
        write_log("[ERRO] {$name}: API indisponivel");
        continue;
    }

    $remote_last = (int)$latest['concurso'];
    output("  API: #{$remote_last}");

    if ($local_last >= $remote_last) {
        output("  [OK] Ja esta atualizado!");
        write_log("{$name}: atualizado (local={$local_last}, api={$remote_last})");
        continue;
    }

    $missing = $remote_last - $local_last;
    output("  [ATUALIZADO] Faltam {$missing} concursos ({$local_last} -> {$remote_last})");

    // Baixar concursos faltantes
    $new_sql = "\n-- Atualizacao incremental: " . date('Y-m-d H:i:s') . "\n";
    $new_sql .= "-- Concursos " . ($local_last + 1) . " a {$remote_last}\n\n";

    $imported = 0;

    for ($n = $local_last + 1; $n <= $remote_last; $n++) {
        $contest = fetch_json("{$API_BASE}/{$api_path}/{$n}");

        if (!$contest || !isset($contest['concurso'])) {
            output("  [WARN] Concurso #{$n} nao encontrado, pulando...");
            continue;
        }

        $new_sql .= contest_to_sql($type, $contest) . "\n";
        $imported++;

        if ($imported % 50 === 0) {
            output("  [NOVO] {$imported}/{$missing} baixados...");
        }

        usleep($DELAY_MS * 1000);
    }

    // Atualizar sync state no final
    $new_sql .= "\nUPDATE sync_state SET last_imported_contest = {$remote_last}, last_synced_at = datetime('now') WHERE game_type = '{$type}';\n";

    // Se o arquivo nao existe, criar com header
    if (!file_exists($filepath)) {
        $header = "-- LotoLab SQL Export: {$name}\n";
        $header .= "-- Gerado em: " . date('Y-m-d H:i:s') . "\n";
        $header .= "-- Fonte: loteriascaixa-api.herokuapp.com\n\n";
        $header .= "INSERT OR IGNORE INTO sync_state (game_type, last_imported_contest, sync_status) VALUES ('{$type}', 0, 'idle');\n\n";
        file_put_contents($filepath, $header);
    } else {
        // Remover o UPDATE sync_state antigo do final do arquivo
        $content = file_get_contents($filepath);
        $content = preg_replace('/\nUPDATE sync_state SET last_imported_contest = \d+.*WHERE game_type = \'' . $type . '\';\s*$/', '', $content);
        file_put_contents($filepath, $content);
    }

    // Append novos dados
    file_put_contents($filepath, $new_sql, FILE_APPEND);

    $size = round(filesize($filepath) / 1024 / 1024, 2);
    output("  [OK] +{$imported} concursos adicionados ({$size} MB total)");
    write_log("{$name}: +{$imported} concursos (local={$local_last}->{$remote_last})");

    $total_new += $imported;
    $total_loterias++;
}

output("\n========================================");
output("  RESUMO");
output("  Loterias atualizadas: {$total_loterias}");
output("  Novos concursos: {$total_new}");
output("  Data: " . date('d/m/Y H:i:s'));
output("========================================");

write_log("Finalizado: {$total_new} novos concursos em {$total_loterias} loterias");

if (!$is_cli) {
    echo '<div class="footer">';
    echo '<h2>Instrucoes para cPanel Cron Job</h2>';
    echo '<p>1. Acesse cPanel > Cron Jobs</p>';
    echo '<p>2. Adicione um novo cron com o comando:</p>';
    echo '<pre style="background:#222e31;padding:10px;border-radius:8px;color:#6edba6">php ' . __FILE__ . '</pre>';
    echo '<p>3. Configure a frequencia: <strong>Todo dia as 8h</strong></p>';
    echo '<pre style="background:#222e31;padding:10px;border-radius:8px;color:#6edba6">0 8 * * *</pre>';
    echo '<p>4. Os arquivos SQL ficam em: <code>' . $OUTPUT_DIR . '/</code></p>';
    echo '<p>5. O LotoLab app importa de: <code>https://seusite.com.br/loterias/{loteria}.sql</code></p>';
    echo '<hr style="border-color:#3e4942;margin:12px 0">';
    echo '<p>Log de atualizacoes: <a href="update-log.txt" style="color:#6edba6">update-log.txt</a></p>';
    echo '</div>';
    echo '</body></html>';
}
