<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\WrikeApiFacade as Wrike;
use App\Offer;
use App\Task;
use App\TaskTask;

class TasksController extends Controller
{
    /* Wrike Custom Field IDs */
    private const AUFWAND_GESAMT = 'IEABIK4UJUAAFUWB';
    private const AUFWAND_DESIGN_UND_UX = 'IEABIK4UJUAAFKGZ';
    private const AUFWAND_TECHNIK = 'IEABIK4UJUAAFKGS';
    
    private function getCustomFieldFromWrikeTask($wrikeTask, $searchedFieldId)
    {
        foreach($wrikeTask->customFields as $customField) {
            if($customField->id == $searchedFieldId) {
                if($customField->value == '0h' || $customField->value == '') {
                    return 0;
                }
                if(preg_match('%^[0-9:]$%', $customField->value)) {
                    return $customField->value;
                }
            }
        }
        return null;
    }
    private function taskExists($wrikeTask)
    {
        static $id = null;
        static $task = null;
        if($wrikeTask->id == $id) {
            return $task;
        }
        else {
            $task = Task::where('wrike_task_id_v3', $wrikeTask->id)->first();
            $id = $wrikeTask->id;
            if($task) {
                return $task;
            }
            else {
                return false;
            }
        }
    }
    private function createTaskFromWrikeTask($offerId, $wrikeTask)
    {
        if($this->taskExists($wrikeTask)) {
            $task = $this->taskExists($wrikeTask); 
        }
        else {
            $task = new Task();
            $task->wrike_task_id_v3 = $wrikeTask->id;
            $task->offer_id = $offerId;
        }
        $task->wrike_has_parent_tasks = !empty($wrikeTask->superTaskIds);
        $task->wrike_has_child_tasks = !empty($wrikeTask->subTaskIds);
        if($task->wrike_has_child_tasks) {
            foreach($wrikeTask->subTaskIds as $childTaskV3Id) {
                $taskTask = new TaskTask();
                $taskTask->parent_wrike_task_id_v3 = $wrikeTask->id;
                $taskTask->child_wrike_task_id_v3 = $childTaskV3Id;
                $taskTask->save();
            }
        }
        $task->wrike_title = $wrikeTask->title;
        $task->wrike_description = $wrikeTask->description;
        $task->wrike_effort = $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_GESAMT);
        $task->wrike_effort_design = $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_DESIGN_UND_UX);
        $task->wrike_effort_tech = $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_TECHNIK);
        $task->wrike_optional = preg_match('%\[optional\]%', $wrikeTask->title);
        if( ! $this->taskExists($wrikeTask)) {
            $task->title = $task->wrike_title;
            $task->description = $task->wrike_description;
            $task->effort = $task->wrike_effort;
            $task->effort_design = $task->wrike_effort_design;
            $task->effort_tech = $task->wrike_effort_tech;
            $task->optional = $task->wrike_optional;
            $task->visible = true;
            $task->included_in_the_price = true;
        }
        $task->save();
    }
    
    public function import(Request $request, $id)
    {
        $offer = Offer::find($id)->first();
        
        $wrikeProjectId = $offer->wrike_project_id_v3;
        
        $wrikeTasks = Wrike::getProjectTasks($wrikeProjectId);
        
        foreach($wrikeTasks as $wrikeTask) {
            $this->createTaskFromWrikeTask($id, $wrikeTask);
        }
        
        return redirect('offers/' . $id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
