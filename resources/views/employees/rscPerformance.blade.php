@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush


@php
$viewEmployeeTasks = user()->permission('view_employee_tasks');
$viewEmployeeProjects = user()->permission('view_employee_projects');
$viewEmployeeTimelogs = user()->permission('view_employee_timelogs');
$manageRolePermissionSetting = user()->permission('manage_role_permission_setting');
@endphp


@section('filter-section')

@endsection

@push('styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@endpush

@section('content')

    <div class="content-wrapper pt-0 border-top-0 client-detail-wrapper">
        @include($view)
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        var data_chart = "{{ ($project1) }}";
        const data_real = JSON.parse(data_chart.replaceAll("&quot;", '"'));
        console.log(data_real)
        for(let [i, v] of data_real.entries()){
                if(v.data.length > 0){

                    var ctx = document.getElementById("chart_"+i).getContext('2d');
                    var xValues = ["Complete", "Incomplete"];
                    var yValues = [v.data[1] ? v.data[1].num : 0, v.data[0] ? v.data[0].num : 0];
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
                // else{
                //     html = `<div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                //         <div class="bg-white p-20 rounded b-shadow-4">
                //             <div class="row center-text align-items-center" style="justify-content: center;">
                //                 <h6 class="text-darkest-grey">${v.project}</h6>
                //             </div>
                //             <div class="row mt-2">
                //                 <div class="col-lg-12 text-center text-lightest" style="padding:37px; width: 268px; max-width: 600px; display: block; height: 133px;">
                //                     <i class="side-icon f-21 bi bi-pie-chart"></i>
                //                     <div class="f-15 mt-4">
                //                         - Not enough data -
                //                     </div>
                //                 </div>
                //             </div>
                //         </div>
                //     </div>`;
                //     $(`#project_chart_${index_carousel}`).append(html);
                // }
            }
        
        $("body").on("click", ".project-menu .ajax-tab", function(event) {
            event.preventDefault();

            $('.project-menu .p-sub-menu').removeClass('active');
            $(this).addClass('active');

            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: ".content-wrapper",
                historyPush: true,
                blockUI: true,
                success: function(response) {
                    if (response.status == "success") {
                        $('.content-wrapper').html(response.html);
                        init('.content-wrapper');
                    }
                }
            });
        });

        
    </script>
    <script>
        const activeTab = "{{ $activeTab }}";
        $('.project-menu .' + activeTab).addClass('active');
    </script>
@endpush
