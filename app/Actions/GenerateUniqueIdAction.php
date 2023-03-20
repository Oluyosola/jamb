<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;

class GenerateUniqueIdAction
{
    use QueueableAction;

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @param string $table The table to check uniqueness against.
     * @param string $column The unique column.
     * @param int $length Number of characters
     * @return string
     * @throws \Exception
     */
    public function execute(string $table, string $column, int $length = 10)
    {
        if ($length < 2) {
            throw new \Exception('Length must be greater than 1');
        }

        // Generate the ID.
        $uniqueCharacters = bin2hex(random_bytes($length / 2));

        if (DB::table($table)->where($column, $uniqueCharacters)->exists()) {
            $this->execute($table, $column);
        }

        return $uniqueCharacters;
    }
}
