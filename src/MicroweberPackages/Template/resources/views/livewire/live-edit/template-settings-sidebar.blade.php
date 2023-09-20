@extends('admin::layouts.iframe')

@section('content')

<div wire:ignore>
    <style>
        body {
            background: #f6f8fb !important;
            padding-left:15px;
            padding-top:10px;
        }
    </style>

    <script>
        document.addEventListener('mw-option-saved', function() {

            var customFontsStylesheet = mw.top().app.canvas.getDocument().getElementById("mw-custom-user-css");
            if (customFontsStylesheet != null) {
                var customFontsStylesheetRestyle = mw.settings.api_url + 'template/print_custom_css?time=' + Math.random(0, 10000);
                customFontsStylesheet.href = customFontsStylesheetRestyle;
            }
            setTimeout(function() {
                mw.top().app.templateSettings.reloadStylesheet('{{$styleSheetSourceFile}}', '{{$optionGroupLess}}');
            }, 1000);

        });
    </script>

    <script>
        function openRTECSsEditor2(settings) {

            console.log('openRTECSsEditor2', settings);

            let iframeStyleEdiorId = 'iframeStyleEditorId-1';
            let checkIframeStyleEditor = document.getElementById(iframeStyleEdiorId);

            if (!checkIframeStyleEditor) {
                var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2';
                var attrsForSettings = {};

                attrsForSettings.live_edit = true;
                attrsForSettings.module_settings = true;
                attrsForSettings.id = 'mw_global_rte_css_editor2_editor';
                attrsForSettings.type = moduleType;
                attrsForSettings.iframe = true;
                attrsForSettings.disable_auto_element_change = true;
                attrsForSettings.output_static_selector = true;
                attrsForSettings.from_url = mw.top().app.canvas.getWindow().location.href;

                var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

                $('#iframe-holder').html('<iframe id="' + iframeStyleEdiorId + '" src="' + src + '" style="width:100%;height:500px;border:none;"></iframe>');

                document.getElementById(iframeStyleEdiorId).addEventListener('load', function(e) {
                    // alert('iframe loaded');
                    // alert(settings.selectors[0]);
                    mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                });

            } else {
                mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
            }

        }
        function openRTECSsEditor2Vue(settings) {

            console.log('openRTECSsEditor2Vue', settings);

            let iframeStyleEdiorId = 'iframeStyleEditorId-Vue';
            let checkIframeStyleEditor = document.getElementById(iframeStyleEdiorId);

            if (!checkIframeStyleEditor) {
                var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue';
                var attrsForSettings = {};

                attrsForSettings.live_edit = true;
                attrsForSettings.module_settings = true;
                attrsForSettings.id = 'mw_global_rte_css_editor2_editor_vue';
                attrsForSettings.type = moduleType;
                attrsForSettings.iframe = true;
                attrsForSettings.disable_auto_element_change = true;
                attrsForSettings.output_static_selector = true;
                attrsForSettings.from_url = mw.top().app.canvas.getWindow().location.href;

                var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

                $('#iframe-holder').append('<iframe id="' + iframeStyleEdiorId + '" src="' + src + '" style="width:100%;height:500px;border:none;"></iframe>');

                document.getElementById(iframeStyleEdiorId).addEventListener('load', function(e) {
                    // alert('iframe loaded');
                    // alert(settings.selectors[0]);
                    mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                });

            } else {
                mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
            }

        }
        mw.top().app.on('mw.rte.css.editor2.open', function(e) {
            openRTECSsEditor2(e);
            openRTECSsEditor2Vue(e);
        });
    </script>

    @if(isset($styleSettings))

        <div
            x-data="{styleEditorData:{}, showStyleSettings: '/'}"

            x-init="()=>{

        $watch('styleEditorData', (value) => {
            if (value.selectors) {
                mw.top().app.dispatch('mw.rte.css.editor2.open', value);
            }
        });

        }"
        >

            <div>
            <span
                x-show="showStyleSettings == '/'"
                class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
               Styles
            </span>

            @foreach($styleSettings as $styleSetting)

                <div
                    @if(isset($styleSetting['main']))
                    x-show="showStyleSettings == '/'"
                    @else
                    x-show="showStyleSettings == '{{$styleSetting['url']}}'"
                    @endif

                    x-transition:enter="tab-pane-slide-left-active"

                    class="mt-2">

                    <div
                        x-show="showStyleSettings == '/'"
                    >
                        @if (isset($styleSetting['title']))
                            <a x-on:click="showStyleSettings = '{{ $styleSetting['url'] }}'" class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                                {{ $styleSetting['title'] }}
                            </a>
                        @endif

                    </div>

                    <div class="mt-3" x-show="showStyleSettings == '{{$styleSetting['url']}}'">

                        <div>
                            <button x-on:click="showStyleSettings = '{{$styleSetting['backUrl']}}'" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link&#45;&#45;arrowed text-start text-start" type="button">
                                <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon&#45;&#45;circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon&#45;&#45;arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                                <div class="ms-1 font-weight-bold">
                                    Back
                                </div>
                            </button>
                        </div>

                        <div>
                            @if(isset($styleSetting['title']))
                                <h4>{{$styleSetting['title']}}</h4>
                            @endif
                            @if(isset($styleSetting['description']))
                                <p>{{$styleSetting['description']}}</p>
                            @endif
                        </div>

                        @if(isset($styleSetting['fieldType']))
                            @include('template::livewire.live-edit.template-setting-render', [
                                    'setting' => $styleSetting
                                ])
                        @endif

                        @if(isset($styleSetting['settings']))
                            @foreach($styleSetting['settings'] as $setting)
                                @include('template::livewire.live-edit.template-setting-render', [
                                    'setting' => $setting
                                ])
                            @endforeach
                        @endif

                    </div>

                </div>
            @endforeach

            </div>

            <div

                x-show="showStyleSettings == 'styleEditor'"
                class="mt-3"
            >

                <div>
                    <button x-on:click="showStyleSettings = styleEditorData.backUrl" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link&#45;&#45;arrowed text-start text-start" type="button">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon&#45;&#45;circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon&#45;&#45;arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                        <div class="ms-1 font-weight-bold">
                            Back
                        </div>
                    </button>
                </div>

                <b x-html="styleEditorData.title"></b>
                <p x-html="styleEditorData.description"></p>

                <div>
                    <div id="iframe-holder"></div>
                </div>
            </div>


                @foreach($settingsGroups as $settingGroupName=>$settingGroup)
                    <div wire:key="setting-group-key-{{md5($settingGroupName)}}" class="mt-3">

                        <div x-show="showStyleSettings == '/'">
                            <a class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                                {{$settingGroupName}}
                            </a>
                        </div>

                        <div>
                            @foreach($settingGroup['values'] as $settingName=>$settingFields)
                                <div wire:key="setting-values-key-{{md5($settingName)}}">

                                    <div x-show="showStyleSettings == 'setting-values-key-{{md5($settingName)}}'">
                                        <button x-on:click="showStyleSettings = '/'" class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" type="button">
                                            <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                                            <div class="ms-1 font-weight-bold">
                                                Back to {{mb_strtolower($settingGroupName)}}
                                            </div>
                                        </button>
                                    </div>

                                    <div x-show="showStyleSettings == '/'" class="mt-2">
                                        <a x-on:click="showStyleSettings = 'setting-values-key-{{md5($settingName)}}'" class="mw-admin-action-links">
                                            <b>{{$settingName}}</b>
                                        </a>
                                    </div>

                                    <div x-show="showStyleSettings == 'setting-values-key-{{md5($settingName)}}'" x-transition:enter="tab-pane-slide-right-active">
                                        @foreach($settingFields as $settingFieldKey=>$settingField)

                                            <div wire:key="setting-field-key-{{md5($settingFieldKey)}}">
                                                @php
                                                    $settingFieldOptionGroup = $settingField['optionGroup'];
                                                @endphp

                                                @if($settingField['type'] == 'font_selector')

                                                    <div class="mt-3">
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}}
                                                        </label>
                                                        <livewire:microweber-option::font-picker label="{{$settingField['label']}}"
                                                                                                 :optionKey="$settingFieldKey"
                                                                                                 :optionGroup="$settingFieldOptionGroup"
                                                        />
                                                    </div>

                                                @elseif($settingField['type'] == 'color')
                                                    <div class="mt-3">
                                                        <livewire:microweber-option::color-picker
                                                            label="{{$settingField['label']}}"
                                                            :optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                                                    </div>
                                                @elseif($settingField['type'] == 'dropdown')
                                                    <div class="mt-4 mb-3">
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}} <br />
                                                            @if(isset($settingField['help']))
                                                                {{$settingField['help']}}
                                                            @endif
                                                        </label>
                                                        @php
                                                            $dropdownOptions = $settingField['options'];
                                                        @endphp
                                                        <livewire:microweber-option::dropdown :dropdownOptions="$dropdownOptions" optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                                                    </div>
                                                @else
                                                    <div>
                                                        <label class="live-edit-label">
                                                            {{$settingField['label']}}
                                                        </label>
                                                        <livewire:microweber-option::text :optionKey="$settingFieldKey" :optionGroup="$settingFieldOptionGroup" />
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach

        </div>
    @endif

</div>
@endsection
