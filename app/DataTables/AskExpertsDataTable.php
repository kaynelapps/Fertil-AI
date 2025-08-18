<?php

namespace App\DataTables;

use App\Models\Article;
use App\Models\AskExperts;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;
use App\Models\Tags;

class AskExpertsDataTable extends DataTable
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
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="select-table-row-checked-values" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('status', function($query) {
                $status = 'warning';
                $status_name = 'inactive';
                switch ($query->status) {
                    case 0:
                        $status = 'warning';
                        $status_name = __('message.pending');
                        break;
                    case 1:
                        $status = 'primary';
                        $status_name = __('message.assign');
                        break;
                    case 2:
                        $status = 'danger';
                        $status_name = __('message.close');
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$status_name.'</span>';
            })

            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('updated_at', function ($query) {
                return dateAgoFormate($query->updated_at, true);
            })
            ->editColumn('user_id', function ($query) {
                $user = optional($query->users);
                if ($user && $user->id && $user->display_name) {
                    try {
                        return '<a href="' . route('users.show', $user->id) . '">' . e($user->display_name) . '</a>';
                    } catch (\Exception $e) {
                        return e($user->display_name);
                    }
                }

                return '-';
            })
            ->filterColumn('user_id', function ($query,$keyword) {
                return $query->wherehas('users',function($q) use($keyword){
                    $q->where('display_name','Like',"%{$keyword}%");
                });
            })
            ->editColumn('expert_id', function ($query) {
                $user = optional($query->healthexpert);
                $userName = optional($user->users);
                if ($query->expert_id != null) {
                    return '<a href="' . route('health-experts.show', $user->id) . '">' . $userName->display_name . '</a>';
                }
                return '-';
            })
            ->filterColumn('expert_id', function ($query,$keyword) {
                return $query->wherehas('healthexpert',function($q) use($keyword){
                    $q->where('display_name','Like',"%{$keyword}%");
                });
            })
            ->editColumn('description', function ($query) {
                return isset($query->description) ? '<span data-toggle="tooltip" title="'.$query->description.'">'.stringLong($query->description, 'title',50).'</span>' : '-';
            })
            ->editColumn('expert_answer', function ($query) {
                return isset($query->expert_answer) ? '<span data-toggle="tooltip" title="'.$query->expert_answer.'">'.stringLong($query->expert_answer, 'title',50).'</span>' : '-';
            })
            ->addColumn('action', function($askexpert){
                $id = $askexpert->id;
                $action_type = 'action';
                $deleted_at = $askexpert->deleted_at;
                $hasImage = getMediaFileExit($askexpert, 'askexpert_image');
                return view('askexpert.action',compact('id','action_type','deleted_at','hasImage'))->render();
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
            ->rawColumns(['checkbox','expert_id','action','status','user_id','expert_answer','description']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AskExperts $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AskExperts $model)
    {
        $query = $model->newQuery();
        return $query->withTrashed();
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
            ['data' => 'user_id', 'name' => 'user_id', 'title' => __('message.users')],
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'description', 'name' => 'description', 'title' => __('message.description')],
            ['data' => 'expert_id', 'name' => 'expert_id', 'title' => __('message.health_experts')],
            ['data' => 'expert_answer', 'name' => 'expert_answer', 'title' => __('message.expert_answer')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('message.updated_at')],
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
