<?php

namespace App\DataTables;

use App\Models\VideosUpload;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VideosUploadDataTable extends DataTable
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
            return '<input type="checkbox" class=" select-table-row-checked-values" id="datatable-row-' . $row->id . '" name="datatable_ids[]" value="' . $row->id . '" onclick="dataTableRowCheck(' . $row->id . ')">';
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
        ->addColumn('videos_upload', function($row){
            return '<a href="'.getSingleMedia($row , 'videos_upload').'" class="video-popup-vertical-fit"><video src="'.getSingleMedia($row , 'videos_upload').'" width="60" height="60" ></video></a>';
        })
        ->addColumn('thumbnail_image', function($row){
            return '<a href="'.getSingleMedia($row , 'upload_video_thumbnail_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($row , 'upload_video_thumbnail_image').'" width="40" height="40" ></a>';
        })
        ->addColumn('created_at', function ($query) {
            return dateAgoFormate($query->created_at, true);
        })
        ->addColumn('action', function($upload_videos){
            $id = $upload_videos->id;
            $deleted_at = $upload_videos->deleted_at;
            return view('upload-videos.action',compact('upload_videos','id','deleted_at'))->render();
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
        ->rawColumns(['checkbox','created_at','status','videos_upload','action','thumbnail_image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VideosUploadDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VideosUpload $model)
    {
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
            ['data' => 'video_duration', 'name' => 'video_duration', 'title' => __('message.video_duration')],
            ['data' => 'thumbnail_image', 'name' => 'thumbnail_image', 'title' => __('message.thumbnail_image'), 'orderable' => false],
            ['data' => 'videos_upload', 'name' => 'videos_upload', 'title' => __('message.video'), 'orderable' => false],
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
