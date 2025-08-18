<?php

namespace App\DataTables;

use App\Models\Symptoms;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SymptomsDataTable extends DataTable
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
            ->editColumn('bg_color', function ($query) {
                return '<div class="rounded-pill p-2 w-75" style="background-color: ' . $query->bg_color . ';"><span class="p-1">' . $query->bg_color . '</span></div>';
            })
            ->editColumn('article_id',function($query){
                $action_type = 'article_type';
                $deleted_at = null;
                return view('symptoms.action',compact('query','action_type','deleted_at'))->render();
            })
            ->filterColumn('article_id',function($query,$keyword){
                return $query->wherehas('article',function($q) use($keyword){
                    $q->where('name','Like',"%{$keyword}%");
                });
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->addColumn('total_subsymptom', function ($query) {
                return count($query->subSymptoms);
            })
            ->editColumn('updated_at', function ($query) {
                return dateAgoFormate($query->updated_at, true);
            })
            ->addColumn('action', function ($symptoms) {
                $id = $symptoms->id;
                $action_type = 'action';
                $deleted_at = $symptoms->deleted_at;
                return view('symptoms.action', compact('symptoms', 'id','action_type','deleted_at'))->render();
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
            ->rawColumns(['checkbox','action', 'status','bg_color','article_id','created_at','updated_at']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SymptomsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Symptoms $model)
    {
        return $model->withTrashed();
    }

    /**
     * Get columns.
     *
     * @return array
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
            ['data' => 'bg_color', 'name' => 'bg_color', 'title' => __('message.bg_color'), 'orderable' => false],
            ['data' => 'article_id', 'name' => 'article_id', 'title' => __('message.article')],
            ['data' => 'total_subsymptom', 'name' => 'total_subsymptom', 'title' => __('message.total_sub_symptoms'), 'orderable' => false],
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
