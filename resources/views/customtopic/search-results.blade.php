@if(count($results) > 0)
    <table id="basic-table" class="table mb-3 text-center">
        <thead>
            <tr>
                <th>{{__('message.title')}}</th>
                <th>{{__('message.main_title')}}</th>
                <th>{{__('message.view_type')}}</th>
                <th>{{__('message.category')}}</th>
                <th>{{__('message.action')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->title }}</td>
                    <td>{{ $result->section_data_main->title ?? '-' }}</td>
                    <td>
                        @if ($result->view_type == 0)
                            {{ __('message.story_view') }}
                        @elseif ($result->view_type == 1)
                            {{ __('message.video') }}
                        @elseif ($result->view_type == 2)
                            {{ __('message.categorie') }}
                        @elseif ($result->view_type == 3)
                            {{ __('message.video_course') }}
                        @elseif ($result->view_type == 4)
                            {{ __('message.blog_course') }}
                        @elseif ($result->view_type == 5)
                            {{ __('message.podcast') }}
                        @elseif ($result->view_type == 6)
                            {{ __('message.article') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $result->category->name ?? '-' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary add-topic-btn" data-id="{{ $result->id }}">
                            <i class="fa fa-plus-circle"></i> {{__('message.add')}}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-center">{{__('message.no_record_found')}}</p>
@endif
