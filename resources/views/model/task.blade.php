<div id="mwd-task-{{ $task->id }}" class="row mwd-task mwd-task-level-{{ substr_count($space, '&nbsp;') / 5 }}">
    <div class="row">
        <div class="col-sm-6">
            <span>{{ $space }}</span><span data-task="title">{{ $task->title }}</span>
        </div>
        <div class="col-sm-2 text-right">
            @if($task->add_price_to_task_id)<s>@endif<span>{{ $task->getPrice() }}</span> &euro;@if($task->add_price_to_task_id)</s>@endif
        </div>
        <div class="col-sm-1 text-right">
            <span data-task="special_rph">{{ $task->getRph() }}</span> &euro;
        </div>
        <div class="col-sm-2">
            <span data-task="effort">{{ $task->getEffort() }}</span>&nbsp;-&nbsp;<span data-task="effort_design">{{ $task->getEffortDesign() }}</span>&nbsp;-&nbsp;<span data-task="effort_tech">{{ $task->getEffortTech() }}</span>
        </div>
        <span data-toggle="collapse" href="#collapse{{ $task->id }}" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
    </div>
    <div class="row">
        <div id="collapse{{ $task->id }}" class="collapse">
            <div class="row">
                <span data-task="description">{!! $task->description !!}</span>
            </div>
        </div>
    </div>
</div>
@if( !$task->children->isEmpty())
    <div class="row mwd-children">
        @foreach($task->children as $task)
            @include('model.task', ['space' => $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'])
        @endforeach
    </div>
@endif