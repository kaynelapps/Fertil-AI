<?php

namespace App\DataTables;

use App\Models\Article;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Traits\DataTableTrait;
use App\Models\Tags;

class ArticleDataTable extends DataTable
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
                        $status_name = __('message.inactive');
                        break;
                    case 1:
                        $status = 'primary';
                        $status_name = __('message.active');
                        break;
                }
                return '<span class="text-capitalize badge bg-'.$status.'">'.$status_name.'</span>';
            })

            ->addColumn('article_image', function($query){
                return '<a href="'.getSingleMedia($query , 'article_image').'" class="image-popup-vertical-fit"><img src="'.getSingleMedia($query , 'article_image').'" width="40" height="40" ></a>';
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
            ->editColumn('article_type', function ($query) {
                $types = getArticleType();
                return isset($types[$query->article_type]) ? $types[$query->article_type] : '-';
            })
            ->editColumn('expert_id', function($article){
                if($this->expert_id != null) {
                    return optional($article->health_experts->users)->display_name;
                } else {
                    $id = $article->id;
                    $action_type = 'health_expert';
                    $deleted_at = null;
                    return view('article.action',compact('article','id','action_type','deleted_at'))->render();
                }
            })
            ->editColumn('type', function($row){
                return ucfirst($row->type) ?? '-';
            })
            ->addColumn('action', function($article){
                $id = $article->id;
                $action_type = 'action';
                $deleted_at = $article->deleted_at;
                return view('article.action',compact('article','id','action_type','deleted_at'))->render();
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
            ->rawColumns(['checkbox','article_image','action','status','expert_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Article $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Article $model)
    {
        if (request()->has('goal_type') && request('goal_type') !== '') {
            $model = $model->where('goal_type', request('goal_type'));
        }
        if (request()->has('article_type') && request('article_type') !== '') {
            $model = $model->where('article_type', request('article_type'));
        }
        if (request()->has('articles_type') && request('articles_type') !== '') {
            $model = $model->where('type', request('articles_type'));
        }
        if (request()->has('expert_id') && request('expert_id') !== '') {
            $model = $model->where('expert_id', request('expert_id'));
        }
        if (request()->has('sub_symptoms_id') && request('sub_symptoms_id') !== '') {
            $model = $model->whereJsonContains('sub_symptoms_id', request('sub_symptoms_id'));
        }

        if ($this->expert_id != null) {
            # code...
            $model = $model->where('expert_id',$this->expert_id);
        }
        $query = $model->newQuery();

        $health_expert_id = isset($_GET['health_expert_id']) ? $_GET['health_expert_id'] : null;
        if( $health_expert_id != null ) {
            $model = $model->where('expert_id',$health_expert_id);
        }

      
        $articleType = isset($_GET['article_type']) ? $_GET['article_type'] : null;
    switch($articleType){
        case 'menstrual_phase':
            $query = $query->where('article_type','1');
            break;
        case 'follicular_phase':
            $query = $query->where('article_type','2');
            break;
        case 'ovulation_phase':
            $query = $query->where('article_type','3');
            break;
        case 'luteal_phase':
            $query = $query->where('article_type','4');
            break;
        case 'late_period':
            $query = $query->where('article_type','5');
            break;
        case 'pregnancy':
            $query = $query->where('article_type','6');
            break;
        default:
        break;
    }
        return $query->withTrashed();
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $column = [];
        $column[] = Column::make('checkbox')
            ->searchable(false)
            ->orderable(false)
            ->title('<input type="checkbox" class ="select-all-table" name="select_all" id="select-all-table">')
            ->width(10);
        $column[] = Column::make('DT_RowIndex')
            ->searchable(false)
            ->title(__('message.srno'))
            ->orderable(false);
               $column[] = ['data' => 'article_image', 'name' => 'article_image', 'title' => __('message.image'), 'orderable' => false];
               $column[] = ['data' => 'name', 'name' => 'name', 'title' => __('message.name')];
               $column[] = ['data' => 'goal_type', 'name' => 'goal_type', 'title' => __('message.goal_type')];
               $column[] = ['data' => 'article_type', 'name' => 'article_type', 'title' => __('message.article_use_for')];
               $column[] = ['data' => 'type', 'name' => 'type', 'title' => __('message.type')];
               if (auth()->user()->hasRole('admin')) {
                $column[] = ['data' => 'expert_id', 'name' => 'expert_id', 'title' => __('message.health_experts')];
               }
               $column[] = ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')];
               $column[] = ['data' => 'status', 'name' => 'status', 'title' => __('message.status')];
        $column[] = Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->title(__('message.action'))
            ->width(60)
            ->addClass('text-center hide-search');

        return $column;
    }
}
