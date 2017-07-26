<div class="row mwd-task mwd-task-level-{{ substr_count($space, '&nbsp;') / 5 }}">
    <div class="col-sm-6">
        {{ $space }}{{ $task->title }}
    </div>
    <div class="col-sm-2 text-right">
        {{ $task->getPrice() }} &euro;
    </div>
    <div class="col-sm-1">
        {{ $task->getEffort() }}
    </div>
    <div class="col-sm-1">
        {{ $task->getEffortDesign() }}
    </div>
    <div class="col-sm-1">
        {{ $task->getEffortTech() }}
    </div>
    <span data-toggle="collapse" href="#collapse{{ $task->id }}" class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
    <div id="collapse{{ $task->id }}" class="collapse">
        <div class="row">
            {!! $task->description !!}
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