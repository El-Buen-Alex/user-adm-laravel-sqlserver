<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDefaultSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:create-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creación de esquema por defecto en la base de datos SQL Server';

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
     * @return int
     */
    public function handle()
    {
        $schemaName = config('SystemVars.DB_DATABASE');
        DB::statement("
            IF NOT EXISTS (SELECT * FROM sys.schemas WHERE name = '$schemaName')
            BEGIN
                EXEC('CREATE SCHEMA $schemaName');
            END
        ");
        return 0;
    }
}
