<?php

namespace App\Livewire\Admin\Master\MaterialCategory;

use App\Models\Master\Question\MaterialCategory;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterMaterialCategoryIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $data_id, $name, $description;

    public function render()
    {
        $material_categories = MaterialCategory::search($this->search)
            ->select('id', 'company_id', 'topic_id', 'material_category_id', 'name', 'description')
            ->with([
                'topic:name'
            ]);
        return view('livewire.admin.master.material-category.admin-master-material-category-index',[
            'material_categories' => $material_categories->paginate($this->perPage)
        ])->extends('layout.app')->section('content');
    }
}
