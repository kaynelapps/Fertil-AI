<?php

namespace App\DataTables;

use App\Models\SectionData;
use App\Models\SectionDataMain;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SectionDataDataTable extends DataTable
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
            ->editColumn('section_data_image', function($query){
                return '<a href="'.getSingleMedia($query , 'section_data_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($query , 'section_data_image').'" width="40" height="40" ></a>';
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
                }
            })
            ->editColumn('created_at', function ($query) {
                return dateAgoFormate($query->created_at, true);
            })
            ->editColumn('status', function($query) {
                $status = 'warning';
                $status_name = 'inactive';
                switch ($query->status) {
                    case 0:
                        $status = 'warning';
                        $status_name = __('message.inactive');
                        break;
                    case 1:
                        $status = 'success-light';
                        $status_name = __('message.active');
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$status_name.'</span>';
            })
            ->addColumn('action', function($section_data){
                $id = $section_data->id;
                $deleted_at = $section_data->deleted_at;
                $custome = $this->custome_id;
                $topicData= $this->topic_id;
                $section_data_main_category_id = $this->section_id != null ? SectionDataMain::find($this->section_id)->category_id : null;
                return view('sections-data.action',compact('section_data','id','section_data_main_category_id','deleted_at','topicData','custome'))->render();
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
            ->rawColumns(['checkbox','article_id','section_data_image','view_type','created_at','action','status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SectionDataDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SectionData $model)
    {
        if($this->section_id != null){
            $model = $model->where('main_title_id',$this->section_id);
        }
        if($this->custome_id != null){
            $model = $model->whereIn('id',$this->topic_id);
        }
        if (request()->has('view_type') && request('view_type') !== '') {
            $model = $model->where('view_type', request('view_type'));
        }

        return $model->withTrashed();
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
                ['data' => 'section_data_image', 'name' => 'section_data_image', 'title' => __('message.image'), 'orderable' => false,'searchable' => false],
                ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
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
