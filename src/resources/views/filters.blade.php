
<div id="lel-filter" class="panel panel-default">
    <div class="panel-heading">
        <a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne"> Filter</a>
    </div>

    <div id="collapseOne" class="panel-collapse collapse">
        <div class="panel-body"> 

            <form class="form-horizontal" action="{{ route('log-filter') }}" method="POST"> 

                {{ csrf_field() }}
                
                <!--sort-->
                <div class="form-group">
                    <label for="result-sort" class="col-sm-2 control-label">Sort by</label>
                    <div class="col-sm-5">
                        <select id="result-number" name="result-sort" class="form-control">
                            @foreach($columns as $col)
                            <option value="{{ $col }}" {{ ($col==$resultSort) ? 'selected' : '' }}>{{ $col }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <!--sort-direction-->
                    <div class="col-sm-2">
                        <select id="result-sort-direction" name="result-sort-direction" class="form-control"> 
                            {{ getResultSortDirection() }}
                        </select>
                    </div>
                </div>

                <!--result-number-->
                <div class="form-group">
                    <label for="result-number" class="col-sm-2 control-label">Results</label>
                    <div class="col-sm-10">
                        <select id="result-number" name="result-number" class="form-control">
                            {{ getResultNumberOptions() }}
                        </select>
                    </div>
                </div>

                <!--result-level-->
                <div class="form-group">
                    <label for="result-level" class="col-sm-2 control-label">Level</label>
                    <div class="col-sm-10">
                        <select id="result-level" name="result-level[]" class="form-control" multiple="multiple">
                            {{ getResultLevelOptions($resultLevel) }}
                        </select>
                    </div>
                </div> 

                <!--search text-->
                <div class="form-group">
                    <label for="result-search-text" class="col-sm-2 control-label">Search</label>
                    <div class="col-sm-5">
                        <input id="result-search-text" name="result-search-text" type="text" class="form-control" placeholder="search ..." value="{{ $resultSearchText }}">
                    </div>
                    <div class="col-sm-5">
                        <select id="result-search-column" name="result-search-column[]" class="form-control" multiple="multiple"> 
                            @foreach($columns as $col)
                            <option value="{{ $col }}"  {{ (in_array($col, $resultSearchColumn)) ? 'selected' : '' }}>{{ $col }}</option> 
                            @endforeach
                        </select>
                    </div>
                </div> 

                <!--daterange-->
                @if($resultDaterange)
                <input id="date-range-for-JS" type="hidden" value="{{ $resultDaterange }}">
                @endif 
                <div class="form-group">
                    <label for="result-daterange" class="col-sm-2 control-label">From</label>
                    <div class="col-sm-10">
                        <input id="result-daterange" name="result-daterange"  type="text" class="form-control">
                    </div>
                </div>  
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Ok</button>
                        <a href="{{ route('lel') }}" class="btn btn-default">Reset</a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div> 
