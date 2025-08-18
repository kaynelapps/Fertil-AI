<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle }}</h5>
                            <div class="float-right">
                                <a href="{{ route('topic.view.filter', ['id' => $id] + $params) }}" class="loadRemoteModel float-right btn btn-sm btn-orange m-2 mt-1 p-1">
                                    <i class="fa fa-filter"></i> {{ __('message.filter') }}
                                </a>
                                <a href="{{ route('section-data-main.index') }}" class="float-right btn btn-sm btn-primary m-2 mt-1 p-1"><i class="fa fa-angle-double-left"></i> {{ __('message.back') }}</a>
                                <a href="{{ route('section-data.create',['id' => $id , 'category_id' => $data->category ? $data->category->id : '' ]) }}" class="float-right btn btn-sm btn-primary m-2 mt-1 p-1"><i class="fa fa-plus-circle"></i> {{ __('message.add_form_title',['form' => __('message.self_care_data')]) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="row">
                                <div class="card-body">
                                    {!! html()->hidden('main_section_id', $id) !!}
                                    {{ $dataTable->table(['class' => 'table  w-100'],false) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
        {{ $dataTable->scripts() }}
    @endsection
</x-master-layout>
