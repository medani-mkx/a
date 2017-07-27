<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\WrikeApiFacade as Wrike;
use App\Offer;
use App\Task;
use App\TaskTask;
use Illuminate\Support\Facades\App;
use App\WrikeApi;

class TasksController extends Controller
{
    /* Wrike Custom Field IDs */
    const AUFWAND_GESAMT = 'IEABIK4UJUAAFUWB';
    const AUFWAND_DESIGN_UND_UX = 'IEABIK4UJUAAFKGZ';
    const AUFWAND_TECHNIK = 'IEABIK4UJUAAFKGS';

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
        if($task = Task::find($id)) {

            $task->title = $request->title;
            $task->description = $request->description;
            $task->effort = $request->effort;
            $task->effort_design = $request->effort_design;
            $task->effort_tech = $request->effort_tech;
            $task->special_rph = $request->special_rph;

            $task->save();

            return 1;
        }
        else {
            return response()->json(['Fehler' => 'Task nicht gefunden']);
        }
        return 1;
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

    ################################################## Import #####################################################
    public function import(Request $request, $id)
    {
        $offerId = $id;

        $offer = Offer::find($offerId);

        $wrikeProjectId = $offer->wrike_project_id_v3;

        $wrikeTasks = $offer->wrike_project_id_v3 = App::make(WrikeApi::class)->getProjectTasks($wrikeProjectId);

        foreach($wrikeTasks as $wrikeTask) {
            $this->createTaskFromWrikeTask($offerId, $wrikeTask);
        }

        return redirect('offers/' . $offerId);
    }

    private function getCustomFieldFromWrikeTask($wrikeTask, $searchedFieldId)
    {
        foreach($wrikeTask['customFields'] as $customField) {
            if($customField['id'] == $searchedFieldId) {
                if($customField['value'] == '0h' || $customField['value'] == '') {
                    return null;
                }
                if(preg_match('%^[0-9:]+$%', $customField['value'])) {
                    return $customField['value'];
                }
                else {
                    return null;
                }
            }
        }
        return null;
    }
    private function taskExistsInDb($wrikeTask)
    {
        static $id = null;
        static $task = null;
        if($wrikeTask['id'] == $id) {
            return $task;
        }
        else {
            $task = Task::where('wrike_task_id_v3', $wrikeTask['id'])->first();
            $id = $wrikeTask['id'];
            if($task) {
                return $task;
            }
            else {
                return false;
            }
        }
    }
    private function taskToTaskRelationExistsInDb($parentId, $childId) {
        $taskToTaskRelation = TaskTask::where('parent_wrike_task_id_v3', $parentId)->where('child_wrike_task_id_v3', $childId)->first();
        return $taskToTaskRelation;
    }

    private function createTaskFromWrikeTask($offerId, $wrikeTask)
    {
        // Get task object
        if($this->taskExistsInDb($wrikeTask)) {
            $task = $this->taskExistsInDb($wrikeTask);
        }
        else {
            $task = new Task();
            $task->wrike_task_id_v3 = $wrikeTask['id'];
            $task->offer_id = $offerId;
        }

        // Set relations to other tasks
        $task->wrike_has_parent_tasks = !empty($wrikeTask['superTaskIds']);
        $task->wrike_has_child_tasks = !empty($wrikeTask['subTaskIds']);
        if($task->wrike_has_child_tasks) {
            foreach($wrikeTask['subTaskIds'] as $childTaskV3Id) {
                if( !$this->taskToTaskRelationExistsInDb($wrikeTask['id'], $childTaskV3Id) ) {
                    $taskTask = new TaskTask();
                    $taskTask->parent_wrike_task_id_v3 = $wrikeTask['id'];
                    $taskTask->child_wrike_task_id_v3 = $childTaskV3Id;
                    $taskTask->save();
                }
            }
        }

        // Set Wrike-fields
        $task->wrike_title =            $wrikeTask['title'];
        $task->wrike_description =      $wrikeTask['description'];
        $task->wrike_effort =           $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_GESAMT);
        $task->wrike_effort_design =    $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_DESIGN_UND_UX);
        $task->wrike_effort_tech =      $this->getCustomFieldFromWrikeTask($wrikeTask, self::AUFWAND_TECHNIK);
        $task->wrike_optional =         preg_match('%\[optional\]%', $wrikeTask['title']);

        // Set angebote-fields
        if( ! $this->taskExistsInDb($wrikeTask)) {
            $task->title =                  $task->wrike_title;
            $task->description =            $task->wrike_description;
            $task->effort =                 $task->wrike_effort ? $task->wrike_effort : $task->getEffort();
            $task->effort_design =          $task->wrike_effort_design;
            $task->effort_tech =            $task->wrike_effort_tech;
            $task->optional =               $task->wrike_optional;
            $task->add_price_to_task_id =   false;
            $task->visible =                true;
            $task->included_in_the_price =  true;
        }

        // Save task
        $task->save();
    }
}

