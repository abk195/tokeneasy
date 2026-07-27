<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\IssuerTokenRequest;
use App\Services\TokenizerService;

class TokenDeployStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploying the token in backend';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $tokensTodeploy = IssuerTokenRequest::where('token_deploy_status', 0)->get();
            $tokenizerService = new TokenizerService();

            foreach ($tokensTodeploy as $tokendeploy) {
                $result = $tokenizerService->deployToken($tokendeploy->id);

                if (!empty($result['hasError'])) {
                    \Log::warning('Token deploy cron failed', [
                        'issuer_token_id' => $tokendeploy->id,
                        'message' => $result['message'] ?? null,
                    ]);
                    continue;
                }

                \Log::info('Token deployed successfully in cron', [
                    'issuer_token_id' => $tokendeploy->id,
                    'contract_address' => $result['contract_address'] ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::critical("Issue in token deploy cron tab " . $e);
            \Log::info($e);
        }
    }
}
