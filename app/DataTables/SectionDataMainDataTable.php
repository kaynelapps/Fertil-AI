<?php

namespace App\DataTables;

use App\Models\SectionDataMain;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SectionDataMainDataTable extends DataTable
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
            ->addIndexColumn()
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->setRowAttr([
                'data-id' => function ($row) {
                    return $row->id;
                },
            ])
            ->editColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="select-table-row-checked-values" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('category_id', function ($query) {
                return optional($query->category)->name;
            })
            ->editColumn('status', function ($query) {
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
                return '<span class="text-capitalize badge bg-' . $status . '">' . $status_name . '</span>';
            })
            ->editColumn('goal_type', function ($query) {
                $message = '-';
                switch ($query->goal_type) {
                    case '0':
                        $message = __('message.track_cycle');
                        break;
                    case '1':
                        $message = __('message.track_pragnancy');
                        break;
                    default:
                    # code...
                        break;
                }
                return $message;
            })
            ->addColumn('action', function ($section_data) {
                $id = $section_data->id;
                $deleted_at = $section_data->deleted_at;
                return view('sections-data.main-action', compact('section_data', 'deleted_at', 'id'))->render();
            })
        ->addIndexColumn()
            ->order(function ($query) {
                if (request()->has('order')) {
                    $order = request()->order[0];
                    $column_index = $order['column'];

                    $column_name = 'dragondrop';
                    $direction = 'desc';
                    if ($column_index != 0) {
                        $column_name = request()->columns[$column_index]['data'];
                        $direction = $order['dir'];
                    }

                    $query->orderBy($column_name, $direction);
                }
            })
            ->rawColumns(['checkbox', 'article_id', 'category_id', 'created_at', 'action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SectionDataMain $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SectionDataMain $model)
    {
        if ($this->category_id != null) {
            $model = $model->where('category_id', $this->category_id);
        }
        if (request()->has('goal_type') && request('goal_type') !== '') {
                $model = $model->where('goal_type',request('goal_type'));
        }
        if(request('category_id') && request('category_id') != '')
        {
            $model = $model->where('category_id',request('category_id'));
        }
        return $model->withTrashed()->applyScopes();
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
                ->title('<input type="checkbox" class="select-all-table" name="select_all" id="select-all-table">')
                ->width(10),
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'category_id', 'name' => 'category_id', 'title' => __('message.category')],
            ['data' => 'goal_type', 'name' => 'goal_type', 'title' => __('message.goal_type')],
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
