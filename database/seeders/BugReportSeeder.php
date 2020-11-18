<?php

namespace Database\Seeders;

use App\Models\BugReport;
use Illuminate\Database\Seeder;

/**
 * Database seeder to generate random bug reports
 *
 * @package Database\Seeders
 */
class BugReportSeeder extends Seeder
{
    /**
     * Run the seeder for bug reports.
     *
     * @return void
     */
    public function run()
    {
	    // generate random bug reports
	    BugReport::factory()->times(50)->create();
    }
}