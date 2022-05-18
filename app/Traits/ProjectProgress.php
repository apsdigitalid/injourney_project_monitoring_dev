<?php

/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 13/07/17
 * Time: 4:53 PM
 */

namespace App\Traits;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskboardColumn;

trait ProjectProgress
{

    public function calculateProjectProgress($projectId, $weight)
    {

        $project = Project::findOrFail($projectId);

        if (!is_null($project) && $project->calculate_task_progress == 'true') {
            $taskBoardColumn = TaskboardColumn::completeColumn();

            if (is_null($projectId)) {
                return false;
            }

            $totalTasks = Task::where('project_id', $projectId)->count();

            if ($totalTasks == 0) {
                return '0';
            }

            if($weight == null){
                $completedTasks = Task::where('project_id', $projectId)
                    ->where('tasks.board_column_id', $taskBoardColumn->id)
                    ->count();
                $percentComplete = ($completedTasks / $totalTasks) * 100;
            }
            else{
                $percentComplete = $project->completion_percent + $weight;
            }
            $project->completion_percent = $percentComplete;
            $project->status = $percentComplete == 100 ? 'finished' : 'in progress';

            $project->save();

            return $percentComplete;
        }
    }

}
