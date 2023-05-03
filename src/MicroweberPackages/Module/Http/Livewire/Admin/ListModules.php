<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class ListModules extends Component
{
    use WithPagination;

    public $keyword;
    public $type = 'admin';
    public $installed = 1;

    public $queryString = [
        'keyword',
        'page',
        'type',
        'installed',
    ];


    public function reloadModules()
    {
        mw_post_update();
    }

    public function filter()
    {
        $this->gotoPage(1);
    }

    public function render()
    {
        $modules = \MicroweberPackages\Module\Module::query();

        if ($this->keyword) {
            $modules->where('name', 'like', '%' . $this->keyword . '%');
        }

        if (!empty($this->type)) {
            if ($this->type == 'elements') {
                $modules->where('as_element', 1);
            }
            if ($this->type == 'admin') {
                $modules->where('ui_admin', 1);
            }
            if ($this->type == 'live_edit') {
                $modules->where('ui', 1);
            }
        }

        $modules->where('installed', $this->installed);

        $modules = $modules->paginate(28);

        return view('module::livewire.admin.list-modules', [
            'modules' => $modules
        ]);
    }
}