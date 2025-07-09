<?php

namespace App\Livewire\Admin\Report\Incentive;

use App\Models\User;
use App\Models\User\UserIncentive;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportIncentiveIndex extends Component
{
    use WithPagination;

    public $user_id, $search;
    public $perPage = 5, $year, $month, $type;

    // Array
    public $getYears = [], $getMonths = [], $getUsers = [];

    public function mount()
    {
        $this->year = intval(date('Y'));
        $this->month = intval(date('m'));
        $this->getYears = range(date('Y'), 2000);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $this->getMonths = [];

        foreach ($months as $number => $name) {
            $this->getMonths[] = [
                'number' => $number,
                'name' => $name
            ];
        }

        $this->getUsers = User::where('company_id', auth()->user()->company_id)->where('type_user','employee')->select('id', 'name')->get()->toArray();
    }

    public function changeMonth($month)
    {
        $this->month = intval($month);
    }

    public function render()
    {
        $userIncentive = UserIncentive::search($this->search)->with(['user:id,name', 'company:id,name']);

        if ($this->user_id) {
            $userIncentive->where('user_id', $this->user_id);
        }

        if ($this->year) {
            $userIncentive->where('year', $this->year);
        }

        if ($this->month) {
            $userIncentive->where('month', $this->month);
        }

        if ($this->type) {
            $userIncentive->where('status', $this->type);
        }

        return view('livewire.admin.report.incentive.admin-report-incentive-index',[
            'userIncentives' => $userIncentive->paginate($this->perPage),
            'totals' => $userIncentive->sum('amount'),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
