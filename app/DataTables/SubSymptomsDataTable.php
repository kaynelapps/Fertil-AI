<?php

namespace App\DataTables;

use App\Models\Insights;
use App\Models\SubSymptoms;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubSymptomsDataTable extends DataTable
{
    use DataTableTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="select-table-row-checked-values" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('status', function ($query) {
                $status = 'warning-light';
                $status_name = 'inactive';
                switch ($query->status) {
                    case 0:
                        $status = 'warning-light';
                        $status_name = __('message.inactive');
                        break;
                    case 1:
                        $status = 'success-light';
                        $status_name = __('message.active');
                        break;
                }
                return '<span class="text-capitalize badge bg-' . $status . '">' . $status_name . '</span>';
            })
            ->addColumn('sub_symptom_icon', function($row){
                return '<a href="'.getSingleMedia($row , 'sub_symptom_icon').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'sub_symptom_icon').'" width="40" height="40" ></a>';
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('symptoms_id', function ($query) {
                return optional($query->symptom)->title;
            })
            ->filterColumn('symptoms_id', function ($query, $keyword) {
                $query->whereHas('symptom', function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('updated_at', function ($query) {
                return dateAgoFormate($query->updated_at, true);
            })
            ->addColumn('insights', function ($query) {
                $insights_data = Insights::where('sub_symptoms_id',$query->id)->first();
                return isset($insights_data) ? __('message.yes') : __('message.no');
            })
            ->addColumn('action', function ($symptoms) {
                $id = $symptoms->id;
                $deleted_at = $symptoms->deleted_at;
                return view('sub-symptoms.action', compact('symptoms', 'id','deleted_at'))->render();
            })
            ->addIndexColumn()
            ->order(function ($query) {
                if (request()->has('order')) {
                    $order = request()->order[0];
                    $column_index = $order['column'];

                    $column_name = 'created_at';
                    $direction = 'desc';
                    if( $column_index != 0) {
                        $column_name = request()->columns[$column_index]['data'];
                        $direction = $order['dir'];
                    }

                    $query->orderBy($column_name, $direction);
                }
            })
            ->rawColumns(['checkbox','action', 'status','sub_symptom_icon','created_at','updated_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SubSymptomsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SubSymptoms $model)
    {
        if(request('symptoms_id') && request('symptoms_id') != '')
        {
            $model = $model->where('symptoms_id',request('symptoms_id'));
        }
        return $model->withTrashed();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */

    protected function getColumns()
    {
        return [
            Column::make('checkbox')
            ->searchable(false)
            ->orderable(false)
            ->title('<input type="checkbox" class ="select-all-table" name="select_all" id="select-all-table">')
            ->width(10),
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'symptoms_id', 'name' => 'symptoms_id', 'title' => __('message.symptoms')],
            ['data' => 'sub_symptom_icon', 'name' => 'sub_symptom_icon', 'title' => __('message.icon'), 'orderable' => false],
            ['data' => 'insights', 'name' => 'insights', 'title' => __('message.insights'), 'orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status')],
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->title(__('message.action'))
                  ->width(60)
                  ->addClass('text-center hide-search'),
        ];
    }
}
