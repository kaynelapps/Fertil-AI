<?php

namespace App\DataTables;

use App\Models\DailyInsights;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\SubSymptoms;

class DailyInsightsDataTable extends DataTable
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
            ->editColumn('title', function ($query) {
                return '<span class="faq-content" data-toggle="tooltip" data-html="true" data-bs-placement="bottom" title="'.$query->title.'">'.$query->title.'</span>';
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })    
            ->editColumn('goal_type', function ($query) {
                if ($query->goal_type == 0) {
                    return __('message.track_cycle');
                } elseif ($query->goal_type == 1) {
                    return __('message.track_pragnancy');
                }
            })
            ->editColumn('phase', function ($query) {
                $types = getArticleType();
                return isset($types[$query->phase]) ? $types[$query->phase] : '-';
            })
            ->editColumn('sub_symptoms_id', function ($query) {
                $ids = json_decode($query->sub_symptoms_id, true);
                if (empty($ids)) {
                    return '-';
                }

                return SubSymptoms::whereIn('id', $ids)
                    ->pluck('title')
                    ->implode(', ');
            })

            ->addColumn('action', function($category){
                $id = $category->id;
                $deleted_at = $category->deleted_at;
                return view('dailyinsight.action',compact('category','id','deleted_at'))->render();
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
            ->rawColumns(['checkbox','action','created_at','status','goal_type','phase','sub_symptoms_id','title']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DailyInsights $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DailyInsights $model)
    {
        if (request()->has('goal_type') && request('goal_type') !== '') {
            $model = $model->where('goal_type', request('goal_type'));
        }
        if (request()->has('phase') && request('phase') !== '') {
            $model = $model->where('phase', request('phase'));
        }
        if (request()->has('sub_symptoms_id') && request('sub_symptoms_id') !== '') {
            $model = $model->where('sub_symptoms_id', request('sub_symptoms_id'));
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
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'goal_type', 'name' => 'goal_type', 'title' => __('message.goal_type')],
            ['data' => 'phase', 'name' => 'phase', 'title' => __('message.phase')],
            ['data' => 'sub_symptoms_id', 'name' => 'sub_symptoms_id', 'title' => __('message.sub_symptoms')],
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
