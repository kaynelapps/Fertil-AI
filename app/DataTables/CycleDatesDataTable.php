<?php

namespace App\DataTables;

use App\Models\CycleDates;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CycleDatesDataTable extends DataTable
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
            ->editColumn('day', function ($query) {
                return __('message.cycle_day') .' ' . $query->day;
            })            
            ->editColumn('goal_type', function ($query) {
                if ($query->goal_type == 0) {
                    return __('message.track_cycle');
                } elseif ($query->goal_type == 1) {
                    return __('message.track_pragnancy');
                }
            })
            ->editColumn('view_type', function ($query) {
               if ($query->view_type == 0) {
                    return __('message.story_view');
                } elseif ($query->view_type == 1) {
                    return __('message.video');
                } elseif ($query->view_type == 2) {
                    return __('message.categorie');
                } elseif($query->view_type == 3){
                    return __('message.video_course');
                } elseif ($query->view_type == 4){
                    return __('message.blog_course');
                } elseif($query->view_type == 5){
                    return __('message.podcast');
                } elseif ($query->view_type == 6){
                    return __('message.article');
                } elseif ($query->view_type == 7){
                    return __('message.text_message');
                }elseif ($query->view_type == 8){
                    return __('message.question_answer');
                }
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->addColumn('cycle_dates_thumbnail_image', function($row){
                return '<a href="'.getSingleMedia($row , 'cycle_dates_thumbnail_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'cycle_dates_thumbnail_image').'" width="40" height="40" ></a>';
            })        
            ->addColumn('action', function($category){
                $id = $category->id;
                $deleted_at = $category->deleted_at;
                return view('cycle-dates.action',compact('category','id','deleted_at'))->render();
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
            ->rawColumns(['checkbox','action','created_at','status','cycle_dates_thumbnail_image','goal_type','view_type']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CycleDatesDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CycleDates $model)
    {
        if (request()->has('goal_type') && request('goal_type') !== '') {
                $model = $model->where('goal_type', request('goal_type'));
        }
        if (request()->has('view_type') && request('view_type') !== '') {
                $model = $model->where('view_type', request('view_type'));
        }
        if (request()->has('day') && request('day') !== '') {
                $model = $model->where('day', request('day'));
        }
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
            ['data' => 'cycle_dates_thumbnail_image', 'name' => 'cycle_dates_thumbnail_image', 'title' => __('message.thumbnail_image'), 'orderable' => false],
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'goal_type', 'name' => 'goal_type', 'title' => __('message.goal_type')],
            ['data' => 'day', 'name' => 'day', 'title' => __('message.day')],
            ['data' => 'view_type', 'name' => 'view_type', 'title' => __('message.view_type')],
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
