<?php

namespace Tests\Unit;

use App\Livewire\Admin\Master\Student\AdminMasterStudentIndex;
use PHPUnit\Framework\TestCase;

class StudentPaginationResetTest extends TestCase
{
    public function test_student_table_controls_reset_pagination_to_the_first_page(): void
    {
        $component = new AdminMasterStudentIndex;

        foreach ([
            'updatingSearch',
            'updatingProgramFilter',
            'updatingStatusFilter',
            'updatingIsStudentFilter',
            'updatingPerPage',
        ] as $hook) {
            $component->paginators['page'] = 11;
            $component->{$hook}();

            $this->assertSame(1, $component->paginators['page']);
        }
    }
}
