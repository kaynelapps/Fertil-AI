<?php

namespace App\DataTables;

use App\Models\CycleDateData;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CycleDatesDataDataTable extends DataTable
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
        ->editColumn('cycle_date_data_text_message_image', function($row){
            if (getSingleMedia($row , 'cycle_date_data_text_message_image')) {
                return '<a href="'.getSingleMedia($row , 'cycle_date_data_text_message_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'cycle_date_data_text_message_image').'" width="40" height="40" ></a>';
            } else {
                return '<a href="'.getSingleMedia($row , 'cycle_date_data_que_ans_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'cycle_date_data_que_ans_image').'" width="40" height="40" ></a>';

            }
        })
        ->editColumn('slide_type', function ($query) {
            return $query->slide_type == '0' ? "Text-Message" : 'Question Answer';
        })       
        ->editColumn('article_id', function($row) {
            if (!empty($row->article_id)) {
                return '<a class="mr-2" href="' . route('article.show', ['article' => $row->article_id]) . '">' . optional($row->article)->name . '</a>';
            }
            return '-'; 
        })
        ->filterColumn('article_id',function($query,$keyword){
            return $query->wherehas('article',function($q) use($keyword){
                $q->where('name','Like',"%{$keyword}%");
            });
        })
        ->editColumn('created_at', function ($query) {
            return dateAgoFormate($query->created_at, true);
        })            
        ->addColumn('action', function($cycle_date_data){
            $id = $cycle_date_data->id;
            return view('cycle-dates.data-action',compact('cycle_date_data','id'))->render();
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
        ->rawColumns(['checkbox','action','cycle_date_data_text_message_image','status','article_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CycleDatesDataDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CycleDateData $model)
    {
        if($this->cycle_date_id != null){
            $model = $model->where('cycle_date_id',$this->cycle_date_id);
        }
        return $model->newQuery();
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
            ['data' => 'cycle_date_data_text_message_image', 'name' => 'cycle_date_data_text_message_image', 'title' => __('message.image')],
            ['data' => 'title', 'name' => 'title', 'title' => __('message.title')],
            ['data' => 'slide_type', 'name' => 'slide_type', 'title' => __('message.slide_type')],
            ['data' => 'article_id', 'name' => 'article_id', 'title' => __('message.article')],
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
