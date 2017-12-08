
@if (count($errors) )

<div class="{{ getContainerClass() }}"> 
    <div class="panel panel-danger  lel-errors">
        <div class="panel-heading">Errors</div> 

        <ul class="list-group"> 
            @foreach ($errors->all() as $error)
            <li class="list-group-item">{{$error}}</li>
            @endforeach
        </ul>  
    </div>
</div>
@endif