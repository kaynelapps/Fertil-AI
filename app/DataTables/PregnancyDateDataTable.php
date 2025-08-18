<?php

namespace App\DataTables;

use App\Models\PregnancyDate;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PregnancyDateDataTable extends DataTable
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
            ->editColumn('status', function($query) {
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
                return '<span class="text-capitalize badge bg-'.$status.'">'.$status_name.'</span>';
            })
            ->addColumn('pregnancy_date_image', function($query){
                return '<a href="'.getSingleMedia($query , 'pregnancy_date_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($query , 'pregnancy_date_image').'" width="40" height="40" ></a>';
            })
            ->editColumn('pregnancy_date', function ($query) {
                return $query->pregnancy_date ? $query->pregnancy_date . ' ' . __('message.week') : null;
            })
            ->editColumn('article_id',function($query){
                $action_type = 'article_id';
                $deleted_at = null;
                return view('pregnancy_date.action',compact('query','action_type','deleted_at'))->render();
            })
            ->filterColumn('article_id',function($query,$keyword){
                return $query->wherehas('article',function($q) use($keyword){
                    $q->where('name','Like',"%{$keyword}%");
                });
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->addColumn('action', function($pregnancy_date){
                $id = $pregnancy_date->id;
                $action_type = 'action';
                $deleted_at = $pregnancy_date->deleted_at;
                return view('pregnancy_date.action',compact('pregnancy_date','id','action_type','deleted_at'))->render();
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
            ->rawColumns(['checkbox','pregnancy_date_image','action','article_id','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PregnancyDateDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PregnancyDate $model)
    {
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
            ['data' => 'pregnancy_date_image', 'name' => 'pregnancy_date_image', 'title' => __('message.image'), 'orderable' => false],
            ['data' => 'pregnancy_date', 'name' => 'pregnancy_date', 'title' => __('message.pregnancy_date')],
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'article_id', 'name' => 'article_id', 'title' => __('message.article')],
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
