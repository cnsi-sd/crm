<?php

namespace Tests\Feature;

use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Tests\TestCase;

class TicketTest extends TestCase
{
    public function test_updateDeadline()
    {
        // hour < 16h
        $fakeCurrentDate = Carbon::create(2023, 01, 01, 15, 00, 00);
        Carbon::setTestNow($fakeCurrentDate);
        $calculatedDeadline = Ticket::getAutoDeadline()->format('Y-m-d');
        $this->assertEquals('2023-01-01', $calculatedDeadline);

        // hour > 16h
        $fakeCurrentDate = Carbon::create(2023, 01, 01, 16, 00, 00);
        Carbon::setTestNow($fakeCurrentDate);
        $calculatedDeadline = Ticket::getAutoDeadline()->format('Y-m-d');
        $this->assertEquals('2023-01-02', $calculatedDeadline);
    }
}
