<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GenerateAgentToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:generate-token 
                            {--email=agent@taller.local : Email address for the agent user}
                            {--name=AIAgent : Name of the agent user}
                            {--token-name=AgentAPIToken : Name for the API token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or fetch an Agent user and generate a Laravel Sanctum API Token';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email');
        $name = $this->option('name');
        $tokenName = $this->option('token-name');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make(Str::random(32)),
            ]
        );

        $token = $user->createToken($tokenName);

        $this->info("Agent User: {$user->name} ({$user->email})");
        $this->info("API Token created successfully!");
        $this->line("");
        $this->warn("Token: " . $token->plainTextToken);
        $this->line("");
        $this->comment("Example request to list projects:");
        $this->line("curl -H \"Authorization: Bearer {$token->plainTextToken}\" -H \"Accept: application/json\" http://127.0.0.1:8000/api/v1/proyectos");
        $this->line("");
        $this->comment("Example request to list invoices:");
        $this->line("curl -H \"Authorization: Bearer {$token->plainTextToken}\" -H \"Accept: application/json\" http://127.0.0.1:8000/api/v1/cotizaciones");

        return Command::SUCCESS;
    }
}
