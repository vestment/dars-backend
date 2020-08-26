@extends ('backend.layouts.app')
@php $currentUrl = url()->current();
@endphp
@section('title', __('labels.backend.menu-manager.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        .nav-tabs .nav-link, .nav-tabs .navbar .dropdown-toggle, .navbar .nav-tabs .dropdown-toggle {
            color: #536c79;
            background-color: #f1f1f1;
            border-bottom: 1px solid #a7b7bf;
        }

        .mb-0 > .card-header {
            display: block;
            position: relative;
            outline: none;
            text-align: left;
            border: none;
        }

        .mb-0 > .card-header span:after {
            content: "\f078"; /* fa-chevron-down */
            font-family: 'Font Awesome\ 5 Free';
            font-weight: 900;
            right: 15px;
            left: 10px
        }

        .mb-0 > button.card-header:after {
            position: absolute;
            content: "\f078"; /* fa-chevron-down */
            font-family: 'Font Awesome\ 5 Free';
            font-weight: 900;
            right: 15px;
        }

        .sub-menu {
            margin-left: 30px;
        }

        .sub-sub-menu {
            margin-left: 60px;
        }

        .mb-0 > button.card-header[aria-expanded="true"]:after, .card-header span[aria-expanded="true"]:after {
            content: "\f077"; /* fa-chevron-up */
            font-weight: 900;
        }

        div.disabled {
            pointer-events: none;
            cursor: not-allowed;
            /* for "disabled" effect */
            opacity: 0.5;
            background: #CCC;
        }

        .menu-list {
            list-style-type: none;
            padding-left: 0px;
        }

        .menu-list .card-header {
            cursor: move;
        }

        .menu-list .card-header span {
            cursor: pointer;
        }

        .action-text {
            cursor: pointer;
            color: blue;
        }

        .action-text span {
            margin-right: 20px;
            white-space: nowrap;
        }

        .error {
            border-color: red;
        }

        .card-header h6 {
            color: grey;
            font-size: 0.800rem;
            margin-left: 10px;
            display: inline-block;
        }

    </style>
    <link href="{{asset('vendor/harimayco-menu/style.css')}}" rel="stylesheet">

@endpush
@section('content')
    <div class="title my-3 mx-5">
        <h2 class="page-title mb-3">
            {{ __('labels.backend.menu-manager.title') }}

        </h2>
    </div>
    <div class="shadow-lg p-3 mb-5 bg-white rounded">

        <div class="card-body">
            <?php
            $currentUrl = url()->current();
            if (config('nav_menu') != 0) {
                $nav_menu = \Harimayco\Menu\Models\Menus::findOrFail(config('nav_menu'));
            }


            ?>
            <meta name="viewport" content="width=device-width,initial-scale=1.0">
            <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
            <link href="{{asset('vendor/harimayco-menu/style.css')}}" rel="stylesheet">
            <div id="hwpwrap">
                <div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
                    <div id="wpwrap">
                        <div id="wpcontent">
                            <div id="wpbody">
                                <div id="wpbody-content">

                                    <div class="wrap">

                                        <div class="manage-menus d-none">
                                            <form method="get" action="{{ $currentUrl }}">
                                                <label for="menu"
                                                       class="selected-menu">{{ __('strings.backend.menu_manager.select_to_edit') }}</label>


                                                <span class="submit-btn">
										<input type="submit" class="button-secondary" value="Choose">
									</span>
                                                <span class="add-new-menu-action"> or <a
                                                            href="{{ $currentUrl }}?action=edit&menu=0">{{ __('strings.backend.menu_manager.create_new') }}</a>. </span>
                                            </form>
                                        </div>
                                        <div id="nav-menus-frame" class="row">
                                            <div id="menu-settings-column" class="metabox-holder d-none col-3">

                                                <div class="clear"></div>

                                                <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post"
                                                      enctype="multipart/form-data">
                                                    <div id="side-sortables" class="accordion-container">
                                                        <ul class="outer-border">
                                                            <li class="control-section accordion-section  open add-page"
                                                                id="add-page">
                                                                <h3 class="accordion-section-title hndle"
                                                                    tabindex="0">{{ __('strings.backend.menu_manager.custom_link') }}
                                                                    <span class="screen-reader-text">{{ __('strings.backend.menu_manager.screen_reader_text') }}</span>
                                                                </h3>
                                                                <div class="accordion-section-content ">
                                                                    <div class="inside">
                                                                        <div class="customlinkdiv" id="customlinkdiv">
                                                                            <p id="menu-item-url-wrap">
                                                                                <label class="howto"
                                                                                       for="custom-menu-item-url">
                                                                                    <span>URL</span>&nbsp;&nbsp;&nbsp;
                                                                                    <input id="custom-menu-item-url"
                                                                                           name="url"
                                                                                           type="text"
                                                                                           class="code menu-item-textbox"
                                                                                           value="http://">
                                                                                </label>
                                                                            </p>

                                                                            <p id="menu-item-name-wrap">
                                                                                <label class="howto"
                                                                                       for="custom-menu-item-name">
                                                                                    <span>{{ __('strings.backend.menu_manager.label') }}</span>&nbsp;
                                                                                    <input id="custom-menu-item-name"
                                                                                           name="label" type="text"
                                                                                           class="regular-text menu-item-textbox input-with-default-title"
                                                                                           title="Label menu">
                                                                                </label>
                                                                            </p>


                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="d-inline-block w-100 action-wrapper border-top col-12 pt-2 pb-1">
                                                                    <a href="#" onclick="addcustommenu()"
                                                                       class="btn btn-light add-to-menu border float-right submit-add-to-menu right">{{ __('strings.backend.menu_manager.add_to_menu') }}</a>
                                                                    <span class="spinner" id="spincustomu"></span>

                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </form>

                                                @if(isset($pages))

                                                    <div class="accordion-container mt-4">
                                                        <ul class="outer-border">
                                                            <li class="control-section accordion-section open">
                                                                <h3 class="accordion-section-title hndle"
                                                                    data-toggle="collapse"
                                                                    data-target="#pages"
                                                                    aria-expanded="true" aria-controls="pages"
                                                                    id="headingThree"
                                                                    tabindex="0"> {{ __('strings.backend.menu_manager.pages') }}
                                                                    <span
                                                                            class="screen-reader-text">{{ __('strings.backend.menu_manager.screen_reader_text') }}</span>
                                                                </h3>

                                                                <div id="pages" class="collapse show"
                                                                     aria-labelledby="pages"
                                                                     data-parent="#accordion">
                                                                    <div class="card-body px-3 pt-3  pb-0">
                                                                        <div class="form-group">
                                                                            <input type="text"
                                                                                   placeholder="Search Pages"
                                                                                   class="form-control searchInput mb-3">
                                                                            <div class="checkbox-wrapper page">
                                                                                @if($pages->count() > 0)
                                                                                    @foreach($pages as $item)
                                                                                        <div class="checkbox"
                                                                                             data-value="{{$item->title}}">
                                                                                            {{ html()->label(html()->checkbox('category[]')->value($item->id).' &nbsp;'.$item->title)}}
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-inline-block w-100 action-wrapper border-top col-12 pt-2 pb-1">
                                                                        <div class="checkbox float-left">
                                                                            {{ html()->label(html()->checkbox()->class('select_all').' &nbsp; '. __('strings.backend.menu_manager.select_all') )->class('my-2')}}
                                                                        </div>
                                                                        <button class="btn btn-light add-to-menu border float-right">
                                                                            {{ __('strings.backend.menu_manager.add_to_menu') }}
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif


                                            </div>
                                            <div class="col-lg-9 col-12" id="menu-management-liquid">
                                                <div id="menu-management">
                                                    <form id="update-nav-menu" action="" method="post"
                                                          enctype="multipart/form-data">
                                                        <div class="menu-edit ">
                                                            <div id="nav-menu-header">
                                                                <div class="major-publishing-actions">
                                                                    <label class="menu-name-label howto open-label"
                                                                           for="menu-name">
                                                                        <span>{{ __('strings.backend.menu_manager.name') }}</span>
                                                                        <input name="menu-name" id="menu-name"
                                                                               type="text"
                                                                               class="menu-name regular-text menu-item-textbox"
                                                                               title="Enter menu name"
                                                                               value="@if(isset($indmenu)){{$indmenu->name}}@endif">
                                                                        <input type="hidden" id="idmenu"
                                                                               value="@if(isset($indmenu)){{$indmenu->id}}@endif"/>
                                                                    </label>

                                                                    @if(request()->has('action'))
                                                                        <div class="publishing-action">
                                                                            <a onclick="createnewmenu()"
                                                                               name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.create_menu') }}</a>
                                                                        </div>
                                                                    @elseif(request()->has("menu"))
                                                                        <div class="publishing-action">
                                                                            <a onclick="getmenus()" name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.save_menu') }}</a>
                                                                            <span class="spinner"
                                                                                  id="spincustomu2"></span>
                                                                        </div>

                                                                    @else
                                                                        <div class="publishing-action">
                                                                            <a onclick="createnewmenu()"
                                                                               name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.create_menu') }}</a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div id="post-body">
                                                                <div id="post-body-content">

                                                                    @if(request()->has("menu"))
                                                                        <h3>{{ __('strings.backend.menu_manager.menu_structure') }}</h3>
                                                                        <div class="drag-instructions post-body-plain"
                                                                             style="">
                                                                            <p>
                                                                                {{ __('strings.backend.menu_manager.drag_instruction_1') }}
                                                                            </p>
                                                                        </div>

                                                                    @else
                                                                        <h3>{{ __('strings.backend.menu_manager.menu_creation') }}</h3>
                                                                        <div class="drag-instructions post-body-plain"
                                                                             style="">
                                                                            <p>
                                                                                {{ __('strings.backend.menu_manager.drag_instruction_2') }}
                                                                            </p>
                                                                        </div>
                                                                    @endif

                                                                    <ul class="menu ui-sortable" id="menu-to-edit">

                                                                                <li id="menu-item-1"
                                                                                    class="menu-item menu-item-depth-0 menu-item-page menu-item-edit-inactive pending"
                                                                                    style="display: list-item;">
                                                                                    <dl class="menu-item-bar">
                                                                                        <dt class="menu-item-handle col-12 col-lg-7">
                                                                                <span class="item-title"> <span
                                                                                            class="menu-item-title"> <span
                                                                                                id="menutitletemp_1">Label Here</span> <span
                                                                                                style="color: transparent;">| 1
                                                                                            |</span> </span> <span
                                                                                            class="is-submenu"
                                                                                            style="display: none;">{{ __('strings.backend.menu_manager.sub_menu') }}</span> </span>
                                                                                            <span class="item-controls"> <span
                                                                                                        class="item-type">Type</span> <span
                                                                                                        class="item-order hide-if-js"> <a
                                                                                                            href="{{ url()->current() }}?action=move-up-menu-item&menu-item=1&_wpnonce=8b3eb7ac44"
                                                                                                            class="item-move-up"><abbr
                                                                                                                title="Move Up">↑</abbr></a> | <a
                                                                                                            href="{{ url()->current() }}?action=move-down-menu-item&menu-item=2&_wpnonce=8b3eb7ac44"
                                                                                                            class="item-move-down"><abbr
                                                                                                                title="Move Down">↓</abbr></a> </span> <a
                                                                                                        class="item-edit"
                                                                                                        id="edit-1"
                                                                                                        title=" "
                                                                                                        href="{{ url()->current() }}?edit-menu-item=1#menu-item-settings-1"> </a> </span>
                                                                                        </dt>
                                                                                    </dl>

                                                                                    <div class="menu-item-settings col-12 col-lg-7"
                                                                                         id="menu-item-settings-1">
                                                                                        <div class="row">
                                                                                            <div class="col-12">
                                                                                                <input type="hidden"
                                                                                                       class="edit-menu-item-id"
                                                                                                       name="menuid_1"
                                                                                                       value="1"/>
                                                                                                <p class="description description-thin">
                                                                                                    <label class="d-inline-block w-100"
                                                                                                           for="edit-menu-item-title-1">
                                                                                                        {{ __('strings.backend.menu_manager.label') }}
                                                                                                        <br>
                                                                                                        <input type="text"
                                                                                                               id="idlabelmenu_1"
                                                                                                               class="widefat edit-menu-item-title form-control"
                                                                                                               name="idlabelmenu_1"
                                                                                                               value="Label Here">
                                                                                                    </label>
                                                                                                </p>
                                                                                            </div>

                                                                                            <p class="field-css-url description col-12 description-wide">
                                                                                                <label for="edit-menu-item-url-1">
                                                                                                    {{ __('strings.backend.menu_manager.url') }}
                                                                                                    <br>
                                                                                                    <input type="text"
                                                                                                           id="url_menu_1"
                                                                                                           class="widefat form-control edit-menu-item-url"
                                                                                                           value="Link Here">
                                                                                                </label>
                                                                                            </p>
                                                                                        </div>


                                                                                        <p class="field-move hide-if-no-js description description-wide">
                                                                                            <label> <span>{{ __('strings.backend.menu_manager.move') }}
                                                                                        :</span> <a
                                                                                                        href="{{ url()->current() }}"
                                                                                                        class="menus-move-up"
                                                                                                        style="display: none;">{{ __('strings.backend.menu_manager.move_up') }}</a>
                                                                                                <a
                                                                                                        href="{{ url()->current() }}"
                                                                                                        class="menus-move-down"
                                                                                                        title="Mover uno abajo"
                                                                                                        style="display: inline;">{{ __('strings.backend.menu_manager.move_down') }}</a>
                                                                                                <a
                                                                                                        href="{{ url()->current() }}"
                                                                                                        class="menus-move-left"
                                                                                                        style="display: none;"></a>
                                                                                                <a href="{{ url()->current() }}"
                                                                                                   class="menus-move-right"
                                                                                                   style="display: none;"></a>
                                                                                                <a
                                                                                                        href="{{ url()->current() }}"
                                                                                                        class="menus-move-top"
                                                                                                        style="display: none;">{{ __('strings.backend.menu_manager.top') }}</a>
                                                                                            </label>
                                                                                        </p>

                                                                                        <div class="menu-item-actions description-wide submitbox text-right">

                                                                                            <a class="item-delete submitdelete deletion btn btn-danger"
                                                                                               id="delete-1"
                                                                                               href="{{ url()->current() }}?action=delete-menu-item&menu-item=1&_wpnonce=2844002501">{{ __('strings.backend.menu_manager.delete') }}</a>
                                                                                            <span class="meta-sep hide-if-no-js"> | </span>
                                                                                            <a class="item-cancel btn btn-default submitcancel hide-if-no-js button-secondary"
                                                                                               id="cancel-1"
                                                                                               href="{{ url()->current() }}?edit-menu-item=1&cancel=1424297719#menu-item-settings-1">{{ __('strings.backend.menu_manager.cancel') }}</a>
                                                                                            <span class="meta-sep hide-if-no-js"> | </span>
                                                                                            <a onclick="updateitem(1)"
                                                                                               class="btn btn-primary updatemenu"
                                                                                               id="update-1"
                                                                                               href="javascript:void(0)">{{ __('strings.backend.menu_manager.update_item') }}</a>
                                                                                        </div>
                                                                                    </div>

                                                                                    <ul class="menu-item-transport"></ul>
                                                                                </li>

                                                                    </ul>

                                                                </div>
                                                            </div>
                                                            <div id="nav-menu-footer">
                                                                <div class="major-publishing-actions">
                                                                    @if(request()->has('action'))
                                                                        <div class="publishing-action">
                                                                            <a onclick="createnewmenu()"
                                                                               name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.create_menu') }}</a>
                                                                        </div>
                                                                    @elseif(request()->has("menu"))
                                                                        <span class="delete-action"> <a
                                                                                    class="submitdelete deletion btn btn-danger menu-delete"
                                                                                    onclick="deletemenu()"
                                                                                    href="javascript:void(9)">{{ __('strings.backend.menu_manager.delete_menu') }}</a> </span>
                                                                        <div class="publishing-action">

                                                                            <a onclick="getmenus()" name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.save_menu') }}</a>
                                                                            <span class="spinner"
                                                                                  id="spincustomu2"></span>
                                                                        </div>

                                                                    @else
                                                                        <div class="publishing-action">
                                                                            <a onclick="createnewmenu()"
                                                                               name="save_menu"
                                                                               id="save_menu_header"
                                                                               class="btn btn-primary menu-save">{{ __('strings.backend.menu_manager.create_menu') }}</a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-scripts')
    {!! Menu::scripts() !!}
    <script src="{{url('/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
    <script type="text/javascript">
        $('#menu_icon').iconpicker({});

        $(document).ready(function () {
            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var tableFields = $('.table-fields'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(tableFields);

                newEntry.find('input').val('');
                tableFields.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="fa fa-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });

        });
    </script>
@endpush
