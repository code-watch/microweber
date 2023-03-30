<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use MicroweberPackages\App\Traits\LiveEditTrait;



class LiveEditIframeController
{
    use LiveEditTrait;

    public function index()
    {

        $l = view('live_edit::live_edit.iframe');
        $l = app()->template->append_api_js_to_layout($l);
        // $l = $this->liveEditToolbar($l);
        return $l;

    }

    public function devPreview()
    {
        $l = view('live_edit::iframe-dev-preview');
        $l = app()->template->append_api_js_to_layout($l);

        return $l;
    }
}
