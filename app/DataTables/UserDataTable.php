<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;
use Carbon\Carbon;

class UserDataTable extends DataTable
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
            ->addColumn('name', function ($user) {
                $fullName = $user->first_name . ' ' . $user->last_name;
                $url = route('users.show', $user->id);
                return '<a href="' . $url . '" class="text-primary fw-bold">' . e($fullName) . '</a>';
            })
            ->addColumn('country_name', function ($user) {
                return $user->country_name ?? '-';
            })
            ->addColumn('region', function ($user) {
                return $user->region ?? '-';
            })
            ->addColumn('city', function ($user) {
                return $user->city ?? '-';
            })
            ->addColumn('app_version', function ($user) {
                return $user->app_version ?? '-';
            })
            ->addColumn('app_source', function ($user) {
                return $user->app_source ?? '-';
            })
            ->addColumn('last_actived_at', function ($user) {
                return $user->last_actived_at ? Carbon::parse($user->last_actived_at)->format('F d, Y g:i A') : '-';
            })
            ->editColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="select-table-row-checked-values" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('status', function($data) {
                $action_type = 'status';
                $deleted_at = null;
                return view('users.action',compact('data','action_type','deleted_at'))->render();
            })
            ->editColumn('email', function($query) {
                return auth()->user()->hasRole('admin') ? $query->email : maskSensitiveInfo('email', $query->email);
            })
            ->addColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->addColumn('action', function($users){
                $id = $users->id;
                $action_type = 'action';
                $deleted_at = $users->deleted_at;
                return view('users.action',compact('users','action_type','id','deleted_at'))->render();
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
            ->rawColumns(['checkbox','action','status','name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $model = User::where('user_type','app_user');

        if (request()->has('goal_type') && request('goal_type') !== '') {
            $model = $model->where('goal_type', request('goal_type'));
        }
        if (request()->has('app_version') && request('app_version') !== '') {
            $model = $model->where('app_version', request('app_version'));
        }
        if (request()->has('app_source') && request('app_source') !== '') {
            $model = $model->where('app_source', request('app_source'));
        }
        if (request()->has('region') && request('region') !== '') {
            $model = $model->where('region', 'LIKE', '%' . request('region') . '%');
        }
        if (request()->has('country_name') && request('country_name') !== '') {
            $model = $model->where('country_name', 'LIKE', '%' . request('country_name') . '%');
        }
        if (request()->has('city') && request('city') != '') {
            $model = $model->where('city', 'LIKE', '%' . request('city') . '%');
        }

        $status = request('status');
        switch ($status) {
            case '':
                break;
            case 'active':
                $model = $model->where('status', 'active');
                break;
            case 'inactive':
                $model = $model->where('status', 'inactive');
                break;  
            default:
                break;
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
                ['data' => 'name', 'name' => 'name', 'title' => __('message.name')],
                ['data' => 'email', 'name' => 'email', 'title' => __('message.email')],
                ['data' => 'country_name', 'name' => 'country_name', 'title' => __('message.country')],
                ['data' => 'region', 'name' => 'region', 'title' => __('message.region')],
                ['data' => 'city', 'name' => 'city', 'title' => __('message.city')],
                ['data' => 'app_version', 'name' => 'app_version', 'title' => __('message.app_version')],
                ['data' => 'app_source', 'name' => 'app_source', 'title' => __('message.app_source')],
                ['data' => 'last_actived_at', 'name' => 'last_actived_at', 'title' => __('message.last_active')],
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
