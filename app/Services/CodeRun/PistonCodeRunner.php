<?php
// file: app/Services/CodeRun/PistonCodeRunner.php
namespace App\Services\CodeRun;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class PistonCodeRunner implements CodeRunner
{
    public function __construct(private string $baseUrl, private int $timeoutMs) {}

    public function run(string $language, string $source): array
    {
        $lang = match ($language) {
            'javascript' => 'js',
            'python' => 'py',
            'php' => 'php',
            default => $language
        };

        $client = new Client(['base_uri'=>$this->baseUrl, 'timeout'=>$this->timeoutMs/1000]);

        $payload = [
            'language' => $lang,
            'version' => '*',
            'files' => [['content' => $source]],
            'stdin' => '',
            'args' => [],
            'compile_timeout' => $this->timeoutMs,
            'run_timeout' => $this->timeoutMs,
        ];

        $resp = $client->post('/execute', ['json'=>$payload]);
        $data = json_decode((string)$resp->getBody(), true);

        $stdout = Arr::get($data, 'run.stdout', '');
        $stderr = Arr::get($data, 'run.stderr', '');
        $code = (int) Arr::get($data, 'run.code', 0);
        $timeMs = (int) round((float) (Arr::get($data, 'run.signal', 0)) ?: 0);

        return ['stdout'=>$stdout, 'stderr'=>$stderr, 'code'=>$code, 'time_ms'=>$timeMs];
    }
}

