<?php
// file: app/Services/CodeRun/CodeRunner.php
namespace App\Services\CodeRun;

interface CodeRunner
{
    /**
     * @return array{stdout:string,stderr:string,code:int,time_ms:int}
     */
    public function run(string $language, string $source): array;
}

