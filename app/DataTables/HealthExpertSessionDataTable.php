<?php

namespace App\DataTables;

use App\Models\HealthExpertSession;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;
use Carbon\Carbon;

class HealthExpertSessionDataTable extends DataTable
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
        $sessions = $this->transformData($query->get());

        return datatables()
            ->collection($sessions)
            ->addColumn('day', function ($row) {
                return $row['day'];
            })
            ->editColumn('morning_start_time', function ($row) {
                return dateAgoFormate($row['morning_start_time'],true);
            })
            ->editColumn('morning_end_time', function ($row) {
                return dateAgoFormate($row['morning_end_time'],true);
            })
            ->editColumn('evening_start_time', function ($row) {
                return dateAgoFormate($row['evening_start_time'],true);
            })
            ->editColumn('evening_end_time', function ($row) {
                return dateAgoFormate($row['evening_end_time'],true);
            })
            ->addColumn('created_at', function ($row) {
                return dateAgoFormate($row['created_at'], true);
            })
            ->addColumn('action', function ($row) {
                $id = $row['id'];
                $deleted_at = $row['deleted_at'];
                return view('health_expert_session.action', compact('id','deleted_at'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['checkbox', 'action']);
    }

    /**
     * Transform the query data to create separate rows for each week day.
     *
     * @param \Illuminate\Support\Collection $sessions
     * @return \Illuminate\Support\Collection
     */
    protected function transformData($sessions)
    {
        $days = [
            '1' => __('message.monday'),
            '2' => __('message.tuesday'),
            '3' => __('message.wednesday'),
            '4' => __('message.thursday'),
            '5' => __('message.friday'),
            '6' => __('message.saturday'),
            '7' => __('message.sunday'),
        ];

        $transformed = collect();

        foreach ($sessions as $session) {
            $weekDays = is_array($session->week_days) ? $session->week_days : json_decode($session->week_days, true);

            foreach ($weekDays as $day) {
                $transformed->push([
                    'id' => $session->id,
                    'day' => $days[$day] ?? '-',
                    'health_expert_id' => optional($session->health_expert->users)->display_name,
                    // 'health_expert_id' => '<a href="'.route('health-experts.show',optional($session->health_expert)->id).'">'.optional($session->health_expert->users)->display_name.'</span></a>',
                    'morning_start_time' => $session->morning_start_time,
                    'morning_end_time' => $session->morning_end_time,
                    'evening_start_time' => $session->evening_start_time,
                    'evening_end_time' => $session->evening_end_time,
                    'created_at' => $session->created_at,
                    'deleted_at' => $session->deleted_at,
                ]);
            }
        }

        return $transformed;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\HealthExpertSession $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HealthExpertSession $model)
    {
        $auth_user = auth()->user();
        $health_expert_id = optional($auth_user->health_expert)->id;
        if ($auth_user->hasRole('doctor')) {
            $model = $model->where('health_expert_id', $health_expert_id);
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
        $columns = [];
            $columns[] = Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false);
            if (auth()->user()->hasRole('admin')) {
                $columns[] = ['data' => 'health_expert_id', 'name' => 'health_expert_id', 'title' => __('message.health_experts')];
            }
            $columns[] = ['data' => 'day', 'name' => 'day', 'title' => __('message.week_days')];
            $columns[] = ['data' => 'morning_start_time', 'name' => 'morning_start_time', 'title' => __('message.morning_start_time')];
            $columns[] = ['data' => 'morning_end_time', 'name' => 'morning_end_time', 'title' => __('message.morning_end_time')];
            $columns[] = ['data' => 'evening_start_time', 'name' => 'evening_start_time', 'title' => __('message.evening_start_time')];
            $columns[] = ['data' => 'evening_end_time', 'name' => 'evening_end_time', 'title' => __('message.evening_end_time')];
            $columns[] = ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')];
            $columns[] = Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->title(__('message.action'))
                ->addClass('text-center hide-search');
        return $columns;
    }
}
