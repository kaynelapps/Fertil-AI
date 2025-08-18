<?php

namespace App\DataTables;

use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;

use App\Traits\DataTableTrait;

class ActivityDataTable extends DataTable
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
            ->editColumn('description', function ($activity) {
                $desc = $activity->description ?? '';
                return explode(' – ', $desc)[0];
            })
            ->addColumn('user', function ($activity) {
                return $activity->causer->display_name ?? 'System';
            })
            ->addColumn('ip_address', function ($activity) {
                return $activity->properties['ip'] ?? '-';
            })
            ->addColumn('model', function ($activity) {
                return class_basename($activity->subject_type ?? '');
            })
            ->editColumn('created_at', function ($activity) {
                return $activity->created_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($activity) {
                $id = $activity->id;
                $url = route('viewChanges', $id);
                return '<a href="' . $url . '" class="btn btn-outline-primary btn-sm loadRemoteModel" data-toggle="modal" data-target="#activityModal"><i class="fas fa-eye"></i> View changes</a>';
            })
            ->rawColumns(['changes','user','action']);
    }

    public function query(Activity $model)
    {
        $query = $model->newQuery()->with('causer', 'subject')->latest();

        // if ($this->request()->has('user') && $this->request()->get('user') != '') {
            $user = $this->request()->get('user');
            $query->whereHas('causer', function ($q) use ($user) {
                $q->where('first_name', 'like', "%{$user}%")
                  ->orWhere('last_name', 'like', "%{$user}%")
                  ->orWhere('display_name', 'like', "%{$user}%");
            });
        // }

        return $query;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('description')->title('Event'),
            Column::make('model')->title('Model'),
            Column::make('subject_id')->title('Model ID'),
            Column::make('user')->title('User'),
            Column::make('ip_address')->title('IP Address'),
            Column::make('created_at')->title('Time'),
            Column::computed('action')
                ->title('Actions')
                ->orderable(false)
                ->searchable(false),

        ];
    }

}
