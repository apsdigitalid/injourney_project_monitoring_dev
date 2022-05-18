<style>
    .card-img {
        width: 120px;
        height: 120px;
    }

    .card-img img {
        width: 120px;
        height: 120px;
        object-fit: cover;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    th
    {
        background-color:black;
        color:white;
    }
    th:first-child, td:first-child
    {
        position:sticky;
        left:0px;
    }
    td:first-child
    {
        background-color:white;
    }

</style>

@php
$editEmployeePermission = user()->permission('edit_employees');
@endphp
<div class="px-1 py-0 py-lg-2  border-top-0 ">
    <div class="row mt-3">
        <div class="col-xl-12 col-lg-6 col-md-6 mb-3">
            <div class="card bg-white border-0 b-shadow-4  mt-4">
                <div class="card-header bg-white border-0 text-capitalize d-flex justify-content-between p-20">
                    <h4 class="f-18 f-w-500 mb-0">Resource Performance</h4>
                </div>
                
                <div class="card-body">
                    <div class="card-body" style="overflow-x:auto; padding: 0px;">
                        <table class="table table-striped" style="background:#FFF;">
                            <tbody>
                                <tr style="background:#FFF;">
                                    <td style="min-width:200px;max-width:200px; background:#FFF;"><h5 class="mb-0 f-14 text-darkest-grey">Projetcs</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td style="min-width:220px;"><h5 class="mb-0 f-14 text-darkest-grey">{{ $project[$i]['project_name'] }}</h5></td>
                                    @endfor
                                </tr>
                                <tr style="background:#FFF;">
                                    <td style="min-width:200px; background:#FFF;"><h5 class="mb-0 f-14 text-darkest-grey">Projetc Time</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td>
                                            <p>Start : {{ \Carbon\Carbon::createFromFormat('Y-m-d',$project[$i]['start_date'])->format('d-M-Y') }}</p>    
                                            <p>End : {{ \Carbon\Carbon::createFromFormat('Y-m-d',$project[$i]['deadline'])->format('d-M-Y') }}</p>
                                        </td>
                                    @endfor
                                </tr>
                                <tr style="background:#FFF;">
                                    <td style="min-width:200px; background:#FFF;"><h5 class="mb-0 f-14 text-darkest-grey">Projetc Status</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td><p>{{ strtoupper($project[$i]['status']) }}</p></td>
                                    @endfor
                                </tr>
                                <!-- <tr>
                                    <td><h5 class="mb-0 f-14 text-darkest-grey">Role</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td><p>{{ $project[$i]['role'] ?? '-' }}</p></td>
                                    @endfor
                                </tr> -->
                                <tr style="background:#FFF;">
                                    <td style="min-width:200px; background:#FFF;"><h5 class="mb-0 f-14 text-darkest-grey">Task Completion</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td>
                                            <canvas id="chart_{{$i}}" style="width:100%; max-width:170px;"></canvas>
                                        </td>
                                    @endfor
                                </tr>
                                <!-- <tr>
                                    <td><h5 class="mb-0 f-14 text-darkest-grey">Team Performance Review</h5></td>
                                    @for ($i = 0; $i < count($project); $i++)
                                        <td><p>{{ $project[$i]['role'] ?? '-' }}</p></td>
                                    @endfor
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>