<?php

namespace App\DataTables;

use App\Models\PersonalisedInsights;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PersonalisedInsightsDataTable extends DataTable
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
            ->editColumn('thumbnail_image', function($query){
                return '<a href="'.getSingleMedia($query , 'thumbnail_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($query , 'thumbnail_image').'" width="40" height="40" ></a>';
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
                    return __('message.text');
                }
            })
            ->editColumn('goal_type', function ($query) {
                if ($query->goal_type == 0) {
                    return __('message.track_cycle');
                } elseif ($query->goal_type == 1) {
                    return __('message.track_pragnancy');
                }
            })
            ->editColumn('insights_type', function ($query) {
                $types = getArticleType();
                return isset($types[$query->insights_type]) ? $types[$query->insights_type] : '-';
            })
            ->editColumn('users', function($query) {
                $userIds = json_decode($query->users, true);
                return is_array($userIds) ? count($userIds) : 0;
            })
            ->editColumn('anonymous_user', function($query) {
                $anonymousIds = json_decode($query->anonymous_user, true);
                return is_array($anonymousIds) ? count($anonymousIds) : 0;
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
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
            ->addColumn('action', function($insights){
                $id = $insights->id;
                $deleted_at = $insights->deleted_at;
                return view('personalinsights.action',compact('insights','id','deleted_at'))->render();
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
            ->rawColumns(['checkbox','thumbnail_image','article_id','created_at','action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PersonalisedInsights $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PersonalisedInsights $model)
    {
        if (request()->has('goal_type') && request('goal_type') !== '') {
            $model = $model->where('goal_type', request('goal_type'));
        }
        if(request('view_type') && request('view_type') != '')
        {
            $model = $model->where('view_type',request('view_type'));
        }
        if(request('insights_type') && request('insights_type') != '')
        {
            $model = $model->where('insights_type',request('insights_type'));
        }
        return $model->withTrashed();
    }

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
                ['data' => 'thumbnail_image', 'name' => 'thumbnail_image', 'title' => __('message.thumbnail_image'), 'orderable' => false,'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
                ['data' => 'goal_type', 'name' => 'goal_type', 'title' => __('message.goal_type')],
                ['data' => 'insights_type', 'name' => 'insights_type', 'title' => __('message.insights_use_for')],
                ['data' => 'view_type', 'name' => 'view_type', 'title' => __('message.view_type')],
                ['data' => 'users', 'name' => 'users', 'title' => __('message.users')],
                ['data' => 'anonymous_user', 'name' => 'anonymous_user', 'title' => __('message.anonymous_user')],
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
