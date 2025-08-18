<?php

namespace App\DataTables;

use App\Models\HealthExpert;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class HealthExpertsDataTable extends DataTable
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
            ->editColumn('user_id', function ($query) {
                     $user = optional($query->users);
                return $user ? '<a href="' . route('health-experts.show',$query->id) . '">' .$user->display_name . '</a>' : '-' ;
            })
            ->filterColumn('user_id', function ($query,$keyword) {
                return $query->wherehas('users',function($q) use($keyword){
                    $q->where('display_name','Like',"%{$keyword}%");
                });
            })
            ->addColumn('status', function($query) {
                $status = 'warning';
                $status_name = 'inactive';
                switch (optional($query->users)->status) {
                    case 'inactive':
                        $status = 'warning';
                        $status_name = __('message.inactive');
                        break;
                    case 'active':
                        $status = 'success-light';
                        $status_name = __('message.active');
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$status_name.'</span>';
            })
            ->editColumn('tag_line', function ($query) {
                return '<span data-toggle="tooltip" title="'.$query->tag_line.'">'. stringLong($query->tag_line,'title') .'</span>';
            })
            ->addColumn('health_experts_image', function($row){
                return '<a href="'.getSingleMedia($row , 'health_experts_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'health_experts_image').'" width="40" height="40" ></a>';
            })
            ->addColumn('email', function($query) {
                return auth()->user()->hasRole('admin') ? $query->users->email : maskSensitiveInfo('email', $query->users->email);
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('is_access', function($query) {
                $id = $query->id;
                return view('health-experts.health_experts_status',compact('query','id'))->render();
            })
            ->addColumn('action', function($healthexpert){
                $id = $healthexpert->id;
                $action_type = 'action';
                $deleted_at = $healthexpert->deleted_at;
                return view('health-experts.action',compact('id','action_type','deleted_at'))->render();
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
            ->rawColumns(['checkbox','health_experts_image','tag_line','action','status','is_access','user_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\HealthExpertsDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HealthExpert $model)
    {
        if (request()->segment(1) == 'healthexperts') {
            $model = $model->where('is_access',1);
        }

        return $model->newQuery()->withTrashed();
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
            ['data' => 'health_experts_image', 'name' => 'health_experts_image', 'title' => __('message.image'), 'orderable' => false],
            ['data' => 'user_id', 'name' => 'user_id', 'title' => __('message.name')],
            ['data' => 'email', 'name' => 'email', 'title' => __('message.email'),'orderable' => false],
            ['data' => 'tag_line', 'name' => 'tag_line', 'title' => __('message.tag_line')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            ['data' => 'is_access', 'name' => 'is_access', 'title' => __('message.access')],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status'),'orderable' => false],
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->title(__('message.action'))
                  ->width(60)
                  ->addClass('text-center hide-search'),
        ];
    }
}
