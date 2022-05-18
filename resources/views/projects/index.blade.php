@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
    <style>
        
        /* equal card height */
        .row-equal > div[class*='col-'] {
            display: flex;
            flex: 1 0 auto;
        }

        .row-equal .card {
        width: 100%;
        }

        /* ensure equal card height inside carousel */
        .carousel-inner>.row-equal.active, 
        .carousel-inner>.row-equal.next, 
        .carousel-inner>.row-equal.prev {
            display: flex;
        }

        /* prevent flicker during transition */
        .carousel-inner>.row-equal.active.left, 
        .carousel-inner>.row-equal.active.right {
            opacity: 0.5;
            display: flex;
        }


        /* control image height */
        .card-img-top-250 {
            max-height: 250px;
            overflow:hidden;
        }
        
        
    </style>
@endpush

@section('filter-section')
    
    <x-filters.filter-box>
        <div
            class="select-box {{ !in_array('client', user_roles()) ? 'd-flex' : 'd-none' }} py-2  pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.clientName')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="client_id" id="client_id" data-live-search="true"
                    data-size="8">
                    @if (!in_array('client', user_roles()))
                        <option value="all">@lang('app.all')</option>
                    @endif
                    @foreach ($clients as $client)
                        <option
                            data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle' src='{{ $client->image_url }}' ></div> {{ ucfirst($client->name) }}"
                            value="{{ $client->id }}">{{ ucfirst($client->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div
            class="select-box d-flex py-2 {{ !in_array('client', user_roles()) ? 'px-lg-2 px-md-2 px-0' : '' }}  border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.status')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="status" id="status" data-live-search="true" data-size="8">
                    <option value="not finished">@lang('modules.projects.hideFinishedProjects')</option>
                    <option {{ request('status') == 'all' ? 'selected' : '' }} value="all">@lang('app.all')</option>
                    <option {{ request('status') == 'overdue' ? 'selected' : '' }} value="overdue">@lang('app.overdue')
                    </option>
                    <option value="not started">@lang('app.notStarted')</option>
                    <option {{ request('status') == 'in progress' ? 'selected' : '' }} value="in progress">@lang('app.inProgress')</option>
                    <option value="on hold">@lang('app.onHold')</option>
                    <option value="canceled">@lang('app.canceled')</option>
                    <option value="finished">@lang('app.finished')</option>
                </select>
            </div>
        </div>
        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                        placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

        <!-- MORE FILTERS START -->
        <x-filters.more-filter-box>
            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize"
                    for="usr">@lang('modules.projects.projectCategory')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="category_id" id="category_id"
                            data-live-search="true" data-container="body" data-size="8">
                            <option selected value="all">@lang('app.all')</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            @if (!in_array('client', user_roles()))
                <div class="more-filter-items">
                    <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.projectMember')</label>
                    <div class="select-filter mb-4">
                        <div class="select-others">
                            <select class="form-control select-picker" name="employee_id" id="employee_id"
                                data-live-search="true" data-container="body" data-size="8">
                                <option value="all">@lang('app.all')</option>
                                @foreach ($allEmployees as $employee)
                                    <option
                                        @if (request('assignee') == 'me' && $employee->id == user()->id)
                                            selected
                                        @endif
                                        data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle' src='{{ $employee->image_url }}' ></div> {{ ucfirst($employee->name) }}"
                                        value="{{ $employee->id }}">{{ ucfirst($employee->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.department')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="team_id" id="team_id" data-live-search="true"
                            data-container="body" data-size="8">
                            <option value="all">@lang('app.all')</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="more-filter-items">
                <label class="f-14 text-dark-grey mb-12 text-capitalize" for="usr">@lang('app.pinned')</label>
                <div class="select-filter mb-4">
                    <div class="select-others">
                        <select class="form-control select-picker" name="pinned" id="pinned" data-container="body"
                            data-size="8">
                            <option value="all">@lang('app.all')</option>
                            <option value="pinned">@lang('app.pinned')</option>
                        </select>
                    </div>
                </div>
            </div>
        </x-filters.more-filter-box>
        <!-- MORE FILTERS END -->

    </x-filters.filter-box>

@endsection

@php
$addProjectPermission = user()->permission('add_projects');
$manageProjectTemplatePermission = user()->permission('manage_project_template');
@endphp

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar">
            <div id="table-actions" class="flex-grow-1 align-items-center mb-2 mb-lg-0 mb-md-0">
                @if ($addProjectPermission == 'all' || $addProjectPermission == 'added' || $addProjectPermission == 'both')
                    <x-forms.link-primary :link="route('projects.create')"
                        class="mr-3 openRightModal float-left mb-2 mb-lg-0 mb-md-0" icon="plus">
                        @lang('app.add')
                        @lang('app.project')
                    </x-forms.link-primary>
                @endif
                @if ($manageProjectTemplatePermission == 'all' || $manageProjectTemplatePermission == 'added')
                    <x-forms.link-secondary :link="route('project-template.index')"
                        class="mr-3 mb-2 mb-lg-0 mb-md-0 float-left" icon="layer-group">
                        @lang('app.menu.projectTemplate')
                    </x-forms.link-secondary>
                @endif

            </div>

            @if (!in_array('client', user_roles()))
                <x-datatable.actions>
                    <div class="select-status mr-3 pl-3">
                        <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                            <option value="">@lang('app.selectAction')</option>
                            <option value="change-status">@lang('modules.tasks.changeStatus')</option>
                            <option value="archive">@lang('app.archive')</option>
                            <option value="delete">@lang('app.delete')</option>
                        </select>
                    </div>
                    <div class="select-status mr-3 d-none quick-action-field" id="change-status-action">
                        <select name="status" class="form-control select-picker">
                            <option value="not started">@lang('app.notStarted')</option>
                            <option value="in progress">@lang('app.inProgress')</option>
                            <option value="on hold">@lang('app.onHold')</option>
                            <option value="canceled">@lang('app.canceled')</option>
                            <option value="finished">@lang('app.finished')</option>
                        </select>
                    </div>
                </x-datatable.actions>
            @endif


            <div class="btn-group ml-0 ml-lg-3 ml-md-3" role="group">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary f-14 btn-active" data-toggle="tooltip"
                    data-original-title="@lang('app.menu.projects')"><i class="side-icon bi bi-list-ul"></i></a>

                <a href="{{ route('projects.archive') }}" class="btn btn-secondary f-14" data-toggle="tooltip"
                    data-original-title="@lang('app.archive')"><i class="side-icon bi bi-archive"></i></a>

                <a href="javascript:;" class="btn btn-secondary f-14 show-pinned" data-toggle="tooltip"
                    data-original-title="@lang('app.pinned')"><i class="side-icon bi bi-pin-angle"></i></a>
            </div>
        </div>
        <!-- Add Task Export Buttons End -->

        <!-- Chart Project -->
        <section class="carousel slide"  data-interval="false" data-ride="carousel" id="postsCarousel">
            <div class="row" id="arrow_wrapper" style="position: absolute;width: 100%;z-index: 1;height: 100%;opacity:0;">
                <div class="col-lg-12 lead" style="margin:auto; padding-right:0px;">
                    <a class="btn btn-light prev" href="" title="go back"><i class="fa fa-lg fa-chevron-left"></i></a>
                    <a class="btn btn-light next" href="" title="more" style="float: right;margin-right: -20px;"><i class="fa fa-lg fa-chevron-right"></i></a>
                </div>
            </div>
            <div id="carouselExampleIndicators2" class="carousel-inner" style="max-height: 240px;">
                <div class="row mt-3 row-equal carousel-item active" id="project_chart"></div>
            </div>
        </section>
        
        
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
            <div class="row mt-3">
                <div class="col-xl-12 col-lg-6 col-md-6 mb-3">
                    <div class="bg-white p-20 rounded b-shadow-4">
                        <canvas id="bar_total" style="width:100%;"></canvas>
                    </div>
                </div>
            </div>
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')
    <script src="{{ asset('js/Chart.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            // manual carousel controls
            $('.next').click(function(){ $('.carousel').carousel('next');return false; });
            $('.prev').click(function(){ $('.carousel').carousel('prev');return false; });
            
        })(jQuery);
        var deadLineStartDate = '';
        var deadLineEndDate = '';
       
        
        var temp_project_id = [];
        var table = $('#projects-table').DataTable();
            table.on( 'draw', function () {
                temp_project_id = []
                const num_rows = $('#projects-table tbody tr').length;
                if(num_rows > 1){$('#arrow_wrapper').css({'opacity':'1'})}
                console.log(num_rows)
                for(let i = 0; i < num_rows; i++){
                    temp_project_id.push($('#projects-table tbody').find('tr').eq(i).find('td:eq(1)').text())
                }
                get_information_project_status(temp_project_id);
                get_information_project_task_total(temp_project_id);
            });

       

        $('#projects-table').on('preXhr.dt', function(e, settings, data) {

            var status = $('#status').val();
            var clientID = $('#client_id').val();
            var categoryID = $('#category_id').val();
            var teamID = $('#team_id').val();
            var employee_id = $('#employee_id').val();
            var pinned = $('#pinned').val();
            var searchText = $('#search-text-field').val();

            @if (request('deadLineStartDate') && request('deadLineEndDate'))
                deadLineStartDate = '{{ request("deadLineStartDate") }}';
                deadLineEndDate = '{{ request("deadLineEndDate") }}'
            @endif

            data['status'] = status;
            data['client_id'] = clientID;
            data['pinned'] = pinned;
            data['category_id'] = categoryID;
            data['team_id'] = teamID;
            data['employee_id'] = employee_id;
            data['deadLineStartDate'] = deadLineStartDate;
            data['deadLineEndDate'] = deadLineEndDate;
            data['searchText'] = searchText;
            @if (!is_null(request('start')) && !is_null(request('end')))
                data['startDate'] = '{{ request('start') }}';
                data['endDate'] = '{{ request('end') }}';
            @endif
        });

        const showTable = () => {
            window.LaravelDataTables["projects-table"].draw();
        }



        $('#client_id, #status, #search-text-field, #employee_id, #team_id, #category_id, #pinned').on('change keyup',
            function() {
                if ($('#status').val() != "not finished") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#employee_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#team_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#category_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#client_id').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#pinned').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#search-text-field').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else {
                    $('#reset-filters').addClass('d-none');
                    showTable();
                }
            });

        $('.show-pinned').click(function() {
            if ($(this).hasClass('btn-active')) {
                $('#pinned').val('all');
            } else {
                $('#pinned').val('pinned');
            }

            $('#pinned').selectpicker('refresh');
            $(this).toggleClass('btn-active')
            $('#reset-filters').removeClass('d-none');
            showTable();
        });

        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();
            $('.filter-box #status').val('not finished');
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#reset-filters-2').click(function() {
            $('#filter-form')[0].reset();
            $('.filter-box #status').val('not finished');
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        $('#quick-action-type').change(function() {
            const actionValue = $(this).val();
            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');

                if (actionValue == 'change-status') {
                    $('.quick-action-field').addClass('d-none');
                    $('#change-status-action').removeClass('d-none');
                } else {
                    $('.quick-action-field').addClass('d-none');
                }
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        });

        $('#quick-action-apply').click(function() {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'delete') {
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.recoverRecord')",
                    icon: 'warning',
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "@lang('messages.confirmDelete')",
                    cancelButtonText: "@lang('app.cancel')",
                    customClass: {
                        confirmButton: 'btn btn-primary mr-3',
                        cancelButton: 'btn btn-secondary'
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        applyQuickAction();
                    }
                });

            } else {
                applyQuickAction();
            }
        });

        $('body').on('click', '.delete-table-row', function() {
            var id = $(this).data('user-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('projects.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                showTable();
                            }
                        }
                    });
                }
            });
        });

        $('body').on('click', '.archive', function() {
            var id = $(this).data('user-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.archiveMessage')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmArchive')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('projects.archive_delete', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': token,
                        },
                        success: function(response) {
                            if (response.status == "success") {
                                window.LaravelDataTables["projects-table"].draw();
                            }
                        }
                    });
                }
            });
        });

        const applyQuickAction = () => {
            var rowdIds = $("#projects-table input:checkbox:checked").map(function() {
                return $(this).val();
            }).get();

            var url = "{{ route('projects.apply_quick_action') }}?row_ids=" + rowdIds;

            $.easyAjax({
                url: url,
                container: '#quick-action-form',
                type: "POST",
                disableButton: true,
                buttonSelector: "#quick-action-apply",
                data: $('#quick-action-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        showTable();
                        resetActionButtons();
                        deSelectAll();
                        $('#quick-action-apply').attr('disabled', 'disabled');
                        $('#change-status-action').addClass('d-none');
                    }
                }
            })
        };

        const get_information_project_status = async (project_id) => {
            var url = "{{ route('projects.get_project_information') }}";
            var token = "{{ csrf_token() }}";
            var html='';
            await $.easyAjax({
                url: url,
                type: "POST",
                dataType: 'json',
                contentType: 'application/json',
                data : {'_token' : token, id :project_id.join(',')},
                beforeSend: function(){
                    $('#carouselExampleIndicators2').html(`<div class="col-lg-12 text-center ">
                                                    <div class="spinner-border text-secondary" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>`);
                },
                success: async function(response) {
                    console.log(response);
                    if(response.data.length > 0){
                        $('#carouselExampleIndicators2').html('');
                        let index_carousel = 0;
                        for(let [i, v] of response.data.entries()){
                            if(i == 0 || i % 4 == 0){
                                 $('#carouselExampleIndicators2').append(`<div class="mt-3 row-equal carousel-item ${i == 0  ? 'active' : ''}" id="project_chart_${i}"></div>`);
                                 index_carousel = i;
                            }
                            if(v.status !== null){
                                 html = `<div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                                    <div class="bg-white p-20 rounded b-shadow-4">
                                        <div class="row center-text align-items-center" style="justify-content: center;">
                                            <h6 class="text-darkest-grey">${v.project}</h6>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-12 text-center text-lightest" style="width: 268px; max-width: 600px; display: block; height: 133px;">
                                                <canvas id="pieChart${i}" style="width:100%;max-width:600px;position:relative;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                $(`#project_chart_${index_carousel}`).append(html);

                                var ctx = document.getElementById("pieChart"+i).getContext('2d');
                                var xValues = ["Complete", "Incomplete"];
                                var yValues = [v.status.complete, v.status.incomplete];
                                var barColors = [
                                    "#679c0d",
                                    "#d21010"
                                ];
                                new Chart(ctx, {
                                    type: "pie",
                                    data: {
                                        labels: xValues,
                                        datasets: [{
                                        backgroundColor: barColors,
                                        data: yValues
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                boxWidth: 10,
                                                padding: 5
                                            }
                                        }
                                    }
                                });
                            }
                            else{
                                html = `<div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                                    <div class="bg-white p-20 rounded b-shadow-4">
                                        <div class="row center-text align-items-center" style="justify-content: center;">
                                            <h6 class="text-darkest-grey">${v.project}</h6>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-12 text-center text-lightest" style="padding:37px; width: 268px; max-width: 600px; display: block; height: 133px;">
                                                <i class="side-icon f-21 bi bi-pie-chart"></i>
                                                <div class="f-15 mt-4">
                                                    - Not enough data -
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                $(`#project_chart_${index_carousel}`).append(html);
                            }
                        }
                        $('.next').click(function(){ $('.carousel').carousel('next');console.log('next'); return false; });
                        $('.prev').click(function(){ $('.carousel').carousel('prev');return false; });
                    }
                    else{
                        $('#carouselExampleIndicators2').html('');
                    }
                }
            })
        }

        const get_information_project_task_total = async (project_id) => {
            var url = "{{ route('projects.get_information_project_task_total') }}";
            var token = "{{ csrf_token() }}";
            var html='';
            await $.easyAjax({
                url: url,
                type: "POST",
                dataType: 'json',
                contentType: 'application/json',
                data : {'_token' : token, id :project_id.join(',')},
                beforeSend: function(){
                    
                },
                success: async function(response) {
                    var xBar = [];
                    var yBar = [];
                    var bColor=[];
                    var border=[];
                    response.data.map((v, k) => {
                        xBar.push(v.project_name);
                        yBar.push(v.count_task);
                        let color = `${Math.floor(Math.random() * 254)}, ${Math.floor(Math.random() * 125)}, ${Math.floor(Math.random() * 255)}`;
                        bColor.push(`rgba(${color}, 0.5)`);
                        border.push(`rgba(${color}, 1)`);
                    })

                    setTimeout(() => {
                        var ctx = document.getElementById("bar_total").getContext('2d');
                        new Chart(ctx, {
                            type: "bar",
                            data: {
                                labels: xBar,
                                datasets: [{
                                    data: yBar,
                                    backgroundColor: bColor,
                                    borderColor: border,
                                    borderWidth: .5
                                }]
                            },
                            options: {
                                legend: {display: false},
                                title: {
                                    display: true,
                                    text: "Total"
                                },
                                scales: {
                                    yAxes: [{
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Number of Task',
                                        },
                                        ticks: { precision: 0 }
                                    }],
                                    xAxes: [{
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Project Name'
                                        }
                                    }]
                                }
                            }
                        });
                    }, 1000);
                }
            })
        }
    </script>
@endpush
