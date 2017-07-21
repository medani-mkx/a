<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    public static function getTaskTreeForOffer($offerId)
    {
        $firstLevelTasks = Task::where('offer_id', '=', $offerId)
                ->where('wrike_has_parent_tasks', '=', 0)
                ->get();
        
        $tree = [];
        
        $i = 0;
        foreach($firstLevelTasks as $firstLevelTask) {
            $tree[$i]['task'] = $firstLevelTask;
            if($firstLevelTask->wrike_has_child_tasks) {
                $tree[$i]['children'] = self::getTaskTree($firstLevelTask);
            }
            $i++;
        }
        
        return $tree;
    }
    
    private static function getTaskTree($task)
    {
        static $tree = [];
        $children = self::children($task->wrike_task_id_v3);
        
        $i = 0;
        foreach($children as $firstLevelTask) {
            $tree[$i]['task'] = $firstLevelTask;
            if($firstLevelTask->wrike_has_child_tasks) {
                $tree[$i]['children'] = self::getTaskTree($firstLevelTask);
            } else {
                unset($tree[$i]['children']);
            }
            $i++;
        }
        
        return $tree;
    }
    
    private static function children($wrikeTaskv3Id)
    {
        $children = Task::join(
            DB::raw(' (SELECT tt.* FROM tasks INNER JOIN task_task AS tt ON tt.parent_wrike_task_id_v3 = tasks.wrike_task_id_v3 WHERE tasks.wrike_task_id_v3 = "' . $wrikeTaskv3Id . '") AS tt '),
            'tasks.wrike_task_id_v3', '=', 'tt.child_wrike_task_id_v3'
        )->get();
        return $children;
    }
}
