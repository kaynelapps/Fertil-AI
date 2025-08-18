<?php

namespace App\DataTables;

use App\Models\CalculatorTool;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CalculatorToolDataTable extends DataTable
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
            ->addColumn('calculator_thumbnail_image', function($row){
                return '<a href="'.getSingleMedia($row , 'calculator_thumbnail_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'calculator_thumbnail_image').'" width="40" height="40" ></a>';
            })
            ->editColumn('blog_link',function($query){
                $action_type = 'blog_link';
                return view('calculator_tool.action',compact('query','action_type'))->render();
            })
            ->filterColumn('blog_link',function($query,$keyword){
                return $query->wherehas('article',function($q) use($keyword){
                    $q->where('name','Like',"%{$keyword}%");
                });
            })
            ->addColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->addColumn('action', function($calculator_tool){
                $id = $calculator_tool->id;
                $action_type = 'action';
                return view('calculator_tool.action',compact('calculator_tool','id','action_type'))->render();
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
            ->rawColumns(['calculator_thumbnail_image','action','status','blog_link']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CalculatorToolDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CalculatorTool $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
                ['data' => 'type', 'name' => 'type', 'title' => __('message.type')],
                ['data' => 'name', 'name' => 'name', 'title' => __('message.name')],
                ['data' => 'blog_link', 'name' => 'blog_link', 'title' => __('message.blog')],
                ['data' => 'calculator_thumbnail_image', 'name' => 'calculator_thumbnail_image', 'title' => __('message.image'), 'orderable' => false],
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
