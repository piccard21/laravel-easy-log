@extends('lel::layouts.master')

@section('content')

    <main class="site-content lel-content">
        <div class="{{ getContainerClass() }}">
            <div class="row">
                <div class="col-xs-12">

                    @include('lel::filters')

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-condensed" style="width:100%">
                            <thead>
                            <tr>
                                @foreach ($columns as $col)
                                    <th {{ getColumnWidth($col) }}>{{ prepareColumnHeading($col) }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            @foreach ($paginate as $log)
                                <tr class="{{ getLevelColumnClass($log->level) }}">
                                    @foreach ($columns as $col)
                                        <td class="">{{ prepareOutput($col, $log->$col) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 text-center">
                    {{ $paginate->links() }}
                </div>
            </div>
        </div>
    </main>

@endsection
