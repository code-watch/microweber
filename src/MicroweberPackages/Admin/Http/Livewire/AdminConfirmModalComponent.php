<?php
namespace MicroweberPackages\Admin\Http\Livewire;

class AdminConfirmModalComponent extends AdminModalComponent
{
    public $action = '';
    public $data = [];

    public $listeners = [
        'closeAdminConfirmModal' => 'closeModal',
    ];

    public function render()
    {
        return view('admin::admin.livewire.modals.confirm');
    }

    public function confirm()
    {
        $this->closeModal();
        if ($this->action) {
            $this->emit($this->action, $this->data);
        }
    }
}
