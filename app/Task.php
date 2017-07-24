<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    public $children = [];

    public static function getOfferSubtaskTree($offerId)
    {
        $tree = [];
        
        $firstLevelTasks = Task::where('offer_id', '=', $offerId)
                ->where('wrike_has_parent_tasks', '=', 0)
                ->orderBy('title')
                ->get();
        
        foreach($firstLevelTasks as $firstLevelTask) {
            $tree[] = $firstLevelTask;
            $firstLevelTask->children = self::getTaskSubtaskTree($firstLevelTask);
        }
       
        usort($tree, function ($a, $b) {
            return strnatcmp($a->title, $b->title);
        });
        
        return $tree;
    }
    
    private static function getTaskSubtaskTree(Task $task)
    {
        $children = self::children($task->wrike_task_id_v3);
        foreach($children as $child) {
            $child->children = self::getTaskSubtaskTree($child);
        }
        return $children;
    }
    
    private static function children($wrikeTaskv3Id)
    {
        $children = Task::join(
            DB::raw(' (SELECT tt.* FROM tasks INNER JOIN task_task AS tt ON tt.parent_wrike_task_id_v3 = tasks.wrike_task_id_v3 WHERE tasks.wrike_task_id_v3 = "' . $wrikeTaskv3Id . '") AS tt '),
            'tasks.wrike_task_id_v3', '=', 'tt.child_wrike_task_id_v3'
        )->orderBy('title')->get();
        $t=$children;
        
        return $children;
    }
    
    public function getEffort()
    {
        if($this->effort_design && $this->effort_tech) {
            $timestamp = strtotime($this->effort_design) + strtotime($this->effort_tech);
            $effort = date('H:i:s', $timestamp);
            return preg_replace('%:[0-9][0-9]$%', '', $effort);
        }
        if($this->effort_design) {
            return preg_replace('%:[0-9][0-9]$%', '', $this->effort_design);
        }
        if($this->effort_tech) {
            return preg_replace('%:[0-9][0-9]$%', '', $this->effort_tech);
        }
        return '00:00';
    }
    
    public function getEffortDesign()
    {
        return $this->effort_design ? preg_replace('%:[0-9][0-9]$%', '', $this->effort_design) : '00:00';
    }
    
    public function getEffortTech()
    {
        return $this->effort_tech ? preg_replace('%:[0-9][0-9]$%', '', $this->effort_tech) : '00:00';
    }
    
    public function getPrice()
    {
        $array = explode(':', $this->getEffort());
        $hours = $array[0] + $array[1] / 60;
        $price = $hours * $this->getRph();
        return number_format($price, 2);
    }
    
    private function getRph()
    {
        $offer = Offer::find($this->offer_id);
        return $offer->rph;
    }
}
