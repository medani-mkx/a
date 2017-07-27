<div id="mwd-task-{{ $task->id }}" class="row mwd-task mwd-task-level-{{ substr_count($space, '&nbsp;') / 5 }}">
    <div class="row">
        <div class="col-sm-6">
            <span>{{ $space }}</span><span class="mwd-editable mwd-title">{{ $task->title }}</span>
        </div>
        <div class="col-sm-2 text-right">
            <span class="mwd-editable mwd-price">{{ $task->getPrice() }}</span> &euro;
        </div>
        <div class="col-sm-1">
            <span class="mwd-editable mwd-effort">{{ $task->getEffort() }}</span>
        </div>
        <div class="col-sm-1">
            <span class="mwd-editable mwd-effort-design">{{ $task->getEffortDesign() }}</span>
        </div>
        <div class="col-sm-1">
            <span class="mwd-editable mwd-effort-tech">{{ $task->getEffortTech() }}</span>
        </div>
        <span data-toggle="collapse" href="#collapse{{ $task->id }}" class="glyphicon glyphicon-edit" aria-hidden="true"></span>
    </div>
    <div class="row">
        <div id="collapse{{ $task->id }}" class="collapse">
            <div class="row">
                <span class="mwd-editable mwd-description">{!! $task->description !!}</span>
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